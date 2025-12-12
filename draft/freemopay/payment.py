"""
FreeMoPay Payment Service

Service simple pour initialiser les paiements.
PAS DE POLLING - on attend les callbacks de FreeMoPay.
"""

import logging
from decimal import Decimal
from typing import Optional, Dict, Any
from django.utils import timezone
from django.db import transaction as db_transaction

from api.models import PaymentTrx, Package, Users, ProfilePayment, Product
from .config import get_config, FreemoPayConfig
from .client import FreemoPayHTTPClient
from .auth import get_token_manager, TokenManager
from .exceptions import (
    FreemoPayError,
    FreemoPayPaymentError,
    FreemoPayValidationError,
    FreemoPayDuplicateError
)

logger = logging.getLogger(__name__)


def normalize_phone_number(phone: str) -> str:
    """
    Normaliser un numéro de téléphone pour FreeMoPay.

    FreeMoPay attend: 237XXXXXXXXX (sans +)

    Args:
        phone: Numéro brut (peut contenir +, espaces, etc.)

    Returns:
        Numéro normalisé

    Raises:
        FreemoPayValidationError: Si le format est invalide
    """
    if not phone:
        raise FreemoPayValidationError("Phone number is required", field='payer')

    # Retirer +, espaces, tirets
    cleaned = phone.replace('+', '').replace(' ', '').replace('-', '')

    # Si commence par 243 (RDC), on garde
    # Si commence par 237 (Cameroun), on garde
    # Sinon erreur
    if not (cleaned.startswith('243') or cleaned.startswith('237')):
        raise FreemoPayValidationError(
            f"Phone number must start with 243 (RDC) or 237 (Cameroon): {phone}",
            field='payer'
        )

    # Vérifier longueur (12 chiffres attendus)
    if len(cleaned) != 12 or not cleaned.isdigit():
        raise FreemoPayValidationError(
            f"Invalid phone format. Expected 12 digits (237XXXXXXXXX or 243XXXXXXXXX): {phone}",
            field='payer'
        )

    return cleaned


def generate_external_id(prefix: str = "TRX") -> str:
    """
    Générer un external_id unique et simple.

    Args:
        prefix: Préfixe (TRX, PREMIUM, RENEW)

    Returns:
        External ID (ex: TRX-20251125161234)
    """
    timestamp = timezone.now().strftime('%Y%m%d%H%M%S')
    return f"{prefix}-{timestamp}"


def create_ussd_description(package_name: str, product_name: Optional[str] = None) -> str:
    """
    Créer une description courte pour USSD (max 30 caractères).

    Args:
        package_name: Nom du package
        product_name: Nom du produit (optionnel)

    Returns:
        Description tronquée
    """
    if product_name:
        return f"Premium {product_name[:20]}"
    else:
        return f"Achat {package_name[:20]}"


