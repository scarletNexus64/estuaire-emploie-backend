<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Models\PlatformWithdrawal;
use App\Services\WalletNotifier;
use App\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Traitement asynchrone d'un webhook KPay (déjà vérifié par signature).
 *
 * Évènements :
 *  - payment.completed/failed/cancelled  → dépôts (Payment)
 *  - payout.completed/failed/cancelled   → retraits (PlatformWithdrawal)
 *
 * Idempotence : verrou Cache (5 min) sur paymentId + gardes de statut
 * (completeRechargeForPayment / isCompleted / isFailed) → aucun double-traitement
 * même en cas de retries KPay ou de course avec les jobs de polling.
 */
class ProcessKPayWebhook implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [5, 15, 30];

    /**
     * @param bool $deferredVerdict true lorsque ce job est un ré-essai différé
     *   d'un échec transitoire (fenêtre USSD) : la déduplication est alors
     *   bypassée car le verrou initial event+id est encore actif (TTL 5 min).
     */
    public function __construct(public array $payload, public string $event, public bool $deferredVerdict = false)
    {
        // Queue écoutée par le worker (deposits,withdrawals,default).
        $this->onQueue('default');
    }

    public function handle(WalletService $wallet, WalletNotifier $notifier): void
    {
        $event = $this->event ?: ($this->payload['event'] ?? '');
        $paymentId = $this->payload['paymentId'] ?? ($this->payload['externalId'] ?? null);

        Log::info('🔔 [KPay] WEBHOOK reçu', [
            'event' => $event,
            'paymentId' => $this->payload['paymentId'] ?? null,
            'externalId' => $this->payload['externalId'] ?? null,
            'status' => $this->payload['status'] ?? null,
        ]);

        if (!$event || !$paymentId) {
            Log::warning('[KPay Webhook Job] Évènement ou identifiant manquant', ['event' => $event]);
            return;
        }

        // Déduplication : un même évènement (event + id) ne doit être traité qu'une fois.
        // Exception : un ré-essai différé (verdict d'échec après fenêtre USSD) doit
        // pouvoir re-traiter le même event+id — son verrou initial est encore actif.
        if (!$this->deferredVerdict) {
            $lock = Cache::lock("kpay_wh:{$event}:{$paymentId}", 300);
            if (!$lock->get()) {
                Log::info('[KPay Webhook Job] Évènement déjà en cours/traité, ignoré', ['event' => $event]);
                return;
            }
        }

        try {
            if (str_starts_with($event, 'payment.')) {
                $this->handleDeposit($event, $wallet, $notifier);
            } elseif (str_starts_with($event, 'payout.')) {
                $this->handleWithdrawal($event, $notifier);
            } else {
                Log::info('[KPay Webhook Job] Évènement non géré', ['event' => $event]);
            }
        } finally {
            // On garde le verrou jusqu'à expiration (5 min) pour absorber les retries.
            // Pas de release explicite : la garde de statut protège déjà.
        }
    }

    protected function handleDeposit(string $event, WalletService $wallet, WalletNotifier $notifier): void
    {
        $payment = $this->locatePayment();
        if (!$payment) {
            Log::warning('[KPay Webhook Job] Payment introuvable', ['payload' => $this->payload]);
            return;
        }

        if ($event === 'payment.completed') {
            Log::info('🟢 [KPay] WEBHOOK payment.completed → finalisation dépôt', ['payment_id' => $payment->id]);
            $credited = $wallet->completeRechargeForPayment($payment);
            if ($credited) {
                $notifier->rechargeSuccess($payment->fresh());
                Log::info('🟢 [KPay] FCM dépôt envoyée', ['payment_id' => $payment->id]);
            }
            return;
        }

        // payment.failed / payment.cancelled
        if (!$payment->isPending()) {
            return;
        }

        // ⚠️ FAILED TRANSITOIRE pendant l'USSD : sur réseau mobile pauvre, KPay
        // peut émettre un webhook payment.failed AVANT que l'utilisateur ait saisi
        // son code (push USSD reçu mais confirmation pas encore remontée). Figer
        // "failed" immédiatement gèle le paiement en échec alors qu'il peut encore
        // aboutir → c'est le bug observé (poll #1 = failed à t≈0s).
        //
        // Garde-fou : pendant la fenêtre USSD (graceSeconds après l'init), on ne
        // tranche pas sur la foi du seul webhook. On RE-VÉRIFIE le statut réel
        // auprès de KPay (GET /payments/:id). Si KPay confirme COMPLETED → on
        // finalise. Si toujours échoué, on REPLANIFIE ce même webhook après le
        // délai de grâce restant : à la 2e passe (age >= grace) le verdict est
        // considéré définitif. Cohérent avec ProcessDepositPolling + le status-check.
        $graceSeconds = 90;
        $age = now()->diffInSeconds($payment->created_at, true);

        if ($age < $graceSeconds) {
            try {
                $kpay = app(\App\Services\Payment\KPayService::class);
                $statusResponse = $kpay->checkPaymentStatus($payment->provider_reference);
                $liveStatus = strtoupper($statusResponse['status'] ?? '');

                if ($liveStatus === 'COMPLETED') {
                    Log::info('🟢 [KPay] WEBHOOK failed transitoire → KPay confirme COMPLETED, finalisation', ['payment_id' => $payment->id]);
                    $credited = $wallet->completeRechargeForPayment($payment);
                    if ($credited) {
                        $notifier->rechargeSuccess($payment->fresh());
                    }
                    return;
                }
            } catch (\Throwable $e) {
                Log::warning('[KPay Webhook Job] Re-vérification statut impossible: ' . $e->getMessage(), ['payment_id' => $payment->id]);
            }

            // Toujours échoué (ou re-vérif impossible) : on ne fige PAS maintenant.
            // On replanifie le verdict après la fenêtre de grâce (au-delà de laquelle
            // un failed est réputé réel). Le ré-essai porte deferredVerdict=true pour
            // contourner le verrou de dédup (event+id) encore actif (TTL 5 min).
            $delay = (int) ceil($graceSeconds - $age) + 2;
            Log::info("⏳ [KPay] WEBHOOK {$event} transitoire ignoré (fenêtre USSD, age={$age}s) → replanifié dans {$delay}s", [
                'payment_id' => $payment->id,
                'kpay_failure_reason' => $this->payload['failureReason'] ?? null,
            ]);
            self::dispatch($this->payload, $this->event, true)->delay(now()->addSeconds($delay));
            return;
        }

        // Hors fenêtre de grâce : le verdict d'échec est définitif.
        $payment->markAsFailed($this->payload['failureReason'] ?? $event);
        $notifier->rechargeFailed($payment->fresh());
        Log::info("🔴 [KPay] WEBHOOK {$event} → dépôt marqué failed (hors fenêtre USSD, age={$age}s)", ['payment_id' => $payment->id]);
    }

    protected function handleWithdrawal(string $event, WalletNotifier $notifier): void
    {
        $withdrawal = $this->locateWithdrawal();
        if (!$withdrawal) {
            Log::warning('[KPay Webhook Job] Withdrawal introuvable', ['payload' => $this->payload]);
            return;
        }

        $reference = $this->payload['paymentId'] ?? $withdrawal->freemopay_reference;
        $finalizer = app(\App\Services\KPayWithdrawalFinalizer::class);

        if ($event === 'payout.completed') {
            $finalizer->complete($withdrawal, $reference, $this->payload);
            return;
        }

        // payout.failed / payout.cancelled — pas de déduction (solde non débité)
        $finalizer->fail($withdrawal, $this->payload['failureReason'] ?? $event);
    }

    /**
     * Localise le Payment par external_id puis par provider_reference (id KPay).
     */
    protected function locatePayment(): ?Payment
    {
        $externalId = $this->payload['externalId'] ?? null;
        $kpayId = $this->payload['paymentId'] ?? null;

        return Payment::query()
            ->where('provider', 'kpay')
            ->when($externalId, fn ($q) => $q->where('external_id', $externalId))
            ->first()
            ?? Payment::query()
                ->where('provider', 'kpay')
                ->where('provider_reference', $kpayId)
                ->first();
    }

    /**
     * Localise le PlatformWithdrawal par transaction_reference (externalId) puis freemopay_reference (id KPay).
     */
    protected function locateWithdrawal(): ?PlatformWithdrawal
    {
        $externalId = $this->payload['externalId'] ?? null;
        $kpayId = $this->payload['paymentId'] ?? null;

        return PlatformWithdrawal::query()
            ->when($externalId, fn ($q) => $q->where('transaction_reference', $externalId))
            ->first()
            ?? PlatformWithdrawal::query()
                ->where('freemopay_reference', $kpayId)
                ->first();
    }
}
