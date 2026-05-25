<?php

namespace App\Services\Payment;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeMoPayClient
{
    protected array $config;

    public function __construct()
    {
        $this->config = $this->loadConfig();
    }

    /**
     * Load FreeMoPay configuration from PlatformSettings
     */
    protected function loadConfig(): array
    {
        return [
            'base_url' => PlatformSetting::getValue('freemopay_base_url', 'https://api-v2.freemopay.com'),
            'app_key' => PlatformSetting::getValue('freemopay_api_key', ''),
            'secret_key' => PlatformSetting::getValue('freemopay_api_secret', ''),
            'timeout_init' => (int) PlatformSetting::getValue('freemopay_timeout_init', 5),
            'timeout_verify' => (int) PlatformSetting::getValue('freemopay_timeout_verify', 30),
            'timeout_token' => (int) PlatformSetting::getValue('freemopay_timeout_token', 10),
            'cache_duration' => (int) PlatformSetting::getValue('freemopay_cache_duration', 3000),
            'retry_count' => (int) PlatformSetting::getValue('freemopay_retry_count', 3),
            'retry_delay' => (float) PlatformSetting::getValue('freemopay_retry_delay', 0.5),
        ];
    }

    /**
     * Check if FreeMoPay is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->config['app_key']) && !empty($this->config['secret_key']);
    }

    /**
     * Get configuration value
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Make a POST request to FreeMoPay API
     */
    public function post(
        string $endpoint,
        array $data,
        ?string $bearerToken = null,
        bool $useBasicAuth = false,
        ?int $timeout = null
    ): array {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré correctement. Vérifiez les clés API.');
        }

