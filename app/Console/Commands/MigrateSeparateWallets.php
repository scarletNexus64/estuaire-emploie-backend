<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Payment;
use App\Models\PlatformWithdrawal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSeparateWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:migrate-separate
                            {--dry-run : Execute in dry-run mode without saving changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing wallet balances to separate FreeMoPay and PayPal wallets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be saved');
        }

        $this->info('╔════════════════════════════════════════════════════════════════╗');
        $this->info('║ Migration des wallets vers système séparé FreeMoPay/PayPal    ║');
        $this->info('╚════════════════════════════════════════════════════════════════╝');
        $this->newLine();

        DB::beginTransaction();

        try {
            // ÉTAPE 1: Migrer les WalletTransactions existantes
            $this->migrateWalletTransactions($dryRun);

            // ÉTAPE 2: Calculer et attribuer les soldes par provider
            $this->calculateAndAssignBalances($dryRun);

            if ($dryRun) {
                DB::rollBack();
                $this->newLine();
                $this->warn('✓ Dry-run terminé - Aucune modification n\'a été sauvegardée');
            } else {
                DB::commit();
                $this->newLine();
                $this->info('✅ Migration terminée avec succès!');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erreur: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }

        return 0;
    }

    /**
     * Migrer les WalletTransactions existantes pour ajouter le provider
     */
    protected function migrateWalletTransactions(bool $dryRun): void
    {
        $this->info('📝 ÉTAPE 1: Migration des WalletTransactions...');
        $this->newLine();

        $transactions = WalletTransaction::whereNull('provider')->get();
        $this->info("   Transactions à migrer: {$transactions->count()}");

        $bar = $this->output->createProgressBar($transactions->count());
        $bar->start();

        $stats = [
            'freemopay' => 0,
            'paypal' => 0,
            'unknown' => 0,
        ];

        foreach ($transactions as $transaction) {
            $provider = $this->detectProvider($transaction);

            $stats[$provider]++;

            if (!$dryRun) {
                $transaction->update(['provider' => $provider]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("   ✓ FreeMoPay: {$stats['freemopay']} transactions");
        $this->info("   ✓ PayPal: {$stats['paypal']} transactions");
        if ($stats['unknown'] > 0) {
            $this->warn("   ⚠ Unknown: {$stats['unknown']} transactions (defaults to FreeMoPay)");
        }
        $this->newLine();
    }

    /**
     * Détecter le provider d'une transaction
     */
    protected function detectProvider(WalletTransaction $transaction): string
    {
        // 1. Vérifier si c'est un retrait (PlatformWithdrawal)
        if ($transaction->reference_type === 'platform_withdrawal' && $transaction->reference_id) {
            $withdrawal = PlatformWithdrawal::find($transaction->reference_id);
            if ($withdrawal) {
                return $withdrawal->provider === 'paypal' ? 'paypal' : 'freemopay';
            }
        }

        // 2. Vérifier le paiement associé
        if ($transaction->payment_id) {
            $payment = Payment::find($transaction->payment_id);
            if ($payment) {
                // Vérifier le provider du paiement
                if (in_array(strtolower($payment->provider ?? ''), ['paypal'])) {
                    return 'paypal';
                }
                if (in_array(strtolower($payment->payment_method ?? ''), ['paypal'])) {
                    return 'paypal';
                }

                // FreeMoPay par défaut
                return 'freemopay';
            }
        }

        // 3. Analyser la description
        $description = strtolower($transaction->description ?? '');
        if (str_contains($description, 'paypal')) {
            return 'paypal';
        }

        // 4. Par défaut: FreeMoPay (Mobile Money est plus commun)
        return 'freemopay';
    }

    /**
     * Calculer et attribuer les soldes par provider
     */
    protected function calculateAndAssignBalances(bool $dryRun): void
    {
        $this->info('💰 ÉTAPE 2: Calcul des soldes par provider...');
        $this->newLine();

        $users = User::whereNotNull('wallet_balance')
            ->where('wallet_balance', '>', 0)
            ->get();

        $this->info("   Utilisateurs avec solde: {$users->count()}");
        $this->newLine();

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $totals = [
            'freemopay' => 0,
            'paypal' => 0,
        ];

        foreach ($users as $user) {
            // Calculer les soldes par provider
            $freemopayBalance = WalletTransaction::where('user_id', $user->id)
                ->where('provider', 'freemopay')
                ->where('status', 'completed')
                ->sum('amount');

            $paypalBalance = WalletTransaction::where('user_id', $user->id)
                ->where('provider', 'paypal')
                ->where('status', 'completed')
                ->sum('amount');

            // Arrondir à 2 décimales
            $freemopayBalance = round($freemopayBalance, 2);
            $paypalBalance = round($paypalBalance, 2);

            // Vérification: la somme doit correspondre au solde actuel
            $calculatedTotal = $freemopayBalance + $paypalBalance;
            $actualBalance = $user->wallet_balance ?? 0;

            // Si différence mineure (due aux arrondis), ajuster FreeMoPay
            if (abs($calculatedTotal - $actualBalance) < 0.1) {
                $diff = $actualBalance - $calculatedTotal;
                $freemopayBalance += $diff;
            }

            $totals['freemopay'] += max(0, $freemopayBalance);
            $totals['paypal'] += max(0, $paypalBalance);

            if (!$dryRun) {
                $user->update([
                    'freemopay_wallet_balance' => max(0, $freemopayBalance),
                    'paypal_wallet_balance' => max(0, $paypalBalance),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("   ✓ Total FreeMoPay: " . number_format($totals['freemopay'], 0, ',', ' ') . " FCFA");
        $this->info("   ✓ Total PayPal: " . number_format($totals['paypal'], 0, ',', ' ') . " FCFA");
        $this->info("   ✓ Total général: " . number_format($totals['freemopay'] + $totals['paypal'], 0, ',', ' ') . " FCFA");
        $this->newLine();
    }
}
