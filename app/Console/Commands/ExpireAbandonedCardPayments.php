<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\Payment\KPayLiteConfig;
use App\Services\Payment\KPayLiteService;
use Illuminate\Console\Command;

/**
 * Clôt les paiements carte KPay Lite abandonnés.
 *
 * Un client qui ferme la page hébergée sans payer laisse un paiement PENDING
 * pour toujours chez KPay Lite, sans webhook. Passé le délai configuré
 * (`expiry_minutes`, 45 min par défaut), chaque paiement est vérifié une
 * dernière fois auprès de KPay Lite puis, s'il est toujours en attente,
 * passé en `failed` avec le motif `expired`.
 *
 * Filet de sécurité du polling de secours (qui expire déjà de lui-même) pour
 * les jobs perdus ou une file arrêtée.
 */
class ExpireAbandonedCardPayments extends Command
{
    protected $signature = 'payments:expire-abandoned-cards {--limit=200 : Nombre maximum de paiements traités par passage}';

    protected $description = 'Passe en "expired" les paiements carte KPay Lite restés en attente trop longtemps';

    public function handle(): int
    {
        $config = KPayLiteConfig::make();
        $cutoff = now()->subMinutes($config->expiryMinutes());

        $candidates = Payment::query()
            ->where('status', 'pending')
            ->where('payment_method', 'card')
            ->where('provider', 'kpay')
            ->where('created_at', '<=', $cutoff)
            ->orderBy('id')
            ->limit((int) $this->option('limit'))
            ->get()
            ->filter(fn (Payment $payment) => KPayLiteService::isLitePayment($payment));

        if ($candidates->isEmpty()) {
            $this->info('Aucun paiement carte abandonné.');

            return self::SUCCESS;
        }

        $service = app(KPayLiteService::class);
        $expired = 0;

        foreach ($candidates as $payment) {
            if ($service->expireIfAbandoned($payment)) {
                $expired++;
            }

            // KPay Lite : 60 requêtes / minute au maximum.
            usleep(1_100_000);
        }

        $this->info("{$expired} paiement(s) carte expiré(s) sur {$candidates->count()} vérifié(s).");

        return self::SUCCESS;
    }
}
