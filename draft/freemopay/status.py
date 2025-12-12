"""
FreeMoPay Status Service

Service pour vérifier manuellement le statut d'un paiement.
À utiliser uniquement en backup si le callback n'est pas reçu.

PAS UTILISÉ EN TEMPS NORMAL - Les callbacks sont automatiques !
"""

import logging
from typing import Dict, Any, Optional
from django.utils import timezone

from api.models import PaymentTrx
from .config import get_config, FreemoPayConfig
from .client import FreemoPayHTTPClient
from .auth import get_token_manager, TokenManager
from .callback import CallbackHandler
from .exceptions import (
    FreemoPayError,
    FreemoPayTransactionNotFoundError
)

logger = logging.getLogger(__name__)


class StatusService:
    """
    Service pour vérifier le statut d'un paiement auprès de FreeMoPay.

    Usage:
        service = StatusService()

        # Vérifier par référence FreeMoPay
        status = service.check_status('uuid-freemopay')

        # Ou vérifier et mettre à jour notre DB
        payment_trx = service.sync_status('uuid-freemopay')
    """

    def __init__(
        self,
        config: Optional[FreemoPayConfig] = None,
        client: Optional[FreemoPayHTTPClient] = None,
        token_manager: Optional[TokenManager] = None
    ):
        """
        Initialiser le service de statut.

        Args:
            config: Configuration FreeMoPay
            client: Client HTTP
            token_manager: Gestionnaire de tokens
        """
        self.config = config or get_config()
        self.client = client or FreemoPayHTTPClient(self.config)
        self.token_manager = token_manager or get_token_manager()

    def check_status(self, reference: str) -> Dict[str, Any]:
        """
        Vérifier le statut d'un paiement auprès de FreeMoPay.

        Args:
            reference: Référence FreeMoPay du paiement

        Returns:
            Réponse API FreeMoPay

        Raises:
            FreemoPayError: Si la requête échoue

        Example response:
        {
            "reference": "uuid",
            "merchandRef": "votre-externalId",
            "amount": 100,
            "status": "SUCCESS",  # ou FAILED, PENDING
            "reason": "cancelled"  # si FAILED
        }
        """
        logger.info(f"[StatusService] 🔍 Vérification statut - Reference: {reference}")

        try:
            # Obtenir token Bearer
            bearer_token = self.token_manager.get_token()

            # GET /api/v2/payment/:reference
            url = self.config.get_status_url(reference)

            response = self.client.get(
                url,
                bearer_token=bearer_token,
                timeout=self.config.status_check_timeout
            )

            logger.debug(f"[StatusService] Réponse API: {response}")

            return response

        except FreemoPayError:
            # Propager les erreurs FreeMoPay
            raise

        except Exception as e:
            logger.error(f"[StatusService] 💥 Erreur vérification statut: {e}")
            raise FreemoPayError(
                f"Status check failed: {str(e)}",
                details={'reference': reference, 'error': str(e)}
            ) from e

    def sync_status(self, reference: str) -> PaymentTrx:
        """
        Vérifier le statut ET mettre à jour notre PaymentTrx.

        Cette méthode:
        1. Appelle l'API FreeMoPay
        2. Trouve la PaymentTrx locale
        3. Simule un callback pour mettre à jour
        4. Retourne la PaymentTrx mise à jour

        Args:
            reference: Référence FreeMoPay

        Returns:
            PaymentTrx mise à jour

        Raises:
            FreemoPayTransactionNotFoundError: Si transaction non trouvée
            FreemoPayError: Si requête échoue
        """
        logger.info(f"[StatusService] 🔄 Synchronisation statut - Reference: {reference}")

        # 1. Obtenir le statut depuis l'API
        freemo_status = self.check_status(reference)

        # 2. Trouver la PaymentTrx
        payment_trx = PaymentTrx.objects.filter(reference=reference).first()

        if not payment_trx:
            # Essayer par external_id
            external_id = freemo_status.get('merchandRef')
            if external_id:
                payment_trx = PaymentTrx.objects.filter(
                    external_id=external_id
                ).first()

        if not payment_trx:
            logger.error(
                f"[StatusService] ❌ PaymentTrx non trouvée pour reference: {reference}"
            )
            raise FreemoPayTransactionNotFoundError(reference)

        # 3. Simuler un callback pour mettre à jour
        # On réutilise le CallbackHandler pour maintenir la cohérence
        callback_data = {
            'status': freemo_status.get('status'),
            'reference': freemo_status.get('reference'),
            'externalId': freemo_status.get('merchandRef'),
            'amount': freemo_status.get('amount'),
            'message': freemo_status.get('reason', ''),
            'transactionType': 'DEPOSIT'
        }

        callback_handler = CallbackHandler()
        updated_trx = callback_handler.process(callback_data)

        logger.info(
            f"[StatusService] ✅ PaymentTrx synchronisé - "
            f"ID: {updated_trx.id}, Status: {updated_trx.status}"
        )

        return updated_trx

    def sync_pending_payments(self, max_age_minutes: int = 30) -> int:
        """
        Synchroniser tous les paiements en statut 'pending' de plus de X minutes.

        Utile pour récupérer les paiements dont le callback a été perdu.
        À appeler périodiquement (via Cron/Celery beat).

        Args:
            max_age_minutes: Age minimum des paiements à synchroniser

        Returns:
            Nombre de paiements synchronisés

        Example:
            # Dans un task Celery beat (toutes les 5 minutes)
            @periodic_task(run_every=timedelta(minutes=5))
            def sync_pending_payments():
                service = StatusService()
                count = service.sync_pending_payments(max_age_minutes=10)
                logger.info(f"Synchronized {count} pending payments")
        """
        from datetime import timedelta

        cutoff_time = timezone.now() - timedelta(minutes=max_age_minutes)

        # Trouver les paiements pending de plus de X minutes
        pending_payments = PaymentTrx.objects.filter(
            status='pending',
            created_at__lt=cutoff_time,
            reference__isnull=False  # Seulement ceux qui ont une référence FreeMoPay
        )

        count = pending_payments.count()

        if count == 0:
            logger.info("[StatusService] Aucun paiement pending à synchroniser")
            return 0

        logger.info(
            f"[StatusService] 🔄 Synchronisation de {count} paiements pending "
            f"(plus anciens que {max_age_minutes} minutes)"
        )

        synced = 0

        for payment_trx in pending_payments:
            try:
                self.sync_status(payment_trx.reference)
                synced += 1
            except Exception as e:
                logger.error(
                    f"[StatusService] ⚠️ Erreur sync PaymentTrx {payment_trx.id}: {e}"
                )
                # Continuer avec les autres

        logger.info(f"[StatusService] ✅ {synced}/{count} paiements synchronisés")

        return synced
