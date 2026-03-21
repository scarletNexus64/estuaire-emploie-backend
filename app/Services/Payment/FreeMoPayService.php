<?php

namespace App\Services\Payment;

use App\Models\ServiceConfiguration;
use App\Models\Payment;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FreeMoPayService
{
    protected ?ServiceConfiguration $config = null;
    protected FreeMoPayClient $client;
    protected FreeMoPayTokenManager $tokenManager;

    // Polling configuration
    protected int $pollingInterval = 3;      // seconds between each status check
    protected int $pollingTimeout = 300;     // max seconds to wait for payment completion (5 minutes)
    protected int $maxPollingAttempts = 100; // max number of polling attempts

    public function __construct()
    {
        $this->config = ServiceConfiguration::getFreeMoPayConfig();
        $this->client = new FreeMoPayClient();
        $this->tokenManager = new FreeMoPayTokenManager($this->client);
    }

    /**
     * Initialize a payment with FreeMoPay and wait for completion (SYNCHRONOUS)
     *
     * This method will:
     * 1. Create payment record in DB
     * 2. Call FreeMoPay API to initiate payment
     * 3. Poll for status until SUCCESS, FAILED, or CANCELLED
     * 4. Return final result to caller
     *
     * @param User|Company $payer The entity making the payment
     * @param float $amount Payment amount
     * @param string $phoneNumber Payer's phone number (237XXXXXXXXX or XXXXXXXXX)
     * @param string $description Payment description
     * @param string|null $externalId Optional external ID
     * @param \Illuminate\Database\Eloquent\Model|null $payable The payable entity (e.g., SubscriptionPlan)
     * @param string|null $paymentType Type of payment (e.g., 'subscription', 'wallet_recharge')
     * @return Payment
     * @throws \Exception
     */
    public function initPayment(
        $payer,
        float $amount,
        string $phoneNumber,
        string $description,
        ?string $externalId = null,
        $payable = null,
        ?string $paymentType = null
    ): Payment {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('FreeMoPay service is not configured properly');
        }

        Log::info("╔════════════════════════════════════════════════════════════════════╗");
        Log::info("║ [FreeMoPay Service] 💳 INITIATING SYNCHRONOUS PAYMENT             ║");
        Log::info("╚════════════════════════════════════════════════════════════════════╝");
        Log::info("   💰 Amount: {$amount} XAF");
        Log::info("   📱 Phone: {$phoneNumber}");
        Log::info("   📝 Description: {$description}");

        // 1. Validate phone number
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);
        Log::info("   ✓ Phone normalized: {$normalizedPhone}");

        // 2. Generate external ID if not provided
        if (!$externalId) {
            $externalId = $this->generateExternalId();
        }
        $externalId = $this->ensureUniqueExternalId($externalId);
        Log::info("   ✓ External ID: {$externalId}");

        // 3. Get callback URL (not used for polling but required by API)
        $callbackUrl = $this->config->freemopay_callback_url ?? config('app.url') . '/api/webhooks/freemopay';
        Log::info("   ✓ Callback URL: {$callbackUrl} (note: using polling, callback not required)");

        // 4. Create Payment record in database (status: pending)
        $payment = DB::transaction(function () use ($payer, $amount, $normalizedPhone, $description, $externalId, $payable, $paymentType) {
            // Déterminer la méthode de paiement en fonction du préfixe téléphone
            $paymentMethod = $this->detectPaymentMethod($normalizedPhone);

            $paymentData = [
                'amount' => $amount,
                'fees' => 0,
                'total' => $amount,
                'phone_number' => $normalizedPhone,
                'description' => $description,
                'external_id' => $externalId,
                'status' => 'pending',
                'provider' => 'freemopay',
                'payment_method' => $paymentMethod,
                'payment_type' => $paymentType,
                'currency' => 'XAF',
            ];

            if ($payer instanceof Company) {
                $paymentData['company_id'] = $payer->id;
            } elseif ($payer instanceof User) {
                $paymentData['user_id'] = $payer->id;
            }

            // Add payable (morph relation) if provided
            if ($payable) {
                $paymentData['payable_type'] = get_class($payable);
                $paymentData['payable_id'] = $payable->id;
            }

            return Payment::create($paymentData);
        });

        Log::info("   ✓ Payment record created in database");
        Log::info("   📋 Payment ID: {$payment->id}");
        Log::info("   📊 Status: {$payment->status}");
        Log::info("   💳 Payment method: {$payment->payment_method}");

        // 5. Call FreeMoPay API to initiate payment
        try {
            Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            Log::info("[FreeMoPay API] 🚀 Calling FreeMoPay API to initiate payment...");

            $freemoResponse = $this->callFreeMoPayAPI(
                $normalizedPhone,
                $amount,
                $externalId,
                $description,
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                Log::error("[FreeMoPay API] ❌ ERROR - No reference in response");
                Log::error("[FreeMoPay API] Response: " . json_encode($freemoResponse));
                Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                $payment->update(['status' => 'failed']);
                throw new \Exception('No reference in FreeMoPay response');
            }

            $payment->update([
                'provider_reference' => $reference,
                'payment_provider_response' => $freemoResponse,
            ]);

            Log::info("[FreeMoPay API] ✓ Payment initiated successfully");
            Log::info("[FreeMoPay API] 🔖 Reference: {$reference}");
            Log::info("[FreeMoPay API] 📱 User should receive payment prompt on phone");

            // 6. SYNCHRONOUS POLLING: Wait for payment completion
            $finalPayment = $this->waitForPaymentCompletion($payment, $reference);

            Log::info("╔════════════════════════════════════════════════════════════════════╗");
            Log::info("║ [FreeMoPay Service] ✅ PAYMENT COMPLETED SUCCESSFULLY             ║");
            Log::info("╚════════════════════════════════════════════════════════════════════╝");
            Log::info("   📋 Payment ID: {$finalPayment->id}");
            Log::info("   📊 Final status: {$finalPayment->status}");
            Log::info("   💰 Amount: {$finalPayment->amount} XAF");

            return $finalPayment;

        } catch (\Exception $e) {
            Log::error("╔════════════════════════════════════════════════════════════════════╗");
            Log::error("║ [FreeMoPay Service] ❌ PAYMENT FAILED                             ║");
            Log::error("╚════════════════════════════════════════════════════════════════════╝");
            Log::error("   📋 Payment ID: {$payment->id}");
            Log::error("   ❌ Error: {$e->getMessage()}");
            Log::error("   📊 Stack trace: " . $e->getTraceAsString());

            $payment->update(['status' => 'failed']);
            throw $e;
        }
    }

    /**
     * Poll FreeMoPay API until payment is completed, failed, or timeout
     *
     * @param Payment $payment
     * @param string $reference
     * @return Payment
     * @throws \Exception
     */
    protected function waitForPaymentCompletion(Payment $payment, string $reference): Payment
    {
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        Log::info("[FreeMoPay Polling] 🔄 Starting payment status polling");
        Log::info("[FreeMoPay Polling] 📋 Payment ID: {$payment->id}");
        Log::info("[FreeMoPay Polling] 🔖 Reference: {$reference}");
        Log::info("[FreeMoPay Polling] ⏱️  Polling config: Interval={$this->pollingInterval}s, Timeout={$this->pollingTimeout}s, Max attempts={$this->maxPollingAttempts}");
        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $startTime = time();
        $attempts = 0;

        // Terminal statuses that indicate payment is complete
        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;
            $remainingTime = $this->pollingTimeout - $elapsed;

            Log::info("┌─────────────────────────────────────────────────────────────────┐");
            Log::info("│ [Polling Attempt #{$attempts}]");
            Log::info("│ ⏱️  Time elapsed: {$elapsed}s / {$this->pollingTimeout}s (⏳ {$remainingTime}s remaining)");
            Log::info("└─────────────────────────────────────────────────────────────────┘");

            // Check timeout
            if ($elapsed >= $this->pollingTimeout) {
                Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                Log::error("[FreeMoPay Polling] ⏰ TIMEOUT - Polling exceeded maximum time");
                Log::error("[FreeMoPay Polling] 📋 Payment ID: {$payment->id}");
                Log::error("[FreeMoPay Polling] 🔖 Reference: {$reference}");
                Log::error("[FreeMoPay Polling] ⏱️  Elapsed: {$elapsed}s / {$this->pollingTimeout}s");
                Log::error("[FreeMoPay Polling] 🔄 Total attempts: {$attempts}");
                Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

                $payment->update([
                    'status' => 'pending',
                    'notes' => "Payment polling timeout after {$elapsed}s and {$attempts} attempts. Please verify payment status with provider.",
                ]);
                throw new \Exception("Le délai d'attente du paiement a expiré. Veuillez vérifier votre téléphone et réessayer.");
            }

            // Check max attempts
            if ($attempts > $this->maxPollingAttempts) {
                Log::warning("[FreeMoPay Polling] ⚠️  Max polling attempts ({$this->maxPollingAttempts}) reached - Reference: {$reference}");
                break;
            }

            try {
                Log::info("   ↳ 📡 Checking payment status with FreeMoPay API...");

                $statusResponse = $this->checkPaymentStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? 'UNKNOWN');
                $message = $statusResponse['message'] ?? 'No message';

                Log::info("   ↳ 📥 Received status: {$currentStatus}");
                if ($message !== 'No message') {
                    Log::info("   ↳ 💬 Message: {$message}");
                }

                // Check for SUCCESS
                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                    Log::info("[FreeMoPay Polling] ✅ PAYMENT SUCCESS!");
                    Log::info("[FreeMoPay Polling] 📋 Payment ID: {$payment->id}");
                    Log::info("[FreeMoPay Polling] 🔖 Reference: {$reference}");
                    Log::info("[FreeMoPay Polling] ⏱️  Completed in: {$elapsed}s after {$attempts} attempts");
                    Log::info("[FreeMoPay Polling] 💰 Amount: {$payment->amount} XAF");
                    Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                        'payment_provider_response' => $statusResponse,
                    ]);
                    return $payment->fresh();
                }

                // Check for FAILED/CANCELLED
                if (in_array($currentStatus, $failedStatuses)) {
                    Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                    Log::error("[FreeMoPay Polling] ❌ PAYMENT FAILED!");
                    Log::error("[FreeMoPay Polling] 📋 Payment ID: {$payment->id}");
                    Log::error("[FreeMoPay Polling] 🔖 Reference: {$reference}");
                    Log::error("[FreeMoPay Polling] ⏱️  Failed after: {$elapsed}s and {$attempts} attempts");
                    Log::error("[FreeMoPay Polling] 💬 Reason: {$message}");
                    Log::error("[FreeMoPay Polling] 📊 Status: {$currentStatus}");
                    Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

                    $payment->update([
                        'status' => 'failed',
                        'failure_reason' => $message,
                        'payment_provider_response' => $statusResponse,
                    ]);
                    throw new \Exception("Le paiement a échoué: {$message}");
                }

                // Still PENDING/PROCESSING - wait and retry
                Log::info("   ↳ ⏳ Payment still {$currentStatus}, waiting {$this->pollingInterval}s before next attempt...");
                sleep($this->pollingInterval);

            } catch (\Exception $e) {
                // If it's our own exception (payment failed or timeout), rethrow it
                if (str_starts_with($e->getMessage(), 'Le paiement a échoué:') ||
                    str_starts_with($e->getMessage(), 'Le délai d\'attente')) {
                    throw $e;
                }

                // Otherwise, log and continue polling
                Log::warning("   ↳ ⚠️  Polling error (attempt {$attempts}): " . $e->getMessage());
                Log::warning("   ↳ 🔄 Continuing to poll...");
                sleep($this->pollingInterval);
            }
        }

        Log::warning("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        Log::warning("[FreeMoPay Polling] ⚠️  Polling loop exited without terminal status");
        Log::warning("[FreeMoPay Polling] 📋 Payment ID: {$payment->id}");
        Log::warning("[FreeMoPay Polling] 🔖 Reference: {$reference}");
        Log::warning("[FreeMoPay Polling] 🔄 Total attempts: {$attempts}");
        Log::warning("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // If we exit the loop without success/failure, return current state
        return $payment->fresh();
    }

    /**
     * Call FreeMoPay API v2 to initialize payment
     *
     * Endpoint: POST /api/v2/payment
     * Headers: Authorization: Bearer <token>
     * Body: { payer, amount, externalId, description, callback }
     *
     * @param string $payer
     * @param float $amount
     * @param string $externalId
     * @param string $description
     * @param string $callback
     * @return array
     * @throws \Exception
     */
    protected function callFreeMoPayAPI(
        string $payer,
        float $amount,
        string $externalId,
        string $description,
        string $callback
    ): array {
        // Get Bearer token
        $bearerToken = $this->tokenManager->getToken();

        // Prepare payload
        $payload = [
            'payer' => $payer,
            'amount' => $amount,
            'externalId' => $externalId,
            'description' => $description,
            'callback' => $callback
        ];

        // API v2 endpoint
        $baseUrl = rtrim($this->config->freemopay_base_url, '/');
        $endpoint = "{$baseUrl}/api/v2/payment";

        Log::info("[FreeMoPay Service] Calling FreeMoPay API v2");
        Log::info("[FreeMoPay Service] URL: {$endpoint}");
        Log::info("[FreeMoPay Service] Payload: " . json_encode($payload));

        // POST /api/v2/payment
        $response = $this->client->post(
            $endpoint,
            $payload,
            $bearerToken,
            false,
            $this->config->freemopay_init_payment_timeout ?? 60
        );

        Log::info("[FreeMoPay Service] FreeMoPay response: " . json_encode($response));

        // Check init status
        $initStatus = strtoupper($response['status'] ?? '');

        // Valid init statuses
        $validInitStatuses = ['SUCCESS', 'CREATED', 'PENDING', 'PROCESSING'];

        // Failed statuses
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        if (in_array($initStatus, $failedStatuses)) {
            $errorMessage = $response['message'] ?? 'Unknown error';
            Log::error("[FreeMoPay Service] Init failed - Status: {$initStatus}, Message: {$errorMessage}");
            throw new \Exception("Payment initialization failed: {$errorMessage}");
        }

        if (!in_array($initStatus, $validInitStatuses)) {
            Log::warning("[FreeMoPay Service] Unknown init status: {$initStatus}, treating as pending");
        }

        return $response;
    }

    /**
     * Check payment status via FreeMoPay API v2
     *
     * Endpoint: GET /api/v2/payment/{reference}
     *
     * @param string $reference FreeMoPay reference
     * @return array
     * @throws \Exception
     */
    public function checkPaymentStatus(string $reference): array
    {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('FreeMoPay service is not configured properly');
        }

        Log::debug("[FreeMoPay Service] Checking payment status - Reference: {$reference}");

        // Get Bearer token
        $bearerToken = $this->tokenManager->getToken();

        // API v2 endpoint
        $baseUrl = rtrim($this->config->freemopay_base_url, '/');
        $endpoint = "{$baseUrl}/api/v2/payment/{$reference}";

        // GET /api/v2/payment/{reference}
        $response = $this->client->get(
            $endpoint,
            $bearerToken,
            false,
            $this->config->freemopay_status_check_timeout ?? 30
        );

        Log::debug("[FreeMoPay Service] Status check response: " . json_encode($response));

        return $response;
    }

    /**
     * Normalize phone number for FreeMoPay (237XXXXXXXXX)
     *
     * @param string $phone
     * @return string
     * @throws \Exception
     */
    protected function normalizePhoneNumber(string $phone): string
    {
        if (!$phone) {
            throw new \Exception('Phone number is required');
        }

        // Remove +, spaces, dashes
        $cleaned = preg_replace('/[\s\-+]/', '', $phone);

        // If phone doesn't start with 237 or 243, assume it's a Cameroon number and add 237
        if (!str_starts_with($cleaned, '237') && !str_starts_with($cleaned, '243')) {
            // Check if it's a valid Cameroon mobile number (9 digits starting with 6)
            if (strlen($cleaned) === 9 && str_starts_with($cleaned, '6')) {
                $cleaned = '237' . $cleaned;
                Log::info("[FreeMoPay Service] 📱 Auto-normalized phone: Added 237 prefix");
            } else {
                throw new \Exception("Invalid phone number format. Expected 237XXXXXXXXX (Cameroon) or 243XXXXXXXXX (RDC), or 9-digit Cameroon number starting with 6: {$phone}");
            }
        }

        // Check length (12 digits expected)
        if (strlen($cleaned) !== 12 || !ctype_digit($cleaned)) {
            throw new \Exception("Invalid phone format. Expected 12 digits (237XXXXXXXXX or 243XXXXXXXXX): {$phone}");
        }

        return $cleaned;
    }

    /**
     * Generate a unique external ID
     *
     * @param string $prefix
     * @return string
     */
    protected function generateExternalId(string $prefix = 'PAY'): string
    {
        $timestamp = now()->format('YmdHis');
        $random = substr(uniqid(), -4);
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Ensure external ID is unique
     *
     * @param string $baseExternalId
     * @return string
     */
    protected function ensureUniqueExternalId(string $baseExternalId): string
    {
        $externalId = $baseExternalId;
        $counter = 1;

        while (Payment::where('external_id', $externalId)->exists()) {
            $externalId = "{$baseExternalId}-{$counter}";
            $counter++;
            Log::debug("[FreeMoPay Service] External ID exists, trying: {$externalId}");
        }

        return $externalId;
    }

    /**
     * Test FreeMoPay connection by generating a token
     *
     * @return array
     */
    public function testConnection(): array
    {
        try {
            if (!$this->config || !$this->config->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'FreeMoPay configuration is incomplete',
                    'data' => null
                ];
            }

            // Clear cache and generate fresh token
            $this->tokenManager->clearToken();
            $token = $this->tokenManager->getToken();

            return [
                'success' => true,
                'message' => 'FreeMoPay connection successful, token generated',
                'data' => [
                    'token_length' => strlen($token),
                    'token_preview' => substr($token, 0, 20) . '...'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Connection test failed: {$e->getMessage()}",
                'data' => null
            ];
        }
    }

    /**
     * Set polling configuration
     *
     * @param int $interval Seconds between polls
     * @param int $timeout Max seconds to wait
     * @return self
     */
    public function setPollingConfig(int $interval = 3, int $timeout = 90): self
    {
        $this->pollingInterval = $interval;
        $this->pollingTimeout = $timeout;
        $this->maxPollingAttempts = (int) ceil($timeout / $interval);
        return $this;
    }

    /**
     * Detect payment method based on phone number prefix
     *
     * Cameroon (237):
     * - MTN: 67, 68, 650-654
     * - Orange: 69, 655-659
     *
     * RDC (243):
     * - MTN: 81, 82, 83, 84, 85
     * - Orange: 89, 80
     *
     * @param string $phone Normalized phone (237XXXXXXXXX or 243XXXXXXXXX)
     * @return string
     */
    protected function detectPaymentMethod(string $phone): string
    {
        // Cameroon numbers (237)
        if (str_starts_with($phone, '237')) {
            $prefix = substr($phone, 3, 2); // Get digits after 237
            $prefix3 = substr($phone, 3, 3); // Get 3 digits after 237

            // MTN prefixes: 67, 68, 650-654
            if (in_array($prefix, ['67', '68']) || in_array($prefix3, ['650', '651', '652', '653', '654'])) {
                return 'mtn_money';
            }

            // Orange prefixes: 69, 655-659
            if ($prefix === '69' || in_array($prefix3, ['655', '656', '657', '658', '659'])) {
                return 'orange_money';
            }
        }

        // RDC numbers (243)
        if (str_starts_with($phone, '243')) {
            $prefix = substr($phone, 3, 2);

            // MTN prefixes: 81, 82, 83, 84, 85
            if (in_array($prefix, ['81', '82', '83', '84', '85'])) {
                return 'mtn_money';
            }

            // Orange prefixes: 89, 80
            if (in_array($prefix, ['89', '80'])) {
                return 'orange_money';
            }
        }

        // Default to MTN if unknown
        return 'mtn_money';
    }
}