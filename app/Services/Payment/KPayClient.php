<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Client HTTP bas-niveau pour l'API KPay (https://admin.kpay.site/api/v1).
 *
 * Authentification par double header :
 *   X-API-Key    : clé publique (kpay_test_ / kpay_live_)
 *   X-Secret-Key : clé secrète  (sk_test_ / sk_live_)
 *
 * Contrairement à FreeMoPay, KPay n'utilise PAS de token Bearer.
 *
 * Deux modes :
 *  - sans argument : compte KPay Mobile Money (ServiceConfiguration `kpay`),
 *    comportement historique inchangé ;
 *  - avec `$credentials` : autre compte de la même famille d'API, ex. KPay Lite
 *    (cartes) — clés `base_url`, `api_key`, `secret_key`, `timeout`, `log_tag`.
 *    Le préfixe `api/v1/` est le même.
 */
class KPayClient
{
    protected ?ServiceConfiguration $config = null;

    /** Identifiants explicites (mode KPay Lite) ; null = compte KPay historique. */
    protected ?array $credentials = null;

    protected string $logTag = '[KPay]';

    public function __construct(?array $credentials = null)
    {
        if ($credentials !== null) {
            $this->credentials = $credentials;
            $this->logTag = $credentials['log_tag'] ?? '[KPay]';

            return;
        }

        $this->config = ServiceConfiguration::getKPayConfig();
    }

    /**
     * Client pré-configuré pour le compte KPay Lite (cartes Visa / Mastercard).
     */
    public static function forLite(?KPayLiteConfig $lite = null): self
    {
        return new self(($lite ?? KPayLiteConfig::make())->clientCredentials());
    }

