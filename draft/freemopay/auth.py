"""
FreeMoPay Authentication Service

Gestion des tokens d'authentification avec cache Django.
Les tokens sont valides 60 minutes, on les cache 50 minutes.
"""

import logging
from typing import Optional
from datetime import timedelta
from django.core.cache import cache
from django.utils import timezone

from .config import get_config, FreemoPayConfig
from .client import FreemoPayHTTPClient
from .exceptions import FreemoPayAuthError

logger = logging.getLogger(__name__)


class TokenManager:
    """
    Gestionnaire de tokens FreeMoPay avec cache automatique.

    Features:
        - Génération token via API FreeMoPay
        - Cache Django (Redis/Memcached)
        - Renouvellement automatique avant expiration
        - Thread-safe (cache Django est thread-safe)

    Usage:
        manager = TokenManager()
        token = manager.get_token()  # Automatiquement cached
    """

    def __init__(
        self,
        config: Optional[FreemoPayConfig] = None,
        client: Optional[FreemoPayHTTPClient] = None
    ):
        """
        Initialiser le gestionnaire de tokens.

        Args:
            config: Configuration FreeMoPay
            client: Client HTTP (si None, en crée un nouveau)
        """
        self.config = config or get_config()
        self.client = client or FreemoPayHTTPClient(self.config)

    def get_token(self) -> str:
        """
        Obtenir un token d'authentification valide.

        Retourne le token depuis le cache si valide, sinon en génère un nouveau.

        Returns:
            Token d'authentification Bearer

        Raises:
            FreemoPayAuthError: Si la génération échoue
        """
        # Essayer d'obtenir depuis le cache
        cached_token = self._get_from_cache()
        if cached_token:
            logger.debug("[FreeMoPay Auth] Token récupéré depuis le cache")
            return cached_token

        # Sinon, générer un nouveau token
        logger.info("[FreeMoPay Auth] Génération d'un nouveau token")
        return self._generate_and_cache_token()

    def _get_from_cache(self) -> Optional[str]:
        """
        Récupérer le token depuis le cache Django.

        Returns:
            Token si trouvé et valide, None sinon
        """
        token = cache.get(self.config.token_cache_key)

        if token:
            logger.debug(f"[FreeMoPay Auth] Token trouvé en cache")
            return token

        return None

    def _generate_and_cache_token(self) -> str:
        """
        Générer un nouveau token et le mettre en cache.

        Returns:
            Nouveau token

        Raises:
            FreemoPayAuthError: Si la génération échoue
        """
        try:
            # Préparer la requête
            payload = {
                "appKey": self.config.app_key,
                "secretKey": self.config.secret_key
            }

            # Appeler l'API FreeMoPay
            response = self.client.post(
                self.config.token_url,
                data=payload,
                timeout=self.config.token_timeout
            )

            # Parser la réponse
            access_token = response.get('access_token')
            expires_in = response.get('expires_in', 3600)  # Défaut: 1h

            if not access_token:
                logger.error(f"[FreeMoPay Auth] Pas de access_token dans la réponse: {response}")
                raise FreemoPayAuthError(
                    "No access_token in response",
                    details=response
                )

            logger.info(
                f"[FreeMoPay Auth] ✅ Token généré avec succès "
                f"(expire dans {expires_in}s)"
            )

            # Mettre en cache (avec buffer de 10 minutes)
            # Si le token expire à 60min, on le cache 50min
            cache_duration = min(
                expires_in - 600,  # -10 minutes de buffer
                self.config.token_cache_duration
            )

            if cache_duration > 0:
                cache.set(
                    self.config.token_cache_key,
                    access_token,
                    timeout=cache_duration
                )
                logger.debug(
                    f"[FreeMoPay Auth] Token mis en cache pour {cache_duration}s"
                )
            else:
                logger.warning(
                    f"[FreeMoPay Auth] Token non mis en cache "
                    f"(durée trop courte: {expires_in}s)"
                )

            return access_token

        except FreemoPayAuthError:
            # Propager les erreurs d'auth
            raise

        except Exception as e:
            logger.error(f"[FreeMoPay Auth] Erreur lors de la génération du token: {e}")
            raise FreemoPayAuthError(
                f"Token generation failed: {str(e)}",
                details={'error': str(e)}
            ) from e

    def invalidate_token(self):
        """
        Invalider le token en cache.

        Utile si on détecte que le token est invalide (401 de l'API).
        """
        cache.delete(self.config.token_cache_key)
        logger.info("[FreeMoPay Auth] Token invalidé du cache")

    def refresh_token(self) -> str:
        """
        Forcer le renouvellement du token.

        Returns:
            Nouveau token

        Raises:
            FreemoPayAuthError: Si la génération échoue
        """
        logger.info("[FreeMoPay Auth] Renouvellement forcé du token")
        self.invalidate_token()
        return self._generate_and_cache_token()

    def get_authorization_header(self) -> str:
        """
        Obtenir le header Authorization complet.

        Returns:
            Header Authorization (ex: 'Bearer abc123...')

        Raises:
            FreemoPayAuthError: Si la génération du token échoue
        """
        token = self.get_token()
        return f"Bearer {token}"


# Instance singleton globale
_token_manager: Optional[TokenManager] = None


def get_token_manager() -> TokenManager:
    """
    Obtenir l'instance singleton du gestionnaire de tokens.

    Returns:
        Instance TokenManager
    """
    global _token_manager

    if _token_manager is None:
        _token_manager = TokenManager()

    return _token_manager


def get_token() -> str:
    """
    Raccourci pour obtenir un token d'authentification.

    Returns:
        Token Bearer

    Raises:
        FreemoPayAuthError: Si la génération échoue
    """
    return get_token_manager().get_token()


def get_authorization_header() -> str:
    """
    Raccourci pour obtenir le header Authorization.

    Returns:
        Header Authorization complet

    Raises:
        FreemoPayAuthError: Si la génération du token échoue
    """
    return get_token_manager().get_authorization_header()


def invalidate_token():
    """Raccourci pour invalider le token en cache."""
    get_token_manager().invalidate_token()


def refresh_token() -> str:
    """
    Raccourci pour forcer le renouvellement du token.

    Returns:
        Nouveau token
    """
    return get_token_manager().refresh_token()


# Pour réinitialiser (utile en tests)
def reset_token_manager():
    """Réinitialiser le singleton (pour tests uniquement)."""
    global _token_manager
    _token_manager = None
