<?php

namespace App\Services;

use App\Models\PlatformWithdrawal;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Finalisation IDEMPOTENTE d'un retrait KPay (succès ou échec).
 *
 * Point d'entrée unique appelé par : le webhook (ProcessKPayWebhook), le job de
 * polling (ProcessWithdrawalPolling) et l'endpoint checkWithdrawalStatus. Les
 * gardes isCompleted()/isFailed() + le verrou de ligne garantissent une seule
 * déduction même en cas de course webhook/polling/status-check.
 */
class KPayWithdrawalFinalizer
{
    public function __construct(protected WalletNotifier $notifier)
    {
    }

    /**
     * Marque le retrait complété, débite le wallet et notifie (une seule fois).
     * @return bool true si la finalisation a réellement eu lieu.
     */
    public function complete(PlatformWithdrawal $withdrawal, string $reference, array $providerResponse = []): bool
    {
        return DB::transaction(function () use ($withdrawal, $reference, $providerResponse) {
            /** @var PlatformWithdrawal $fresh */
            $fresh = PlatformWithdrawal::whereKey($withdrawal->id)->lockForUpdate()->first();
            if (!$fresh || $fresh->isCompleted()) {
                return false;
            }

            $fresh->markAsCompleted($reference, $providerResponse);

            // Déduire le solde Mobile Money (kpay → freemopay_wallet_balance)
            $user = $fresh->user;
            if ($user && $fresh->user_id) {
                $field = $fresh->provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';
                $user->{$field} = max(0, ($user->{$field} ?? 0) - $fresh->amount_requested);
                $user->save();
            }

            $this->updateTransaction($fresh, 'completed');

            Log::info('🟠 [KPay] RETRAIT processing → completed (wallet débité)', [
                'withdrawal_id' => $fresh->id,
                'amount' => $fresh->amount_requested,
            ]);

            // FCM hors transaction critique mais ici suffisant (queue worker / inline).
            $this->notifier->withdrawalSuccess($fresh->fresh());

            return true;
        });
    }

    /**
     * Marque le retrait échoué et notifie (une seule fois). Pas de déduction.
     * @return bool true si la finalisation a réellement eu lieu.
     */
    public function fail(PlatformWithdrawal $withdrawal, string $reason): bool
    {
        return DB::transaction(function () use ($withdrawal, $reason) {
            /** @var PlatformWithdrawal $fresh */
            $fresh = PlatformWithdrawal::whereKey($withdrawal->id)->lockForUpdate()->first();
            if (!$fresh || $fresh->isFailed()) {
                return false;
            }

            $fresh->markAsFailed('disbursement_failed', $reason);
            $this->updateTransaction($fresh, 'failed', $reason);

            Log::info('🟠 [KPay] RETRAIT processing → failed', [
                'withdrawal_id' => $fresh->id,
                'reason' => $reason,
            ]);

            $this->notifier->withdrawalFailed($fresh->fresh(), $reason);

            return true;
        });
    }

    protected function updateTransaction(PlatformWithdrawal $withdrawal, string $status, ?string $failureReason = null): void
    {
        try {
            $tx = WalletTransaction::where('reference_type', 'platform_withdrawal')
                ->where('reference_id', $withdrawal->id)
                ->first();
            if (!$tx) {
                return;
            }

            $update = ['status' => $status];
            if ($status === 'failed' && $failureReason) {
                $metadata = $tx->metadata ?? [];
                $metadata['failure_reason'] = $failureReason;
                $update['metadata'] = $metadata;
            }
            $tx->update($update);
        } catch (\Throwable $e) {
            Log::error("[KPayWithdrawalFinalizer] MAJ WalletTransaction échouée: {$e->getMessage()}");
        }
    }
}
