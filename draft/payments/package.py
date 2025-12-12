"""
Package Payment View - Phase 4 Refactoring

Vue simplifiée pour l'achat de packages (stockage/certifications).
Utilise PaymentService (pas de polling, réponse < 5s).
"""

from rest_framework import status
from rest_framework.response import Response
from rest_framework.views import APIView
from drf_yasg.utils import swagger_auto_schema
from drf_yasg import openapi
from decimal import Decimal
import logging
import time

from api.models import Package, Users, ProfilePayment, Product
from api.services.freemopay import PaymentService
from api.services.freemopay.exceptions import (
    FreemoPayValidationError,
    FreemoPayDuplicateError,
    FreemoPayPaymentError,
    FreemoPayError
)

logger = logging.getLogger(__name__)


class PackagePaymentView(APIView):
    """
    Vue pour acheter un package (stockage ou certification).

    Architecture:
        1. Validation des données
        2. Init paiement via PaymentService (< 5s)
        3. Retour immédiat avec référence
        4. FreeMoPay envoie callback → Business logic déclenchée

    Plus de:
        - Polling synchrone
        - Tâches Celery
        - Timeouts de 2 minutes
    """

    permission_classes = []

    @swagger_auto_schema(
        operation_description=(
            "Initie un paiement FreeMoPay pour acheter un package.\n\n"
            "**Nouvelle architecture (Phase 4):**\n"
            "- Réponse immédiate (< 5s)\n"
            "- Pas de polling, utilise les callbacks FreeMoPay\n"
            "- Retourne statut 'pending' avec référence\n"
            "- Client doit écouter les notifications ou vérifier le statut\n\n"
            "**Flow:**\n"
            "1. Init paiement (5s max)\n"
            "2. Retour 202 ACCEPTED avec référence\n"
            "3. FreeMoPay envoie callback automatiquement\n"
            "4. Business logic déclenchée (stockage, certifications, etc.)\n"
            "5. Notification envoyée à l'utilisateur"
        ),
        operation_summary="Acheter un package (nouvelle architecture)",
        tags=['Paiements'],
        request_body=openapi.Schema(
            type=openapi.TYPE_OBJECT,
            required=['payer', 'amount', 'user_id', 'package_id'],
            properties={
                'payer': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Numéro de téléphone (format: +243XXXXXXXXX ou 243XXXXXXXXX)',
                    example='+243812345678'
                ),
                'amount': openapi.Schema(
                    type=openapi.TYPE_NUMBER,
                    format=openapi.FORMAT_FLOAT,
                    description='Montant à payer (doit correspondre au prix du package)',
                    example=50.0
                ),
                'user_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID de l\'utilisateur effectuant le paiement',
                    example=123
                ),
                'package_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID du package à acheter',
                    example=1
                ),
                'externalId': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Identifiant externe (optionnel, généré automatiquement si absent)',
                    example='TRX-20251125161234'
                ),
                'description': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Description du paiement (optionnel)',
                    example='Achat Package Standard 50GB'
                ),
                'profile_payment_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID du profil de paiement (optionnel)',
                    example=5
                ),
                'product_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID du produit pour boost premium (optionnel)',
                    example=789
                ),
                'label': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Label de souscription (optionnel)',
                    example='Mon espace pro'
                ),
                'longitude': openapi.Schema(
                    type=openapi.TYPE_NUMBER,
                    format=openapi.FORMAT_FLOAT,
                    description='Longitude (optionnel)',
                    example=-1.234567
                ),
                'latitude': openapi.Schema(
                    type=openapi.TYPE_NUMBER,
                    format=openapi.FORMAT_FLOAT,
                    description='Latitude (optionnel)',
                    example=15.234567
                ),
                'city': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Ville (optionnel)',
                    example='Kinshasa'
                )
            }
        ),
        responses={
            status.HTTP_202_ACCEPTED: openapi.Response(
                description="Paiement initié avec succès",
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
                            example=456
                        ),
                        'external_id': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='TRX-20251125161234'
                        ),
                        'amount': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='50.00'
                        ),
                        'message': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='Paiement initié avec succès. Veuillez confirmer sur votre téléphone.'
                        ),
                        'response_time': openapi.Schema(
                            type=openapi.TYPE_NUMBER,
                            example=4.2
                        )
                    }
                )
            ),
            status.HTTP_400_BAD_REQUEST: openapi.Response(
                description="Erreur de validation"
            ),
            status.HTTP_404_NOT_FOUND: openapi.Response(
                description="Package ou utilisateur non trouvé"
            ),
            status.HTTP_409_CONFLICT: openapi.Response(
                description="Paiement en double (un paiement est déjà en cours)"
            ),
            status.HTTP_500_INTERNAL_SERVER_ERROR: openapi.Response(
                description="Erreur serveur"
            )
        }
    )
    def post(self, request):
        """
        Initier un paiement pour un package.
        """
        start_time = time.time()

        try:
            # 1. Extraction des données
            user_id = request.data.get('user_id')
            package_id = request.data.get('package_id')
            payer = request.data.get('payer')
            amount = request.data.get('amount')
            external_id = request.data.get('externalId')
            description = request.data.get('description')
            profile_payment_id = request.data.get('profile_payment_id')
            product_id = request.data.get('product_id')
            label = request.data.get('label')
            longitude = request.data.get('longitude')
            latitude = request.data.get('latitude')
            city = request.data.get('city')

            logger.info(
                f"[PackagePaymentView] 📦 Nouvelle demande - "
                f"User: {user_id}, Package: {package_id}, Amount: {amount}"
            )

            # 2. Validation de base
            if not all([user_id, package_id, payer, amount]):
                return Response({
                    'error': 'Champs requis manquants',
                    'required_fields': ['user_id', 'package_id', 'payer', 'amount']
                }, status=status.HTTP_400_BAD_REQUEST)

            # 3. Récupération des objets
            try:
                user = Users.objects.get(id=user_id)
            except Users.DoesNotExist:
                logger.error(f"[PackagePaymentView] ❌ User {user_id} non trouvé")
                return Response({
                    'error': f'Utilisateur {user_id} non trouvé'
                }, status=status.HTTP_404_NOT_FOUND)

            try:
                package = Package.objects.get(package_id=package_id)
            except Package.DoesNotExist:
                logger.error(f"[PackagePaymentView] ❌ Package {package_id} non trouvé")
                return Response({
                    'error': f'Package {package_id} non trouvé'
                }, status=status.HTTP_404_NOT_FOUND)

            # 4. Product (pour boost premium via cette API)
            product = None
            if product_id:
                try:
                    product = Product.objects.get(id=product_id, user=user)
                    logger.info(f"[PackagePaymentView] 🚀 Boost produit: {product.name}")
                except Product.DoesNotExist:
                    logger.error(f"[PackagePaymentView] ❌ Produit {product_id} non trouvé")
                    return Response({
                        'error': f'Produit {product_id} non trouvé ou non autorisé'
                    }, status=status.HTTP_404_NOT_FOUND)

            # 5. ProfilePayment (optionnel)
            profile_payment = None
            if profile_payment_id:
                try:
                    profile_payment = ProfilePayment.objects.get(id=profile_payment_id)
                except ProfilePayment.DoesNotExist:
                    logger.warning(f"[PackagePaymentView] ⚠️ ProfilePayment {profile_payment_id} non trouvé")

            # 6. ⚠️ VALIDATION LABEL: Vérifier si le label existe déjà pour ce user+package
            # IMPORTANT: On vérifie AVANT d'initier le paiement chez FreeMoPay !
            if label:
                from api.models import UserSubscription

                existing_subscription = UserSubscription.objects.filter(
                    user=user,
                    package=package,
                    label=label
                ).first()

                if existing_subscription:
                    logger.warning(
                        f"[PackagePaymentView] ❌ Label '{label}' existe déjà - "
                        f"User: {user.id}, Package: {package.name}, Subscription: {existing_subscription.id}"
                    )
                    return Response({
                        'error': f"Le nom '{label}' existe déjà pour ce package.",
                        'message': f"Vous avez déjà un espace de stockage nommé '{label}' pour ce package. Veuillez choisir un autre nom.",
                        'existing_subscription_id': existing_subscription.id,
                        'label': label
                    }, status=status.HTTP_409_CONFLICT)

                logger.info(f"[PackagePaymentView] ✅ Label '{label}' disponible")

            # 7. Conversion montant
            try:
                amount_decimal = Decimal(str(amount))
            except (ValueError, TypeError):
                return Response({
                    'error': 'Montant invalide'
                }, status=status.HTTP_400_BAD_REQUEST)

            # 8. Initialiser le paiement via PaymentService
            payment_service = PaymentService()

            try:
                payment_trx = payment_service.init_payment(
                    user=user,
                    package=package,
                    payer=payer,
                    amount=amount_decimal,
                    product=product,
                    profile_payment=profile_payment,
                    external_id=external_id,
                    description=description,
                    label=label,
                    longitude=longitude,
                    latitude=latitude,
                    city=city,
                    check_duplicate=True  # Vérifier les doublons
                )

                # 9. Temps de réponse
                response_time = time.time() - start_time

                logger.info(
                    f"[PackagePaymentView] ✅ Paiement initié en {response_time:.2f}s - "
                    f"Référence: {payment_trx.reference}, PaymentTrx: {payment_trx.id}"
                )

                # 10. Retourner immédiatement au client
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
                    'message': (
                        'Paiement initié avec succès. '
                        'Veuillez confirmer la transaction sur votre téléphone. '
                        'Vous recevrez une notification une fois le paiement confirmé.'
                    ),
                    'response_time': round(response_time, 2)
                }, status=status.HTTP_202_ACCEPTED)

            except FreemoPayValidationError as e:
                logger.error(f"[PackagePaymentView] ❌ Validation error: {e.message}")
                response_data = {'error': e.message}
                # Le field est stocké dans details, pas comme attribut direct
                if e.details and 'field' in e.details:
                    response_data['field'] = e.details['field']
                return Response(response_data, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayDuplicateError as e:
                logger.warning(f"[PackagePaymentView] ⚠️ Duplicate payment: {e.message}")
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_409_CONFLICT)

            except FreemoPayPaymentError as e:
                logger.error(f"[PackagePaymentView] 💥 Payment error: {e.message}")
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayError as e:
                logger.error(f"[PackagePaymentView] 💥 FreeMoPay error: {e.message}")
                return Response({
                    'error': f'Erreur FreeMoPay: {e.message}'
                }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)

        except Exception as e:
            logger.error(
                f"[PackagePaymentView] 💥 Erreur inattendue: {str(e)}",
                exc_info=True
            )
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)