class PaymentService:
    """
    Service pour initialiser les paiements FreeMoPay.

    Features:
        - Initialisation simple (< 5s)
        - Pas de polling (callbacks only)
        - Validation des données
        - Création PaymentTrx en statut 'pending'

    Usage:
        service = PaymentService()

        payment_trx = service.init_payment(
            user=user,
            package=package,
            payer='243812345678',
            amount=Decimal('50.00')
        )

        # Retourner immédiatement au client avec reference
        # FreeMoPay envoie callback quand terminé
    """

    def __init__(
        self,
        config: Optional[FreemoPayConfig] = None,
        client: Optional[FreemoPayHTTPClient] = None,
        token_manager: Optional[TokenManager] = None
    ):
        """
        Initialiser le service de paiement.

        Args:
            config: Configuration FreeMoPay
            client: Client HTTP
            token_manager: Gestionnaire de tokens
        """
        self.config = config or get_config()
        self.client = client or FreemoPayHTTPClient(self.config)
        self.token_manager = token_manager or get_token_manager()

    def init_payment(
        self,
        user: Users,
        package: Package,
        payer: str,
        amount: Decimal,
        product: Optional[Product] = None,
        profile_payment: Optional[ProfilePayment] = None,
        external_id: Optional[str] = None,
        description: Optional[str] = None,
        label: Optional[str] = None,
        longitude: Optional[float] = None,
        latitude: Optional[float] = None,
        city: Optional[str] = None,
        check_duplicate: bool = True
    ) -> PaymentTrx:
        """
        Initialiser un paiement FreeMoPay.

        Process:
            1. Valider les données
            2. Vérifier les doublons (optionnel)
            3. Créer PaymentTrx en statut 'pending'
            4. Appeler FreeMoPay API (< 5s)
            5. Mettre à jour PaymentTrx avec référence FreeMoPay
            6. Retourner immédiatement

        Args:
            user: Utilisateur effectuant le paiement
            package: Package acheté
            payer: Numéro de téléphone (ex: 243812345678 ou +243812345678)
            amount: Montant du paiement
            product: Produit (si paiement premium)
            profile_payment: Profil de paiement (optionnel)
            external_id: ID externe (généré si None)
            description: Description (générée si None)
            label: Label subscription (optionnel)
            longitude: Position client
            latitude: Position client
            city: Ville client
            check_duplicate: Vérifier les doublons (défaut: True)

        Returns:
            PaymentTrx créée avec référence FreeMoPay

        Raises:
            FreemoPayValidationError: Données invalides
            FreemoPayDuplicateError: Paiement en double
            FreemoPayPaymentError: Erreur API FreeMoPay
        """
        logger.info(
            f"[PaymentService] 🚀 Init paiement - "
            f"User: {user.id}, Package: {package.name}, Amount: {amount}"
        )

        # 1. Valider les données
        self._validate_payment_data(user, package, amount)

        # 2. Normaliser le numéro de téléphone
        normalized_payer = normalize_phone_number(payer)

        # 3. Générer external_id si non fourni
        if not external_id:
            prefix = "PREMIUM" if product else "TRX"
            external_id = generate_external_id(prefix)

        # Assurer unicité de l'external_id
        external_id = self._ensure_unique_external_id(external_id)

        # 4. Vérifier les doublons (paiement en cours)
        if check_duplicate:
            self._check_duplicate_payment(user, package, product)

        # 5. Créer description
        if not description:
            product_name = product.name if product else None
            description = create_ussd_description(package.name, product_name)

        # 6. Callback URL
        callback_url = self.config.callback_url

        logger.info(
            f"[PaymentService] 📞 Callback URL: {callback_url}, "
            f"External ID: {external_id}"
        )

        # 7. Créer PaymentTrx en statut 'pending' (AVANT l'appel API)
        with db_transaction.atomic():
            payment_trx = PaymentTrx.objects.create(
                user=user,
                package=package,
                product=product,
                profile_payment=profile_payment,
                mobile_number=normalized_payer,
                external_id=external_id,
                amount=amount,
                description=description,
                status='pending',  # Pas de reference encore
                longitude=longitude,
                latitude=latitude,
                city=city,
                subscription_label=label
            )

            logger.info(f"[PaymentService] 💾 PaymentTrx créé - ID: {payment_trx.id}")

        # 8. Appeler FreeMoPay API
        try:
            freemo_response = self._call_freemopay_api(
                payer=normalized_payer,
                amount=float(amount),
                external_id=external_id,
                description=description,
                callback=callback_url
            )

            # 9. Mettre à jour avec la référence FreeMoPay
            reference = freemo_response.get('reference')

            if not reference:
                logger.error(
                    f"[PaymentService] ❌ Pas de référence dans la réponse: {freemo_response}"
                )
                # Marquer comme failed
                payment_trx.status = 'error'
                payment_trx.save()

                raise FreemoPayPaymentError(
                    "No reference in FreeMoPay response",
                    details=freemo_response
                )

            # Mettre à jour la transaction avec la référence
            payment_trx.reference = reference
            payment_trx.save(update_fields=['reference'])

            logger.info(
                f"[PaymentService] ✅ Paiement initié avec succès - "
                f"Référence: {reference}, PaymentTrx: {payment_trx.id}"
            )

            return payment_trx

        except FreemoPayError as e:
            # Marquer la transaction comme failed
            payment_trx.status = 'error'
            payment_trx.save(update_fields=['status'])

            logger.error(
                f"[PaymentService] ❌ Erreur FreeMoPay: {e.message}"
            )
            raise

        except Exception as e:
            # Erreur inattendue
            payment_trx.status = 'error'
            payment_trx.save(update_fields=['status'])

            logger.exception(
                f"[PaymentService] 💥 Erreur inattendue: {str(e)}"
            )
            raise FreemoPayPaymentError(
                f"Unexpected error: {str(e)}",
                details={'error': str(e)}
            ) from e

    def _validate_payment_data(
        self,
        user: Users,
        package: Package,
        amount: Decimal
    ):
        """
        Valider les données de paiement.

        Args:
            user: Utilisateur
            package: Package
            amount: Montant

        Raises:
            FreemoPayValidationError: Si données invalides
        """
        # Vérifier que le montant correspond au prix du package
        package_price = Decimal(str(package.price))

        if amount != package_price:
            logger.error(
                f"[PaymentService] ❌ Montant incorrect - "
                f"Demandé: {amount}, Prix: {package_price}"
            )
            raise FreemoPayValidationError(
                f"Amount mismatch: requested {amount}, package price is {package_price}",
                field='amount'
            )

        # Vérifier que l'utilisateur est actif
        if not user.is_active:
            raise FreemoPayValidationError(
                f"User {user.id} is inactive",
                field='user'
            )

        # Vérifier que le package est actif
        if not package.active:
            raise FreemoPayValidationError(
                f"Package {package.package_id} is inactive",
                field='package'
            )

    def _ensure_unique_external_id(self, base_external_id: str) -> str:
        """
        Assurer l'unicité de l'external_id.

        Args:
            base_external_id: ID de base

        Returns:
            ID unique
        """
        external_id = base_external_id
        counter = 1

        while PaymentTrx.objects.filter(external_id=external_id).exists():
            external_id = f"{base_external_id}-{counter}"
            counter += 1
            logger.debug(
                f"[PaymentService] external_id existe, nouveau: {external_id}"
            )

        return external_id

    def _check_duplicate_payment(
        self,
        user: Users,
        package: Package,
        product: Optional[Product]
    ):
        """
        Vérifier s'il existe un paiement en cours (pending).

        Avec callbacks, un paiement se termine en < 30s normalement.
        Window de 2 minutes pour être safe.

        Args:
            user: Utilisateur
            package: Package
            product: Produit (pour premium)

        Raises:
            FreemoPayDuplicateError: Si paiement en cours existe
        """
        from datetime import timedelta

        two_minutes_ago = timezone.now() - timedelta(minutes=2)

        # Filtrer par user + package + status pending + créé récemment
        filters = {
            'user': user,
            'package': package,
            'status': 'pending',
            'created_at__gte': two_minutes_ago
        }

        # Si c'est un paiement premium, vérifier aussi le produit
        if product:
            filters['product'] = product

        existing = PaymentTrx.objects.filter(**filters).first()

        if existing:
            logger.warning(
                f"[PaymentService] ⚠️ Paiement en cours détecté - "
                f"PaymentTrx: {existing.id}, Référence: {existing.reference}"
            )
            raise FreemoPayDuplicateError(
                "A payment for this package is already in progress",
                details={
                    'existing_payment_id': existing.id,
                    'existing_reference': existing.reference,
                    'created_at': existing.created_at.isoformat()
                }
            )

    def _call_freemopay_api(
        self,
        payer: str,
        amount: float,
        external_id: str,
        description: str,
        callback: str
    ) -> Dict[str, Any]:
        """
        Appeler l'API FreeMoPay pour initialiser le paiement.

        Args:
            payer: Numéro normalisé
            amount: Montant
            external_id: ID externe
            description: Description
            callback: URL callback

        Returns:
            Réponse API FreeMoPay

        Raises:
            FreemoPayPaymentError: Si l'appel échoue
        """
        # Obtenir le token Bearer
        bearer_token = self.token_manager.get_token()

        # Préparer le payload
        payload = {
            "payer": payer,
            "amount": amount,
            "externalId": external_id,
            "description": description,
            "callback": callback
        }

        logger.info(
            f"[PaymentService] 🔄 Appel FreeMoPay API - "
            f"External ID: {external_id}, Amount: {amount}"
        )
        logger.info(f"[PaymentService] 📤 Payload envoyé: {payload}")
        logger.info(f"[PaymentService] 🌐 URL: {self.config.payment_url}")

        try:
            # POST /api/v2/payment
            response = self.client.post(
                self.config.payment_url,
                data=payload,
                bearer_token=bearer_token,
                timeout=self.config.init_payment_timeout
            )

            logger.info(f"[PaymentService] 📥 Réponse FreeMoPay complète: {response}")

            # Vérifier le statut de la réponse
            init_status = response.get('status')

            # Statuts acceptés: SUCCESS, CREATED (transaction créée), PENDING (en attente)
            valid_init_statuses = ['SUCCESS', 'CREATED', 'PENDING', 'PROCESSING']

            # Statuts d'échec connus
            failed_statuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED']

            if init_status in failed_statuses:
                # Initialisation a échoué
                error_message = response.get('message', 'Unknown error')
                logger.error(
                    f"[PaymentService] ❌ Init failed - Status: {init_status}, "
                    f"Message: {error_message}"
                )
                raise FreemoPayPaymentError(
                    f"Payment initialization failed: {error_message}",
                    details=response
                )

            # Si le statut n'est ni dans les valides ni dans les échecs, logger un warning
            if init_status not in valid_init_statuses:
                logger.warning(
                    f"[PaymentService] ⚠️ Unknown init status: {init_status}, "
                    f"treating as success. Message: {response.get('message')}"
                )

            return response

        except FreemoPayError:
            # Propager les erreurs FreeMoPay
            raise

        except Exception as e:
            logger.error(
                f"[PaymentService] 💥 Erreur appel API: {str(e)}"
            )
            raise FreemoPayPaymentError(
                f"API call failed: {str(e)}",
                details={'error': str(e)}
            ) from e