        $url = $this->buildUrl($endpoint);
        $timeout = $timeout ?? $this->config['timeout_init'];
        $userAgent = $this->defaultUserAgent();

        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => $userAgent,
        ];

        $this->logRequest('POST', $url, $headers, $data);

        $startTime = microtime(true);

        try {
            $http = Http::withHeaders($headers)->timeout($timeout);

            if ($bearerToken) {
                $http = $http->withToken($bearerToken);
            } elseif ($useBasicAuth) {
                $http = $http->withBasicAuth(
                    $this->config['app_key'],
                    $this->config['secret_key']
                );
            }

            $response = $http->post($url, $data);

            $duration = microtime(true) - $startTime;

            $this->logResponse($response->status(), $response->body(), $duration);

            Log::info("[FreeMoPay Client] Status Code: {$response->status()}");
            Log::debug("[FreeMoPay Client] Response Body: {$response->body()}");

            return $this->handleResponse($response);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("[FreeMoPay] Erreur de connexion: " . $e->getMessage());
            throw new \Exception("Erreur de connexion à FreeMoPay: {$e->getMessage()}");
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error("[FreeMoPay] Erreur de requête: " . $e->getMessage());
            throw new \Exception("Échec de la requête FreeMoPay: {$e->getMessage()}");
        } catch (\Exception $e) {
            Log::error("[FreeMoPay] Erreur inattendue: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Make a GET request to FreeMoPay API
     */
    public function get(
        string $endpoint,
        ?string $bearerToken = null,
        bool $useBasicAuth = false,
        ?int $timeout = null
    ): array {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré correctement. Vérifiez les clés API.');
        }

        $url = $this->buildUrl($endpoint);
        $timeout = $timeout ?? $this->config['timeout_verify'];
        $userAgent = $this->defaultUserAgent();

        $headers = [
            'Accept' => 'application/json',
            'User-Agent' => $userAgent,
        ];

        $this->logRequest('GET', $url, $headers);

        $startTime = microtime(true);

        try {
            $http = Http::withHeaders($headers)->timeout($timeout);

            if ($bearerToken) {
                $http = $http->withToken($bearerToken);
            } elseif ($useBasicAuth) {
                $http = $http->withBasicAuth(
                    $this->config['app_key'],
                    $this->config['secret_key']
                );
            }

            $response = $http->get($url);

            $duration = microtime(true) - $startTime;

            $this->logResponse($response->status(), $response->body(), $duration);

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error("[FreeMoPay] Erreur de requête: " . $e->getMessage());
            throw new \Exception("Échec de la requête: {$e->getMessage()}");
        }
    }

    protected function defaultUserAgent(): string
    {
        $platformName = PlatformSetting::getName();
        $sanitized = preg_replace('/[^A-Za-z0-9\-]/', '-', $platformName);
        $sanitized = trim($sanitized, '-');

        if ($sanitized === '') {
            $sanitized = 'Platform';
        }

        return "{$sanitized}-Backend/1.0";
    }

    /**
     * Build full URL from endpoint
     */
    protected function buildUrl(string $endpoint): string
    {
        if (str_starts_with($endpoint, 'http://') || str_starts_with($endpoint, 'https://')) {
            return $endpoint;
        }

        $base = rtrim($this->config['base_url'], '/');
        $endpoint = ltrim($endpoint, '/');

        return "{$base}/{$endpoint}";
    }

    /**
     * Log request details (without sensitive data)
     */
    protected function logRequest(string $method, string $url, array $headers, ?array $data = null): void
    {
        $safeHeaders = $headers;
        if (isset($safeHeaders['Authorization'])) {
            $authType = explode(' ', $safeHeaders['Authorization'])[0] ?? '';
            $safeHeaders['Authorization'] = "{$authType} [HIDDEN]";
        }

        Log::debug("[FreeMoPay] {$method} {$url}");
        Log::debug("[FreeMoPay] Headers: " . json_encode($safeHeaders));

        if ($data) {
            $safeData = $this->maskSensitiveData($data);
            Log::debug("[FreeMoPay] Body: " . json_encode($safeData));
        }
    }

    /**
     * Mask sensitive data for logging
     */
    protected function maskSensitiveData(array $data): array
    {
        $safeData = $data;
        $sensitiveKeys = ['secretKey', 'secret_key', 'password', 'token'];

        foreach ($sensitiveKeys as $key) {
            if (isset($safeData[$key])) {
                $safeData[$key] = '[HIDDEN]';
            }
        }

        return $safeData;
    }

    /**
     * Log response details
     */
    protected function logResponse(int $statusCode, string $responseBody, float $duration): void
    {
        $bodyPreview = strlen($responseBody) > 500
            ? substr($responseBody, 0, 500) . '...'
            : $responseBody;

        Log::debug("[FreeMoPay] Response {$statusCode} in " . number_format($duration, 2) . "s");
        Log::debug("[FreeMoPay] Body: {$bodyPreview}");

        if ($duration > 3.0) {
            Log::warning("[FreeMoPay] Requête lente: " . number_format($duration, 2) . "s");
        }
    }

    /**
     * Handle HTTP response
     */
    protected function handleResponse($response): array
    {
        try {
            $data = $response->json();
        } catch (\Exception $e) {
            Log::error("[FreeMoPay] Réponse non-JSON: " . substr($response->body(), 0, 200));
            throw new \Exception("Réponse API invalide (non JSON)");
        }

        if ($response->failed()) {
            Log::error("[FreeMoPay] Erreur API {$response->status()}: " . json_encode($data));

            // Extract error message from various possible formats
            $errorMessage = 'Erreur inconnue';
            if (isset($data['message'])) {
                $errorMessage = is_array($data['message']) ? json_encode($data['message']) : $data['message'];
            } elseif (isset($data['error'])) {
                $errorMessage = is_array($data['error']) ? json_encode($data['error']) : $data['error'];
            } elseif (isset($data['errors'])) {
                $errorMessage = is_array($data['errors']) ? json_encode($data['errors']) : $data['errors'];
            }

            throw new \Exception("Erreur API: {$response->status()} - {$errorMessage}");
        }

        return $data;
    }
}
