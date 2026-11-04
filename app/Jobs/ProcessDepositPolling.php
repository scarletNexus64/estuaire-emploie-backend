<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Services\Payment\KPayService;
use App\Services\WalletService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Polling de SECOURS pour les dépôts KPay.
 *
 * Le webhook payment.* reste la source de vérité. Ce job interroge
 * GET /payments/:id pendant ~90s (KPay auto-suit la transaction 90s après init).
 * La finalisation passe TOUJOURS par WalletService::completeRechargeForPayment()
 * (idempotent, lock + garde de statut) → aucun double-crédit possible.
 */
class ProcessDepositPolling implements ShouldQueue
{
    use Queueable;

    public $timeout = 120;
    public $tries = 1;
    public $maxExceptions = 1;

    public function __construct(public int $paymentId)
    {
        $this->onQueue('deposits');
    }

    public function handle(KPayService $kpay, WalletService $wallet): void
    {
        $payment = Payment::find($this->paymentId);

        if (!$payment) {
            Log::warning('[ProcessDepositPolling] Payment introuvable', ['payment_id' => $this->paymentId]);
            return;
        }

        if ($payment->provider !== 'kpay' || !$payment->provider_reference) {
            return;
        }

        // Carte via KPay Lite : pas de fenêtre USSD ni de boucle bloquante. Une
        // vérification par passage, puis replanification jusqu'au verdict ou à
        // l'expiration (paiement abandonné sur la page hébergée).
        if (\App\Services\Payment\KPayLiteService::isLitePayment($payment)) {
            $this->pollLite($payment);
            return;
        }

        $startTime = time();
        $attempts = 0;
        $interval = 6;          // 6s (rate limit KPay ~10 req/min/paiement)
        $timeout = (int) config('kpay.ussd_grace_seconds', 90); // fenêtre USSD partagée
        $maxAttempts = 15;

        $successStatuses = ['COMPLETED'];
        $failedStatuses = ['FAILED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;

            // Le webhook a peut-être déjà finalisé → on s'arrête.
            $fresh = $payment->fresh();
            if (!$fresh || in_array($fresh->status, ['completed', 'failed'])) {
                Log::info('[ProcessDepositPolling] Déjà finalisé, arrêt', [
                    'payment_id' => $payment->id,
                    'status' => $fresh?->status,
                ]);
                return;
            }

            if ((time() - $startTime) >= $timeout || $attempts > $maxAttempts) {
                Log::info('[ProcessDepositPolling] Timeout polling, reste pending', ['payment_id' => $payment->id]);
                return; // webhook / status-check finaliseront
            }

            try {
                $response = $kpay->checkPaymentStatus($payment->provider_reference);
                $status = strtoupper($response['status'] ?? '');

                Log::info("🟢 [KPay] DÉPÔT polling secours #{$attempts} → status={$status}", ['payment_id' => $payment->id]);

                if (in_array($status, $successStatuses)) {
                    $credited = $wallet->completeRechargeForPayment($payment);
                    if ($credited) {
                        app(\App\Services\WalletNotifier::class)->rechargeSuccess($payment->fresh());
                    }
                    return;
                }

                if (in_array($status, $failedStatuses)) {
                    // ⚠️ FAILED TRANSITOIRE pendant l'USSD : KPay peut renvoyer
                    // FAILED alors que l'utilisateur n'a pas encore saisi son code
                    // (push USSD bien reçu). Ce job de secours tourne justement
                    // pendant cette fenêtre → on NE fige PAS "failed" ici. La
                    // source de vérité du verdict d'échec est le WEBHOOK
                    // payment.failed. On continue simplement à poller : si le
                    // FAILED est réel, le webhook finalisera ; sinon le paiement
                    // pourra encore passer COMPLETED.
                    Log::info("🟡 [KPay] DÉPÔT polling secours #{$attempts} → FAILED transitoire ignoré (webhook = source de vérité)", [
                        'payment_id' => $payment->id,
                        'kpay_status' => $status,
                        'kpay_failure_reason' => $response['failureReason'] ?? null,
                    ]);
                    sleep($interval);
                    continue;
                }

                sleep($interval);
            } catch (\Exception $e) {
                Log::warning("[ProcessDepositPolling] Poll #{$attempts} error: {$e->getMessage()}");
                sleep($interval);
            }
        }
    }

    /**
     * Un passage de polling pour un paiement carte KPay Lite.
     *
     * Cadence : toutes les 30 s pendant les 10 premières minutes (le client
     * saisit sa carte, 3-D Secure…), puis toutes les 2 min jusqu'à
     * l'expiration. KPay Lite limite à 60 requêtes/min : on reste très en deçà.
     */
    protected function pollLite(Payment $payment): void
    {
        $lite = app(\App\Services\Payment\KPayLiteService::class);

        if (!$payment->isPending()) {
            return;
        }

        $lite->refreshPayment($payment, 'polling');

        $fresh = $payment->fresh();
        if (!$fresh || !$fresh->isPending()) {
            return;
        }

        $ageMinutes = $fresh->created_at ? $fresh->created_at->diffInMinutes(now(), true) : 0;
        $delay = $ageMinutes < 10 ? 30 : 120;

        self::dispatch($fresh->id)->delay(now()->addSeconds($delay));
    }
}
