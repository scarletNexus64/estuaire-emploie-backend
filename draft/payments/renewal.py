"""
Renewal Payment View - Phase 4 Refactoring

Vue simplifiée pour le renouvellement de subscriptions.
Utilise PaymentService (pas de polling, réponse < 5s).
"""

from rest_framework import status
from rest_framework.response import Response
from rest_framework.views import APIView
from rest_framework.permissions import IsAuthenticated
from drf_yasg.utils import swagger_auto_schema
from drf_yasg import openapi
from decimal import Decimal
from django.utils import timezone
import logging
import time

from api.models import UserSubscription, ProfilePayment
from api.services.freemopay import PaymentService
from api.services.freemopay.payment import generate_external_id
from api.services.freemopay.exceptions import (
    FreemoPayValidationError,
    FreemoPayDuplicateError,
    FreemoPayPaymentError,
    FreemoPayError
)

logger = logging.getLogger(__name__)


class RenewalPaymentView(APIView):
    """
    Vue pour renouveler une subscription de stockage.

    Architecture:
        1. Vérification que la subscription appartient à l'utilisateur
        2. Vérification que c'est une subscription de type stockage
        3. Init paiement via PaymentService (< 5s)
        4. Retour immédiat avec référence
        5. FreeMoPay envoie callback → RenewalPaymentAdapter activé

    L'adapter RenewalPaymentAdapter gère:
        - Extension de expired_date (+ durée du package)
        - Réactivation si expiré
    """

    permission_classes = [IsAuthenticated]

    @swagger_auto_schema(
        operation_description=(
            "Renouvelle une souscription de stockage existante.\n\n"
            "**Nouvelle architecture (Phase 4):**\n"
            "- Réponse immédiate (< 5s)\n"
            "- Pas de polling, utilise les callbacks FreeMoPay\n"
            "- Retourne statut 'pending' avec référence\n\n"
            "**Flow:**\n"
            "1. Vérifie que la souscription existe et est de type stockage\n"
            "2. Init paiement (5s max)\n"
            "3. Retour 202 ACCEPTED avec référence\n"
            "4. FreeMoPay envoie callback automatiquement\n"
            "5. RenewalPaymentAdapter étend l'expiration\n"
            "6. Notification envoyée à l'utilisateur"
        ),
        operation_summary="Renouveler une souscription de stockage (nouvelle architecture)",
        tags=['Souscriptions'],
        request_body=openapi.Schema(
            type=openapi.TYPE_OBJECT,
            properties={
                'payer': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Numéro de téléphone pour le paiement (optionnel, utilise celui de l\'utilisateur si absent)',
                    example='+243812345678'
                )
            }
        ),
        responses={
            status.HTTP_202_ACCEPTED: openapi.Response(
                description="Renouvellement initié avec succès",
                schema=openapi.Schema(
                    type=openapi.TYPE_OBJECT,
                    properties={
                        'status': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='pending'
                        ),
                        'reference': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='uuid-freemopay'
                        ),
                        'payment_trx_id': openapi.Schema(
                            type=openapi.TYPE_INTEGER,
                            example=123
                        ),
                        'external_id': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='RENEW-175-20251125161234'
                        ),
                        'amount': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='50.00'
                        ),
                        'subscription': openapi.Schema(
                            type=openapi.TYPE_OBJECT,
                            properties={
                                'id': openapi.Schema(type=openapi.TYPE_INTEGER, example=175),
                                'label': openapi.Schema(type=openapi.TYPE_STRING, example='Espace IT'),
                                'current_expiry': openapi.Schema(
                                    type=openapi.TYPE_STRING,
                                    example='2025-09-30T15:18:46.659222Z'
                                )
                            }
                        ),
                        'message': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='Renouvellement initié. Veuillez confirmer sur votre téléphone.'
                        ),
                        'response_time': openapi.Schema(
                            type=openapi.TYPE_NUMBER,
                            example=4.1
                        )
                    }
                )
            ),
            status.HTTP_400_BAD_REQUEST: openapi.Response(
                description="Erreur de validation (ex: souscription n'est pas de type stockage)"
            ),
            status.HTTP_404_NOT_FOUND: openapi.Response(
                description="Souscription non trouvée ou n'appartient pas à l'utilisateur"
            ),
            status.HTTP_409_CONFLICT: openapi.Response(
                description="Renouvellement en double"
            )
        }
    )
    def post(self, request, subscription_id):
        """
        Renouveler une souscription de stockage.

        Args:
            subscription_id: ID de la souscription à renouveler (dans l'URL)
        """
        start_time = time.time()

        try:
            # 1. Récupération de la souscription
            try:
                subscription = UserSubscription.objects.select_related(
                    'package', 'user'
                ).get(
                    id=subscription_id,
                    user=request.user
                )
            except UserSubscription.DoesNotExist:
                logger.error(
                    f"[RenewalPaymentView] ❌ Subscription {subscription_id} "
                    f"non trouvée pour user {request.user.id}"
                )
                return Response({
                    'error': 'Souscription introuvable'
                }, status=status.HTTP_404_NOT_FOUND)

            logger.info(
                f"[RenewalPaymentView] 🔄 Demande de renouvellement - "
                f"Subscription: {subscription.id}, Label: {subscription.label}, "
                f"User: {request.user.id}"
            )

            # 2. Vérification que c'est bien une souscription de stockage
            if not subscription.package or not subscription.package.is_storage:
                logger.error(
                    f"[RenewalPaymentView] ❌ Subscription {subscription_id} "
                    f"n'est pas de type stockage"
                )
                return Response({
                    'error': 'Cette souscription n\'est pas de type stockage'
                }, status=status.HTTP_400_BAD_REQUEST)

            # 3. Récupérer le numéro de paiement
            payer = request.data.get('payer', subscription.user.mobile_number)
            if not payer:
                logger.error(
                    f"[RenewalPaymentView] ❌ Aucun numéro de téléphone disponible "
                    f"pour user {request.user.id}"
                )
                return Response({
                    'error': 'Aucun numéro de téléphone disponible pour le paiement'
                }, status=status.HTTP_400_BAD_REQUEST)

            # 4. Préparer les données de paiement
            package = subscription.package
            amount_decimal = Decimal(str(package.price))

            # External ID avec format spécifique pour renewal
            external_id = f'RENEW-{subscription.id}-{timezone.now().strftime("%Y%m%d%H%M%S")}'

            # Description
            description = (
                f'Renouvellement {subscription.label or "Espace de stockage"} - '
                f'{package.name}'
            )

            # ProfilePayment (optionnel)
            profile_payment = None
            try:
                profile_payment = ProfilePayment.objects.get(user=subscription.user)
            except ProfilePayment.DoesNotExist:
                pass

            # Label de subscription
            subscription_label = subscription.label or package.name

            logger.info(
                f"[RenewalPaymentView] 💰 Montant: {amount_decimal}, "
                f"Package: {package.name}, Payer: {payer}"
            )

            # 5. Initialiser le paiement via PaymentService
            payment_service = PaymentService()

            try:
                payment_trx = payment_service.init_payment(
                    user=subscription.user,
                    package=package,
                    payer=payer,
                    amount=amount_decimal,
                    profile_payment=profile_payment,
                    external_id=external_id,
                    description=description,
                    label=subscription_label,
                    longitude=subscription.longitude,
                    latitude=subscription.latitude,
                    city=subscription.city,
                    check_duplicate=True  # Vérifier les doublons
                )

                # 6. Temps de réponse
                response_time = time.time() - start_time

                logger.info(
                    f"[RenewalPaymentView] ✅ Renouvellement initié en {response_time:.2f}s - "
                    f"Référence: {payment_trx.reference}, PaymentTrx: {payment_trx.id}, "
                    f"Subscription: {subscription.id}"
                )

                # 7. Retourner immédiatement au client
                return Response({
                    'status': payment_trx.status,
                    'reference': payment_trx.reference,
                    'payment_trx_id': payment_trx.id,
                    'external_id': payment_trx.external_id,
                    'amount': str(payment_trx.amount),
                    'package': {
                        'id': package.package_id,
                        'name': package.name
                    },
                    'subscription': {
                        'id': subscription.id,
                        'label': subscription.label or package.name,
                        'current_expiry': (
                            subscription.expired_date.isoformat()
                            if subscription.expired_date
                            else None
                        ),
                        'is_expired': subscription.is_expired
                    },
                    'message': (
                        f'Renouvellement initié pour "{subscription.label or package.name}". '
                        f'Veuillez confirmer sur votre téléphone. '
                        f'Votre souscription sera prolongée après confirmation.'
                    ),
                    'response_time': round(response_time, 2)
                }, status=status.HTTP_202_ACCEPTED)

            except FreemoPayValidationError as e:
                logger.error(f"[RenewalPaymentView] ❌ Validation error: {e.message}")
                response_data = {'error': e.message}
                # Le field est stocké dans details, pas comme attribut direct
                if e.details and 'field' in e.details:
                    response_data['field'] = e.details['field']
                return Response(response_data, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayDuplicateError as e:
                logger.warning(f"[RenewalPaymentView] ⚠️ Duplicate payment: {e.message}")
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_409_CONFLICT)

            except FreemoPayPaymentError as e:
                logger.error(f"[RenewalPaymentView] 💥 Payment error: {e.message}")
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayError as e:
                logger.error(f"[RenewalPaymentView] 💥 FreeMoPay error: {e.message}")
                return Response({
                    'error': f'Erreur FreeMoPay: {e.message}'
                }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)

        except Exception as e:
            logger.error(
                f"[RenewalPaymentView] 💥 Erreur inattendue: {str(e)}",
                exc_info=True
            )
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)
