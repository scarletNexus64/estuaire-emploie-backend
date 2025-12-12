"""
Renewal Payment Adapter

Logique métier pour les renouvellements de subscriptions.
"""

import logging
from datetime import timedelta
from typing import Optional
from django.utils import timezone
from django.db import transaction as db_transaction

from api.models import (
    PaymentTrx,
    UserSubscription,
    Notification
)

logger = logging.getLogger(__name__)


class RenewalPaymentAdapter:
    """
    Adapter pour gérer la logique métier des renouvellements.

    Responsabilités:
        - Trouver la UserSubscription à renouveler
        - Prolonger expired_date
        - Réactiver si expired
        - Notifications de succès

    Usage:
        adapter = RenewalPaymentAdapter()
        adapter.on_payment_success(payment_trx)
    """

    def on_payment_success(self, payment_trx: PaymentTrx):
        """
        Callback appelé quand un paiement de renouvellement réussit.

        Args:
            payment_trx: Transaction de paiement réussie
        """
        logger.info(
            f"[RenewalAdapter] 🔄 Traitement renouvellement réussi - "
            f"PaymentTrx: {payment_trx.id}"
        )

        # Vérifier que c'est bien un renouvellement
        if not 'RENEW' in payment_trx.external_id.upper():
            logger.warning(
                f"[RenewalAdapter] ⚠️ External ID ne contient pas 'RENEW': "
                f"{payment_trx.external_id}"
            )

        # Trouver la subscription à renouveler
        subscription = self._find_subscription(payment_trx)

        if not subscription:
            logger.error(
                f"[RenewalAdapter] ❌ Subscription non trouvée - "
                f"PaymentTrx: {payment_trx.id}, User: {payment_trx.user.id}, "
                f"Package: {payment_trx.package.name if payment_trx.package else 'N/A'}"
            )
            # Créer notification d'erreur
            self._create_error_notification(payment_trx)
            return

        with db_transaction.atomic():
            # Renouveler la subscription
            self._renew_subscription(subscription, payment_trx)

        # Créer notification de succès
        self._create_success_notification(payment_trx, subscription)

        logger.info(
            f"[RenewalAdapter] ✅ Renouvellement terminé - "
            f"Subscription: {subscription.id}, Nouvelle expiration: {subscription.expired_date}"
        )

    def _find_subscription(
        self,
        payment_trx: PaymentTrx
    ) -> Optional[UserSubscription]:
        """
        Trouver la subscription à renouveler.

        Stratégie:
        1. Essayer par external_id (ex: RENEW-175-20251125...)
        2. Sinon, chercher par user + package

        Args:
            payment_trx: Transaction

        Returns:
            UserSubscription si trouvée
        """
        # Essayer d'extraire l'ID de la subscription depuis external_id
        # Format attendu: RENEW-{subscription_id}-{timestamp}
        external_id = payment_trx.external_id

        if external_id.startswith('RENEW-'):
            parts = external_id.split('-')
            if len(parts) >= 2:
                try:
                    subscription_id = int(parts[1])

                    subscription = UserSubscription.objects.filter(
                        id=subscription_id,
                        user=payment_trx.user
                    ).first()

                    if subscription:
                        logger.debug(
                            f"[RenewalAdapter] Subscription trouvée par ID: {subscription_id}"
                        )
                        return subscription

                except ValueError:
                    pass

        # Sinon, chercher par user + package
        if payment_trx.package:
            subscription = UserSubscription.objects.filter(
                user=payment_trx.user,
                package=payment_trx.package
            ).order_by('-created_at').first()  # La plus récente

            if subscription:
                logger.debug(
                    f"[RenewalAdapter] Subscription trouvée par user+package: {subscription.id}"
                )
                return subscription

        return None

    def _renew_subscription(
        self,
        subscription: UserSubscription,
        payment_trx: PaymentTrx
    ):
        """
        Renouveler une subscription (prolonger expired_date).

        Args:
            subscription: Subscription à renouveler
            payment_trx: Transaction de paiement
        """
        logger.info(
            f"[RenewalAdapter] 📝 Renouvellement subscription - "
            f"ID: {subscription.id}, Ancienne expiration: {subscription.expired_date}"
        )

        package = payment_trx.package or subscription.package

        if not package:
            logger.error(
                f"[RenewalAdapter] ❌ Pas de package disponible pour calculer la durée"
            )
            return

        # Calculer nouvelle date d'expiration
        # Si déjà expiré, partir de maintenant
        # Sinon, ajouter à la date actuelle
        if subscription.is_expired or subscription.expired_date < timezone.now():
            base_date = timezone.now()
            logger.debug("[RenewalAdapter] Subscription expirée, base = now")
        else:
            base_date = subscription.expired_date
            logger.debug(f"[RenewalAdapter] Subscription active, base = {base_date}")

        new_expired_date = base_date + timedelta(days=package.nbrday)

        # Mettre à jour
        subscription.expired_date = new_expired_date
        subscription.is_expired = False

        # Mettre à jour le label si fourni
        if payment_trx.subscription_label:
            subscription.label = payment_trx.subscription_label

        subscription.save(
            update_fields=['expired_date', 'is_expired', 'label', 'updated_at']
        )

        logger.info(
            f"[RenewalAdapter] ✅ Subscription renouvelée - "
            f"Nouvelle expiration: {new_expired_date} (+{package.nbrday} jours)"
        )

    def _create_success_notification(
        self,
        payment_trx: PaymentTrx,
        subscription: UserSubscription
    ):
        """
        Créer une notification de succès pour le renouvellement.

        Args:
            payment_trx: Transaction
            subscription: Subscription renouvelée
        """
        try:
            user = payment_trx.user
            amount = payment_trx.amount
            label = subscription.label or subscription.package.name

            content = (
                f"✅ Renouvellement réussi de '{label}' pour {amount} FCFA. "
                f"Votre abonnement est actif jusqu'au "
                f"{subscription.expired_date.strftime('%d/%m/%Y')}."
            )

            Notification.objects.create(
                user=user,
                content=content,
                unread=True,
                read=False
            )

            logger.debug(
                f"[RenewalAdapter] 📝 Notification succès créée pour user {user.id}"
            )

        except Exception as e:
            # Ne pas bloquer le flux principal
            logger.error(
                f"[RenewalAdapter] ⚠️ Erreur création notification: {e}"
            )

    def _create_error_notification(self, payment_trx: PaymentTrx):
        """
        Créer une notification d'erreur si subscription non trouvée.

        Args:
            payment_trx: Transaction
        """
        try:
            user = payment_trx.user
            amount = payment_trx.amount

            content = (
                f"⚠️ Votre paiement de {amount} FCFA a été reçu, "
                f"mais nous n'avons pas trouvé l'abonnement à renouveler. "
                f"Contactez le support avec la référence: {payment_trx.reference}"
            )

            Notification.objects.create(
                user=user,
                content=content,
                unread=True,
                read=False
            )

            logger.debug(
                f"[RenewalAdapter] 📝 Notification erreur créée pour user {user.id}"
            )

        except Exception as e:
            logger.error(
                f"[RenewalAdapter] ⚠️ Erreur création notification erreur: {e}"
            )
