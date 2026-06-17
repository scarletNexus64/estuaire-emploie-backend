<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Payment;
use App\Models\PlatformWithdrawal;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Notifier FCM partagé pour les évènements wallet (recharges & retraits).
 *
 * Centralise la création de la Notification en base + l'envoi FCM, pour que
 * le webhook, les jobs de polling et les endpoints de statut utilisent le même
 * format sans divergence. Appelé depuis des jobs en queue → asynchrone.
 */
class WalletNotifier
{
    /**
     * Notification de recharge réussie.
     */
    public function rechargeSuccess(Payment $payment): void
    {
        $user = $payment->user;
        if (!$user) {
            return;
        }

        $this->dispatch(
            $user,
            'wallet_recharge_success',
            'Recharge effectuée',
            'Votre wallet a été crédité de ' . $this->fmt($payment->amount) . ' FCFA avec succès.',
            [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
            ]
        );
    }

    /**
     * Notification de recharge échouée.
     */
    public function rechargeFailed(Payment $payment): void
    {
        $user = $payment->user;
        if (!$user) {
            return;
        }

        $this->dispatch(
            $user,
            'wallet_recharge_failed',
            'Recharge échouée',
            'Votre recharge de ' . $this->fmt($payment->amount) . ' FCFA n\'a pas abouti.'
                . ($payment->failure_reason ? ' Raison: ' . $payment->failure_reason : ''),
            [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'failure_reason' => $payment->failure_reason,
            ]
        );
    }

    /**
     * Notification de retrait réussi.
     */
    public function withdrawalSuccess(PlatformWithdrawal $withdrawal): void
    {
        if (!$withdrawal->user_id || !$withdrawal->user) {
            return;
        }

        $body = $withdrawal->provider === 'paypal'
            ? 'Votre retrait PayPal de ' . number_format($withdrawal->amount_sent, 2) . ' USD a été effectué avec succès.'
            : 'Votre retrait de ' . $this->fmt($withdrawal->amount_requested) . ' FCFA a été effectué avec succès.';

        $this->dispatch(
            $withdrawal->user,
            'wallet_withdrawal_success',
            'Retrait effectué',
            $body,
            [
                'withdrawal_id' => $withdrawal->id,
                'amount' => $withdrawal->amount_requested,
                'provider' => $withdrawal->provider,
            ]
        );
    }

    /**
     * Notification de retrait échoué.
     */
    public function withdrawalFailed(PlatformWithdrawal $withdrawal, ?string $reason = null): void
    {
        if (!$withdrawal->user_id || !$withdrawal->user) {
            return;
        }

        $this->dispatch(
            $withdrawal->user,
            'wallet_withdrawal_failed',
            'Retrait échoué',
            'Votre retrait de ' . $this->fmt($withdrawal->amount_requested) . ' FCFA a échoué.'
                . ($reason ? ' Raison: ' . $reason : ''),
            [
                'withdrawal_id' => $withdrawal->id,
                'amount' => $withdrawal->amount_requested,
                'provider' => $withdrawal->provider,
                'failure_reason' => $reason,
            ]
        );
    }

    /**
     * Crée la Notification en base et l'envoie via FCM.
     */
    protected function dispatch(User $user, string $type, string $title, string $body, array $extra = []): void
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            $notification = Notification::create([
                'type' => $type,
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => array_merge(['title' => $title, 'body' => $body], $extra),
            ]);

            $data = ['type' => $type, 'notification_id' => (string) $notification->id];
            foreach ($extra as $k => $v) {
                $data[$k] = is_scalar($v) ? (string) $v : json_encode($v);
            }

            app(\App\Services\FirebaseNotificationService::class)
                ->sendToToken($user->fcm_token, $title, $body, $data);

            Log::info("[WalletNotifier] FCM envoyée: {$type}", ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error("[WalletNotifier] Échec FCM ({$type}): {$e->getMessage()}");
        }
    }

    protected function fmt($amount): string
    {
        return number_format((float) $amount, 0, ',', ' ');
    }
}
