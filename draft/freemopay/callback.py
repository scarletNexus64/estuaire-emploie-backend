"""
FreeMoPay Callback Handler

Traite les webhooks/callbacks envoyés par FreeMoPay quand un paiement est terminé.
C'EST LE COEUR DU SYSTÈME - Plus de polling !
"""

import logging
from typing import Dict, Any, Optional
from django.utils import timezone
from django.db import transaction as db_transaction

from api.models import PaymentTrx, Notification
from .exceptions import (
    FreemoPayCallbackError,
    FreemoPayTransactionNotFoundError
)

logger = logging.getLogger(__name__)


class CallbackHandler:
    """
    Handler pour traiter les callbacks/webhooks FreeMoPay.

    Format callback FreeMoPay (doc):
    {
        "status": "SUCCESS" | "FAILED",
        "reference": "uuid-de-transaction",
        "amount": 100,
        "transactionType": "DEPOSIT",
        "externalId": "votreIdUnique",
        "message": "Transaction completed." | "Transaction canceled by the customer."
    }

    Features:
        - Parsing callback
        - Validation données
        - Mise à jour PaymentTrx
        - Idempotence (callback peut être envoyé plusieurs fois)
        - Déclenchement business logic via adapters

    Usage:
        handler = CallbackHandler()
        payment_trx = handler.process(callback_data)
    """

    # Mapping des statuts FreeMoPay vers nos statuts DB
    STATUS_MAPPING = {
        'SUCCESS': 'success',
        'SUCCESSFUL': 'success',
        'COMPLETED': 'success',
        'FAILED': 'error',
        'FAILURE': 'error',
        'CANCELLED': 'cancelled',
        'CANCELED': 'cancelled',
        'PENDING': 'pending',
        'PROCESSING': 'pending',
        'CREATED': 'pending',  # Transaction créée, en attente de paiement
    }

    def process(self, callback_data: Dict[str, Any]) -> PaymentTrx:
        """
        Traiter un callback FreeMoPay.

        Args:
            callback_data: Données du callback

        Returns:
            PaymentTrx mise à jour

        Raises:
            FreemoPayCallbackError: Si le traitement échoue
            FreemoPayTransactionNotFoundError: Si transaction introuvable
        """
        logger.info(f"[CallbackHandler] 📥 Callback reçu: {callback_data}")

        # 1. Parser et valider les données
        parsed = self._parse_callback(callback_data)

        # 2. Trouver la transaction
        payment_trx = self._find_transaction(parsed)

        if not payment_trx:
            logger.error(
                f"[CallbackHandler] ❌ Transaction non trouvée - "
                f"Reference: {parsed.get('reference')}, "
                f"ExternalId: {parsed.get('external_id')}"
            )
            raise FreemoPayTransactionNotFoundError(
                parsed.get('reference') or parsed.get('external_id') or 'unknown'
            )

        # 3. Vérifier idempotence (callback déjà traité ?)
        if self._is_already_processed(payment_trx, parsed['status']):
            logger.info(
                f"[CallbackHandler] ℹ️ Callback déjà traité - "
                f"PaymentTrx: {payment_trx.id}, Status actuel: {payment_trx.status}"
            )
            return payment_trx

        # 4. Mettre à jour la transaction (atomiquement)
        with db_transaction.atomic():
            # Lock la ligne pour éviter race conditions
            payment_trx = PaymentTrx.objects.select_for_update().get(id=payment_trx.id)

            # Mettre à jour les champs
            old_status = payment_trx.status
            new_status = parsed['status']

            payment_trx.status = new_status
            payment_trx.reference = parsed.get('reference') or payment_trx.reference

            # Mettre à jour updated_at
            payment_trx.save()

            logger.info(
                f"[CallbackHandler] ✅ PaymentTrx mis à jour - "
                f"ID: {payment_trx.id}, {old_status} → {new_status}"
            )

        # 5. Créer notification utilisateur
        self._create_notification(payment_trx, parsed)

        # 6. Déclencher business logic via adapter
        self._trigger_business_logic(payment_trx, old_status, new_status)

        return payment_trx

    def _parse_callback(self, callback_data: Dict[str, Any]) -> Dict[str, Any]:
        """
        Parser et valider les données du callback.

        Args:
            callback_data: Données brutes

        Returns:
            Données parsées et validées

        Raises:
            FreemoPayCallbackError: Si format invalide
        """
        # Extraire les champs importants
        status_raw = callback_data.get('status', '').upper()
        reference = callback_data.get('reference')
        external_id = callback_data.get('externalId')
        amount = callback_data.get('amount')
        message = callback_data.get('message', '')

        # Valider status
        if not status_raw:
            raise FreemoPayCallbackError(
                "Missing 'status' field in callback",
                details=callback_data
            )

        # Mapper le statut
        our_status = self.STATUS_MAPPING.get(status_raw)

        if not our_status:
            logger.warning(
                f"[CallbackHandler] ⚠️ Statut inconnu: {status_raw}, "
                f"utilisation de 'pending' par défaut"
            )
            our_status = 'pending'

        # Valider qu'on a au moins une référence
        if not reference and not external_id:
            raise FreemoPayCallbackError(
                "Missing both 'reference' and 'externalId' in callback",
                details=callback_data
            )

        return {
            'status': our_status,
            'status_raw': status_raw,
            'reference': reference,
            'external_id': external_id,
            'amount': amount,
            'message': message,
            'raw_data': callback_data
        }

    def _find_transaction(self, parsed: Dict[str, Any]) -> Optional[PaymentTrx]:
        """
        Trouver la PaymentTrx correspondante.

        Essaie d'abord par reference, puis par external_id.

        Args:
            parsed: Données parsées du callback

        Returns:
            PaymentTrx si trouvée, None sinon
        """
        # Essayer par reference d'abord
        reference = parsed.get('reference')
        if reference:
            trx = PaymentTrx.objects.filter(reference=reference).first()
            if trx:
                logger.debug(
                    f"[CallbackHandler] Transaction trouvée par reference: {reference}"
                )
                return trx

        # Sinon essayer par external_id
        external_id = parsed.get('external_id')
        if external_id:
            trx = PaymentTrx.objects.filter(external_id=external_id).first()
            if trx:
                logger.debug(
                    f"[CallbackHandler] Transaction trouvée par external_id: {external_id}"
                )
                return trx

        return None

    def _is_already_processed(
        self,
        payment_trx: PaymentTrx,
        new_status: str
    ) -> bool:
        """
        Vérifier si le callback a déjà été traité (idempotence).

        Args:
            payment_trx: Transaction
            new_status: Nouveau statut du callback

        Returns:
            True si déjà traité
        """
        # Si la transaction est déjà dans un statut final (success, error, cancelled)
        # et que le callback veut mettre le même statut, c'est déjà traité
        final_statuses = ['success', 'error', 'cancelled']

        if payment_trx.status in final_statuses and payment_trx.status == new_status:
            return True

        return False

    def _create_notification(
        self,
        payment_trx: PaymentTrx,
        parsed: Dict[str, Any]
    ):
        """
        Créer une notification pour l'utilisateur.

        Args:
            payment_trx: Transaction
            parsed: Données du callback
        """
        try:
            user = payment_trx.user
            status = parsed['status']
            amount = payment_trx.amount
            package_name = payment_trx.package.name if payment_trx.package else 'Package'

            if status == 'success':
                content = (
                    f"✅ Paiement réussi de {amount} FCFA pour {package_name}. "
                    f"Votre commande est confirmée."
                )
            elif status == 'error' or status == 'cancelled':
                reason = parsed.get('message', 'Raison inconnue')
                content = (
                    f"❌ Paiement échoué de {amount} FCFA pour {package_name}. "
                    f"Raison: {reason}"
                )
            else:
                content = (
                    f"⏳ Paiement en cours de {amount} FCFA pour {package_name}."
                )

            Notification.objects.create(
                user=user,
                content=content,
                unread=True,
                read=False
            )

            logger.debug(
                f"[CallbackHandler] 📝 Notification créée pour user {user.id}"
            )

        except Exception as e:
            # Ne pas bloquer le flux principal si notification échoue
            logger.error(
                f"[CallbackHandler] ⚠️ Erreur création notification: {e}"
            )

    def _trigger_business_logic(
        self,
        payment_trx: PaymentTrx,
        old_status: str,
        new_status: str
    ):
        """
        Déclencher la logique métier selon le type de paiement.

        Cette méthode délègue aux adapters appropriés:
        - PackagePaymentAdapter pour les paiements package
        - PremiumPaymentAdapter pour les paiements premium
        - RenewalPaymentAdapter pour les renouvellements

        Args:
            payment_trx: Transaction
            old_status: Ancien statut
            new_status: Nouveau statut
        """
        # ⚠️ IMPORTANT: Ne déclencher la logique métier QUE si le paiement a réussi
        if new_status != 'success':
            logger.info(
                f"[CallbackHandler] ⏭️ Skip business logic - "
                f"Statut non-success: {new_status}"
            )
            return

        # Importer les adapters (éviter circular imports)
        from .adapters import (
            PackagePaymentAdapter,
            PremiumPaymentAdapter,
            RenewalPaymentAdapter
        )

        try:
            # Détecter le type de paiement
            if payment_trx.product:
                # Paiement premium (boost produit)
                logger.info(
                    f"[CallbackHandler] 🚀 Déclenchement PremiumPaymentAdapter - "
                    f"Product: {payment_trx.product.id}"
                )
                adapter = PremiumPaymentAdapter()
                adapter.on_payment_success(payment_trx)

            elif 'RENEW' in payment_trx.external_id.upper():
                # Renouvellement (détecté par external_id)
                logger.info(
                    f"[CallbackHandler] 🔄 Déclenchement RenewalPaymentAdapter - "
                    f"External ID: {payment_trx.external_id}"
                )
                adapter = RenewalPaymentAdapter()
                adapter.on_payment_success(payment_trx)

            else:
                # Paiement package standard (stockage/certification)
                logger.info(
                    f"[CallbackHandler] 📦 Déclenchement PackagePaymentAdapter - "
                    f"Package: {payment_trx.package.name if payment_trx.package else 'N/A'}"
                )
                adapter = PackagePaymentAdapter()
                adapter.on_payment_success(payment_trx)

        except Exception as e:
            # Logger l'erreur mais ne pas bloquer le callback
            logger.error(
                f"[CallbackHandler] 💥 Erreur business logic: {e}",
                exc_info=True
            )
            # On ne propage pas l'erreur pour que FreeMoPay considère le callback comme reçu


    def verify_signature(
        self,
        callback_data: Dict[str, Any],
        signature: str
    ) -> bool:
        """
        Vérifier la signature du callback (si FreeMoPay envoie une signature).

        TODO: Implémenter si FreeMoPay fournit un mécanisme de signature.

        Args:
            callback_data: Données du callback
            signature: Signature reçue

        Returns:
            True si valide
        """
        # À implémenter si FreeMoPay documente le mécanisme de signature
        logger.warning(
            "[CallbackHandler] Signature verification not implemented yet"
        )
        return True
