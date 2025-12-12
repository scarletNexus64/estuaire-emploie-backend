"""
Premium Payment Adapter

Logique métier pour les paiements de boost premium de produits.
"""

import logging
from datetime import timedelta
from typing import Optional
from django.utils import timezone
from django.db import transaction as db_transaction

from api.models import (
    PaymentTrx,
    Product,
    ProductAudience,
    Notification
)

logger = logging.getLogger(__name__)


class PremiumPaymentAdapter:
    """
    Adapter pour gérer la logique métier des paiements premium.

    Responsabilités:
        - Activer is_premium sur le produit
        - Définir date_expire_premium (14 jours)
        - Créer/mettre à jour ProductAudience
        - Notifications de succès

    Usage:
        adapter = PremiumPaymentAdapter()
        adapter.on_payment_success(payment_trx)
    """

    # Durée du boost premium (14 jours selon le code existant)
    PREMIUM_DURATION_DAYS = 14

    def on_payment_success(self, payment_trx: PaymentTrx):
        """
        Callback appelé quand un paiement premium réussit.

        Args:
            payment_trx: Transaction de paiement réussie
        """
        logger.info(
            f"[PremiumAdapter] 🚀 Traitement paiement premium réussi - "
            f"PaymentTrx: {payment_trx.id}"
        )

        # Vérifier qu'un produit est associé
        if not payment_trx.product:
            logger.error(
                f"[PremiumAdapter] ❌ Pas de produit associé - "
                f"PaymentTrx: {payment_trx.id}"
            )
            return

        product = payment_trx.product

        with db_transaction.atomic():
            # 1. Activer premium sur le produit
            self._activate_premium(product)

            # 2. Créer/mettre à jour ProductAudience
            self._ensure_product_audience(product, payment_trx.user)

        # 3. Créer notification de succès
        self._create_success_notification(payment_trx, product)

        logger.info(
            f"[PremiumAdapter] ✅ Traitement terminé - "
            f"Product: {product.id}, Premium actif jusqu'au {product.date_expire_premium}"
        )

    def _activate_premium(self, product: Product):
        """
        Activer le statut premium sur un produit.

        Args:
            product: Produit à rendre premium
        """
        logger.info(
            f"[PremiumAdapter] 🌟 Activation premium - "
            f"Product: {product.id} ({product.name})"
        )

        # Calculer la date d'expiration
        # Si déjà premium et non expiré, on prolonge
        # Sinon, on part de maintenant
        if product.is_premium and product.date_expire_premium:
            if product.date_expire_premium > timezone.now():
                # Prolonger depuis la date actuelle
                base_date = product.date_expire_premium
                logger.debug(
                    f"[PremiumAdapter] Prolongement depuis {base_date}"
                )
            else:
                # Expiré, partir de maintenant
                base_date = timezone.now()
        else:
            # Première activation
            base_date = timezone.now()

        # Nouvelle date d'expiration
        new_expire_date = base_date + timedelta(days=self.PREMIUM_DURATION_DAYS)

        # Mettre à jour le produit
        product.is_premium = True
        product.date_expire_premium = new_expire_date
        product.save(update_fields=['is_premium', 'date_expire_premium', 'updated_at'])

        logger.info(
            f"[PremiumAdapter] ✅ Premium activé jusqu'au {new_expire_date} "
            f"({self.PREMIUM_DURATION_DAYS} jours)"
        )

    def _ensure_product_audience(self, product: Product, user):
        """
        Créer ou mettre à jour ProductAudience pour le produit.

        ProductAudience est créé avec une vue initiale du propriétaire.

        Args:
            product: Produit
            user: Propriétaire du produit
        """
        logger.info(
            f"[PremiumAdapter] 👁️ Gestion ProductAudience - "
            f"Product: {product.id}"
        )

        # Vérifier si ProductAudience existe déjà
        audience, created = ProductAudience.objects.get_or_create(
            product=product,
            user=user,
            defaults={
                'seen_persons': 1,  # Vue initiale du propriétaire
                'clicked_persons': 0,
                'total_views': 1,
                'total_clicks': 0,
                'last_view_at': timezone.now()
            }
        )

        if created:
            logger.info(
                f"[PremiumAdapter] ✅ ProductAudience créé - "
                f"ID: {audience.id}, Vue initiale: 1"
            )
        else:
            logger.debug(
                f"[PremiumAdapter] ProductAudience existe déjà - "
                f"ID: {audience.id}"
            )

    def _create_success_notification(
        self,
        payment_trx: PaymentTrx,
        product: Product
    ):
        """
        Créer une notification de succès pour le boost premium.

        Args:
            payment_trx: Transaction
            product: Produit boosté
        """
        try:
            user = payment_trx.user
            amount = payment_trx.amount
            expire_date = product.date_expire_premium

            content = (
                f"✅ Boost Premium activé pour '{product.name}' ! "
                f"Votre produit bénéficie d'une visibilité maximale "
                f"jusqu'au {expire_date.strftime('%d/%m/%Y %H:%M') if expire_date else 'N/A'}."
            )

            Notification.objects.create(
                user=user,
                content=content,
                unread=True,
                read=False
            )

            logger.debug(
                f"[PremiumAdapter] 📝 Notification succès créée pour user {user.id}"
            )

        except Exception as e:
            # Ne pas bloquer le flux principal
            logger.error(
                f"[PremiumAdapter] ⚠️ Erreur création notification: {e}"
            )

    def on_premium_expiration(self, product: Product):
        """
        Callback appelé quand un produit premium expire.

        À appeler depuis un job Celery Beat périodique qui vérifie les expirations.

        Args:
            product: Produit dont le premium a expiré
        """
        logger.info(
            f"[PremiumAdapter] ⏰ Expiration premium - "
            f"Product: {product.id} ({product.name})"
        )

        with db_transaction.atomic():
            # Désactiver premium
            product.is_premium = False
            product.save(update_fields=['is_premium', 'updated_at'])

        # Créer notification d'expiration
        self._create_expiration_notification(product)

        logger.info(
            f"[PremiumAdapter] ✅ Premium désactivé - Product: {product.id}"
        )

    def _create_expiration_notification(self, product: Product):
        """
        Créer une notification d'expiration premium.

        Args:
            product: Produit expiré
        """
        try:
            user = product.user

            content = (
                f"⏰ Le boost Premium de '{product.name}' a expiré. "
                f"Renouvelez-le pour continuer à bénéficier d'une visibilité maximale."
            )

            Notification.objects.create(
                user=user,
                content=content,
                unread=True,
                read=False
            )

            logger.debug(
                f"[PremiumAdapter] 📝 Notification expiration créée pour user {user.id}"
            )

        except Exception as e:
            logger.error(
                f"[PremiumAdapter] ⚠️ Erreur création notification expiration: {e}"
            )
