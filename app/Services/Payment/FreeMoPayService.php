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
    protected int $pollingTimeout = 90;      // max seconds to wait for payment completion
    protected int $maxPollingAttempts = 30;  // max number of polling attempts

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
     * @param string $phoneNumber Payer's phone number (237XXXXXXXXX)
     * @param string $description Payment description
     * @param string|null $externalId Optional external ID
     * @param \Illuminate\Database\Eloquent\Model|null $payable The payable entity (e.g., SubscriptionPlan)
     * @return Payment
     * @throws \Exception
     */
    public function initPayment(
        $payer,
        float $amount,
        string $phoneNumber,
        string $description,
        ?string $externalId = null,
        $payable = null
    ): Payment {
        if (!$this->config || !$this->config->isConfigured()) {
            throw new \Exception('FreeMoPay service is not configured properly');
        }

        Log::info("[FreeMoPay Service] Initiating SYNCHRONOUS payment - Amount: {$amount}, Phone: {$phoneNumber}");

        // 1. Validate phone number
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

        // 2. Generate external ID if not provided
        if (!$externalId) {
            $externalId = $this->generateExternalId();
        }
        $externalId = $this->ensureUniqueExternalId($externalId);

        // 3. Get callback URL
        $callbackUrl = $this->config->freemopay_callback_url;

        Log::info("[FreeMoPay Service] Callback URL: {$callbackUrl}, External ID: {$externalId}");

        // 4. Create Payment record in database (status: pending)
        $payment = DB::transaction(function () use ($payer, $amount, $normalizedPhone, $description, $externalId, $payable) {
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

        Log::info("[FreeMoPay Service] Payment record created - ID: {$payment->id}");

        // 5. Call FreeMoPay API to initiate payment
        try {
            $freemoResponse = $this->callFreeMoPayAPI(
                $normalizedPhone,
                $amount,
                $externalId,
                $description,
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("[FreeMoPay Service] No reference in response: " . json_encode($freemoResponse));
                $payment->update(['status' => 'failed']);
                throw new \Exception('No reference in FreeMoPay response');
            }

            $payment->update([
                'provider_reference' => $reference,
                'payment_provider_response' => $freemoResponse,
            ]);

            Log::info("[FreeMoPay Service] Payment initiated - Reference: {$reference}");

            // 6. SYNCHRONOUS POLLING: Wait for payment completion
            $finalPayment = $this->waitForPaymentCompletion($payment, $reference);

            return $finalPayment;

        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            Log::error("[FreeMoPay Service] Payment initiation failed: " . $e->getMessage());
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
        Log::info("[FreeMoPay Service] Starting polling for reference: {$reference}");

        $startTime = time();
        $attempts = 0;

        // Terminal statuses that indicate payment is complete
        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            // Check timeout
            if ($elapsed >= $this->pollingTimeout) {
                Log::warning("[FreeMoPay Service] Polling timeout after {$elapsed}s - Reference: {$reference}");
                $payment->update([
                    'status' => 'pending',
                    'notes' => "Payment timeout after {$elapsed} seconds. Please check status manually.",
                ]);
                throw new \Exception("Payment timeout. Please check your phone and try again.");
            }

            // Check max attempts
            if ($attempts > $this->maxPollingAttempts) {
                Log::warning("[FreeMoPay Service] Max polling attempts ({$this->maxPollingAttempts}) reached - Reference: {$reference}");
                break;
            }

            try {
                Log::debug("[FreeMoPay Service] Polling attempt {$attempts} - Elapsed: {$elapsed}s");

                $statusResponse = $this->checkPaymentStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[FreeMoPay Service] Poll {$attempts}: Status = {$currentStatus}");

                // Check for SUCCESS
                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[FreeMoPay Service] Payment SUCCESS - Reference: {$reference}");
                    $payment->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                        'payment_provider_response' => $statusResponse,
                    ]);
                    return $payment->fresh();
                }

                // Check for FAILED/CANCELLED
                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Payment failed or cancelled';
                    Log::info("[FreeMoPay Service] Payment FAILED - Reference: {$reference}, Reason: {$message}");
                    $payment->update([
                        'status' => 'failed',
                        'failure_reason' => $message,
                        'payment_provider_response' => $statusResponse,
                    ]);
                    throw new \Exception("Payment failed: {$message}");
                }

                // Still PENDING/PROCESSING - wait and retry
                Log::debug("[FreeMoPay Service] Payment still pending, waiting {$this->pollingInterval}s...");
                sleep($this->pollingInterval);

            } catch (\Exception $e) {
                // If it's our own exception (payment failed), rethrow it
                if (str_starts_with($e->getMessage(), 'Payment failed:') ||
                    str_starts_with($e->getMessage(), 'Payment timeout')) {
                    throw $e;
                }

                // Otherwise, log and continue polling
                Log::warning("[FreeMoPay Service] Polling error (attempt {$attempts}): " . $e->getMessage());
                sleep($this->pollingInterval);
            }
        }

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

        // Check if starts with 237 (Cameroon) or 243 (RDC)
        if (!str_starts_with($cleaned, '237') && !str_starts_with($cleaned, '243')) {
            throw new \Exception("Phone number must start with 237 (Cameroon) or 243 (RDC): {$phone}");
        }

        // Check length (12 digits expected)
        if (strlen($cleaned) !== 12 || !ctype_digit($cleaned)) {
            throw new \Exception("Invalid phone format. Expected 12 digits (237XXXXXXXXX): {$phone}");
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
