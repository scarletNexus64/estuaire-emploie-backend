<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FreeMoPayDisbursementService
{
    protected ?ServiceConfiguration $config = null;
    protected string $baseUrl;
    protected ?string $appKey;
    protected ?string $secretKey;

    // Polling configuration for disbursement
    protected int $pollingInterval = 3;      // seconds between each status check
    protected int $pollingTimeout = 90;      // max seconds to wait for disbursement completion
    protected int $maxPollingAttempts = 30;  // max number of polling attempts

    public function __construct()
    {
        try {
            $this->config = ServiceConfiguration::getFreeMoPayConfig();
        } catch (\Exception $e) {
            $this->config = null;
        }

        if ($this->config && $this->config->isConfigured()) {
            $this->baseUrl = rtrim($this->config->freemopay_base_url ?? 'https://api-v2.freemopay.com', '/');
            $this->appKey = $this->config->freemopay_app_key ?? null;
            $this->secretKey = $this->config->freemopay_secret_key ?? null;
        } else {
            $this->baseUrl = 'https://api-v2.freemopay.com';
            $this->appKey = null;
            $this->secretKey = null;
        }
    }

    /**
     * Check if FreeMoPay is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->appKey) && !empty($this->secretKey);
    }

    /**
     * Normalize phone number to international format
     * Supports Cameroon (237) and DRC (243)
     */
    public function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If already starts with country code, return as is
        if (preg_match('/^(237|243)/', $phone)) {
            return $phone;
        }

        // Try to detect country from first digits
        // Cameroon mobile: 6XX XXX XXX
        if (preg_match('/^6\d{8}$/', $phone)) {
            return '237' . $phone;
        }

        // DRC mobile: 8XX XXX XXX or 9XX XXX XXX
        if (preg_match('/^[89]\d{8}$/', $phone)) {
            return '243' . $phone;
        }

        throw new \Exception('Format de numéro invalide. Utilisez le format: 237XXXXXXXXX ou 243XXXXXXXXX');
    }

    /**
     * Check withdrawal status by reference
     */
    public function checkWithdrawalStatus(string $reference): array
    {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré.');
        }

        $endpoint = "{$this->baseUrl}/api/v2/payment/check-status/{$reference}";

        Log::debug("[FreeMoPay] Vérification statut - Référence: {$reference}");

        try {
            $response = Http::withBasicAuth($this->appKey, $this->secretKey)
                ->timeout(30)
                ->get($endpoint);

            if (!$response->successful()) {
                $errorBody = $response->json() ?? ['message' => $response->body()];
                $errorMessage = is_array($errorBody['message'] ?? null)
                    ? implode(', ', $errorBody['message'])
                    : ($errorBody['message'] ?? "Erreur HTTP {$response->status()}");

                Log::error("[FreeMoPay] Erreur check-status: {$errorMessage}");
                throw new \Exception("Erreur vérification statut: {$errorMessage}");
            }

            $data = $response->json();
            Log::debug("[FreeMoPay] Statut reçu: " . json_encode($data));

            return $data;

        } catch (\Exception $e) {
            Log::error("[FreeMoPay] Exception check-status: " . $e->getMessage());
            throw $e;
        }
    }
}
