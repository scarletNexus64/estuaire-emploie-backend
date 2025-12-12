"""
FreeMoPay Configuration

Configuration centralisée pour l'intégration FreeMoPay.
Toutes les valeurs sont chargées depuis settings.py ou variables d'environnement.
"""

from django.conf import settings
from dataclasses import dataclass
from typing import Optional


@dataclass(frozen=True)
class FreemoPayConfig:
    """
    Configuration immuable pour FreeMoPay API v2.

    Attributes:
        base_url: URL de base de l'API FreeMoPay
        app_key: Clé d'application (username pour Basic Auth)
        secret_key: Clé secrète (password pour Basic Auth)
        callback_url: URL publique pour recevoir les webhooks

        # Timeouts optimisés (plus de polling!)
        init_payment_timeout: Timeout pour init payment (5s suffit)
        status_check_timeout: Timeout pour vérification manuelle
        token_timeout: Timeout pour génération token

        # Cache
        token_cache_key: Clé Redis/Cache pour stocker le token
        token_cache_duration: Durée de cache du token (50min sur 60min)
    """

    # API Credentials
    base_url: str
    app_key: str
    secret_key: str
    callback_url: str

    # Timeouts (en secondes) - OPTIMISÉS !
    init_payment_timeout: int = 5       # 5s max (au lieu de 25s)
    status_check_timeout: int = 5       # 5s max (au lieu de 10s)
    token_timeout: int = 10             # 10s max (au lieu de 15s)

    # Cache configuration
    token_cache_key: str = 'freemopay_access_token'
    token_cache_duration: int = 3000    # 50 minutes (token expire à 60min)

    # Retry configuration
    max_retries: int = 2                # Nombre de retry en cas d'erreur réseau
    retry_delay: float = 0.5            # Délai entre retries (secondes)

    @property
    def token_url(self) -> str:
        """URL pour générer un token d'authentification."""
        return f"{self.base_url}/payment/token"

    @property
    def payment_url(self) -> str:
        """URL pour initialiser un paiement."""
        return f"{self.base_url}/payment"

    @property
    def status_url(self) -> str:
        """URL template pour vérifier le statut d'un paiement."""
        return f"{self.base_url}/payment/{{reference}}"

    def get_status_url(self, reference: str) -> str:
        """
        Obtenir l'URL complète pour vérifier un paiement.

        Args:
            reference: Référence FreeMoPay du paiement

        Returns:
            URL complète
        """
        return self.status_url.format(reference=reference)

    def validate(self) -> None:
        """
        Valider la configuration.

        Raises:
            ValueError: Si la configuration est invalide
        """
        if not self.base_url:
            raise ValueError("FREEMOPAY_BASE_URL est requis")

        if not self.app_key:
            raise ValueError("FREEMOPAY_APP_KEY est requis")

        if not self.secret_key:
            raise ValueError("FREEMOPAY_SECRET_KEY est requis")

        if not self.callback_url:
            raise ValueError("FREEMOPAY_CALLBACK_URL est requis")

        # Vérifier que l'URL callback est publique (pas localhost en prod)
        if not settings.DEBUG and 'localhost' in self.callback_url:
            raise ValueError(
                "FREEMOPAY_CALLBACK_URL ne peut pas être localhost en production. "
                "FreeMoPay doit pouvoir appeler cette URL."
            )

        if not self.callback_url.startswith('https://') and not settings.DEBUG:
            raise ValueError(
                "FREEMOPAY_CALLBACK_URL doit utiliser HTTPS en production"
            )


def load_config() -> FreemoPayConfig:
    """
    Charger la configuration depuis Django settings.

    Returns:
        Instance FreemoPayConfig validée

    Raises:
        ValueError: Si la configuration est invalide
    """
    config = FreemoPayConfig(
        base_url=getattr(
            settings,
            'FREEMOPAY_BASE_URL',
            'https://api-v2.freemopay.com'
        ),
        app_key=getattr(settings, 'FREEMOPAY_APP_KEY', ''),
        secret_key=getattr(settings, 'FREEMOPAY_SECRET_KEY', ''),
        callback_url=getattr(settings, 'FREEMOPAY_CALLBACK_URL', ''),

        # Timeouts personnalisables
        init_payment_timeout=getattr(settings, 'FREEMOPAY_INIT_PAYMENT_TIMEOUT', 5),
        status_check_timeout=getattr(settings, 'FREEMOPAY_STATUS_CHECK_TIMEOUT', 5),
        token_timeout=getattr(settings, 'FREEMOPAY_TOKEN_TIMEOUT', 10),

        # Cache
        token_cache_key=getattr(
            settings,
            'FREEMOPAY_TOKEN_CACHE_KEY',
            'freemopay_access_token'
        ),
        token_cache_duration=getattr(settings, 'FREEMOPAY_TOKEN_CACHE_DURATION', 3000),

        # Retry
        max_retries=getattr(settings, 'FREEMOPAY_MAX_RETRIES', 2),
        retry_delay=getattr(settings, 'FREEMOPAY_RETRY_DELAY', 0.5),
    )

    # Valider avant de retourner
    config.validate()

    return config


# Instance singleton globale
_config: Optional[FreemoPayConfig] = None


def get_config() -> FreemoPayConfig:
    """
    Obtenir l'instance singleton de configuration.

    Returns:
        Instance FreemoPayConfig validée

    Raises:
        ValueError: Si la configuration est invalide
    """
    global _config

    if _config is None:
        _config = load_config()

    return _config


# Pour réinitialiser (utile en tests)
def reset_config():
    """Réinitialiser le singleton (pour tests uniquement)."""
    global _config
    _config = None
