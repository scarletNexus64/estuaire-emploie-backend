<?php

namespace App\Jobs;

use App\Models\PlatformWithdrawal;
use App\Services\Payment\FreeMoPayDisbursementService;
use App\Services\Payment\PayPalPayoutService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessWithdrawalPolling implements ShouldQueue
{
    use Queueable;

    public $timeout = 180; // 3 minutes max
    public $tries = 1; // Ne pas retry automatiquement
    public $maxExceptions = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public PlatformWithdrawal $withdrawal,
        public string $reference
    ) {
        // Queue 'notifications' pour priorité élevée
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("[ProcessWithdrawalPolling] 🚀 Starting polling job", [
            'withdrawal_id' => $this->withdrawal->id,
            'provider' => $this->withdrawal->provider,
            'reference' => $this->reference,
        ]);

        try {
            if ($this->withdrawal->provider === 'freemopay') {
                $this->pollFreeMoPay();
            } elseif ($this->withdrawal->provider === 'paypal') {
                $this->pollPayPal();
            } else {
                throw new \Exception("Provider inconnu: {$this->withdrawal->provider}");
            }
        } catch (\Exception $e) {
            Log::error("[ProcessWithdrawalPolling] ❌ Polling failed", [
                'withdrawal_id' => $this->withdrawal->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Marquer comme failed si pas déjà fait
            if ($this->withdrawal->fresh()->status === 'processing') {
                $this->withdrawal->markAsFailed('polling_failed', $e->getMessage());
            }

            throw $e;
        }
    }

    /**
     * Poll FreeMoPay withdrawal status
     */
    protected function pollFreeMoPay(): void
    {
        $disbursementService = app(FreeMoPayDisbursementService::class);
        $startTime = time();
        $attempts = 0;
        $pollingInterval = 3; // 3 secondes
        $pollingTimeout = 150; // 2min 30s (le job timeout à 3min)
        $maxPollingAttempts = 50;

        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            if ($elapsed >= $pollingTimeout || $attempts > $maxPollingAttempts) {
                Log::warning("[ProcessWithdrawalPolling] ⏱️ Polling timeout", [
                    'withdrawal_id' => $this->withdrawal->id,
                    'attempts' => $attempts,
                    'elapsed' => $elapsed,
                ]);
                return; // Laisser le retrait en 'processing', un autre job ou webhook le finalisera
            }

            try {
                $statusResponse = $disbursementService->checkWithdrawalStatus($this->reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[ProcessWithdrawalPolling] Poll #{$attempts}: {$currentStatus}");

                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[ProcessWithdrawalPolling] ✅ FreeMoPay withdrawal SUCCESS", [
                        'withdrawal_id' => $this->withdrawal->id,
                    ]);

                    // Vérifier si le retrait n'est pas déjà complété (éviter double déduction)
                    $wasAlreadyCompleted = $this->withdrawal->fresh()->isCompleted();

                    $this->withdrawal->markAsCompleted($this->reference, $statusResponse);

                    // Déduire seulement si ce n'était pas déjà complété
                    if (!$wasAlreadyCompleted) {
                        $this->deductWalletBalance();
                        $this->updateWalletTransactionStatus('completed');
                        $this->sendSuccessNotification();
                    } else {
                        Log::info("[ProcessWithdrawalPolling] ℹ️ Withdrawal already completed, skipping deduction");
                    }

                    return;
                }

                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? $currentStatus;
                    Log::error("[ProcessWithdrawalPolling] ❌ FreeMoPay withdrawal FAILED", [
                        'withdrawal_id' => $this->withdrawal->id,
                        'reason' => $message,
                    ]);

                    // Vérifier si le retrait n'est pas déjà marqué comme échoué
                    $wasAlreadyFailed = $this->withdrawal->fresh()->isFailed();

                    $this->withdrawal->markAsFailed('disbursement_failed', $message);

                    // Envoyer les notifications seulement si ce n'était pas déjà échoué
                    if (!$wasAlreadyFailed) {
                        $this->updateWalletTransactionStatus('failed', $message);
                        $this->sendFailureNotification($message);
                    } else {
                        Log::info("[ProcessWithdrawalPolling] ℹ️ Withdrawal already failed, skipping notifications");
                    }

                    return;
                }

                sleep($pollingInterval);

            } catch (\Exception $e) {
                Log::warning("[ProcessWithdrawalPolling] ⚠️ Poll attempt #{$attempts} error: {$e->getMessage()}");
                sleep($pollingInterval);
            }
        }
    }

    /**
     * Poll PayPal payout status
     */
    protected function pollPayPal(): void
    {
        $payoutService = app(PayPalPayoutService::class);
        $startTime = time();
        $attempts = 0;
        $pollingInterval = 5; // 5 secondes (PayPal plus lent)
        $pollingTimeout = 150;
        $maxPollingAttempts = 30;

        $successStatuses = ['SUCCESS', 'COMPLETE', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'DENIED', 'BLOCKED', 'REFUNDED', 'RETURNED', 'REVERSED', 'UNCLAIMED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            if ($elapsed >= $pollingTimeout || $attempts > $maxPollingAttempts) {
                Log::warning("[ProcessWithdrawalPolling] ⏱️ PayPal polling timeout", [
                    'withdrawal_id' => $this->withdrawal->id,
                    'attempts' => $attempts,
                ]);
                return;
            }

            try {
                $statusResponse = $payoutService->checkPayoutStatus($this->reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[ProcessWithdrawalPolling] Poll #{$attempts}: {$currentStatus}");

                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[ProcessWithdrawalPolling] ✅ PayPal payout SUCCESS", [
                        'withdrawal_id' => $this->withdrawal->id,
                    ]);

                    // Vérifier si le retrait n'est pas déjà complété (éviter double déduction)
                    $wasAlreadyCompleted = $this->withdrawal->fresh()->isCompleted();

                    $this->withdrawal->markAsCompleted($this->reference, $statusResponse['response']);

                    // Déduire seulement si ce n'était pas déjà complété
                    if (!$wasAlreadyCompleted) {
                        $this->deductWalletBalance();
                        $this->updateWalletTransactionStatus('completed');
                        $this->sendSuccessNotification();
                    } else {
                        Log::info("[ProcessWithdrawalPolling] ℹ️ Withdrawal already completed, skipping deduction");
                    }

                    return;
                }

                if (in_array($currentStatus, $failedStatuses)) {
                    $message = 'Payout failed: ' . $currentStatus;
                    Log::error("[ProcessWithdrawalPolling] ❌ PayPal payout FAILED", [
                        'withdrawal_id' => $this->withdrawal->id,
                        'reason' => $currentStatus,
                    ]);

                    // Vérifier si le retrait n'est pas déjà marqué comme échoué
                    $wasAlreadyFailed = $this->withdrawal->fresh()->isFailed();

                    $this->withdrawal->markAsFailed('payout_failed', $message);

                    // Envoyer les notifications seulement si ce n'était pas déjà échoué
                    if (!$wasAlreadyFailed) {
                        $this->updateWalletTransactionStatus('failed', $message);
                        $this->sendFailureNotification($message);
                    } else {
                        Log::info("[ProcessWithdrawalPolling] ℹ️ Withdrawal already failed, skipping notifications");
                    }

                    return;
                }

                sleep($pollingInterval);

            } catch (\Exception $e) {
                Log::warning("[ProcessWithdrawalPolling] ⚠️ Poll attempt #{$attempts} error: {$e->getMessage()}");
                sleep($pollingInterval);
            }
        }
    }

    /**
     * Send success notification to user
     */
    protected function sendSuccessNotification(): void
    {
        try {
            // Rafraîchir pour avoir les dernières données
            $withdrawal = $this->withdrawal->fresh();

            if (!$withdrawal->user_id) {
                Log::info("[ProcessWithdrawalPolling] ℹ️ No user_id, skipping notification (admin withdrawal)");
                return;
            }

            $user = $withdrawal->user;

            if (!$user || !$user->fcm_token) {
                Log::info("[ProcessWithdrawalPolling] ℹ️ No FCM token for user {$withdrawal->user_id}");
                return;
            }

            $title = "Retrait effectué";

            // Message différent selon le provider
            if ($withdrawal->provider === 'paypal') {
                $body = "Votre retrait PayPal de " . number_format($withdrawal->amount_sent, 2) . " USD (" . number_format($withdrawal->amount_requested, 0, ',', ' ') . " FCFA) a été effectué avec succès.";
            } else {
                $body = "Votre retrait de " . number_format($withdrawal->amount_requested, 0, ',', ' ') . " FCFA a été effectué avec succès.";
            }

            // Créer la notification en DB
            $notification = \App\Models\Notification::create([
                'type' => 'wallet_withdrawal_success',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $withdrawal->amount_requested,
                    'provider' => $withdrawal->provider,
                    'reference' => $withdrawal->freemopay_reference ?? $withdrawal->paypal_batch_id,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'wallet_withdrawal_success',
                        'withdrawal_id' => $withdrawal->id,
                        'notification_id' => $notification->id,
                    ],
                ]);

            Log::info("[ProcessWithdrawalPolling] ✅ FCM notification sent", [
                'user_id' => $user->id,
                'withdrawal_id' => $withdrawal->id,
                'provider' => $withdrawal->provider,
            ]);

        } catch (\Exception $e) {
            Log::error("[ProcessWithdrawalPolling] ❌ Failed to send FCM notification: {$e->getMessage()}");
        }
    }

    /**
     * Send failure notification to user
     */
    protected function sendFailureNotification(string $reason): void
    {
        try {
            $withdrawal = $this->withdrawal->fresh();

            if (!$withdrawal->user_id) {
                return;
            }

            $user = $withdrawal->user;

            if (!$user || !$user->fcm_token) {
                return;
            }

            $title = "Retrait échoué";
            $body = "Votre retrait de " . number_format($withdrawal->amount_requested, 0, ',', ' ') . " FCFA a échoué. Raison: " . $reason;

            // Créer la notification en DB
            $notification = \App\Models\Notification::create([
                'type' => 'wallet_withdrawal_failed',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $withdrawal->amount_requested,
                    'provider' => $withdrawal->provider,
                    'failure_reason' => $reason,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'wallet_withdrawal_failed',
                        'withdrawal_id' => $withdrawal->id,
                        'notification_id' => $notification->id,
                    ],
                ]);

            Log::info("[ProcessWithdrawalPolling] ✅ Failure FCM notification sent", [
                'user_id' => $user->id,
                'withdrawal_id' => $withdrawal->id,
            ]);

        } catch (\Exception $e) {
            Log::error("[ProcessWithdrawalPolling] ❌ Failed to send failure FCM: {$e->getMessage()}");
        }
    }

    /**
     * Déduit le montant du wallet de l'utilisateur (freemopay ou paypal selon le provider)
     */
    protected function deductWalletBalance(): void
    {
        try {
            $withdrawal = $this->withdrawal->fresh();

            if (!$withdrawal->user_id) {
                Log::info("[ProcessWithdrawalPolling] ℹ️ No user_id, skipping wallet deduction (admin withdrawal)");
                return;
            }

            $user = $withdrawal->user;

            if (!$user) {
                Log::warning("[ProcessWithdrawalPolling] ⚠️ User not found for withdrawal", [
                    'withdrawal_id' => $withdrawal->id,
                    'user_id' => $withdrawal->user_id,
                ]);
                return;
            }

            // Déterminer quel wallet débiter selon le provider
            $walletField = $withdrawal->provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';

            $amountToDeduct = $withdrawal->amount_requested;
            $oldBalance = $user->{$walletField};
            $newBalance = max(0, $oldBalance - $amountToDeduct);

            // Déduire du wallet spécifique
            $user->{$walletField} = $newBalance;
            $user->save();

            Log::info("[ProcessWithdrawalPolling] ✅ Wallet balance deducted", [
                'user_id' => $user->id,
                'withdrawal_id' => $withdrawal->id,
                'provider' => $withdrawal->provider,
                'wallet_field' => $walletField,
                'amount_deducted' => $amountToDeduct,
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
            ]);

        } catch (\Exception $e) {
            Log::error("[ProcessWithdrawalPolling] ❌ Failed to deduct wallet balance: {$e->getMessage()}");
        }
    }

    /**
     * Met à jour la WalletTransaction associée au retrait
     */
    protected function updateWalletTransactionStatus(string $status, ?string $failureReason = null): void
    {
        try {
            $withdrawal = $this->withdrawal->fresh();

            // Chercher la WalletTransaction associée à ce retrait
            $walletTransaction = \App\Models\WalletTransaction::where('reference_type', 'platform_withdrawal')
                ->where('reference_id', $withdrawal->id)
                ->first();

            if (!$walletTransaction) {
                Log::warning("[ProcessWithdrawalPolling] ⚠️ WalletTransaction not found for withdrawal", [
                    'withdrawal_id' => $withdrawal->id,
                ]);
                return;
            }

            // Récupérer l'utilisateur pour avoir le nouveau solde (selon le provider)
            $user = $withdrawal->user;
            if ($user) {
                $walletField = $withdrawal->provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';
                $newBalance = $user->{$walletField};
            } else {
                $newBalance = $walletTransaction->balance_after;
            }

            // Mettre à jour le statut
            $updateData = ['status' => $status];

            if ($status === 'completed') {
                // Mettre à jour le solde après retrait
                $updateData['balance_after'] = $newBalance;

                // Mettre à jour la description pour refléter le succès
                $updateData['description'] = $withdrawal->provider === 'paypal'
                    ? "Retrait PayPal (\${$withdrawal->amount_sent} USD) - Complété"
                    : "Retrait Mobile Money ({$withdrawal->payment_method}) - Complété";
            } elseif ($status === 'failed') {
                // Mettre à jour la description pour refléter l'échec
                $updateData['description'] = $withdrawal->provider === 'paypal'
                    ? "Retrait PayPal (\${$withdrawal->amount_sent} USD) - Échoué"
                    : "Retrait Mobile Money ({$withdrawal->payment_method}) - Échoué";

                // Ajouter la raison de l'échec aux métadonnées
                if ($failureReason) {
                    $metadata = $walletTransaction->metadata ?? [];
                    $metadata['failure_reason'] = $failureReason;
                    $updateData['metadata'] = $metadata;
                }
            }

            $walletTransaction->update($updateData);

            Log::info("[ProcessWithdrawalPolling] ✅ WalletTransaction updated", [
                'transaction_id' => $walletTransaction->id,
                'withdrawal_id' => $withdrawal->id,
                'status' => $status,
            ]);

        } catch (\Exception $e) {
            Log::error("[ProcessWithdrawalPolling] ❌ Failed to update WalletTransaction: {$e->getMessage()}");
        }
    }
}
