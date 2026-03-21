<?php

namespace App\Services\Payment;

use App\Enums\WithdrawalStatus;
use App\Models\AdminWithdrawal;
use App\Models\ManagerWithdrawal;
use App\Models\PlatformSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FreeMoPayDisbursementService
{
    protected string $baseUrl;
    protected string $appKey;
    protected string $secretKey;

    // Polling configuration for disbursement
    protected int $pollingInterval = 3;      // seconds between each status check
    protected int $pollingTimeout = 90;      // max seconds to wait for disbursement completion
    protected int $maxPollingAttempts = 30;  // max number of polling attempts

    public function __construct()
    {
        $this->baseUrl = rtrim(PlatformSetting::getValue('freemopay_base_url', 'https://api-v2.freemopay.com'), '/');
        $this->appKey = PlatformSetting::getValue('freemopay_api_key', '');
        $this->secretKey = PlatformSetting::getValue('freemopay_api_secret', '');
    }

    /**
     * Check if FreeMoPay is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->appKey) && !empty($this->secretKey);
    }

    /**
     * Initiate a withdrawal (direct-withdraw) to a manager
     *
     * Based on FreeMoPay API v2 direct-withdraw endpoint:
     * POST /api/v2/payment/direct-withdraw
     * {
     *   "receiver": "237XXXXXXXXX",
     *   "amount": "5000",
     *   "externalId": "WDR-20260107-XXXX",
     *   "callback": "https://example.com/webhook/disbursement"
     * }
     *
     * Uses Basic Auth: username = appKey, password = secretKey
     */
    public function initiateDisbursement(ManagerWithdrawal $withdrawal): ManagerWithdrawal
    {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré. Contactez l\'administrateur.');
        }

        Log::info("[FreeMoPay Withdraw] Démarrage transfert - Withdrawal ID: {$withdrawal->id}, Amount: {$withdrawal->amount_sent}");

        try {
            // Mark as processing
            $withdrawal->markAsProcessing();

            // Call FreeMoPay API to initiate withdrawal
            $callbackUrl = route('webhook.freemopay.disbursement');

            $freemoResponse = $this->callDirectWithdrawAPI(
                $withdrawal->payment_account,
                (int) $withdrawal->amount_sent,
                $withdrawal->transaction_reference,
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("[FreeMoPay Withdraw] Pas de référence dans la réponse: " . json_encode($freemoResponse));
                $withdrawal->markAsFailed('no_reference', 'Pas de référence FreeMoPay dans la réponse');
                throw new \Exception('Erreur lors de l\'initialisation du transfert');
            }

            $withdrawal->update([
                'freemopay_reference' => $reference,
                'freemopay_response' => $freemoResponse,
            ]);

            Log::info("[FreeMoPay Withdraw] Transfert initié - Référence: {$reference}");

            // SYNCHRONOUS POLLING: Wait for disbursement completion
            $finalWithdrawal = $this->waitForDisbursementCompletion($withdrawal, $reference);

            return $finalWithdrawal;
        } catch (\Exception $e) {
            if ($withdrawal->status !== WithdrawalStatus::FAILED) {
                $withdrawal->markAsFailed('api_error', $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Poll FreeMoPay API until disbursement is completed, failed, or timeout
     */
    protected function waitForDisbursementCompletion(ManagerWithdrawal $withdrawal, string $reference): ManagerWithdrawal
    {
        Log::info("[FreeMoPay Withdraw] Démarrage polling pour référence: {$reference}");

        $startTime = time();
        $attempts = 0;

        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            // Check timeout
            if ($elapsed >= $this->pollingTimeout) {
                Log::warning("[FreeMoPay Withdraw] Timeout polling après {$elapsed}s - Référence: {$reference}");
                // Don't mark as failed on timeout - let webhook handle it
                return $withdrawal->fresh();
            }

            // Check max attempts
            if ($attempts > $this->maxPollingAttempts) {
                Log::warning("[FreeMoPay Withdraw] Max tentatives ({$this->maxPollingAttempts}) atteintes - Référence: {$reference}");
                break;
            }

            try {
                Log::debug("[FreeMoPay Withdraw] Tentative polling {$attempts} - Écoulé: {$elapsed}s");

                $statusResponse = $this->checkWithdrawalStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[FreeMoPay Withdraw] Poll {$attempts}: Status = {$currentStatus}");

                // Check for SUCCESS
                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[FreeMoPay Withdraw] Transfert RÉUSSI - Référence: {$reference}");
                    return $this->handleDisbursementSuccess($withdrawal, $statusResponse);
                }

                // Check for FAILED/CANCELLED
                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Transfert échoué ou annulé';
                    Log::info("[FreeMoPay Withdraw] Transfert ÉCHOUÉ - Référence: {$reference}, Raison: {$message}");
                    $this->handleDisbursementFailure($withdrawal, 'disbursement_failed', $message);
                    throw new \Exception("Transfert échoué: {$message}");
                }

                // Still PENDING/PROCESSING/CREATED - wait and retry
                Log::debug("[FreeMoPay Withdraw] Transfert en attente, pause de {$this->pollingInterval}s...");
                sleep($this->pollingInterval);
            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Transfert échoué:')) {
                    throw $e;
                }

                Log::warning("[FreeMoPay Withdraw] Erreur polling (tentative {$attempts}): " . $e->getMessage());
                sleep($this->pollingInterval);
            }
        }

        return $withdrawal->fresh();
    }

    /**
     * Call FreeMoPay API v2 to initialize direct withdrawal
     *
     * Endpoint: POST /api/v2/payment/direct-withdraw
     * Auth: Basic Auth (appKey:secretKey)
     */
    protected function callDirectWithdrawAPI(
        string $receiver,
        int $amount,
        string $externalId,
        string $callback
    ): array {
        $endpoint = "{$this->baseUrl}/api/v2/payment/direct-withdraw";

        $payload = [
            'receiver' => $receiver,
            'amount' => (string) $amount, // API expects string
            'externalId' => $externalId,
            'callback' => $callback,
        ];

        Log::info("[FreeMoPay Withdraw] Appel API FreeMoPay v2 Direct Withdraw");
        Log::info("[FreeMoPay Withdraw] URL: {$endpoint}");
        Log::info("[FreeMoPay Withdraw] Payload: " . json_encode([
            'receiver' => substr($receiver, 0, 6) . '***',
            'amount' => $amount,
            'externalId' => $externalId,
            'callback' => $callback,
        ]));

        $timeout = PlatformSetting::getValue('freemopay_timeout_init', 60);

        $response = Http::withBasicAuth($this->appKey, $this->secretKey)
            ->timeout($timeout)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($endpoint, $payload);

        Log::info("[FreeMoPay Withdraw] HTTP Status: {$response->status()}");
        Log::info("[FreeMoPay Withdraw] Réponse FreeMoPay: " . $response->body());

        if (!$response->successful()) {
            $errorBody = $response->json() ?? ['message' => $response->body()];
            $rawMessage = $errorBody['message'] ?? "Erreur HTTP {$response->status()}";
            // Handle case where message is an array (FreeMoPay returns array of validation errors)
            $errorMessage = is_array($rawMessage) ? implode(', ', $rawMessage) : $rawMessage;
            Log::error("[FreeMoPay Withdraw] Erreur API: {$errorMessage}");
            throw new \Exception("Erreur FreeMoPay: {$errorMessage}");
        }

        $data = $response->json();

        // Expected response: {"reference": "...", "status": "CREATED", "message": "cashout created"}
        $initStatus = strtoupper($data['status'] ?? '');
        $validInitStatuses = ['SUCCESS', 'CREATED', 'PENDING', 'PROCESSING'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        if (in_array($initStatus, $failedStatuses)) {
            $errorMessage = $data['message'] ?? 'Erreur inconnue';
            Log::error("[FreeMoPay Withdraw] Init échoué - Status: {$initStatus}, Message: {$errorMessage}");
            throw new \Exception("Initialisation du transfert échouée: {$errorMessage}");
        }

        if (!in_array($initStatus, $validInitStatuses)) {
            Log::warning("[FreeMoPay Withdraw] Status init inconnu: {$initStatus}, traité comme pending");
        }

        return $data;
    }

    /**
     * Check withdrawal status via FreeMoPay API v2
     *
     * Endpoint: GET /api/v2/payment/:reference
     * Auth: Basic Auth (appKey:secretKey)
     */
    public function checkWithdrawalStatus(string $reference): array
    {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré correctement.');
        }

        Log::debug("[FreeMoPay Withdraw] Vérification statut - Référence: {$reference}");

        $endpoint = "{$this->baseUrl}/api/v2/payment/{$reference}";
        $timeout = PlatformSetting::getValue('freemopay_timeout_verify', 30);

        $response = Http::withBasicAuth($this->appKey, $this->secretKey)
            ->timeout($timeout)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->get($endpoint);

        Log::debug("[FreeMoPay Withdraw] Réponse vérification: " . $response->body());

        if (!$response->successful()) {
            throw new \Exception("Erreur lors de la vérification du statut: HTTP {$response->status()}");
        }

        return $response->json() ?? [];
    }

    /**
     * Handle successful disbursement
     */
    protected function handleDisbursementSuccess(ManagerWithdrawal $withdrawal, array $response): ManagerWithdrawal
    {
        $withdrawal->markAsCompleted(
            $response['reference'] ?? $withdrawal->freemopay_reference,
            $response
        );

        Log::info("[FreeMoPay Withdraw] Transfert complété - Withdrawal ID: {$withdrawal->id}");

        return $withdrawal->fresh();
    }

    /**
     * Handle disbursement failure
     */
    protected function handleDisbursementFailure(ManagerWithdrawal $withdrawal, string $code, string $message): void
    {
        $withdrawal->markAsFailed($code, $message);

        Log::warning("[FreeMoPay Withdraw] Transfert échoué - Withdrawal ID: {$withdrawal->id}, Code: {$code}, Message: {$message}");
    }

    /**
     * Normalize phone number for FreeMoPay (237XXXXXXXXX)
     */
    public function normalizePhoneNumber(string $phone): string
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
     * Generate a unique transaction reference for withdrawal
     */
    public function generateTransactionReference(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(4));
        return "WDR-{$timestamp}-{$random}";
    }

    /**
     * Detect payment method based on phone number prefix
     */
    public function detectPaymentMethod(string $phone): string
    {
        // Cameroon numbers (237)
        if (str_starts_with($phone, '237')) {
            $prefix = substr($phone, 3, 2);
            $prefix3 = substr($phone, 3, 3);

            // MTN prefixes: 67, 68, 650-654
            if (in_array($prefix, ['67', '68']) || in_array($prefix3, ['650', '651', '652', '653', '654'])) {
                return 'momo';
            }

            // Orange prefixes: 69, 655-659
            if ($prefix === '69' || in_array($prefix3, ['655', '656', '657', '658', '659'])) {
                return 'om';
            }
        }

        // RDC numbers (243)
        if (str_starts_with($phone, '243')) {
            $prefix = substr($phone, 3, 2);

            // MTN prefixes: 81, 82, 83, 84, 85
            if (in_array($prefix, ['81', '82', '83', '84', '85'])) {
                return 'momo';
            }

            // Orange prefixes: 89, 80
            if (in_array($prefix, ['89', '80'])) {
                return 'om';
            }
        }

        // Default to MTN if unknown
        return 'momo';
    }

    /**
     * Test FreeMoPay disbursement connection
     */
    public function testConnection(): array
    {
        try {
            if (!$this->isConfigured()) {
                return [
                    'success' => false,
                    'message' => 'Configuration FreeMoPay incomplète. Vérifiez les clés API.',
                    'data' => null,
                ];
            }

            // Test by calling the token endpoint to verify credentials
            $endpoint = "{$this->baseUrl}/api/v2/payment/token";

            $response = Http::withBasicAuth($this->appKey, $this->secretKey)
                ->timeout(30)
                ->withHeaders([
                    'Accept' => 'application/json',
                ])
                ->post($endpoint);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Connexion FreeMoPay réussie, prêt pour les transferts',
                    'data' => [
                        'token_received' => isset($data['token']),
                    ],
                ];
            }

            return [
                'success' => false,
                'message' => "Test de connexion échoué: HTTP {$response->status()}",
                'data' => null,
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
     * Initiate an admin withdrawal (direct-withdraw)
     * Same process as manager withdrawal but for admin
     */
    public function initiateAdminDisbursement(AdminWithdrawal $withdrawal): AdminWithdrawal
    {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré. Contactez l\'administrateur.');
        }

        Log::info("[FreeMoPay Admin Withdraw] Démarrage transfert - Withdrawal ID: {$withdrawal->id}, Amount: {$withdrawal->amount_sent}");

        try {
            // Mark as processing
            $withdrawal->markAsProcessing();

            // Call FreeMoPay API to initiate withdrawal
            $callbackUrl = route('webhook.freemopay.admin-disbursement');

            $freemoResponse = $this->callDirectWithdrawAPI(
                $withdrawal->payment_account,
                (int) $withdrawal->amount_sent,
                $withdrawal->transaction_reference,
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("[FreeMoPay Admin Withdraw] Pas de référence dans la réponse: " . json_encode($freemoResponse));
                $withdrawal->markAsFailed('no_reference', 'Pas de référence FreeMoPay dans la réponse');
                throw new \Exception('Erreur lors de l\'initialisation du transfert');
            }

            $withdrawal->update([
                'freemopay_reference' => $reference,
                'freemopay_response' => $freemoResponse,
            ]);

            Log::info("[FreeMoPay Admin Withdraw] Transfert initié - Référence: {$reference}");

            // SYNCHRONOUS POLLING: Wait for disbursement completion
            $finalWithdrawal = $this->waitForAdminDisbursementCompletion($withdrawal, $reference);

            return $finalWithdrawal;
        } catch (\Exception $e) {
            if ($withdrawal->status !== WithdrawalStatus::FAILED) {
                $withdrawal->markAsFailed('api_error', $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Poll FreeMoPay API until admin disbursement is completed, failed, or timeout
     */
    protected function waitForAdminDisbursementCompletion(AdminWithdrawal $withdrawal, string $reference): AdminWithdrawal
    {
        Log::info("[FreeMoPay Admin Withdraw] Démarrage polling pour référence: {$reference}");

        $startTime = time();
        $attempts = 0;

        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            // Check timeout
            if ($elapsed >= $this->pollingTimeout) {
                Log::warning("[FreeMoPay Admin Withdraw] Timeout polling après {$elapsed}s - Référence: {$reference}");
                return $withdrawal->fresh();
            }

            // Check max attempts
            if ($attempts > $this->maxPollingAttempts) {
                Log::warning("[FreeMoPay Admin Withdraw] Max tentatives ({$this->maxPollingAttempts}) atteintes - Référence: {$reference}");
                break;
            }

            try {
                Log::debug("[FreeMoPay Admin Withdraw] Tentative polling {$attempts} - Écoulé: {$elapsed}s");

                $statusResponse = $this->checkWithdrawalStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[FreeMoPay Admin Withdraw] Poll {$attempts}: Status = {$currentStatus}");

                // Check for SUCCESS
                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[FreeMoPay Admin Withdraw] Transfert RÉUSSI - Référence: {$reference}");
                    return $this->handleAdminDisbursementSuccess($withdrawal, $statusResponse);
                }

                // Check for FAILED/CANCELLED
                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Transfert échoué ou annulé';
                    Log::info("[FreeMoPay Admin Withdraw] Transfert ÉCHOUÉ - Référence: {$reference}, Raison: {$message}");
                    $this->handleAdminDisbursementFailure($withdrawal, 'disbursement_failed', $message);
                    throw new \Exception("Transfert échoué: {$message}");
                }

                // Still PENDING/PROCESSING/CREATED - wait and retry
                Log::debug("[FreeMoPay Admin Withdraw] Transfert en attente, pause de {$this->pollingInterval}s...");
                sleep($this->pollingInterval);
            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Transfert échoué:')) {
                    throw $e;
                }

                Log::warning("[FreeMoPay Admin Withdraw] Erreur polling (tentative {$attempts}): " . $e->getMessage());
                sleep($this->pollingInterval);
            }
        }

        return $withdrawal->fresh();
    }

    /**
     * Handle successful admin disbursement
     */
    protected function handleAdminDisbursementSuccess(AdminWithdrawal $withdrawal, array $response): AdminWithdrawal
    {
        $withdrawal->markAsCompleted(
            $response['reference'] ?? $withdrawal->freemopay_reference,
            $response
        );

        Log::info("[FreeMoPay Admin Withdraw] Transfert complété - Withdrawal ID: {$withdrawal->id}");

        return $withdrawal->fresh();
    }

    /**
     * Handle admin disbursement failure
     */
    protected function handleAdminDisbursementFailure(AdminWithdrawal $withdrawal, string $code, string $message): void
    {
        $withdrawal->markAsFailed($code, $message);

        Log::warning("[FreeMoPay Admin Withdraw] Transfert échoué - Withdrawal ID: {$withdrawal->id}, Code: {$code}, Message: {$message}");
    }

    /**
     * Initiate a withdrawal (direct-withdraw) for a client
     * Similar to manager withdrawal but for ClientWithdrawal model
     */
    public function initiateClientDisbursement(ClientWithdrawal $withdrawal): ClientWithdrawal
    {
        if (!$this->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré. Contactez l\'administrateur.');
        }

        Log::info("[FreeMoPay Client Withdraw] Démarrage transfert - Withdrawal ID: {$withdrawal->id}, Amount: {$withdrawal->amount_sent}");

        try {
            // Mark as processing
            $withdrawal->markAsProcessing();

            // Call FreeMoPay API to initiate withdrawal
            $callbackUrl = route('webhook.freemopay.client-disbursement');

            $freemoResponse = $this->callDirectWithdrawAPI(
                $withdrawal->payment_account,
                (int) $withdrawal->amount_sent,
                $withdrawal->transaction_reference,
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("[FreeMoPay Client Withdraw] Pas de référence dans la réponse: " . json_encode($freemoResponse));
                $withdrawal->markAsFailed('no_reference', 'Pas de référence FreeMoPay dans la réponse');
                throw new \Exception('Erreur lors de l\'initialisation du transfert');
            }

            $withdrawal->update([
                'freemopay_reference' => $reference,
                'freemopay_response' => $freemoResponse,
            ]);

            Log::info("[FreeMoPay Client Withdraw] Transfert initié - Référence: {$reference}");

            // SYNCHRONOUS POLLING: Wait for disbursement completion
            $finalWithdrawal = $this->waitForClientDisbursementCompletion($withdrawal, $reference);

            return $finalWithdrawal;
        } catch (\Exception $e) {
            if (!$withdrawal->isFailed()) {
                $withdrawal->markAsFailed('api_error', $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Poll FreeMoPay API until client disbursement is completed, failed, or timeout
     */
    protected function waitForClientDisbursementCompletion(ClientWithdrawal $withdrawal, string $reference): ClientWithdrawal
    {
        Log::info("[FreeMoPay Client Withdraw] Démarrage polling pour référence: {$reference}");

        $startTime = time();
        $attempts = 0;

        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            // Check timeout
            if ($elapsed >= $this->pollingTimeout) {
                Log::warning("[FreeMoPay Client Withdraw] Timeout polling après {$elapsed}s - Référence: {$reference}");
                return $withdrawal->fresh();
            }

            // Check max attempts
            if ($attempts > $this->maxPollingAttempts) {
                Log::warning("[FreeMoPay Client Withdraw] Max tentatives ({$this->maxPollingAttempts}) atteintes - Référence: {$reference}");
                break;
            }

            try {
                Log::debug("[FreeMoPay Client Withdraw] Tentative polling {$attempts} - Écoulé: {$elapsed}s");

                $statusResponse = $this->checkWithdrawalStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[FreeMoPay Client Withdraw] Poll {$attempts}: Status = {$currentStatus}");

                // Check for SUCCESS
                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[FreeMoPay Client Withdraw] Transfert RÉUSSI - Référence: {$reference}");
                    return $this->handleClientDisbursementSuccess($withdrawal, $statusResponse);
                }

                // Check for FAILED/CANCELLED
                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Transfert échoué ou annulé';
                    Log::info("[FreeMoPay Client Withdraw] Transfert ÉCHOUÉ - Référence: {$reference}, Raison: {$message}");
                    $this->handleClientDisbursementFailure($withdrawal, 'disbursement_failed', $message);
                    throw new \Exception("Transfert échoué: {$message}");
                }

                // Still PENDING/PROCESSING/CREATED - wait and retry
                Log::debug("[FreeMoPay Client Withdraw] Transfert en attente, pause de {$this->pollingInterval}s...");
                sleep($this->pollingInterval);
            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Transfert échoué:')) {
                    throw $e;
                }

                Log::warning("[FreeMoPay Client Withdraw] Erreur polling (tentative {$attempts}): " . $e->getMessage());
                sleep($this->pollingInterval);
            }
        }

        return $withdrawal->fresh();
    }

    /**
     * Handle successful client disbursement
     */
    protected function handleClientDisbursementSuccess(ClientWithdrawal $withdrawal, array $response): ClientWithdrawal
    {
        $withdrawal->markAsCompleted(
            $response['reference'] ?? $withdrawal->freemopay_reference,
            $response
        );

        Log::info("[FreeMoPay Client Withdraw] Transfert complété - Withdrawal ID: {$withdrawal->id}");

        return $withdrawal->fresh();
    }

    /**
     * Handle client disbursement failure
     */
    protected function handleClientDisbursementFailure(ClientWithdrawal $withdrawal, string $code, string $message): void
    {
        $withdrawal->markAsFailed($code, $message);

        Log::warning("[FreeMoPay Client Withdraw] Transfert échoué - Withdrawal ID: {$withdrawal->id}, Code: {$code}, Message: {$message}");
    }
}
