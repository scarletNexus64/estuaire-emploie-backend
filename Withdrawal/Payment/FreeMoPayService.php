<?php

namespace App\Services\Payment;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\PlatformSetting;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FreeMoPayService
{
    protected FreeMoPayClient $client;
    protected FreeMoPayTokenManager $tokenManager;

    // Polling configuration
    protected int $pollingInterval = 3;      // seconds between each status check
    protected int $pollingTimeout = 90;      // max seconds to wait for payment completion
    protected int $maxPollingAttempts = 30;  // max number of polling attempts

    public function __construct()
    {
        $this->client = new FreeMoPayClient();
        $this->tokenManager = new FreeMoPayTokenManager($this->client);
    }

    /**
     * Initialize a subscription payment with FreeMoPay (SYNCHRONOUS with polling)
     */
    public function initiateSubscriptionPayment(
        User $user,
        SubscriptionPlan $plan,
        string $phoneNumber,
        bool $async = false
    ): Payment {
        if (!$this->client->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré. Contactez l\'administrateur.');
        }

        Log::info("[FreeMoPay Service] Démarrage paiement - User: {$user->id}, Plan: {$plan->id}, Phone: {$phoneNumber}, Async: " . ($async ? 'true' : 'false'));

        // 1. Validate and normalize phone number
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

        // 2. Generate unique external ID
        $externalId = $this->generateExternalId('SUB');

        // 3. Detect payment method based on phone prefix
        $paymentMethod = $this->detectPaymentMethod($normalizedPhone);