    /**
     * Requête POST vers l'API KPay.
     *
     * @throws KPayException
     */
    public function post(string $endpoint, array $data, ?int $timeout = null): array
    {
        $this->ensureConfigured();

        $url = $this->buildUrl($endpoint);
        $timeout = $timeout ?? $this->defaultTimeout('kpay_init_payment_timeout');

        $this->logRequest('POST', $url, $data);
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout($timeout)
                ->post($url, $data);

            $this->logResponse($response->status(), $response->body(), microtime(true) - $startTime);

            return $this->handleResponse($response);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("{$this->logTag} Connection error: {$e->getMessage()}");
            throw new KPayException("Erreur de connexion à KPay: {$e->getMessage()}", 0, 'CONNECTION_ERROR', true, $e);
        }
    }

    /**
     * Requête GET vers l'API KPay.
     *
     * @throws KPayException
     */
    public function get(string $endpoint, ?int $timeout = null): array
    {
        $this->ensureConfigured();

        $url = $this->buildUrl($endpoint);
        $timeout = $timeout ?? $this->defaultTimeout('kpay_status_check_timeout');

        $this->logRequest('GET', $url);
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout($timeout)
                ->get($url);

            $this->logResponse($response->status(), $response->body(), microtime(true) - $startTime);

            return $this->handleResponse($response);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("{$this->logTag} Connection error: {$e->getMessage()}");
            throw new KPayException("Erreur de connexion à KPay: {$e->getMessage()}", 0, 'CONNECTION_ERROR', true, $e);
        }
    }

    /**
     * Headers d'authentification + contenu pour chaque appel.
     */
    protected function headers(): array
    {
        return [
            'X-API-Key' => $this->credentials !== null ? ($this->credentials['api_key'] ?? null) : $this->config->kpay_api_key,
            'X-Secret-Key' => $this->credentials !== null ? ($this->credentials['secret_key'] ?? null) : $this->config->kpay_secret_key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'Estuaire-Emploie-Backend/1.0',
        ];
    }

    protected function defaultTimeout(string $field): int
    {
        if ($this->credentials !== null) {
            return (int) ($this->credentials['timeout'] ?? 30);
        }

        return (int) ($this->config->{$field} ?? 30);
    }

    protected function ensureConfigured(): void
    {
        if ($this->credentials !== null) {
            if (empty($this->credentials['api_key']) || empty($this->credentials['secret_key'])) {
                throw new KPayException('Le service KPay Lite n\'est pas configuré correctement.', 0, 'NOT_CONFIGURED');
            }

            return;
        }

        if (!$this->config || !$this->config->isConfigured()) {
            throw new KPayException('Le service KPay n\'est pas configuré correctement.', 0, 'NOT_CONFIGURED');
        }
    }

    /**
     * Construit l'URL complète. Préfixe /api/v1 si l'endpoint est relatif.
     */
    protected function buildUrl(string $endpoint): string
    {
        if (str_starts_with($endpoint, 'http://') || str_starts_with($endpoint, 'https://')) {
            return $endpoint;
        }

        $base = $this->credentials !== null
            ? rtrim($this->credentials['base_url'] ?? KPayLiteConfig::DEFAULT_BASE_URL, '/')
            : rtrim($this->config->kpay_base_url ?: 'https://admin.kpay.site', '/');
        $endpoint = ltrim($endpoint, '/');

        // Préfixe automatique /api/v1 si absent
        if (!str_starts_with($endpoint, 'api/')) {
            $endpoint = 'api/v1/' . $endpoint;
        }

        return "{$base}/{$endpoint}";
    }

    /**
     * Traite la réponse HTTP et l'enveloppe d'erreur KPay :
     * {statusCode, error, code, message, timestamp, path}
     *
     * @throws KPayException
     */
    protected function handleResponse($response): array
    {
        try {
            $data = $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error("{$this->logTag} Non-JSON response: " . substr($response->body(), 0, 200));
            throw new KPayException('Réponse API invalide (pas du JSON).', $response->status(), 'INVALID_RESPONSE', $response->serverError());
        }

        if (!is_array($data)) {
            $data = [];
        }

        if ($response->failed()) {
            $status = $response->status();
            $kpayCode = $data['code'] ?? null;
            $rawMessage = $data['message'] ?? null;
            $message = match (true) {
                is_string($rawMessage) => $rawMessage,
                // Erreurs de validation : liste de messages par champ.
                is_array($rawMessage) && $rawMessage !== [] => implode(' | ', array_map(
                    fn ($m) => is_scalar($m) ? (string) $m : json_encode($m),
                    $rawMessage
                )),
                default => is_string($data['error'] ?? null) ? $data['error'] : 'Erreur inconnue',
            };

            // Retryable : 429 (rate limit) et 5xx selon la doc KPay
            $retryable = $status === 429 || $status >= 500;

            Log::error("{$this->logTag} API Error {$status} ({$kpayCode}): {$message}");

            $exception = new KPayException("KPay {$status}: {$message}", $status, $kpayCode, $retryable);
            $exception->responseBody = $data;

            throw $exception;
        }

        return $data;
    }

    protected function logRequest(string $method, string $url, ?array $data = null): void
    {
        Log::debug("{$this->logTag} {$method} {$url}");
        if ($data) {
            Log::debug("{$this->logTag} Body: " . json_encode($this->maskSensitiveData($data)));
        }
    }

    protected function logResponse(int $statusCode, string $responseBody, float $duration): void
    {
        $bodyPreview = strlen($responseBody) > 500 ? substr($responseBody, 0, 500) . '...' : $responseBody;
        Log::debug("{$this->logTag} Response {$statusCode} in " . number_format($duration, 2) . "s");
        Log::debug("{$this->logTag} Body: {$bodyPreview}");

        if ($duration > 3.0) {
            Log::warning("{$this->logTag} Slow request: " . number_format($duration, 2) . "s");
        }
    }

    /**
     * Masque les données sensibles dans les logs (numéro de téléphone partiel).
     */
    protected function maskSensitiveData(array $data): array
    {
        $safe = $data;

        if (isset($safe['phoneNumber']) && is_string($safe['phoneNumber'])) {
            $phone = $safe['phoneNumber'];
            $safe['phoneNumber'] = strlen($phone) > 4
                ? substr($phone, 0, 3) . str_repeat('*', max(0, strlen($phone) - 5)) . substr($phone, -2)
                : '****';
        }

        return $safe;
    }
}
