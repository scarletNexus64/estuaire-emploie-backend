"""
Payment List Views - Phase 4 Refactoring

Vues pour lister les paiements (historique utilisateur).
Pas de dépendance à FreeMoPay - juste de la récupération DB.
"""

from rest_framework import status
from rest_framework.response import Response
from rest_framework.views import APIView
from rest_framework.permissions import IsAuthenticated
from drf_yasg.utils import swagger_auto_schema
from drf_yasg import openapi
import logging

from api.models import PaymentTrx, Users
from api.serializers import PaymentTrxSerializer

logger = logging.getLogger(__name__)


class PaymentListView(APIView):
    """
    Vue pour récupérer la liste des paiements de l'utilisateur connecté.

    Retourne tous les paiements (succès, échec, en cours) triés par date.
    """

    permission_classes = [IsAuthenticated]

    @swagger_auto_schema(
        operation_description=(
            "Récupère la liste de tous les paiements de l'utilisateur connecté.\n\n"
            "**Retourne:**\n"
            "- Tous les PaymentTrx de l'utilisateur\n"
            "- Triés par date (plus récent d'abord)\n"
            "- Tous statuts confondus (pending, success, error, cancelled)\n\n"
            "**Utilisation:**\n"
            "- Historique des paiements\n"
            "- Suivi des transactions en cours\n"
            "- Détection des paiements échoués"
        ),
        operation_summary="Liste des paiements de l'utilisateur",
        tags=['Paiements V2'],
        responses={
            status.HTTP_200_OK: openapi.Response(
                description="Liste des paiements",
                schema=openapi.Schema(
                    type=openapi.TYPE_OBJECT,
                    properties={
                        'count': openapi.Schema(
                            type=openapi.TYPE_INTEGER,
                            description='Nombre total de paiements',
                            example=5
                        ),
                        'payments': openapi.Schema(
                            type=openapi.TYPE_ARRAY,
                            description='Liste des paiements',
                            items=openapi.Schema(
                                type=openapi.TYPE_OBJECT,
                                description='PaymentTrx sérialisé'
                            )
                        )
                    }
                )
            ),
            status.HTTP_401_UNAUTHORIZED: openapi.Response(
                description="Non authentifié"
            )
        }
    )
    def get(self, request):
        """
        GET /api/payments/all/

        Récupère tous les paiements de l'utilisateur connecté.
        """
        try:
            user = request.user

            # Récupérer tous les paiements de l'utilisateur, triés par date décroissante
            payments = PaymentTrx.objects.filter(
                user=user
            ).select_related(
                'package', 'product', 'profile_payment'
            ).order_by('-created_at')

            # Sérialiser
            serializer = PaymentTrxSerializer(payments, many=True)

            logger.info(
                f"[PaymentListView] ✅ {len(payments)} paiements récupérés - "
                f"User: {user.id}"
            )

            return Response({
                'count': len(payments),
                'payments': serializer.data
            }, status=status.HTTP_200_OK)

        except Exception as e:
            logger.error(
                f"[PaymentListView] 💥 Erreur: {str(e)}",
                exc_info=True
            )
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)


class UserPaymentListView(APIView):
    """
    Vue pour récupérer les paiements d'un utilisateur spécifique (admin ou self).

    Accessible par:
    - L'utilisateur lui-même
    - Les admins/staff

    Interdit aux autres utilisateurs.
    """

    permission_classes = [IsAuthenticated]

    @swagger_auto_schema(
        operation_description=(
            "Récupère la liste des paiements d'un utilisateur spécifique.\n\n"
            "**Permissions:**\n"
            "- Utilisateur peut voir ses propres paiements\n"
            "- Staff/Admin peut voir les paiements de n'importe qui\n"
            "- Interdit aux autres utilisateurs\n\n"
            "**Retourne:**\n"
            "- Tous les PaymentTrx de l'utilisateur\n"
            "- Triés par date (plus récent d'abord)\n"
            "- Avec détails package, product, profile"
        ),
        operation_summary="Liste des paiements d'un utilisateur",
        tags=['Paiements V2'],
        manual_parameters=[
            openapi.Parameter(
                'user_id',
                openapi.IN_PATH,
                description='ID de l\'utilisateur',
                type=openapi.TYPE_INTEGER,
                required=True,
                example=123
            )
        ],
        responses={
            status.HTTP_200_OK: openapi.Response(
                description="Liste des paiements",
                schema=openapi.Schema(
                    type=openapi.TYPE_OBJECT,
                    properties={
                        'user_id': openapi.Schema(
                            type=openapi.TYPE_INTEGER,
                            example=123
                        ),
                        'username': openapi.Schema(
                            type=openapi.TYPE_STRING,
                            example='john_doe'
                        ),
                        'count': openapi.Schema(
                            type=openapi.TYPE_INTEGER,
                            example=5
                        ),
                        'payments': openapi.Schema(
                            type=openapi.TYPE_ARRAY,
                            items=openapi.Schema(type=openapi.TYPE_OBJECT)
                        )
                    }
                )
            ),
            status.HTTP_403_FORBIDDEN: openapi.Response(
                description="Accès non autorisé"
            ),
            status.HTTP_404_NOT_FOUND: openapi.Response(
                description="Utilisateur non trouvé"
            )
        }
    )
    def get(self, request, user_id):
        """
        GET /api/payments/<user_id>/

        Récupère les paiements d'un utilisateur spécifique.
        """
        try:
            # 1. Vérifier que l'utilisateur existe
            try:
                target_user = Users.objects.get(id=user_id)
            except Users.DoesNotExist:
                logger.error(
                    f"[UserPaymentListView] ❌ User introuvable - ID: {user_id}"
                )
                return Response({
                    'error': f'Utilisateur avec l\'ID {user_id} non trouvé'
                }, status=status.HTTP_404_NOT_FOUND)

            # 2. Vérifier les permissions
            requesting_user = request.user

            # Autoriser si:
            # - Utilisateur demande ses propres paiements
            # - Utilisateur est staff/admin
            if requesting_user.id != target_user.id and not requesting_user.is_staff:
                logger.warning(
                    f"[UserPaymentListView] ⚠️ Accès refusé - "
                    f"User {requesting_user.id} tente d'accéder aux paiements de {target_user.id}"
                )
                return Response({
                    'error': 'Vous n\'êtes pas autorisé à accéder aux paiements de cet utilisateur'
                }, status=status.HTTP_403_FORBIDDEN)

            # 3. Récupérer les paiements
            payments = PaymentTrx.objects.filter(
                user=target_user
            ).select_related(
                'package', 'product', 'profile_payment'
            ).order_by('-created_at')

            # 4. Sérialiser
            serializer = PaymentTrxSerializer(payments, many=True)

            logger.info(
                f"[UserPaymentListView] ✅ {len(payments)} paiements récupérés - "
                f"Target User: {target_user.id}, "
                f"Requesting User: {requesting_user.id}"
            )

            return Response({
                'user_id': target_user.id,
                'username': target_user.username,
                'count': len(payments),
                'payments': serializer.data
            }, status=status.HTTP_200_OK)

        except Exception as e:
            logger.error(
                f"[UserPaymentListView] 💥 Erreur: {str(e)}",
                exc_info=True
            )
            return Response({
                'error': 'Erreur serveur',
                'details': str(e)
            }, status=status.HTTP_500_INTERNAL_SERVER_ERROR)
