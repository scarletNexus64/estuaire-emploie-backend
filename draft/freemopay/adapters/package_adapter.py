"""
Package Payment Adapter

Logique métier pour les paiements de packages (stockage/certification).
"""

import logging
from datetime import timedelta
from typing import Optional
from django.utils import timezone
from django.db import transaction as db_transaction

from api.models import (
    PaymentTrx,
    UserSubscription,
    Product,
    Notification
)

logger = logging.getLogger(__name__)


class PackagePaymentAdapter:
    """
    Adapter pour gérer la logique métier des paiements de packages.

    Responsabilités:
        - Créer/mettre à jour UserSubscription
        - Gérer les certifications (eco, vip, classic)
        - Notifications de succès

    Note: ProfilePayment est créé uniquement à l'inscription (register.py)

    Usage:
        adapter = PackagePaymentAdapter()
        adapter.on_payment_success(payment_trx)
    """

    def on_payment_success(self, payment_trx: PaymentTrx):
        """
        Callback appelé quand un paiement package réussit.

        Args:
            payment_trx: Transaction de paiement réussie
        """
        logger.info(
            f"[PackageAdapter] 🎉 Traitement paiement réussi - "
            f"PaymentTrx: {payment_trx.id}, Package: {payment_trx.package.name}"
        )

        # Vérifier que c'est bien un paiement de package
        if not payment_trx.package:
            logger.error(
                f"[PackageAdapter] ❌ Pas de package associé - "
                f"PaymentTrx: {payment_trx.id}"
            )
            return

        package = payment_trx.package

        with db_transaction.atomic():
            # 1. Créer/mettre à jour UserSubscription
            subscription = self._create_or_update_subscription(payment_trx)

            # 2. Gérer les certifications si le package en fournit
            if package.is_certif_eco or package.is_certif_vip or package.is_certif_classic:
                self._apply_certifications(payment_trx.user, package)

        # 4. Créer notification de succès
        self._create_success_notification(payment_trx, subscription)

        logger.info(
            f"[PackageAdapter] ✅ Traitement terminé - "
            f"Subscription: {subscription.id if subscription else 'N/A'}"
        )

    def _create_or_update_subscription(
        self,
        payment_trx: PaymentTrx
    ) -> Optional[UserSubscription]:
        """
        Créer une NOUVELLE souscription utilisateur.

        ⚠️ IMPORTANT: Cette méthode CRÉE TOUJOURS une nouvelle subscription.
        Elle ne prolonge JAMAIS (c'est le rôle de RenewalPaymentAdapter).

        Chaque achat = nouvelle subscription avec son propre label/espace.

        Args:
            payment_trx: Transaction de paiement

        Returns:
            UserSubscription créée

        Raises:
            Exception si le label existe déjà pour ce user+package
        """
        user = payment_trx.user
        package = payment_trx.package
        label = payment_trx.subscription_label or package.name

        # ⚠️ Vérifier si le label existe déjà pour ce user+package
        # Si oui, c'est un doublon à rejeter
        existing_with_label = UserSubscription.objects.filter(
            user=user,
            package=package,
            label=label
        ).first()

        if existing_with_label:
            logger.error(
                f"[PackageAdapter] ❌ Label '{label}' existe déjà - "
                f"Subscription ID: {existing_with_label.id}"
            )
            raise ValueError(
                f"Une souscription avec le label '{label}' existe déjà pour ce package. "
                f"Veuillez choisir un autre nom."
            )

        # ✅ Créer nouvelle subscription
        logger.info(
            f"[PackageAdapter] 🆕 Création nouvelle subscription - "
            f"User: {user.id}, Package: {package.name}, Label: '{label}'"
        )

        expired_date = timezone.now() + timedelta(days=package.nbrday)
        storage_available = package.storage if package.storage else 0

        subscription = UserSubscription.objects.create(
            user=user,
            package=package,
            expired_date=expired_date,
            is_expired=False,
            is_freetrial=False,
            label=label,
            storage_used=0,
            storage_available=storage_available,
            longitude=payment_trx.longitude,
            latitude=payment_trx.latitude,
            city=payment_trx.city,
            number_of_free_trial_post=0
        )

        logger.info(
            f"[PackageAdapter] ✅ Subscription créée - "
            f"ID: {subscription.id}, Label: '{label}', "
            f"Stockage: {storage_available} GB, Expire: {expired_date}"
        )

        return subscription

    def _apply_certifications(self, user, package):
        """
        Appliquer les certifications du package à tous les produits de l'utilisateur.

        Args:
            user: Utilisateur
            package: Package avec certifications
        """
        logger.info(
            f"[PackageAdapter] 🏅 Application certifications - "
            f"User: {user.id}, Package: {package.name}"
        )

        # Récupérer tous les produits actifs de l'utilisateur
        products = Product.objects.filter(user=user, active=True)

        if not products.exists():
            logger.info(
                f"[PackageAdapter] Aucun produit actif pour user {user.id}"
            )
            return

        # Préparer les champs à mettre à jour
        update_fields = {}

        if package.is_certif_eco:
            update_fields['is_certified_eco'] = True
        if package.is_certif_vip:
            update_fields['is_certified_vip'] = True
        if package.is_certif_classic:
            update_fields['is_certified_classic'] = True

        if not update_fields:
            return

        # Mettre à jour tous les produits en une seule requête
        count = products.update(**update_fields)

        logger.info(
            f"[PackageAdapter] ✅ {count} produits certifiés - "
            f"Certifications: {list(update_fields.keys())}"
        )

    def _create_success_notification(
        self,
        payment_trx: PaymentTrx,
        subscription: Optional[UserSubscription]
    ):
        """
        Créer une notification de succès détaillée.

        Args:
            payment_trx: Transaction
            subscription: Subscription créée/mise à jour
        """
        try:
            user = payment_trx.user
            package = payment_trx.package
            amount = payment_trx.amount

            # Construire le message selon le type de package
            if package.is_storage:
                content = (
                    f"✅ Paiement réussi de {amount} FCFA pour {package.name}. "
                    f"Votre espace de stockage est actif jusqu'au "
                    f"{subscription.expired_date.strftime('%d/%m/%Y') if subscription else 'N/A'}."
                )
            elif package.is_certif_eco or package.is_certif_vip or package.is_certif_classic:
                certifications = []
                if package.is_certif_eco:
                    certifications.append("ECO")
                if package.is_certif_vip:
                    certifications.append("VIP")
                if package.is_certif_classic:
                    certifications.append("CLASSIC")

                content = (
                    f"✅ Paiement réussi de {amount} FCFA. "
                    f"Vos produits sont maintenant certifiés: {', '.join(certifications)}."
                )
            else:
                content = (
                    f"✅ Paiement réussi de {amount} FCFA pour {package.name}."
                )

            Notification.objects.create(
                user=user,
                content=content,
                unread=True,
                read=False
            )

            logger.debug(
                f"[PackageAdapter] 📝 Notification succès créée pour user {user.id}"
            )

        except Exception as e:
            # Ne pas bloquer le flux principal
            logger.error(
                f"[PackageAdapter] ⚠️ Erreur création notification: {e}"
            )
