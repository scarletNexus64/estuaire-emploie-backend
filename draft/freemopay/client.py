"""
FreeMoPay HTTP Client

Client HTTP robuste avec retry, logging et gestion d'erreurs.
"""

import logging
import time
import requests
from typing import Dict, Any, Optional, Tuple
from requests.adapters import HTTPAdapter
from urllib3.util.retry import Retry

from .config import get_config, FreemoPayConfig
from .exceptions import (
    FreemoPayError,
    FreemoPayNetworkError,
    FreemoPayAPIError,
    create_exception_from_api_response
)

logger = logging.getLogger(__name__)


class FreemoPayHTTPClient:
    """
    Client HTTP pour communiquer avec l'API FreeMoPay.

    Features:
        - Retry automatique sur erreurs réseau
        - Logging détaillé
        - Gestion des timeouts
        - Parsing des erreurs API

    Usage:
        client = FreemoPayHTTPClient()

        # POST avec Bearer token
        response = client.post('/api/v2/payment', data, bearer_token='xxx')

        # GET avec Basic Auth
        response = client.get('/api/v2/payment/ref', use_basic_auth=True)
    """

    def __init__(self, config: Optional[FreemoPayConfig] = None):
        """
        Initialiser le client HTTP.

        Args:
            config: Configuration FreeMoPay (si None, charge depuis get_config())
        """
        self.config = config or get_config()
        self._session: Optional[requests.Session] = None

    @property
    def session(self) -> requests.Session:
        """
        Obtenir ou créer la session HTTP avec retry strategy.

        Returns:
            Session requests configurée
        """
        if self._session is None:
            self._session = self._create_session()
        return self._session

    def _create_session(self) -> requests.Session:
        """
        Créer une session requests avec retry strategy.

        Returns:
            Session configurée
        """
        session = requests.Session()

        # Stratégie de retry (uniquement sur certaines erreurs)
        retry_strategy = Retry(
            total=self.config.max_retries,
            backoff_factor=self.config.retry_delay,
            status_forcelist=[429, 500, 502, 503, 504],  # Retry sur ces codes HTTP
            allowed_methods=["GET", "POST"],  # Retry sur GET et POST
        )

        # Adapter avec retry
        adapter = HTTPAdapter(max_retries=retry_strategy)
        session.mount("http://", adapter)
        session.mount("https://", adapter)

        # Headers par défaut
        session.headers.update({
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'User-Agent': 'Ngoma-Backend/2.0'
        })

        return session

    def _build_url(self, endpoint: str) -> str:
        """
        Construire l'URL complète à partir d'un endpoint.

        Args:
            endpoint: Endpoint relatif (ex: '/api/v2/payment')

        Returns:
            URL complète
        """
        # Si l'endpoint est déjà une URL complète, le retourner tel quel
        if endpoint.startswith('http://') or endpoint.startswith('https://'):
            return endpoint

        # Sinon, construire avec base_url
        base = self.config.base_url.rstrip('/')
        endpoint = endpoint.lstrip('/')
        return f"{base}/{endpoint}"

    def _log_request(
        self,
        method: str,
        url: str,
        headers: Dict[str, str],
        data: Optional[Dict[str, Any]] = None
    ):
        """
        Logger les détails d'une requête (sans données sensibles).

        Args:
            method: Méthode HTTP
            url: URL complète
            headers: En-têtes
            data: Données envoyées
        """
        # Masquer les headers sensibles
        safe_headers = headers.copy()
        if 'Authorization' in safe_headers:
            auth_type = safe_headers['Authorization'].split()[0]
            safe_headers['Authorization'] = f"{auth_type} [HIDDEN]"

        logger.debug(f"[FreeMoPay] {method} {url}")
        logger.debug(f"[FreeMoPay] Headers: {safe_headers}")

        if data:
            # Masquer les données sensibles
            safe_data = self._mask_sensitive_data(data)
            logger.debug(f"[FreeMoPay] Body: {safe_data}")

    def _mask_sensitive_data(self, data: Dict[str, Any]) -> Dict[str, Any]:
        """
        Masquer les données sensibles pour le logging.

        Args:
            data: Données originales

        Returns:
            Données avec valeurs sensibles masquées
        """
        safe_data = data.copy()
        sensitive_keys = ['secretKey', 'secret_key', 'password', 'token']

        for key in sensitive_keys:
            if key in safe_data:
                safe_data[key] = '[HIDDEN]'

        return safe_data

    def _log_response(
        self,
        status_code: int,
        response_body: str,
        duration: float
    ):
        """
        Logger la réponse d'une requête.

        Args:
            status_code: Code HTTP
            response_body: Corps de la réponse
            duration: Durée de la requête en secondes
        """
        # Tronquer le body si trop long
        body_preview = response_body[:500] + '...' if len(response_body) > 500 else response_body

        logger.debug(f"[FreeMoPay] Response {status_code} in {duration:.2f}s")
        logger.debug(f"[FreeMoPay] Body: {body_preview}")

        # Warning si la requête est lente
        if duration > 3.0:
            logger.warning(f"[FreeMoPay] Requête lente: {duration:.2f}s")

    def _handle_response(
        self,
        response: requests.Response
    ) -> Dict[str, Any]:
        """
        Traiter la réponse HTTP et gérer les erreurs.

        Args:
            response: Réponse requests

        Returns:
            Données JSON parsées

        Raises:
            FreemoPayAPIError: Si l'API retourne une erreur
            FreemoPayError: Si le parsing échoue
        """
        try:
            data = response.json()
        except ValueError:
            # Réponse n'est pas du JSON
            logger.error(f"[FreeMoPay] Réponse non-JSON: {response.text[:200]}")
            raise FreemoPayError(
                "Invalid API response (not JSON)",
                details={'response': response.text[:500]},
                status_code=response.status_code
            )

        # Si code HTTP indique une erreur (4xx, 5xx)
        if response.status_code >= 400:
            logger.error(f"[FreeMoPay] API Error {response.status_code}: {data}")
            raise create_exception_from_api_response(response.status_code, data)

        return data

    def post(
        self,
        endpoint: str,
        data: Dict[str, Any],
        bearer_token: Optional[str] = None,
        use_basic_auth: bool = False,
        timeout: Optional[int] = None
    ) -> Dict[str, Any]:
        """
        Effectuer une requête POST.

        Args:
            endpoint: Endpoint API (relatif ou absolu)
            data: Données à envoyer (sera converti en JSON)
            bearer_token: Token Bearer (si fourni)
            use_basic_auth: Utiliser Basic Auth (app_key:secret_key)
            timeout: Timeout en secondes (défaut: config.init_payment_timeout)

        Returns:
            Réponse JSON parsée

        Raises:
            FreemoPayNetworkError: Erreur réseau
            FreemoPayAPIError: Erreur API
            FreemoPayError: Autre erreur
        """
        url = self._build_url(endpoint)
        timeout = timeout or self.config.init_payment_timeout

        # Préparer les headers
        headers = self.session.headers.copy()

        if bearer_token:
            headers['Authorization'] = f'Bearer {bearer_token}'
        elif use_basic_auth:
            # Basic Auth sera géré par requests.auth

            pass

        # Log de la requête
        self._log_request('POST', url, headers, data)

        try:
            start_time = time.time()

            if use_basic_auth:
                response = self.session.post(
                    url,
                    json=data,
                    auth=(self.config.app_key, self.config.secret_key),
                    timeout=timeout
                )
            else:
                response = self.session.post(
                    url,
                    json=data,
                    headers=headers,
                    timeout=timeout
                )

            duration = time.time() - start_time

            # Log de la réponse
            self._log_response(response.status_code, response.text, duration)

            # LOGS DÉTAILLÉS POUR DEBUG
            logger.info(f"[FreeMoPay Client] 📊 Status Code: {response.status_code}")
            logger.info(f"[FreeMoPay Client] 📄 Response Headers: {dict(response.headers)}")
            logger.info(f"[FreeMoPay Client] 📝 Response Body (raw): {response.text}")

            # Traiter la réponse
            return self._handle_response(response)

        except requests.exceptions.Timeout as e:
            logger.error(f"[FreeMoPay] Timeout après {timeout}s: {url}")
            raise FreemoPayNetworkError(
                f"Request timeout after {timeout}s",
                details={'url': url, 'timeout': timeout}
            ) from e

        except requests.exceptions.ConnectionError as e:
            logger.error(f"[FreeMoPay] Connection error: {str(e)}")
            raise FreemoPayNetworkError(
                f"Connection error: {str(e)}",
                details={'url': url}
            ) from e

        except requests.exceptions.RequestException as e:
            logger.error(f"[FreeMoPay] Request error: {str(e)}")
            raise FreemoPayNetworkError(
                f"Request failed: {str(e)}",
                details={'url': url}
            ) from e

    def get(
        self,
        endpoint: str,
        bearer_token: Optional[str] = None,
        use_basic_auth: bool = False,
        timeout: Optional[int] = None
    ) -> Dict[str, Any]:
        """
        Effectuer une requête GET.

        Args:
            endpoint: Endpoint API (relatif ou absolu)
            bearer_token: Token Bearer (si fourni)
            use_basic_auth: Utiliser Basic Auth
            timeout: Timeout en secondes (défaut: config.status_check_timeout)

        Returns:
            Réponse JSON parsée

        Raises:
            FreemoPayNetworkError: Erreur réseau
            FreemoPayAPIError: Erreur API
            FreemoPayError: Autre erreur
        """
        url = self._build_url(endpoint)
        timeout = timeout or self.config.status_check_timeout

        # Préparer les headers
        headers = self.session.headers.copy()

        if bearer_token:
            headers['Authorization'] = f'Bearer {bearer_token}'

        # Log de la requête
        self._log_request('GET', url, headers)

        try:
            start_time = time.time()

            if use_basic_auth:
                response = self.session.get(
                    url,
                    auth=(self.config.app_key, self.config.secret_key),
                    timeout=timeout
                )
            else:
                response = self.session.get(
                    url,
                    headers=headers,
                    timeout=timeout
                )

            duration = time.time() - start_time

            # Log de la réponse
            self._log_response(response.status_code, response.text, duration)

            # Traiter la réponse
            return self._handle_response(response)

        except requests.exceptions.Timeout as e:
            logger.error(f"[FreeMoPay] Timeout après {timeout}s: {url}")
            raise FreemoPayNetworkError(
                f"Request timeout after {timeout}s",
                details={'url': url, 'timeout': timeout}
            ) from e

        except requests.exceptions.ConnectionError as e:
            logger.error(f"[FreeMoPay] Connection error: {str(e)}")
            raise FreemoPayNetworkError(
                f"Connection error: {str(e)}",
                details={'url': url}
            ) from e

        except requests.exceptions.RequestException as e:
            logger.error(f"[FreeMoPay] Request error: {str(e)}")
            raise FreemoPayNetworkError(
                f"Request failed: {str(e)}",
                details={'url': url}
            ) from e

    def close(self):
        """Fermer la session HTTP."""
        if self._session:
            self._session.close()
            self._session = None

    def __enter__(self):
        """Context manager entry."""
        return self

    def __exit__(self, exc_type, exc_val, exc_tb):
        """Context manager exit."""
        self.close()
