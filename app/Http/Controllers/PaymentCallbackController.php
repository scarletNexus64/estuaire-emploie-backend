<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Payment\PayPalService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    protected PayPalService $paypalService;
    protected WalletService $walletService;

    public function __construct(PayPalService $paypalService, WalletService $walletService)
    {
        $this->paypalService = $paypalService;
        $this->walletService = $walletService;
    }

    /**
     * Handle successful PayPal payment callback
     *
     * GET /payment/success?payment_id=18&paymentId=PAYID-XXX&token=EC-XXX&PayerID=XXX
     */
    public function success(Request $request)
    {
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [PaymentCallback] ✅ SUCCESS CALLBACK RECEIVED                    ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");
        \Log::info("[PaymentCallback] Query params:", $request->all());

        try {
            $paymentId = $request->input('payment_id');
            $paypalPaymentId = $request->input('paymentId');
            $payerId = $request->input('PayerID');

            if (!$paymentId || !$paypalPaymentId || !$payerId) {
                \Log::error("[PaymentCallback] ❌ Missing required parameters");
                return view('payment.error', [
                    'message' => 'Paramètres de paiement manquants',
                    'details' => 'Les informations de paiement sont incomplètes.'
                ]);
            }

            // Récupérer le paiement
            $payment = Payment::find($paymentId);

            if (!$payment) {
                \Log::error("[PaymentCallback] ❌ Payment not found", ['payment_id' => $paymentId]);
                return view('payment.error', [
                    'message' => 'Paiement introuvable',
                    'details' => 'Le paiement #' . $paymentId . ' n\'existe pas.'
                ]);
            }

            // Si déjà complété, afficher la page de succès directement
            if ($payment->status === 'completed') {
                \Log::info("[PaymentCallback] ℹ️ Payment already completed");

                return view('payment.success', [
                    'payment' => $payment,
                    'amount' => number_format($payment->amount, 0, ',', ' '),
                    'currency' => 'FCFA',
                    'already_completed' => true
                ]);
            }

            // Exécuter le paiement PayPal
            \Log::info("[PaymentCallback] 🔄 Executing PayPal payment...");
            $payment = $this->paypalService->executePayment($paypalPaymentId, $payerId, $payment);

            // Si le paiement est complété, créditer le wallet
            if ($payment->status === 'completed') {
                $this->completeWalletRecharge($payment);

                \Log::info("[PaymentCallback] ✅ Payment executed and wallet credited", [
                    'payment_id' => $payment->id,
                    'user_id' => $payment->user_id,
                    'amount' => $payment->amount,
                ]);

                return view('payment.success', [
                    'payment' => $payment,
                    'amount' => number_format($payment->amount, 0, ',', ' '),
                    'currency' => 'FCFA',
                    'already_completed' => false
                ]);
            }

            // Si échec
            \Log::error("[PaymentCallback] ❌ Payment execution failed", [
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'failure_reason' => $payment->failure_reason,
            ]);

            return view('payment.error', [
                'message' => 'Échec du paiement',
                'details' => $payment->failure_reason ?? 'Le paiement n\'a pas pu être complété.'
            ]);

        } catch (\Exception $e) {
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            \Log::error("[PaymentCallback] ❌ CALLBACK FAILED");
            \Log::error("[PaymentCallback] ❌ Error: {$e->getMessage()}");
            \Log::error("[PaymentCallback] ❌ Trace: {$e->getTraceAsString()}");
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return view('payment.error', [
                'message' => 'Erreur système',
                'details' => 'Une erreur s\'est produite lors du traitement du paiement.'
            ]);
        }
    }

    /**
     * Handle cancelled PayPal payment callback
     *
     * GET /payment/cancel?payment_id=18&token=EC-XXX
     */
    public function cancel(Request $request)
    {
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [PaymentCallback] ❌ CANCEL CALLBACK RECEIVED                     ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");
        \Log::info("[PaymentCallback] Query params:", $request->all());

        $paymentId = $request->input('payment_id');

        if ($paymentId) {
            $payment = Payment::find($paymentId);

            if ($payment && $payment->status === 'pending') {
                $payment->update([
                    'status' => 'cancelled',
                    'failure_reason' => 'User cancelled the payment',
                ]);

                \Log::info("[PaymentCallback] ℹ️ Payment marked as cancelled", ['payment_id' => $paymentId]);
            }
        }

        return view('payment.cancel', [
            'payment_id' => $paymentId
        ]);
    }

    /**
     * Complète une recharge de wallet (crédite le solde de l'utilisateur)
     */
    private function completeWalletRecharge(Payment $payment)
    {
        \Log::info("[PaymentCallback] 💰 Completing wallet recharge", [
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
        ]);

        // Marquer le paiement comme complété
        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Créditer le wallet de l'utilisateur
        $user = $payment->user;
        $metadata = $payment->metadata ?? [];

        $description = "Recharge wallet via " . strtoupper($payment->payment_method);
        if (isset($metadata['currency_conversion'])) {
            $description .= " ({$metadata['currency_conversion']['converted_amount']} {$metadata['currency_conversion']['converted_currency']})";
        }

        $this->walletService->credit(
            $user,
            $payment->amount,
            $payment,
            $description,
            ['payment_id' => $payment->id]
        );

        \Log::info("[PaymentCallback] ✅ Wallet recharged successfully", [
            'user_id' => $user->id,
            'new_balance' => $user->wallet_balance,
        ]);
    }
}
