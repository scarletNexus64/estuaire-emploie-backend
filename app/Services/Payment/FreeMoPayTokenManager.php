<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FreeMoPayTokenManager
{
    protected ?ServiceConfiguration $config = null;
    protected FreeMoPayClient $client;

    public function __construct(FreeMoPayClient $client)
    {
        $this->config = ServiceConfiguration::getFreeMoPayConfig();
        $this->client = $client;
    }

    /**
     * Get access token (from cache or generate new one)
     *
     * @return string
     * @throws \Exception
     */
    public function getToken(): string
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('FreeMoPay service is not configured properly');
        }

        $cacheKey = 'freemopay_access_token';

        // Try to get from cache
        $token = Cache::get($cacheKey);

        if ($token) {
            Log::debug("[FreeMoPay TokenManager] Using cached token");
            return $token;
        }

        // Generate new token
        Log::info("[FreeMoPay TokenManager] Generating new access token");
        $token = $this->generateToken();

        // Cache it (for 50 minutes, token expires in 60 minutes)
        $cacheDuration = $this->config->freemopay_token_cache_duration ?? 3000; // 3000 seconds = 50 minutes
        Cache::put($cacheKey, $token, $cacheDuration);

        Log::info("[FreeMoPay TokenManager] Token cached for {$cacheDuration} seconds");

        return $token;
    }

    /**
     * Generate a new access token from FreeMoPay API v2
     *
     * API v2 endpoint: POST /api/v2/payment/token
     * Body: { "appKey": "...", "secretKey": "..." }
     * Response: { "access_token": "...", "expires_in": 3600 }
     *
     * @return string
     * @throws \Exception
     */
    protected function generateToken(): string
    {
        try {
            // FreeMoPay API v2 endpoint
            $baseUrl = rtrim($this->config->freemopay_base_url, '/');
            $url = $baseUrl . '/api/v2/payment/token';

            $payload = [
                'appKey' => $this->config->freemopay_app_key,
                'secretKey' => $this->config->freemopay_secret_key,
            ];

            Log::info("[FreeMoPay TokenManager] Requesting new token from: {$url}");

            // API v2: Use JSON body (no Basic Auth, no Bearer token)
            $response = $this->client->post(
                $url,
                $payload,
                null,  // no bearer token
                false, // no basic auth - just JSON body
                $this->config->freemopay_token_timeout ?? 30
            );

            // Extract token from response (API v2 returns access_token)
            $token = $response['access_token'] ?? $response['token'] ?? $response['data']['token'] ?? null;

            if (!$token) {
                Log::error("[FreeMoPay TokenManager] No token in response: " . json_encode($response));
                throw new \Exception('No token in response');
            }

            Log::info("[FreeMoPay TokenManager] Token generated successfully");

            return $token;

        } catch (\Exception $e) {
            Log::error("[FreeMoPay TokenManager] Token generation failed: " . $e->getMessage());
            throw new \Exception("Failed to generate access token: {$e->getMessage()}");
        }
    }

    /**
     * Clear cached token (force regeneration on next request)
     *
     * @return void
     */
    public function clearToken(): void
    {
        Cache::forget('freemopay_access_token');
        Log::info("[FreeMoPay TokenManager] Token cache cleared");
    }

    /**
     * Refresh token (clear cache and get new one)
     *
     * @return string
     * @throws \Exception
     */
    public function refreshToken(): string
    {
        $this->clearToken();
        return $this->getToken();
    }
}
