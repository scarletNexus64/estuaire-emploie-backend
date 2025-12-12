"""
Payment Callback View - Phase 4 Refactoring

Vue unifiée pour recevoir les webhooks FreeMoPay.
Traite TOUS les types de paiements (package, premium, renewal).
"""

from rest_framework import status
from rest_framework.response import Response
from rest_framework.views import APIView
from drf_yasg.utils import swagger_auto_schema
from drf_yasg import openapi
import logging
import json

from api.services.freemopay import CallbackHandler
from api.services.freemopay.exceptions import (
    FreemoPayCallbackError,
    FreemoPayTransactionNotFoundError,
    FreemoPayError
)

logger = logging.getLogger(__name__)


class PaymentCallbackView(APIView):
    """
    Vue pour recevoir les callbacks/webhooks de FreeMoPay.

    Cette vue est appelée automatiquement par FreeMoPay quand un paiement
    change de statut (SUCCESS, FAILED, etc.).

    Architecture:
        1. Réception callback FreeMoPay
        2. Parsing et validation via CallbackHandler
        3. Mise à jour PaymentTrx
        4. Déclenchement du bon adapter (Package, Premium ou Renewal)
        5. Création notification utilisateur
        6. Retour 200 OK à FreeMoPay

    Important:
        - Cette URL doit être publique (accessible par FreeMoPay)
        - Pas d'authentification requise
        - Doit répondre rapidement (< 10s)
        - Doit être idempotente (callback peut être envoyé plusieurs fois)
    """

    permission_classes = []  # Pas d'auth (appelé par FreeMoPay)

    @swagger_auto_schema(
        operation_description=(
            "Webhook appelé automatiquement par FreeMoPay quand un paiement change de statut.\n\n"
            "**Format callback FreeMoPay:**\n"
            "```json\n"
            "{\n"
            '  "status": "SUCCESS",\n'
            '  "reference": "uuid-transaction",\n'
            '  "amount": 100,\n'
            '  "transactionType": "DEPOSIT",\n'
            '  "externalId": "TRX-20251125161234",\n'
            '  "message": "Transaction completed."\n'
            "}\n"
            "```\n\n"
            "**Flow:**\n"
            "1. FreeMoPay envoie callback POST\n"
            "2. CallbackHandler parse les données\n"
            "3. PaymentTrx mise à jour (pending → success/error)\n"
            "4. Business logic déclenchée via adapter approprié:\n"
            "   - PackagePaymentAdapter: stockage, certifications\n"
            "   - PremiumPaymentAdapter: boost produit 14 jours\n"
            "   - RenewalPaymentAdapter: extension expiration\n"
            "5. Notification envoyée à l'utilisateur\n"
            "6. Retour 200 OK à FreeMoPay\n\n"
            "**Important:**\n"
            "- Cette URL doit être configurée dans FREEMOPAY_CALLBACK_URL\n"
            "- En dev: utiliser ngrok pour exposer localhost\n"
            "- En prod: HTTPS obligatoire"
        ),
        operation_summary="Webhook FreeMoPay (appelé automatiquement)",
        tags=['Webhooks'],
        request_body=openapi.Schema(
            type=openapi.TYPE_OBJECT,
            required=['status', 'reference'],
            properties={
                'status': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Statut du paiement (SUCCESS, FAILED, CANCELLED, etc.)',
                    example='SUCCESS',
                    enum=['SUCCESS', 'SUCCESSFUL', 'FAILED', 'FAILURE', 'CANCELLED', 'CANCELED', 'PENDING']
                ),
                'reference': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Référence unique FreeMoPay de la transaction',
                    example='550e8400-e29b-41d4-a716-446655440000'
                ),
                'externalId': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='ID externe fourni lors de l\'init paiement',
                    example='TRX-20251125161234'
                ),
                'amount': openapi.Schema(
                    type=openapi.TYPE_NUMBER,
                    format=openapi.FORMAT_FLOAT,
                    description='Montant de la transaction',
                    example=100.0
                ),
                'transactionType': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Type de transaction',
                    example='DEPOSIT'
                ),
                'message': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Message descriptif',
                    example='Transaction completed.'
                )
            }
        ),
        responses={
            status.HTTP_200_OK: openapi.Response(
                description="Callback traité avec succès",
                schema=openapi.Schema(
                    type=openapi.TYPE_OBJECT,
                    properties={
                        'status': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='success'
                        ),
                        'message': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='Callback traité avec succès'
                        ),
                        'payment_trx_id': openapi.Schema(
                            type=openapi.TYPE_INTEGER,
                            example=456
                        ),
                        'payment_status': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='success'
                        )
                    }
                )
            ),
            status.HTTP_400_BAD_REQUEST: openapi.Response(
                description="Format callback invalide"
            ),
            status.HTTP_404_NOT_FOUND: openapi.Response(
                description="Transaction non trouvée"
            ),
            status.HTTP_500_INTERNAL_SERVER_ERROR: openapi.Response(
                description="Erreur traitement callback"
            )
        }
    )
    def post(self, request):
        """
        Recevoir et traiter un callback FreeMoPay.
        """
        try:
            # 1. Logger le callback reçu
            callback_data = request.data

            logger.info(
                f"[PaymentCallbackView] 📥 Callback reçu - "
                f"Status: {callback_data.get('status')}, "
                f"Reference: {callback_data.get('reference')}, "
                f"ExternalId: {callback_data.get('externalId')}"
            )

            # Log détaillé en debug
            logger.debug(
                f"[PaymentCallbackView] Callback complet: "
                f"{json.dumps(callback_data, indent=2)}"
            )

            # 2. Valider que c'est bien du JSON
            if not callback_data:
                logger.error("[PaymentCallbackView] ❌ Callback vide")
                return Response({
                    'error': 'Callback data is empty'
                }, status=status.HTTP_400_BAD_REQUEST)

            # 3. Traiter le callback via CallbackHandler
            callback_handler = CallbackHandler()

            try:
                payment_trx = callback_handler.process(callback_data)

                logger.info(
                    f"[PaymentCallbackView] ✅ Callback traité avec succès - "
                    f"PaymentTrx: {payment_trx.id}, Status: {payment_trx.status}, "
                    f"Reference: {payment_trx.reference}"
                )

                # 4. Retourner 200 OK à FreeMoPay
                # Important: FreeMoPay attend une réponse 2xx pour considérer
                # le callback comme reçu. Sinon il réessaiera.
                return Response({
                    'status': 'success',
                    'message': 'Callback traité avec succès',
                    'payment_trx_id': payment_trx.id,
                    'payment_status': payment_trx.status
                }, status=status.HTTP_200_OK)

            except FreemoPayTransactionNotFoundError as e:
                logger.error(
                    f"[PaymentCallbackView] ❌ Transaction non trouvée: {e.message}"
                )
                # Retourner 404 pour que FreeMoPay sache que cette transaction n'existe pas
                return Response({
                    'error': e.message,
                    'reference': e.reference
                }, status=status.HTTP_404_NOT_FOUND)

            except FreemoPayCallbackError as e:
                logger.error(
                    f"[PaymentCallbackView] ❌ Erreur callback: {e.message}"
                )
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayError as e:
                logger.error(
                    f"[PaymentCallbackView] 💥 Erreur FreeMoPay: {e.message}"
                )
                # Retourner 500 pour que FreeMoPay réessaie le callback plus tard
                return Response({
                    'error': f'Erreur traitement: {e.message}'
                }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)

        except json.JSONDecodeError as e:
            logger.error(
                f"[PaymentCallbackView] ❌ JSON invalide: {str(e)}"
            )
            return Response({
                'error': 'Invalid JSON format'
            }, status=status.HTTP_400_BAD_REQUEST)

        except Exception as e:
            logger.error(
                f"[PaymentCallbackView] 💥 Erreur inattendue: {str(e)}",
                exc_info=True
            )
            # Retourner 500 pour que FreeMoPay réessaie
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


    def get(self, request):
        """
        Endpoint GET pour tester que le callback est accessible.

        Utile pour:
        - Vérifier que l'URL est bien publique
        - Tester la configuration ngrok en dev
        - Health check
        """
        logger.info("[PaymentCallbackView] 🔍 GET request reçu (test endpoint)")

        return Response({
            'status': 'ok',
            'message': 'Callback endpoint is accessible',
            'endpoint': 'POST to this URL to send FreeMoPay callbacks',
            'expected_format': {
                'status': 'SUCCESS | FAILED | CANCELLED',
                'reference': 'uuid-freemopay',
                'externalId': 'your-external-id',
                'amount': 100.0,
                'message': 'Transaction completed.'
            }
        }, status=status.HTTP_200_OK)
