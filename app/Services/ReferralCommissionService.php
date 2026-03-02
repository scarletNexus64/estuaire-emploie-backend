<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralCommissionService
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Traite la commission de parrainage pour une recharge de wallet
     *
     * @param User $user L'utilisateur qui a rechargé son wallet
     * @param Payment $payment Le paiement de recharge
     * @return ReferralCommission|null La commission créée, ou null si pas de parrain
     */
    public function processReferralCommission(User $user, Payment $payment): ?ReferralCommission
    {
        // Vérifier si le système de parrainage est activé
        if (!$this->isReferralSystemEnabled()) {
            Log::info('[ReferralCommission] Système de parrainage désactivé');
            return null;
        }

        // Vérifier si l'utilisateur a un parrain
        if (!$user->referred_by_id) {
            Log::info('[ReferralCommission] Utilisateur sans parrain', [
                'user_id' => $user->id,
            ]);
            return null;
        }

        // Récupérer le parrain
        $referrer = User::find($user->referred_by_id);
        if (!$referrer) {
            Log::warning('[ReferralCommission] Parrain introuvable', [
                'user_id' => $user->id,
                'referred_by_id' => $user->referred_by_id,
            ]);
            return null;
        }

        // Récupérer le pourcentage de commission depuis les settings
        $commissionPercentage = $this->getCommissionPercentage();

        // Calculer le montant de la commission
        $commissionAmount = ($payment->amount * $commissionPercentage) / 100;

        Log::info('[ReferralCommission] Calcul de la commission', [
            'user_id' => $user->id,
            'referrer_id' => $referrer->id,
            'recharge_amount' => $payment->amount,
            'commission_percentage' => $commissionPercentage,
            'commission_amount' => $commissionAmount,
        ]);

        try {
            DB::beginTransaction();

            // Enregistrer la commission dans la table referral_commissions
            $commission = ReferralCommission::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'transaction_type' => $payment->payment_method, // 'paypal' ou 'freemopay'
                'transaction_reference' => $payment->transaction_id ?? $payment->id,
                'transaction_amount' => $payment->amount,
                'commission_percentage' => $commissionPercentage,
                'commission_amount' => $commissionAmount,
            ]);

            // Déterminer le provider basé sur le payment_method
            $provider = 'freemopay'; // Par défaut
            if (in_array(strtolower($payment->payment_method), ['paypal', 'paypal_native'])) {
                $provider = 'paypal';
            }

            // Créditer le wallet du parrain (même provider que la recharge du filleul)
            $this->walletService->credit(
                $referrer,
                $commissionAmount,
                null, // Pas de payment associé
                "Commission de parrainage - Recharge de {$user->name}",
                [
                    'referral_commission_id' => $commission->id,
                    'referred_user_id' => $user->id,
                    'recharge_amount' => $payment->amount,
                ],
                $provider // Utiliser le même provider que la recharge
            );

            DB::commit();

            Log::info('[ReferralCommission] ✅ Commission créditée avec succès', [
                'commission_id' => $commission->id,
                'referrer_id' => $referrer->id,
                'commission_amount' => $commissionAmount,
                'referrer_new_balance' => $referrer->fresh()->wallet_balance,
            ]);

            // Envoyer notification FCM au parrain
            $this->sendReferralCommissionNotification($referrer, $user, $commissionAmount, $payment);

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
    private function sendReferralCommissionNotification(User $referrer, User $referred, float $commissionAmount, Payment $payment): void
    {
        try {
            if (!$referrer->fcm_token) {
                Log::info('[ReferralCommission] Pas de FCM token pour le parrain', [
                    'referrer_id' => $referrer->id,
                ]);
                return;
            }

            $title = "Commission de parrainage reçue !";
            $body = "Vous avez reçu " . number_format($commissionAmount, 0, ',', ' ') . " FCFA de commission pour la recharge de {$referred->name}.";

            // Créer la notification en base de données
            $notification = \App\Models\Notification::create([
                'type' => 'referral_commission_earned',
                'notifiable_type' => User::class,
                'notifiable_id' => $referrer->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'commission_amount' => $commissionAmount,
                    'referred_user_id' => $referred->id,
                    'referred_user_name' => $referred->name,
                    'recharge_amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $referrer->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'referral_commission_earned',
                        'commission_amount' => $commissionAmount,
                        'referred_user_name' => $referred->name,
                        'notification_id' => $notification->id,
                    ],
                ]);

            Log::info('[ReferralCommission] ✅ Notification FCM envoyée au parrain', [
                'referrer_id' => $referrer->id,
                'commission_amount' => $commissionAmount,
                'notification_id' => $notification->id,
            ]);

        } catch (\Exception $e) {
            Log::error('[ReferralCommission] ❌ Erreur lors de l\'envoi de la notification FCM', [
                'referrer_id' => $referrer->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
