"""
FreeMoPay Custom Exceptions

Hiérarchie d'exceptions pour gérer proprement les erreurs FreeMoPay.
"""

from typing import Optional, Dict, Any


class FreemoPayError(Exception):
    """
    Exception de base pour toutes les erreurs FreeMoPay.

    Attributes:
        message: Message d'erreur
        details: Détails supplémentaires (réponse API, etc.)
        status_code: Code HTTP si applicable
    """

    def __init__(
        self,
        message: str,
        details: Optional[Dict[str, Any]] = None,
        status_code: Optional[int] = None
    ):
        self.message = message
        self.details = details or {}
        self.status_code = status_code
        super().__init__(self.message)

    def __str__(self) -> str:
        if self.status_code:
            return f"[{self.status_code}] {self.message}"
        return self.message

    def to_dict(self) -> Dict[str, Any]:
        """Convertir en dictionnaire pour API response."""
        return {
            'error': self.__class__.__name__,
            'message': self.message,
            'details': self.details,
            'status_code': self.status_code
        }


class FreemoPayConfigError(FreemoPayError):
    """Erreur de configuration (app_key manquant, etc.)."""

    def __init__(self, message: str, **kwargs):
        super().__init__(f"Configuration error: {message}", **kwargs)


class FreemoPayAuthError(FreemoPayError):
    """
    Erreur d'authentification (token invalide, credentials incorrects).

    Exemple:
        - Token expiré
        - App key invalide
        - Secret key incorrect
    """

    def __init__(self, message: str = "Authentication failed", **kwargs):
        kwargs.setdefault('status_code', 401)
        super().__init__(message, **kwargs)


class FreemoPayPaymentError(FreemoPayError):
    """
    Erreur lors de l'initialisation d'un paiement.

    Exemple:
        - Montant invalide
        - Numéro de téléphone incorrect
        - API FreeMoPay en erreur
    """

    def __init__(self, message: str = "Payment initialization failed", **kwargs):
        kwargs.setdefault('status_code', 400)
        super().__init__(message, **kwargs)


class FreemoPayCallbackError(FreemoPayError):
    """
    Erreur lors du traitement d'un callback/webhook.

    Exemple:
        - Signature invalide
        - Transaction non trouvée
        - Format de données incorrect
    """

    def __init__(self, message: str = "Callback processing failed", **kwargs):
        kwargs.setdefault('status_code', 400)
        super().__init__(message, **kwargs)


class FreemoPayNetworkError(FreemoPayError):
    """
    Erreur réseau (timeout, connexion refusée, etc.).

    Exemple:
        - Timeout de requête
        - DNS resolution failed
        - Connection refused
    """

    def __init__(self, message: str = "Network error", **kwargs):
        kwargs.setdefault('status_code', 503)
        super().__init__(message, **kwargs)


class FreemoPayValidationError(FreemoPayError):
    """
    Erreur de validation des données avant envoi à l'API.

    Exemple:
        - Phone number format invalide
        - Montant négatif
        - Champs requis manquants
    """

    def __init__(self, message: str, field: Optional[str] = None, **kwargs):
        kwargs.setdefault('status_code', 400)
        if field:
            kwargs['details'] = {'field': field}
        super().__init__(f"Validation error: {message}", **kwargs)


class FreemoPayTransactionNotFoundError(FreemoPayError):
    """
    Transaction non trouvée en base de données.

    Utilisé dans les callbacks quand on ne trouve pas la PaymentTrx.
    """

    def __init__(self, reference: str, **kwargs):
        kwargs.setdefault('status_code', 404)
        kwargs['details'] = {'reference': reference}
        super().__init__(f"Transaction not found: {reference}", **kwargs)


class FreemoPayDuplicateError(FreemoPayError):
    """
    Tentative de créer un paiement en double.

    Exemple:
        - Paiement déjà en cours pour ce user+package
        - External ID déjà utilisé
    """

    def __init__(self, message: str = "Duplicate transaction", **kwargs):
        kwargs.setdefault('status_code', 409)
        super().__init__(message, **kwargs)


class FreemoPayAPIError(FreemoPayError):
    """
    Erreur retournée par l'API FreeMoPay elle-même.

    Exemple:
        - 500 Internal Server Error de FreeMoPay
        - 429 Rate Limit Exceeded
        - 400 Bad Request avec message spécifique
    """

    def __init__(
        self,
        message: str,
        status_code: int,
        response_data: Optional[Dict[str, Any]] = None,
        **kwargs
    ):
        kwargs['status_code'] = status_code
        kwargs['details'] = {
            'api_response': response_data or {},
            'api_status_code': status_code
        }
        super().__init__(f"FreeMoPay API error: {message}", **kwargs)


# Mapping des codes d'erreur FreeMoPay vers nos exceptions
FREEMOPAY_ERROR_MAPPING = {
    'BAD_API_CREDENTIALS': FreemoPayAuthError,
    'INVALID_TOKEN': FreemoPayAuthError,
    'TOKEN_EXPIRED': FreemoPayAuthError,
    'INVALID_PHONE_NUMBER': FreemoPayValidationError,
    'INVALID_AMOUNT': FreemoPayValidationError,
    'INSUFFICIENT_BALANCE': FreemoPayPaymentError,
    'TRANSACTION_FAILED': FreemoPayPaymentError,
    'CANCELLED': FreemoPayPaymentError,
}


def create_exception_from_api_response(
    status_code: int,
    response_data: Dict[str, Any]
) -> FreemoPayError:
    """
    Créer une exception appropriée à partir d'une réponse API FreeMoPay.

    Args:
        status_code: Code HTTP de la réponse
        response_data: Corps de la réponse JSON

    Returns:
        Instance d'exception appropriée

    Example:
        >>> response_data = {'code': 'BAD_API_CREDENTIALS', 'message': '...'}
        >>> exc = create_exception_from_api_response(401, response_data)
        >>> isinstance(exc, FreemoPayAuthError)
        True
    """
    error_code = response_data.get('code', '')
    error_message = response_data.get('message', '')

    # Si c'est un dict avec 'fr' et 'en', prendre 'fr' ou 'en'
    if isinstance(error_message, dict):
        error_message = error_message.get('fr') or error_message.get('en') or ''

    # Mapper le code d'erreur à une exception spécifique
    exception_class = FREEMOPAY_ERROR_MAPPING.get(error_code, FreemoPayAPIError)

    # Créer l'exception
    if exception_class == FreemoPayAPIError:
        return exception_class(
            message=error_message or f"API Error: {error_code}",
            status_code=status_code,
            response_data=response_data
        )
    else:
        return exception_class(
            message=error_message or f"Error: {error_code}",
            details=response_data,
            status_code=status_code
        )
