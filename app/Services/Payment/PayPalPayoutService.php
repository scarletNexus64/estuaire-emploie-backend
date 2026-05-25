<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use App\Models\PlatformWithdrawal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalPayoutService
{
    protected ?ServiceConfiguration $config = null;

    public function __construct()
    {
        $this->config = ServiceConfiguration::getPayPalConfig();
    }

    /**
     * Get PayPal access token
     *
     * @return string
     * @throws \Exception
     */
    private function getAccessToken(): string
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        try {
            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            Log::info("[PayPal Payout] 🔐 Requesting access token", [
                'base_url' => $baseUrl,
                'mode' => $this->config->paypal_mode,
            ]);

            $response = Http::asForm()
                ->withBasicAuth($this->config->paypal_client_id, $this->config->paypal_client_secret)
                ->post($baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (!$response->successful()) {
                Log::error("[PayPal Payout] ❌ Access token request failed", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new \Exception('Failed to get PayPal access token: ' . $response->body());
            }

            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];

            Log::info("[PayPal Payout] ✅ Access token obtained successfully");

            return $accessToken;

        } catch (\Exception $e) {
            Log::error("[PayPal Payout] ❌ Failed to get access token: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Validate PayPal email
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Create a payout to a PayPal account
     *
     * @param PlatformWithdrawal $withdrawal
     * @return array
     * @throws \Exception
     */
    public function createPayout(PlatformWithdrawal $withdrawal): array
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [PayPal Payout] 💸 CREATING PAYOUT                                ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   💰 Amount: {$withdrawal->amount_sent} {$withdrawal->currency}");
        Log::info("   📧 Recipient: {$withdrawal->payment_account}");
        Log::info("   🔖 Reference: {$withdrawal->transaction_reference}");

        try {
            // Get access token
            $accessToken = $this->getAccessToken();

            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            // Format amount to 2 decimals
            $amount = number_format((float) $withdrawal->amount_sent, 2, '.', '');

            // Create payout batch
            $payoutData = [
                'sender_batch_header' => [
                    'sender_batch_id' => $withdrawal->transaction_reference,
                    'email_subject' => 'You have a payout from E-Emploie Platform',
                    'email_message' => 'You have received a payout from E-Emploie. Thank you!',
                ],
                'items' => [
                    [
                        'recipient_type' => 'EMAIL',
                        'amount' => [
                            'value' => $amount,
                            'currency' => $withdrawal->currency ?? 'USD',
                        ],
                        'receiver' => $withdrawal->payment_account,
                        'note' => $withdrawal->admin_notes ?? 'Platform withdrawal',
                        'sender_item_id' => $withdrawal->id . '_' . time(),
                    ],
                ],
            ];

            Log::info("[PayPal Payout] 📤 Sending payout request", [
                'amount' => $amount,
                'currency' => $withdrawal->currency ?? 'USD',
                'recipient' => substr($withdrawal->payment_account, 0, 5) . '***',
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->post($baseUrl . '/v1/payments/payouts', $payoutData);

            if (!$response->successful()) {
                $errorBody = $response->json();
                Log::error("[PayPal Payout] ❌ Payout creation failed", [
                    'status' => $response->status(),
                    'body' => $errorBody,
                ]);

                $errorMessage = $errorBody['message'] ?? 'Failed to create PayPal payout';
                if (isset($errorBody['details'])) {
                    $errorMessage .= ' - ' . json_encode($errorBody['details']);
                }

                throw new \Exception($errorMessage);
            }

            $responseData = $response->json();
            $batchId = $responseData['batch_header']['payout_batch_id'] ?? null;

            if (!$batchId) {
                throw new \Exception('No batch ID returned from PayPal');
            }

            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::info("[PayPal Payout] ✅ Payout created successfully!");
            Log::info("[PayPal Payout] 🔖 Batch ID: {$batchId}");
            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return [
                'success' => true,
                'batch_id' => $batchId,
                'response' => $responseData,
            ];

        } catch (\Exception $e) {
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::error("[PayPal Payout] ❌ Payout creation failed");
            Log::error("[PayPal Payout] ❌ Error: {$e->getMessage()}");
            Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            throw $e;
        }
    }

    /**
     * Check payout status
     *
     * @param string $batchId
     * @return array
     * @throws \Exception
     */
    public function checkPayoutStatus(string $batchId): array
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        try {
            $accessToken = $this->getAccessToken();

            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            Log::info("[PayPal Payout] 🔍 Checking payout status", [
                'batch_id' => $batchId,
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($baseUrl . '/v1/payments/payouts/' . $batchId);

            if (!$response->successful()) {
                throw new \Exception('Failed to check payout status: ' . $response->body());
            }

            $responseData = $response->json();
            $batchStatus = $responseData['batch_header']['batch_status'] ?? 'UNKNOWN';

            Log::info("[PayPal Payout] 📊 Payout status: {$batchStatus}");

            return [
                'success' => true,
                'status' => $batchStatus,
                'response' => $responseData,
            ];

        } catch (\Exception $e) {
            Log::error("[PayPal Payout] ❌ Failed to check payout status: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Get payout item details
     *
     * @param string $payoutItemId
     * @return array
     * @throws \Exception
     */
    public function getPayoutItemDetails(string $payoutItemId): array
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('PayPal service is not configured properly');
        }

        try {
            $accessToken = $this->getAccessToken();

            $baseUrl = $this->config->paypal_mode === 'live'
                ? 'https://api.paypal.com'
                : 'https://api.sandbox.paypal.com';

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($baseUrl . '/v1/payments/payouts-item/' . $payoutItemId);

            if (!$response->successful()) {
                throw new \Exception('Failed to get payout item details: ' . $response->body());
            }

            return [
                'success' => true,
                'response' => $response->json(),
            ];

        } catch (\Exception $e) {
            Log::error("[PayPal Payout] ❌ Failed to get payout item details: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Test PayPal Payout connection
     *
     * @return array
     */
    public function testConnection(): array
    {
        try {
            $accessToken = $this->getAccessToken();

            if ($accessToken) {
                return [
                    'success' => true,
                    'message' => 'PayPal Payout connection successful',
                    'data' => [
                        'mode' => $this->config->paypal_mode,
                    ],
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not retrieve access token',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection test failed: ' . $e->getMessage(),
            ];
        }
    }
}
