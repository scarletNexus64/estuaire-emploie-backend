<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }
    /**
     * Recharge le wallet d'un utilisateur
     *
     * @param User $user
     * @param float $amount
     * @param Payment|null $payment Paiement source (FreeMoPay, PayPal, etc.)
     * @param string $description
     * @param array $metadata
     * @return WalletTransaction
     */
    public function credit(
        User $user,
        float $amount,
        ?Payment $payment = null,
        string $description = 'Recharge wallet',
        array $metadata = []
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $payment, $description, $metadata) {
            $balanceBefore = $user->wallet_balance ?? 0;
            $balanceAfter = $balanceBefore + $amount;

            // Mettre à jour le solde du user
            $user->wallet_balance = $balanceAfter;
            $user->save();

            // Créer la transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
                'payment_id' => $payment?->id,
                'metadata' => $metadata,
                'status' => 'completed',
            ]);

            Log::info("[WalletService] Wallet credited", [
                'user_id' => $user->id,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'transaction_id' => $transaction->id,
            ]);

            return $transaction;
        });
    }

    /**
     * Débite le wallet d'un utilisateur
     *
     * @param User $user
     * @param float $amount
     * @param string $description
     * @param string|null $referenceType Type de référence (subscription, addon_service, etc.)
     * @param int|null $referenceId ID de la référence
     * @param array $metadata
     * @return WalletTransaction
     * @throws \Exception Si solde insuffisant
     */
    public function debit(
        User $user,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        array $metadata = []
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $description, $referenceType, $referenceId, $metadata) {
            $balanceBefore = $user->wallet_balance ?? 0;

            // Vérifier le solde
            if ($balanceBefore < $amount) {
                throw new \Exception("Solde insuffisant. Solde actuel: {$balanceBefore} FCFA, Montant requis: {$amount} FCFA");
            }

            $balanceAfter = $balanceBefore - $amount;

            // Mettre à jour le solde du user
            $user->wallet_balance = $balanceAfter;
            $user->save();

            // Créer la transaction
            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'metadata' => $metadata,
                'status' => 'completed',
            ]);

            Log::info("[WalletService] Wallet debited", [
                'user_id' => $user->id,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'transaction_id' => $transaction->id,
                'reference' => "{$referenceType}:{$referenceId}",
            ]);

            return $transaction;
        });
    }

    /**
     * Rembourse une transaction (remet l'argent dans le wallet)
     *
     * @param WalletTransaction $originalTransaction Transaction à rembourser
     * @param string|null $reason Raison du remboursement
     * @return WalletTransaction
     */
    public function refund(WalletTransaction $originalTransaction, ?string $reason = null): WalletTransaction
    {
        // On ne peut rembourser qu'un débit
        if ($originalTransaction->type !== 'debit') {
            throw new \Exception("Seuls les débits peuvent être remboursés");
        }

        if ($originalTransaction->status !== 'completed') {
            throw new \Exception("Seules les transactions complétées peuvent être remboursées");
        }

        $user = $originalTransaction->user;
        $amount = abs($originalTransaction->amount);
        $description = "Remboursement: " . ($reason ?? $originalTransaction->description);

        return $this->credit(
            $user,
            $amount,
            null,
            $description,
            [
                'refund_of_transaction_id' => $originalTransaction->id,
                'refund_reason' => $reason,
                'original_description' => $originalTransaction->description,
            ]
        );
    }

    /**
     * Ajoute un bonus au wallet (promotion, parrainage, etc.)
     *
     * @param User $user
     * @param float $amount
     * @param string $description
     * @param array $metadata
     * @return WalletTransaction
     */
    public function addBonus(
        User $user,
        float $amount,
        string $description = 'Bonus',
        array $metadata = []
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $description, $metadata) {
            $balanceBefore = $user->wallet_balance ?? 0;
            $balanceAfter = $balanceBefore + $amount;

            $user->wallet_balance = $balanceAfter;
            $user->save();

            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
                'metadata' => $metadata,
                'status' => 'completed',
            ]);

            Log::info("[WalletService] Bonus added", [
                'user_id' => $user->id,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
            ]);

            return $transaction;
        });
    }

    /**
     * Ajustement manuel par un admin
     *
     * @param User $user
     * @param float $amount Positif pour ajouter, négatif pour retirer
     * @param User $admin Admin effectuant l'ajustement
     * @param string $reason Raison de l'ajustement
     * @return WalletTransaction
     */
    public function adjustBalance(
        User $user,
        float $amount,
        User $admin,
        string $reason
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $admin, $reason) {
            $balanceBefore = $user->wallet_balance ?? 0;
            $balanceAfter = $balanceBefore + $amount;

            // Ne pas permettre de balance négative
            if ($balanceAfter < 0) {
                throw new \Exception("L'ajustement rendrait le solde négatif");
            }

            $user->wallet_balance = $balanceAfter;
            $user->save();

            $transaction = WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'adjustment',
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Ajustement admin: {$reason}",
                'admin_id' => $admin->id,
                'metadata' => [
                    'admin_name' => $admin->name,
                    'reason' => $reason,
                ],
                'status' => 'completed',
            ]);

            Log::warning("[WalletService] Balance adjusted by admin", [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'amount' => $amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'reason' => $reason,
            ]);

            return $transaction;
        });
    }

    /**
     * Récupère l'historique des transactions avec pagination
     *
     * @param User $user
     * @param int $perPage
     * @param string|null $type Filtrer par type
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTransactionHistory(User $user, int $perPage = 20, ?string $type = null)
    {
        $query = $user->walletTransactions()->with(['payment', 'admin']);

        if ($type) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage);
    }

    /**
     * Statistiques du wallet pour un utilisateur
     *
     * @param User $user
     * @param string|null $currency Devise souhaitée (null = devise préférée de l'utilisateur)
     * @return array
     */
    public function getWalletStats(User $user, ?string $currency = null): array
    {
        $transactions = $user->walletTransactions()->completed();

        // Le solde est toujours stocké en XAF dans la base
        $balanceXAF = $user->wallet_balance ?? 0;
        $totalCreditsXAF = $transactions->clone()->credits()->sum('amount');
        $totalDebitsXAF = abs($transactions->clone()->debits()->sum('amount'));

        // Devise à utiliser (préférée ou celle demandée)
        $targetCurrency = $currency ?? ($user->preferred_currency ?? 'XAF');

        // Convertir les montants si nécessaire
        if ($targetCurrency !== 'XAF') {
            $balance = $this->currencyService->convert($balanceXAF, 'XAF', $targetCurrency);
            $totalCredits = $this->currencyService->convert($totalCreditsXAF, 'XAF', $targetCurrency);
            $totalDebits = $this->currencyService->convert($totalDebitsXAF, 'XAF', $targetCurrency);
        } else {
            $balance = $balanceXAF;
            $totalCredits = $totalCreditsXAF;
            $totalDebits = $totalDebitsXAF;
        }

        return [
            // Montants dans la devise demandée
            'current_balance' => $balance,
            'formatted_balance' => $this->currencyService->format($balance, $targetCurrency),
            'total_credits' => $totalCredits,
            'total_debits' => $totalDebits,
            'total_transactions' => $transactions->count(),
            'last_transaction' => $transactions->first(),

            // Informations sur la devise
            'currency' => $targetCurrency,
            'currency_symbol' => \App\Models\CurrencyRate::getCurrencySymbol($targetCurrency),

            // Montants bruts en XAF (pour référence)
            'balance_xaf' => $balanceXAF,
        ];
    }

    /**
     * Vérifie si un user peut effectuer un paiement avec son wallet
     *
     * @param User $user
     * @param float $amount
     * @return array ['can_pay' => bool, 'message' => string, 'missing_amount' => float]
     */
    public function canPayWithWallet(User $user, float $amount): array
    {
        $balance = $user->wallet_balance ?? 0;
        $canPay = $balance >= $amount;

        return [
            'can_pay' => $canPay,
            'current_balance' => $balance,
            'required_amount' => $amount,
            'missing_amount' => $canPay ? 0 : ($amount - $balance),
            'message' => $canPay
                ? "Paiement possible"
                : "Solde insuffisant. Il vous manque " . number_format($amount - $balance, 0, ',', ' ') . " FCFA",
        ];
    }
}
