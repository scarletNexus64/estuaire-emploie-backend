<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * Récupère le solde et les stats du wallet
     *
     * GET /api/wallet
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $stats = $this->walletService->getWalletStats($user);

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupère l'historique des transactions
     *
     * GET /api/wallet/transactions
     */
    public function transactions(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->input('per_page', 20);
            $type = $request->input('type'); // credit, debit, etc.

            $transactions = $this->walletService->getTransactionHistory($user, $perPage, $type);

            return response()->json([
                'success' => true,
                'data' => $transactions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des transactions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initie une recharge du wallet
     * Crée un paiement FreeMoPay ou PayPal
     *
     * POST /api/wallet/recharge
     */
    public function recharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100', // Minimum 100 FCFA
            'payment_method' => 'required|in:freemopay,paypal',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $amount = $request->amount;
            $paymentMethod = $request->payment_method;

            // Créer un paiement selon la méthode choisie
            $payment = \App\Models\Payment::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'payment_type' => 'wallet_recharge',
                'status' => 'pending',
                'currency' => 'XAF',
                'metadata' => [
                    'wallet_recharge' => true,
                    'requested_amount' => $amount,
                ],
            ]);

            // Générer l'URL de paiement selon la méthode
            if ($paymentMethod === 'freemopay') {
                $freemopayService = app(\App\Services\Payment\FreeMoPayService::class);
                $paymentUrl = $freemopayService->initiatePayment($payment);
            } else {
                $paypalService = app(\App\Services\Payment\PayPalService::class);
                $paymentUrl = $paypalService->createPayment($payment);
            }

            return response()->json([
                'success' => true,
                'message' => 'Recharge initiée avec succès',
                'data' => [
                    'payment_id' => $payment->id,
                    'payment_url' => $paymentUrl,
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'initiation de la recharge',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur peut payer un montant avec son wallet
     *
     * POST /api/wallet/can-pay
     */
    public function canPay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Montant invalide',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $result = $this->walletService->canPayWithWallet($user, $request->amount);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Paye avec le wallet (pour abonnements et services)
     *
     * POST /api/wallet/pay
     */
    public function pay(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'reference_type' => 'required|string|in:subscription,addon_service',
            'reference_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $amount = $request->amount;
            $description = $request->description;
            $referenceType = $request->reference_type;
            $referenceId = $request->reference_id;

            // Effectuer le paiement
            $transaction = $this->walletService->debit(
                $user,
                $amount,
                $description,
                $referenceType,
                $referenceId,
                ['paid_via_api' => true]
            );

            return response()->json([
                'success' => true,
                'message' => 'Paiement effectué avec succès',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'amount_paid' => $amount,
                    'new_balance' => $transaction->balance_after,
                    'formatted_balance' => $user->formatted_wallet_balance,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
