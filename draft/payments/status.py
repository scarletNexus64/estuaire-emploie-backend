"""
Payment Status View - Phase 4 Refactoring

Vue pour vérifier manuellement le statut d'un paiement.
Utilise StatusService au lieu du polling deprecated.
"""

from rest_framework import status
from rest_framework.response import Response
from rest_framework.views import APIView
from drf_yasg.utils import swagger_auto_schema
from drf_yasg import openapi
import logging

from api.models import PaymentTrx
from api.serializers import PaymentTrxSerializer
from api.services.freemopay import StatusService
from api.services.freemopay.exceptions import (
    FreemoPayError,
    FreemoPayTransactionNotFoundError
)

logger = logging.getLogger(__name__)


class CheckPaymentStatusView(APIView):
    """
    Vue pour vérifier le statut d'un paiement FreeMoPay.

    Utilise StatusService.sync_status() pour:
    1. Interroger l'API FreeMoPay
    2. Mettre à jour notre PaymentTrx
    3. Déclencher la business logic si nécessaire

    À utiliser en backup si le callback n'est pas reçu.
    """

    permission_classes = []  # Public (peut être appelé par le client)

    @swagger_auto_schema(
        operation_description=(
            "Vérifie le statut actuel d'un paiement FreeMoPay.\n\n"
            "**Usage:**\n"
            "- Vérifier un paiement après un délai anormal\n"
            "- Obtenir le statut actuel si callback pas reçu\n"
            "- Forcer une synchronisation manuelle\n\n"
            "**Process:**\n"
            "1. Appel GET /api/v2/payment/:reference vers FreeMoPay\n"
            "2. Mise à jour PaymentTrx en DB\n"
            "3. Déclenchement business logic si statut change\n"
            "4. Retour PaymentTrx à jour\n\n"
            "**Note:**\n"
            "En temps normal, les callbacks automatiques suffisent.\n"
            "Cette API est un backup pour les cas exceptionnels."
        ),
        operation_summary="Vérifier le statut d'un paiement",
        tags=['Paiements V2'],
        manual_parameters=[
            openapi.Parameter(
                'reference',
                openapi.IN_PATH,
                description='Référence unique FreeMoPay du paiement',
                type=openapi.TYPE_STRING,
                required=True,
                example='550e8400-e29b-41d4-a716-446655440000'
            )
        ],
        responses={
            status.HTTP_200_OK: openapi.Response(
                description="Statut du paiement",
                schema=openapi.Schema(
                    type=openapi.TYPE_OBJECT,
                    properties={
                        'payment_trx': openapi.Schema(
                            type=openapi.TYPE_OBJECT,
                            description='Transaction mise à jour'
                        ),
                        'status_updated': openapi.Schema(
                            type=openapi.TYPE_BOOLEAN,
                            description='Indique si le statut a changé'
                        ),
                        'freemopay_status': openapi.Schema(
                            type=openapi.TYPE_OBJECT,
                            description='Statut brut FreeMoPay',
                            properties={
                                'reference': openapi.Schema(type=openapi.TYPE_STRING),
                                'status': openapi.Schema(type=openapi.TYPE_STRING),
                                'amount': openapi.Schema(type=openapi.TYPE_NUMBER),
                                'reason': openapi.Schema(type=openapi.TYPE_STRING)
                            }
                        )
                    }
                )
            ),
            status.HTTP_404_NOT_FOUND: openapi.Response(
                description="Transaction non trouvée"
            ),
            status.HTTP_400_BAD_REQUEST: openapi.Response(
                description="Erreur lors de la vérification"
            )
        }
    )
    def get(self, request, reference):
        """
        Vérifier le statut d'un paiement par sa référence FreeMoPay.
        """
        try:
            logger.info(
                f"[CheckPaymentStatusView] 🔍 Vérification statut - "
                f"Reference: {reference}"
            )

            # 1. Trouver la PaymentTrx en DB (pour comparer)
            try:
                old_payment_trx = PaymentTrx.objects.get(reference=reference)
                old_status = old_payment_trx.status
                logger.debug(
                    f"[CheckPaymentStatusView] PaymentTrx trouvée - "
                    f"ID: {old_payment_trx.id}, Status actuel: {old_status}"
                )
            except PaymentTrx.DoesNotExist:
                logger.error(
                    f"[CheckPaymentStatusView] ❌ PaymentTrx non trouvée - "
                    f"Reference: {reference}"
                )
                return Response({
                    'error': f'Aucune transaction trouvée avec la référence {reference}'
                }, status=status.HTTP_404_NOT_FOUND)

            # 2. Synchroniser avec FreeMoPay via StatusService
            status_service = StatusService()

            try:
                # sync_status() appelle l'API FreeMoPay ET met à jour notre DB
                updated_payment_trx = status_service.sync_status(reference)

                # Vérifier si le statut a changé
                status_updated = (old_status != updated_payment_trx.status)

                logger.info(
                    f"[CheckPaymentStatusView] ✅ Statut synchronisé - "
                    f"PaymentTrx: {updated_payment_trx.id}, "
                    f"Status: {old_status} → {updated_payment_trx.status}, "
                    f"Updated: {status_updated}"
                )

                # 3. Récupérer aussi le statut brut FreeMoPay
                freemopay_status = status_service.check_status(reference)

                # 4. Sérialiser et retourner
                serializer = PaymentTrxSerializer(updated_payment_trx)

                return Response({
                    'payment_trx': serializer.data,
                    'status_updated': status_updated,
                    'old_status': old_status,
                    'new_status': updated_payment_trx.status,
                    'freemopay_status': freemopay_status
                }, status=status.HTTP_200_OK)

            except FreemoPayTransactionNotFoundError as e:
                logger.error(
                    f"[CheckPaymentStatusView] ❌ Transaction introuvable chez FreeMoPay: "
                    f"{e.message}"
                )
                return Response({
                    'error': e.message,
                    'reference': reference
                }, status=status.HTTP_404_NOT_FOUND)

            except FreemoPayError as e:
                logger.error(
                    f"[CheckPaymentStatusView] ❌ Erreur FreeMoPay: {e.message}"
                )
                return Response({
                    'error': f'Erreur FreeMoPay: {e.message}',
                    'details': e.details
                }, status=status.HTTP_400_BAD_REQUEST)

        except Exception as e:
            logger.error(
                f"[CheckPaymentStatusView] 💥 Erreur inattendue: {str(e)}",
                exc_info=True
            )
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)