        // 4. Create pending subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::PENDING,
            'price_paid' => $plan->price,
            'currency' => $plan->currency,
        ]);

        // 5. Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'transaction_reference' => $externalId,
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'status' => PaymentStatus::PENDING,
            'payment_method' => $paymentMethod,
            'payment_phone' => $normalizedPhone,
            'description' => "Abonnement {$plan->name} - {$plan->duration_days} jours",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        Log::info("[FreeMoPay Service] Payment créé - ID: {$payment->id}, External ID: {$externalId}");

        // 6. Call FreeMoPay API to initiate payment
        try {
            $callbackUrl = route('webhook.freemopay');
            $platformName = PlatformSetting::getName();

            $freemoResponse = $this->callFreeMoPayAPI(
                $normalizedPhone,
                (int) ( $plan->price + ( $plan->price * 3) / 100), // On prends les fris FremoPay chez le client
                $externalId,
                "Abonnement {$platformName} - {$plan->name}",
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("[FreeMoPay Service] Pas de référence dans la réponse: " . json_encode($freemoResponse));
                $this->handlePaymentFailure($payment, $subscription, 'no_reference', 'Pas de référence FreeMoPay');
                throw new \Exception('Erreur lors de l\'initialisation du paiement');
            }

            $payment->update([
                'freemopay_reference' => $reference,
                'freemopay_response' => $freemoResponse,
                'status' => PaymentStatus::PROCESSING,
            ]);

            Log::info("[FreeMoPay Service] Paiement initié - Référence: {$reference}");

            // 7. If async mode, return immediately without polling
            if ($async) {
                return $payment->fresh();
            }

            // 8. SYNCHRONOUS POLLING: Wait for payment completion
            $finalPayment = $this->waitForPaymentCompletion($payment, $subscription, $reference);

            return $finalPayment;
        } catch (\Exception $e) {
            $this->handlePaymentFailure($payment, $subscription, 'api_error', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Poll FreeMoPay API until payment is completed, failed, or timeout
     */
    protected function waitForPaymentCompletion(Payment $payment, Subscription $subscription, string $reference): Payment
    {
        Log::info("[FreeMoPay Service] Démarrage polling pour référence: {$reference}");

        $startTime = time();
        $attempts = 0;

        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            // Check timeout
            if ($elapsed >= $this->pollingTimeout) {
                Log::warning("[FreeMoPay Service] Timeout polling après {$elapsed}s - Référence: {$reference}");
                $payment->update([
                    'status' => PaymentStatus::PENDING,
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'timeout_note' => "Timeout après {$elapsed} secondes. Vérifiez le statut manuellement.",
                    ]),
                ]);
                throw new \Exception("Délai d'attente dépassé. Veuillez vérifier votre téléphone et réessayer.");
            }

            // Check max attempts
            if ($attempts > $this->maxPollingAttempts) {
                Log::warning("[FreeMoPay Service] Max tentatives ({$this->maxPollingAttempts}) atteintes - Référence: {$reference}");
                break;
            }

            try {
                Log::debug("[FreeMoPay Service] Tentative polling {$attempts} - Écoulé: {$elapsed}s");

                $statusResponse = $this->checkPaymentStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[FreeMoPay Service] Poll {$attempts}: Status = {$currentStatus}");

                // Check for SUCCESS
                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[FreeMoPay Service] Paiement RÉUSSI - Référence: {$reference}");
                    return $this->handlePaymentSuccess($payment, $subscription, $statusResponse);
                }

                // Check for FAILED/CANCELLED
                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Paiement échoué ou annulé';
                    Log::info("[FreeMoPay Service] Paiement ÉCHOUÉ - Référence: {$reference}, Raison: {$message}");
                    $this->handlePaymentFailure($payment, $subscription, 'payment_failed', $message);
                    throw new \Exception("Paiement échoué: {$message}");
                }

                // Still PENDING/PROCESSING - wait and retry
                Log::debug("[FreeMoPay Service] Paiement en attente, pause de {$this->pollingInterval}s...");
                sleep($this->pollingInterval);
            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Paiement échoué:') ||
                    str_starts_with($e->getMessage(), "Délai d'attente")) {
                    throw $e;
                }

                Log::warning("[FreeMoPay Service] Erreur polling (tentative {$attempts}): " . $e->getMessage());
                sleep($this->pollingInterval);
            }
        }

        return $payment->fresh();
    }

    /**
     * Call FreeMoPay API v2 to initialize payment
     */
    protected function callFreeMoPayAPI(
        string $payer,
        int $amount,
        string $externalId,
        string $description,
        string $callback
    ): array {
        $payload = [
            'payer' => $payer,
            'amount' => $amount,
            'externalId' => $externalId,
            'description' => $description,
            'callback' => $callback,
        ];

        $baseUrl = rtrim($this->client->getConfig('base_url'), '/');
        $endpoint = "{$baseUrl}/api/v2/payment";

        return $this->withTokenRetry(function (string $bearerToken) use ($endpoint, $payload) {
            Log::info("[FreeMoPay Service] Appel API FreeMoPay v2");
            Log::info("[FreeMoPay Service] URL: {$endpoint}");
            Log::info("[FreeMoPay Service] Payload: " . json_encode($payload));

            $response = $this->client->post(
                $endpoint,
                $payload,
                $bearerToken,
                false,
                $this->client->getConfig('timeout_init', 60)
            );

            Log::info("[FreeMoPay Service] Réponse FreeMoPay: " . json_encode($response));

            $initStatus = strtoupper($response['status'] ?? '');
            $validInitStatuses = ['SUCCESS', 'CREATED', 'PENDING', 'PROCESSING'];
            $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

            if (in_array($initStatus, $failedStatuses)) {
                $errorMessage = $response['message'] ?? 'Erreur inconnue';
                Log::error("[FreeMoPay Service] Init échoué - Status: {$initStatus}, Message: {$errorMessage}");
                throw new \Exception("Initialisation du paiement échouée: {$errorMessage}");
            }

            if (!in_array($initStatus, $validInitStatuses)) {
                Log::warning("[FreeMoPay Service] Status init inconnu: {$initStatus}, traité comme pending");
            }

            return $response;
        });
    }

    /**
     * Check payment status via FreeMoPay API v2
     */
    public function checkPaymentStatus(string $reference): array
    {
        if (!$this->client->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré correctement.');
        }

        $baseUrl = rtrim($this->client->getConfig('base_url'), '/');
        $endpoint = "{$baseUrl}/api/v2/payment/{$reference}";

        return $this->withTokenRetry(function (string $bearerToken) use ($endpoint, $reference) {
            Log::debug("[FreeMoPay Service] Vérification statut - Référence: {$reference}");

            $response = $this->client->get(
                $endpoint,
                $bearerToken,
                false,
                $this->client->getConfig('timeout_verify', 30)
            );

            Log::debug("[FreeMoPay Service] Réponse vérification: " . json_encode($response));

            return $response;
        });
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess(Payment $payment, Subscription $subscription, array $response): Payment
    {
        DB::transaction(function () use ($payment, $subscription, $response) {
            $payment->update([
                'status' => PaymentStatus::COMPLETED,
                'paid_at' => now(),
                'freemopay_response' => $response,
            ]);

            // Activate subscription via service (handles referral commissions and phone storage)
            $subscriptionService = app(\App\Services\SubscriptionService::class);
            $subscriptionService->activateSubscription($subscription, $payment->payment_phone);

            // Assign client role if not already
            $payment->user->assignRole('client');

            // ========== REVENUE DISTRIBUTION ==========
            // Distribute revenue: admin isolation + managers pool
            try {
                $distributionService = app(\App\Services\PaymentDistributionService::class);
                $distribution = $distributionService->distributePayment($payment, $subscription);

                // Store distribution info in payment metadata for tracking
                $payment->update([
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'revenue_distribution' => $distribution,
                    ]),
                ]);

                Log::info("[FreeMoPay Service] Revenue distribution completed - Payment: {$payment->id}");
            } catch (\Exception $e) {
                // Log error but don't fail the payment
                Log::error("[FreeMoPay Service] Revenue distribution failed - Payment: {$payment->id}, Error: {$e->getMessage()}");
            }
            // ==========================================
        });

        Log::info("[FreeMoPay Service] Paiement complété et abonnement activé - Payment: {$payment->id}, Subscription: {$subscription->id}");

        return $payment->fresh();
    }

    /**
     * Handle payment failure
     */
    protected function handlePaymentFailure(Payment $payment, Subscription $subscription, string $code, string $message): void
    {
        $payment->markAsFailed($code, $message);

        $subscription->update([
            'status' => SubscriptionStatus::CANCELLED,
        ]);

        Log::warning("[FreeMoPay Service] Paiement échoué - Payment: {$payment->id}, Code: {$code}, Message: {$message}");
    }

    /**
     * Normalize phone number for FreeMoPay (237XXXXXXXXX)
     */
    protected function normalizePhoneNumber(string $phone): string
    {
        if (!$phone) {
            throw new \Exception('Le numéro de téléphone est requis');
        }

        // Remove +, spaces, dashes
        $cleaned = preg_replace('/[\s\-+]/', '', $phone);

        // If starts with 6 or 2, assume Cameroon and add 237
        if (preg_match('/^[62]\d{8}$/', $cleaned)) {
            $cleaned = '237' . $cleaned;
        }

        // Check if starts with 237 (Cameroon) or 243 (RDC)
        if (!str_starts_with($cleaned, '237') && !str_starts_with($cleaned, '243')) {
            throw new \Exception("Le numéro doit commencer par 237 (Cameroun) ou 243 (RDC): {$phone}");
        }

        // Check length (12 digits expected)
        if (strlen($cleaned) !== 12 || !ctype_digit($cleaned)) {
            throw new \Exception("Format de numéro invalide. 12 chiffres attendus (237XXXXXXXXX): {$phone}");
        }

        return $cleaned;
    }

    /**
     * Generate a unique external ID
     */
    protected function generateExternalId(string $prefix = 'PAY'): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(4));
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Test FreeMoPay connection by generating a token
     */
    public function testConnection(): array
    {
        try {
            if (!$this->client->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Configuration FreeMoPay incomplète. Vérifiez les clés API.',
                    'data' => null,
                ];
            }

            // Clear cache and generate fresh token
            $this->tokenManager->clearToken();
            $token = $this->tokenManager->getToken();

            return [
                'success' => true,
                'message' => 'Connexion FreeMoPay réussie, token généré',
                'data' => [
                    'token_length' => strlen($token),
                    'token_preview' => substr($token, 0, 20) . '...',
                ],
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Test de connexion échoué: {$e->getMessage()}",
                'data' => null,
            ];
        }
    }

    /**
     * Set polling configuration
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
     */
    protected function detectPaymentMethod(string $phone): PaymentMethod
    {
        // Cameroon numbers (237)
        if (str_starts_with($phone, '237')) {
            $prefix = substr($phone, 3, 2);
            $prefix3 = substr($phone, 3, 3);

            // MTN prefixes: 67, 68, 650-654
            if (in_array($prefix, ['67', '68']) || in_array($prefix3, ['650', '651', '652', '653', '654'])) {
                return PaymentMethod::MTN_MOMO;
            }

            // Orange prefixes: 69, 655-659
            if ($prefix === '69' || in_array($prefix3, ['655', '656', '657', '658', '659'])) {
                return PaymentMethod::ORANGE_MONEY;
            }
        }

        // RDC numbers (243)
        if (str_starts_with($phone, '243')) {
            $prefix = substr($phone, 3, 2);

            // MTN prefixes: 81, 82, 83, 84, 85
            if (in_array($prefix, ['81', '82', '83', '84', '85'])) {
                return PaymentMethod::MTN_MOMO;
            }

            // Orange prefixes: 89, 80
            if (in_array($prefix, ['89', '80'])) {
                return PaymentMethod::ORANGE_MONEY;
            }
        }

        // Default to MTN if unknown
        return PaymentMethod::MTN_MOMO;
    }

    /**
     * Wrap an API call with logic to refresh the bearer token once if it has expired.
     */
    protected function withTokenRetry(callable $callback): array
    {
        $attempt = 0;

        while (true) {
            $attempt++;

            try {
                $bearerToken = $this->tokenManager->getToken();
                return $callback($bearerToken);
            } catch (\Exception $e) {
                if ($attempt === 1 && $this->shouldRefreshToken($e)) {
                    Log::info("[FreeMoPay Service] Token expiré détecté, tentative de rafraîchissement.");

                    try {
                        $this->tokenManager->refreshToken();
                    } catch (\Exception $tokenException) {
                        Log::error("[FreeMoPay Service] Échec du rafraîchissement du token: {$tokenException->getMessage()}");
                        throw $e;
                    }

                    continue;
                }

                throw $e;
            }
        }
    }

    /**
     * Detect if an exception is caused by an expired/invalid bearer token.
     */
    protected function shouldRefreshToken(\Exception $e): bool
    {
        $message = strtolower($e->getMessage() ?? '');

        return Str::contains($message, [
            '401',
            'invalid token',
            'token invalid',
            'token dead',
            'token expir',
            'expired token',
            'token expiré',
            'access token',
            'authorization',
            'bearer token',
        ]);
    }

    /**
     * Clear token cache
     */
    public function clearTokenCache(): void
    {
        $this->tokenManager->clearToken();
    }
}
