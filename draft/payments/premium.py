"""
Premium Payment View - Phase 4 Refactoring

Vue simplifiée pour le boost premium de produits.
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


class PremiumPaymentView(APIView):
    """
    Vue pour acheter un boost premium pour un produit.

    Architecture:
        1. Validation des données (produit existe et appartient à l'utilisateur)
        2. Init paiement via PaymentService (< 5s)
        3. Retour immédiat avec référence
        4. FreeMoPay envoie callback → PremiumPaymentAdapter activé

    L'adapter PremiumPaymentAdapter gère:
        - Activation is_premium sur Product
        - Extension date_expire (14 jours)
        - Création ProductAudience
    """

    permission_classes = []

    @swagger_auto_schema(
        operation_description=(
            "Initie un paiement pour activer le statut premium d'un produit.\n\n"
            "**Nouvelle architecture (Phase 4):**\n"
            "- Réponse immédiate (< 5s)\n"
            "- Pas de polling, utilise les callbacks FreeMoPay\n"
            "- Retourne statut 'pending' avec référence\n\n"
            "**Flow:**\n"
            "1. Vérifie que le produit appartient à l'utilisateur\n"
            "2. Init paiement (5s max)\n"
            "3. Retour 202 ACCEPTED avec référence\n"
            "4. FreeMoPay envoie callback automatiquement\n"
            "5. PremiumPaymentAdapter active le premium (14 jours)\n"
            "6. Notification envoyée à l'utilisateur"
        ),
        operation_summary="Acheter un boost premium produit (nouvelle architecture)",
        tags=['Paiements Premium'],
        request_body=openapi.Schema(
            type=openapi.TYPE_OBJECT,
            required=['payer', 'amount', 'user_id', 'package_id', 'product_id'],
            properties={
                'payer': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Numéro de téléphone (format: +243XXXXXXXXX ou 237XXXXXXXXX)',
                    example='+243812345678'
                ),
                'amount': openapi.Schema(
                    type=openapi.TYPE_NUMBER,
                    format=openapi.FORMAT_FLOAT,
                    description='Montant à payer (doit correspondre au prix du package premium)',
                    example=100.0
                ),
                'user_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID de l\'utilisateur effectuant le paiement',
                    example=123
                ),
                'package_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID du package premium (ex: package_id=2 pour Premium)',
                    example=2
                ),
                'product_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID du produit à rendre premium',
                    example=456
                ),
                'externalId': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Identifiant externe (optionnel, généré avec préfixe PREMIUM)',
                    example='PREMIUM-20251125161234'
                ),
                'description': openapi.Schema(
                    type=openapi.TYPE_STRING,
                    description='Description du paiement (optionnel)',
                    example='Premium iPhone 15 Pro Max'
                ),
                'profile_payment_id': openapi.Schema(
                    type=openapi.TYPE_INTEGER,
                    description='ID du profil de paiement (optionnel)',
                    example=5
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
                description="Paiement premium initié avec succès",
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
                            example=789
                        ),
                        'external_id': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='PREMIUM-20251125161234'
                        ),
                        'amount': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='100.00'
                        ),
                        'product': openapi.Schema(
                            type=openapi.TYPE_OBJECT,
                            properties={
                                'id': openapi.Schema(type=openapi.TYPE_INTEGER, example=456),
                                'name': openapi.Schema(type=openapi.TYPE_STRING, example='iPhone 15 Pro Max')
                            }
                        ),
                        'message': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='Paiement premium initié. Le produit sera boosté pendant 14 jours après confirmation.'
                        ),
                        'response_time': openapi.Schema(
                            type=openapi.TYPE_NUMBER,
                            example=3.8
                        )
                    }
                )
            ),
            status.HTTP_400_BAD_REQUEST: openapi.Response(
                description="Erreur de validation"
            ),
            status.HTTP_404_NOT_FOUND: openapi.Response(
                description="Produit, package ou utilisateur non trouvé"
            ),
            status.HTTP_409_CONFLICT: openapi.Response(
                description="Paiement en double"
            )
        }
    )
    def post(self, request):
        """
        Initier un paiement premium pour booster un produit.
        """
        start_time = time.time()

        try:
            # 1. Extraction des données
            user_id = request.data.get('user_id')
            package_id = request.data.get('package_id')
            product_id = request.data.get('product_id')
            payer = request.data.get('payer')
            amount = request.data.get('amount')
            external_id = request.data.get('externalId')
            description = request.data.get('description')
            profile_payment_id = request.data.get('profile_payment_id')
            longitude = request.data.get('longitude')
            latitude = request.data.get('latitude')
            city = request.data.get('city')

            logger.info(
                f"[PremiumPaymentView] 🚀 Nouvelle demande premium - "
                f"User: {user_id}, Product: {product_id}, Amount: {amount}"
            )

            # 2. Validation de base
            if not all([user_id, package_id, product_id, payer, amount]):
                return Response({
                    'error': 'Champs requis manquants',
                    'required_fields': ['user_id', 'package_id', 'product_id', 'payer', 'amount']
                }, status=status.HTTP_400_BAD_REQUEST)

            # 3. Récupération des objets
            try:
                user = Users.objects.get(id=user_id)
            except Users.DoesNotExist:
                logger.error(f"[PremiumPaymentView] ❌ User {user_id} non trouvé")
                return Response({
                    'error': f'Utilisateur {user_id} non trouvé'
                }, status=status.HTTP_404_NOT_FOUND)

            try:
                package = Package.objects.get(package_id=package_id)
            except Package.DoesNotExist:
                logger.error(f"[PremiumPaymentView] ❌ Package {package_id} non trouvé")
                return Response({
                    'error': f'Package {package_id} non trouvé'
                }, status=status.HTTP_404_NOT_FOUND)

            # 4. Produit (REQUIS pour premium)
            try:
                product = Product.objects.get(id=product_id, user=user)
                logger.info(
                    f"[PremiumPaymentView] 📱 Produit trouvé: {product.name} (ID: {product.id})"
                )
            except Product.DoesNotExist:
                logger.error(
                    f"[PremiumPaymentView] ❌ Produit {product_id} non trouvé "
                    f"ou n'appartient pas à l'utilisateur {user_id}"
                )
                return Response({
                    'error': f'Produit {product_id} non trouvé ou non autorisé'
                }, status=status.HTTP_404_NOT_FOUND)

            # 5. ProfilePayment (optionnel)
            profile_payment = None
            if profile_payment_id:
                try:
                    profile_payment = ProfilePayment.objects.get(id=profile_payment_id)
                except ProfilePayment.DoesNotExist:
                    logger.warning(
                        f"[PremiumPaymentView] ⚠️ ProfilePayment {profile_payment_id} non trouvé"
                    )

            # 6. Conversion montant
            try:
                amount_decimal = Decimal(str(amount))
            except (ValueError, TypeError):
                return Response({
                    'error': 'Montant invalide'
                }, status=status.HTTP_400_BAD_REQUEST)

            # 7. Générer external_id avec préfixe PREMIUM si non fourni
            if not external_id:
                from api.services.freemopay.payment import generate_external_id
                external_id = generate_external_id(prefix="PREMIUM")

            # 8. Description par défaut
            if not description:
                from api.services.freemopay.payment import create_ussd_description
                description = create_ussd_description(package.name, product.name)

            # 9. Initialiser le paiement via PaymentService
            payment_service = PaymentService()

            try:
                payment_trx = payment_service.init_payment(
                    user=user,
                    package=package,
                    payer=payer,
                    amount=amount_decimal,
                    product=product,  # IMPORTANT: product est fourni
                    profile_payment=profile_payment,
                    external_id=external_id,
                    description=description,
                    longitude=longitude,
                    latitude=latitude,
                    city=city,
                    check_duplicate=True
                )

                # 10. Temps de réponse
                response_time = time.time() - start_time

                logger.info(
                    f"[PremiumPaymentView] ✅ Paiement premium initié en {response_time:.2f}s - "
                    f"Référence: {payment_trx.reference}, PaymentTrx: {payment_trx.id}, "
                    f"Product: {product.name}"
                )

                # 11. Retourner immédiatement au client
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
                    'product': {
                        'id': product.id,
                        'name': product.name
                    },
                    'message': (
                        f'Paiement premium initié pour "{product.name}". '
                        f'Veuillez confirmer sur votre téléphone. '
                        f'Le produit sera boosté pendant 14 jours après confirmation.'
                    ),
                    'response_time': round(response_time, 2)
                }, status=status.HTTP_202_ACCEPTED)

            except FreemoPayValidationError as e:
                logger.error(f"[PremiumPaymentView] ❌ Validation error: {e.message}")
                response_data = {'error': e.message}
                # Le field est stocké dans details, pas comme attribut direct
                if e.details and 'field' in e.details:
                    response_data['field'] = e.details['field']
                return Response(response_data, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayDuplicateError as e:
                logger.warning(f"[PremiumPaymentView] ⚠️ Duplicate payment: {e.message}")
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_409_CONFLICT)

            except FreemoPayPaymentError as e:
                logger.error(f"[PremiumPaymentView] 💥 Payment error: {e.message}")
                return Response({
                    'error': e.message,
                    'details': e.details
                }, status=status.HTTP_400_BAD_REQUEST)

            except FreemoPayError as e:
                logger.error(f"[PremiumPaymentView] 💥 FreeMoPay error: {e.message}")
                return Response({
                    'error': f'Erreur FreeMoPay: {e.message}'
                }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)

        except Exception as e:
            logger.error(
                f"[PremiumPaymentView] 💥 Erreur inattendue: {str(e)}",
                exc_info=True
            )
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)
