<?php

namespace App\Services\Payment;

use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FreeMoPayTokenManager
{
    protected FreeMoPayClient $client;

    public function __construct(FreeMoPayClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get access token (from cache or generate new one)
     */
    public function getToken(): string
    {
        if (!$this->client->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré correctement. Vérifiez les clés API.');
        }

        $cacheKey = 'freemopay_access_token';

        $token = Cache::get($cacheKey);

        if ($token) {
            Log::debug("[FreeMoPay TokenManager] Utilisation du token en cache");
            return $token;
        }

        Log::info("[FreeMoPay TokenManager] Génération d'un nouveau token d'accès");
        $token = $this->generateToken();

        $cacheDuration = $this->client->getConfig('cache_duration', 3000);
        Cache::put($cacheKey, $token, $cacheDuration);

        Log::info("[FreeMoPay TokenManager] Token mis en cache pour {$cacheDuration} secondes");

        return $token;
    }

    /**
     * Generate a new access token from FreeMoPay API v2
     *
     * API v2 endpoint: POST /api/v2/payment/token
     * Body: { "appKey": "...", "secretKey": "..." }
     * Response: { "access_token": "...", "expires_in": 3600 }
     */
    protected function generateToken(): string
    {
        try {
            $baseUrl = rtrim($this->client->getConfig('base_url'), '/');
            $url = $baseUrl . '/api/v2/payment/token';

            $payload = [
                'appKey' => $this->client->getConfig('app_key'),
                'secretKey' => $this->client->getConfig('secret_key'),
            ];

            Log::info("[FreeMoPay TokenManager] Demande de nouveau token à: {$url}");

            $response = $this->client->post(
                $url,
                $payload,
                null,
                false,
                $this->client->getConfig('timeout_token', 30)
            );

            $token = $response['access_token'] ?? $response['token'] ?? $response['data']['token'] ?? null;

            if (!$token) {
                Log::error("[FreeMoPay TokenManager] Pas de token dans la réponse: " . json_encode($response));
                throw new \Exception('Pas de token dans la réponse');
            }

            Log::info("[FreeMoPay TokenManager] Token généré avec succès");

            return $token;
        } catch (\Exception $e) {
            Log::error("[FreeMoPay TokenManager] Échec de génération du token: " . $e->getMessage());
            throw new \Exception("Échec de génération du token d'accès: {$e->getMessage()}");
        }
    }

    /**
     * Clear cached token (force regeneration on next request)
     */
    public function clearToken(): void
    {
        Cache::forget('freemopay_access_token');
        Log::info("[FreeMoPay TokenManager] Cache du token vidé");
    }

    /**
     * Refresh token (clear cache and get new one)
     */
    public function refreshToken(): string
    {
        $this->clearToken();
        return $this->getToken();
    }
}
