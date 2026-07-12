<?php

namespace App\Services;

use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralCommissionService
{
    protected WalletService $walletService;
    protected NotificationService $notificationService;

    public function __construct(WalletService $walletService, NotificationService $notificationService)
    {
        $this->walletService = $walletService;
        $this->notificationService = $notificationService;
    }

    /**
     * Traite la commission de parrainage pour un achat payé via wallet
     * (pack candidat, pack recruteur, service, etc.).
     *
     * Le parrain reçoit X% du montant dépensé, crédité sur son SOLDE DE
     * PARRAINAGE dédié (`referral_balance`, en XAF). Ce solde n'est pas
     * dépensable tel quel : le parrain le transfère explicitement vers son
     * wallet (KPay/PayPal) depuis le dashboard, ce qui crée une transaction.
     *
     * @param User $user L'utilisateur qui a effectué l'achat
     * @param float $purchaseAmount Montant dépensé
     * @param string $provider 'paypal' ou 'freemopay' (provider de l'achat)
     * @param string $purchaseLabel Libellé de l'achat (ex: "Pack candidat C2")
     * @param string|null $reference Référence de la transaction (payment id, etc.)
     * @return ReferralCommission|null La commission créée, ou null si pas de parrain
     */
    public function processPurchaseCommission(
        User $user,
        float $purchaseAmount,
        string $provider,
        string $purchaseLabel,
        ?string $reference = null
    ): ?ReferralCommission {
        if (!$this->isReferralSystemEnabled()) {
            Log::info('[ReferralCommission] Système de parrainage désactivé');
            return null;
        }

        if (!$user->referred_by_id) {
            Log::info('[ReferralCommission] Utilisateur sans parrain', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        $referrer = User::find($user->referred_by_id);
        if (!$referrer) {
            Log::warning('[ReferralCommission] Parrain introuvable', [
                'user_id' => $user->id,
                'referred_by_id' => $user->referred_by_id,
            ]);
            return null;
        }

        if (!in_array($provider, ['freemopay', 'paypal'], true)) {
            Log::warning('[ReferralCommission] Provider invalide', [
                'user_id' => $user->id,
                'provider' => $provider,
            ]);
            return null;
        }

        $commissionPercentage = $this->getCommissionPercentage();
        $commissionAmount = ($purchaseAmount * $commissionPercentage) / 100;

        Log::info('[ReferralCommission] Calcul de la commission (achat)', [
            'user_id' => $user->id,
            'referrer_id' => $referrer->id,
            'purchase_amount' => $purchaseAmount,
            'provider' => $provider,
            'purchase_label' => $purchaseLabel,
            'commission_percentage' => $commissionPercentage,
            'commission_amount' => $commissionAmount,
        ]);

        try {
            DB::beginTransaction();

            $commission = ReferralCommission::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'transaction_type' => $provider, // 'paypal' ou 'freemopay'
                'transaction_reference' => $reference,
                'transaction_amount' => $purchaseAmount,
                'commission_percentage' => $commissionPercentage,
                'commission_amount' => $commissionAmount,
            ]);

            // Créditer le SOLDE DE PARRAINAGE (pas le wallet). Verrou de ligne
            // pour éviter toute course sur le solde.
            $lockedReferrer = User::whereKey($referrer->id)->lockForUpdate()->first();
            $lockedReferrer->referral_balance = (float) $lockedReferrer->referral_balance + $commissionAmount;
            $lockedReferrer->save();

            DB::commit();

            Log::info('[ReferralCommission] ✅ Commission ajoutée au solde de parrainage', [
                'commission_id' => $commission->id,
                'referrer_id' => $referrer->id,
                'commission_amount' => $commissionAmount,
                'referral_balance_after' => $lockedReferrer->referral_balance,
            ]);

            $this->sendReferralCommissionNotification(
                $referrer,
                $user,
                $commissionAmount,
                $provider,
                $purchaseAmount,
                $purchaseLabel
            );

            return $commission;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[ReferralCommission] ❌ Erreur lors du traitement de la commission', [
                'user_id' => $user->id,
                'referrer_id' => $referrer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }

    /**
     * Vérifie si le système de parrainage est activé
     */
    private function isReferralSystemEnabled(): bool
    {
        return (bool) settings('referral_enabled', false);
    }

    /**
     * Récupère le pourcentage de commission depuis les settings
     */
    private function getCommissionPercentage(): float
    {
        return (float) settings('referral_commission_percentage', 5); // 5% par défaut
    }

    /**
     * Envoie une notification FCM au parrain pour sa commission
     */
    private function sendReferralCommissionNotification(
        User $referrer,
        User $referred,
        float $commissionAmount,
        string $provider,
        float $purchaseAmount,
        string $purchaseLabel
    ): void {
        try {
            $formattedAmount = number_format($commissionAmount, 0, ',', ' ');

            $title = "Commission de parrainage reçue !";
            $body = "Vous avez gagné {$formattedAmount} FCFA sur votre solde de parrainage suite à l'achat de {$referred->name} ({$purchaseLabel}). Transférez-le vers votre wallet depuis votre dashboard de parrainage.";

            $this->notificationService->sendToUser(
                $referrer,
                $title,
                $body,
                'referral_commission_earned',
                [
                    'commission_amount' => (string) $commissionAmount,
                    'wallet_provider' => $provider,
                    'referred_user_id' => (string) $referred->id,
                    'referred_user_name' => $referred->name,
                    'purchase_amount' => (string) $purchaseAmount,
                    'purchase_label' => $purchaseLabel,
                ]
            );

            Log::info('[ReferralCommission] ✅ Notification FCM envoyée au parrain', [
                'referrer_id' => $referrer->id,
                'commission_amount' => $commissionAmount,
                'wallet_provider' => $provider,
            ]);
        } catch (\Exception $e) {
            Log::error('[ReferralCommission] ❌ Erreur lors de l\'envoi de la notification FCM', [
                'referrer_id' => $referrer->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
