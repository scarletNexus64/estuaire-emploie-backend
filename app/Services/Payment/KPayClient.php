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
 */
class KPayClient
{
    protected ?ServiceConfiguration $config = null;

    public function __construct()
    {
        $this->config = ServiceConfiguration::getKPayConfig();
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
        $timeout = $timeout ?? (int) ($this->config->kpay_init_payment_timeout ?? 30);

        $this->logRequest('POST', $url, $data);
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout($timeout)
                ->post($url, $data);

            $this->logResponse($response->status(), $response->body(), microtime(true) - $startTime);

            return $this->handleResponse($response);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("[KPay] Connection error: {$e->getMessage()}");
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
        $timeout = $timeout ?? (int) ($this->config->kpay_status_check_timeout ?? 30);

        $this->logRequest('GET', $url);
        $startTime = microtime(true);

        try {
            $response = Http::withHeaders($this->headers())
                ->timeout($timeout)
                ->get($url);

            $this->logResponse($response->status(), $response->body(), microtime(true) - $startTime);

            return $this->handleResponse($response);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("[KPay] Connection error: {$e->getMessage()}");
            throw new KPayException("Erreur de connexion à KPay: {$e->getMessage()}", 0, 'CONNECTION_ERROR', true, $e);
        }
    }

    /**
     * Headers d'authentification + contenu pour chaque appel.
     */
    protected function headers(): array
    {
        return [
            'X-API-Key' => $this->config->kpay_api_key,
            'X-Secret-Key' => $this->config->kpay_secret_key,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'Estuaire-Emploie-Backend/1.0',
        ];
    }

    protected function ensureConfigured(): void
    {
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

        $base = rtrim($this->config->kpay_base_url ?: 'https://admin.kpay.site', '/');
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
            Log::error("[KPay] Non-JSON response: " . substr($response->body(), 0, 200));
            throw new KPayException('Réponse API invalide (pas du JSON).', $response->status(), 'INVALID_RESPONSE', $response->serverError());
        }

        if ($response->failed()) {
            $status = $response->status();
            $kpayCode = $data['code'] ?? null;
            $message = is_string($data['message'] ?? null) ? $data['message'] : ($data['error'] ?? 'Erreur inconnue');

            // Retryable : 429 (rate limit) et 5xx selon la doc KPay
            $retryable = $status === 429 || $status >= 500;

            Log::error("[KPay] API Error {$status} ({$kpayCode}): {$message}");

            throw new KPayException("KPay {$status}: {$message}", $status, $kpayCode, $retryable);
        }

        return $data;
    }

    protected function logRequest(string $method, string $url, ?array $data = null): void
    {
        Log::debug("[KPay] {$method} {$url}");
        if ($data) {
            Log::debug("[KPay] Body: " . json_encode($this->maskSensitiveData($data)));
        }
    }

    protected function logResponse(int $statusCode, string $responseBody, float $duration): void
    {
        $bodyPreview = strlen($responseBody) > 500 ? substr($responseBody, 0, 500) . '...' : $responseBody;
        Log::debug("[KPay] Response {$statusCode} in " . number_format($duration, 2) . "s");
        Log::debug("[KPay] Body: {$bodyPreview}");

        if ($duration > 3.0) {
            Log::warning("[KPay] Slow request: " . number_format($duration, 2) . "s");
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
