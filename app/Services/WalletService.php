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
     * @param string $provider Provider (freemopay ou paypal)
     * @return WalletTransaction
     */
    public function credit(
        User $user,
        float $amount,
        ?Payment $payment = null,
        string $description = 'Recharge wallet',
        array $metadata = [],
        string $provider = 'freemopay'
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $payment, $description, $metadata, $provider) {
            // Déterminer quel wallet mettre à jour
            $walletField = $provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';

            $balanceBefore = $user->{$walletField} ?? 0;
            $balanceAfter = $balanceBefore + $amount;

            // Mettre à jour le solde du wallet spécifique
            $user->{$walletField} = $balanceAfter;
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
                'provider' => $provider,
            ]);

            Log::info("[WalletService] Wallet credited", [
                'user_id' => $user->id,
                'provider' => $provider,
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
     * @param string $provider Provider (freemopay ou paypal)
     * @return WalletTransaction
     * @throws \Exception Si solde insuffisant ou provider invalide
     */
    public function debit(
        User $user,
        float $amount,
        string $description,
        ?string $referenceType = null,
        ?int $referenceId = null,
        array $metadata = [],
        string $provider = 'freemopay'
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $description, $referenceType, $referenceId, $metadata, $provider) {
            // Valider le provider
            if (!in_array($provider, ['freemopay', 'paypal'])) {
                throw new \Exception("Provider invalide. Doit être 'freemopay' ou 'paypal'.");
            }

            // Déterminer quel wallet débiter
            $walletField = $provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';

            $balanceBefore = $user->{$walletField} ?? 0;

            // Vérifier le solde
            if ($balanceBefore < $amount) {
                $providerName = $provider === 'paypal' ? 'PayPal' : 'FreeMoPay';
                throw new \Exception("Solde {$providerName} insuffisant. Solde actuel: {$balanceBefore} FCFA, Montant requis: {$amount} FCFA");
            }

            $balanceAfter = $balanceBefore - $amount;

            // Mettre à jour le solde du wallet spécifique
            $user->{$walletField} = $balanceAfter;
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
                'provider' => $provider,
            ]);

            Log::info("[WalletService] Wallet debited", [
                'user_id' => $user->id,
                'provider' => $provider,
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
     * @param string $provider Provider (freemopay ou paypal)
     * @return WalletTransaction
     */
    public function addBonus(
        User $user,
        float $amount,
        string $description = 'Bonus',
        array $metadata = [],
        string $provider = 'freemopay'
    ): WalletTransaction {
        return DB::transaction(function () use ($user, $amount, $description, $metadata, $provider) {
            // Valider le provider
            if (!in_array($provider, ['freemopay', 'paypal'])) {
                throw new \Exception("Provider invalide. Doit être 'freemopay' ou 'paypal'.");
            }

            // Déterminer quel wallet mettre à jour
            $walletField = $provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';

            $balanceBefore = $user->{$walletField} ?? 0;
            $balanceAfter = $balanceBefore + $amount;

            // Mettre à jour le solde du wallet spécifique
            $user->{$walletField} = $balanceAfter;
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
                'provider' => $provider,
            ]);

            Log::info("[WalletService] Bonus added", [
                'user_id' => $user->id,
                'provider' => $provider,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
            ]);

            // Envoyer une notification push à l'utilisateur
            try {
                $notificationService = app(NotificationService::class);
                $providerName = $provider === 'paypal' ? 'PayPal' : 'Mobile Money (FreeMo)';

                $notificationService->sendToUser(
                    $user,
                    'Bonus reçu !',
                    "Vous avez reçu un bonus de " . number_format($amount, 0, ',', ' ') . " FCFA via {$providerName}. {$description}",
                    'wallet_bonus',
                    [
                        'amount' => $amount,
                        'provider' => $provider,
                        'description' => $description,
                        'balance_after' => $balanceAfter,
                        'transaction_id' => $transaction->id,
                    ]
                );
            } catch (\Exception $e) {
                Log::warning("[WalletService] Failed to send bonus notification", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                // Ne pas échouer la transaction si la notification échoue
            }

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

        // Récupérer les soldes séparés (toujours en XAF dans la base)
        $freemopayBalanceXAF = $user->freemopay_wallet_balance ?? 0;
        $paypalBalanceXAF = $user->paypal_wallet_balance ?? 0;
        $totalBalanceXAF = $freemopayBalanceXAF + $paypalBalanceXAF;

        // Stats par provider
        $freemopayCreditsXAF = $transactions->clone()->where('provider', 'freemopay')->credits()->sum('amount');
        $freemopayDebitsXAF = abs($transactions->clone()->where('provider', 'freemopay')->debits()->sum('amount'));

        $paypalCreditsXAF = $transactions->clone()->where('provider', 'paypal')->credits()->sum('amount');
        $paypalDebitsXAF = abs($transactions->clone()->where('provider', 'paypal')->debits()->sum('amount'));

        $totalCreditsXAF = $freemopayCreditsXAF + $paypalCreditsXAF;
        $totalDebitsXAF = $freemopayDebitsXAF + $paypalDebitsXAF;

        // Devise à utiliser (préférée ou celle demandée)
        $targetCurrency = $currency ?? ($user->preferred_currency ?? 'XAF');

        // Convertir les montants si nécessaire
        if ($targetCurrency !== 'XAF') {
            $freemopayBalance = $this->currencyService->convert($freemopayBalanceXAF, 'XAF', $targetCurrency);
            $paypalBalance = $this->currencyService->convert($paypalBalanceXAF, 'XAF', $targetCurrency);
            $totalBalance = $this->currencyService->convert($totalBalanceXAF, 'XAF', $targetCurrency);
            $totalCredits = $this->currencyService->convert($totalCreditsXAF, 'XAF', $targetCurrency);
            $totalDebits = $this->currencyService->convert($totalDebitsXAF, 'XAF', $targetCurrency);
        } else {
            $freemopayBalance = $freemopayBalanceXAF;
            $paypalBalance = $paypalBalanceXAF;
            $totalBalance = $totalBalanceXAF;
            $totalCredits = $totalCreditsXAF;
            $totalDebits = $totalDebitsXAF;
        }

        return [
            // Soldes par provider
            'freemopay_balance' => $freemopayBalance,
            'paypal_balance' => $paypalBalance,
            'current_balance' => $totalBalance,
            'formatted_balance' => $this->currencyService->format($totalBalance, $targetCurrency),

            // Totaux
            'total_credits' => $totalCredits,
            'total_debits' => $totalDebits,
            'total_transactions' => $transactions->count(),
            'last_transaction' => $transactions->first(),

            // Informations sur la devise
            'currency' => $targetCurrency,
            'currency_symbol' => \App\Models\CurrencyRate::getCurrencySymbol($targetCurrency),

            // Montants bruts en XAF (pour référence)
            'freemopay_balance_xaf' => $freemopayBalanceXAF,
            'paypal_balance_xaf' => $paypalBalanceXAF,
            'balance_xaf' => $totalBalanceXAF,
        ];
    }

    /**
     * Vérifie si un user peut effectuer un paiement avec son wallet
     *
     * @param User $user
     * @param float $amount
     * @param string|null $provider Provider spécifique (null = total des deux wallets)
     * @return array ['can_pay' => bool, 'message' => string, 'missing_amount' => float]
     */
    public function canPayWithWallet(User $user, float $amount, ?string $provider = null): array
    {
        if ($provider) {
            // Vérifier un wallet spécifique
            $walletField = $provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';
            $balance = $user->{$walletField} ?? 0;
            $providerName = $provider === 'paypal' ? 'PayPal' : 'FreeMoPay';

            $canPay = $balance >= $amount;

            return [
                'can_pay' => $canPay,
                'current_balance' => $balance,
                'required_amount' => $amount,
                'missing_amount' => $canPay ? 0 : ($amount - $balance),
                'provider' => $provider,
                'message' => $canPay
                    ? "Paiement possible avec wallet {$providerName}"
                    : "Solde {$providerName} insuffisant. Il vous manque " . number_format($amount - $balance, 0, ',', ' ') . " FCFA",
            ];
        } else {
            // Vérifier le total des deux wallets
            $freemopayBalance = $user->freemopay_wallet_balance ?? 0;
            $paypalBalance = $user->paypal_wallet_balance ?? 0;
            $totalBalance = $freemopayBalance + $paypalBalance;

            $canPay = $totalBalance >= $amount;

            return [
                'can_pay' => $canPay,
                'freemopay_balance' => $freemopayBalance,
                'paypal_balance' => $paypalBalance,
                'total_balance' => $totalBalance,
                'required_amount' => $amount,
                'missing_amount' => $canPay ? 0 : ($amount - $totalBalance),
                'message' => $canPay
                    ? "Paiement possible"
                    : "Solde total insuffisant. Il vous manque " . number_format($amount - $totalBalance, 0, ',', ' ') . " FCFA",
            ];
        }
    }

    /**
     * Transfère de l'argent d'un utilisateur à un autre
     * Les transferts doivent se faire entre wallets du même provider
     * (PayPal → PayPal ou FreeMo → FreeMo uniquement)
     *
     * @param User $sender Utilisateur envoyeur
     * @param User $recipient Utilisateur destinataire
     * @param float $amount Montant à transférer
     * @param string $provider Provider (freemopay ou paypal)
     * @param string|null $note Note optionnelle pour le transfert
     * @return array ['sender_transaction' => WalletTransaction, 'recipient_transaction' => WalletTransaction]
     * @throws \Exception Si solde insuffisant, provider invalide, ou transfert à soi-même
     */
    public function transfer(
        User $sender,
        User $recipient,
        float $amount,
        string $provider,
        ?string $note = null
    ): array {
        // Validations
        if ($sender->id === $recipient->id) {
            throw new \Exception("Vous ne pouvez pas transférer de l'argent à vous-même");
        }

        if (!in_array($provider, ['freemopay', 'paypal'])) {
            throw new \Exception("Provider invalide. Doit être 'freemopay' ou 'paypal'");
        }

        if ($amount <= 0) {
            throw new \Exception("Le montant doit être supérieur à 0");
        }

        return DB::transaction(function () use ($sender, $recipient, $amount, $provider, $note) {
            $providerName = $provider === 'paypal' ? 'PayPal' : 'Mobile Money (FreeMo)';
            $walletField = $provider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';

            // Vérifier le solde de l'envoyeur
            $senderBalance = $sender->{$walletField} ?? 0;
            if ($senderBalance < $amount) {
                throw new \Exception("Solde {$providerName} insuffisant. Solde actuel: " . number_format($senderBalance, 0, ',', ' ') . " FCFA");
            }

            // 1. Débiter l'envoyeur
            $senderBalanceBefore = $senderBalance;
            $senderBalanceAfter = $senderBalanceBefore - $amount;
            $sender->{$walletField} = $senderBalanceAfter;
            $sender->save();

            $noteText = $note ? " - {$note}" : "";
            $senderTransaction = WalletTransaction::create([
                'user_id' => $sender->id,
                'type' => 'debit',
                'amount' => $amount,
                'balance_before' => $senderBalanceBefore,
                'balance_after' => $senderBalanceAfter,
                'description' => "Transfert vers {$recipient->name} via {$providerName}{$noteText}",
                'metadata' => [
                    'transfer_type' => 'sent',
                    'recipient_id' => $recipient->id,
                    'recipient_name' => $recipient->name,
                    'recipient_email' => $recipient->email,
                    'note' => $note,
                ],
                'status' => 'completed',
                'provider' => $provider,
            ]);

            // 2. Créditer le destinataire
            $recipientBalanceBefore = $recipient->{$walletField} ?? 0;
            $recipientBalanceAfter = $recipientBalanceBefore + $amount;
            $recipient->{$walletField} = $recipientBalanceAfter;
            $recipient->save();

            $recipientTransaction = WalletTransaction::create([
                'user_id' => $recipient->id,
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $recipientBalanceBefore,
                'balance_after' => $recipientBalanceAfter,
                'description' => "Transfert reçu de {$sender->name} via {$providerName}{$noteText}",
                'metadata' => [
                    'transfer_type' => 'received',
                    'sender_id' => $sender->id,
                    'sender_name' => $sender->name,
                    'sender_email' => $sender->email,
                    'note' => $note,
                ],
                'status' => 'completed',
                'provider' => $provider,
            ]);

            Log::info("[WalletService] Transfer completed", [
                'sender_id' => $sender->id,
                'recipient_id' => $recipient->id,
                'amount' => $amount,
                'provider' => $provider,
                'sender_balance_after' => $senderBalanceAfter,
                'recipient_balance_after' => $recipientBalanceAfter,
            ]);

            // 3. Envoyer les notifications push
            try {
                $notificationService = app(NotificationService::class);

                // Notification pour l'envoyeur
                $notificationService->sendToUser(
                    $sender,
                    'Transfert envoyé',
                    "Vous avez envoyé " . number_format($amount, 0, ',', ' ') . " FCFA à {$recipient->name} via {$providerName}",
                    'wallet_transfer_sent',
                    [
                        'amount' => $amount,
                        'provider' => $provider,
                        'recipient_name' => $recipient->name,
                        'recipient_id' => $recipient->id,
                        'balance_after' => $senderBalanceAfter,
                        'transaction_id' => $senderTransaction->id,
                        'note' => $note,
                    ]
                );

                // Notification pour le destinataire
                $notificationService->sendToUser(
                    $recipient,
                    'Transfert reçu',
                    "Vous avez reçu " . number_format($amount, 0, ',', ' ') . " FCFA de {$sender->name} via {$providerName}",
                    'wallet_transfer_received',
                    [
                        'amount' => $amount,
                        'provider' => $provider,
                        'sender_name' => $sender->name,
                        'sender_id' => $sender->id,
                        'balance_after' => $recipientBalanceAfter,
                        'transaction_id' => $recipientTransaction->id,
                        'note' => $note,
                    ]
                );
            } catch (\Exception $e) {
                Log::warning("[WalletService] Failed to send transfer notifications", [
                    'error' => $e->getMessage(),
                ]);
                // Ne pas échouer la transaction si la notification échoue
            }

            return [
                'sender_transaction' => $senderTransaction,
                'recipient_transaction' => $recipientTransaction,
            ];
        });
    }
}
