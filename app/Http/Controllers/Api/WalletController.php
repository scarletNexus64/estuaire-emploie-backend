<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWithdrawalPolling;
use App\Models\PlatformWithdrawal;
use App\Services\ReferralCommissionService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    protected WalletService $walletService;
    protected ReferralCommissionService $referralCommissionService;

    public function __construct(
        WalletService $walletService,
        ReferralCommissionService $referralCommissionService
    ) {
        $this->walletService = $walletService;
        $this->referralCommissionService = $referralCommissionService;
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
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [WalletController] 💰 WALLET RECHARGE REQUEST                     ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50', // Minimum 100 FCFA
            'payment_method' => 'required|in:freemopay,paypal',
            'phone_number' => 'required_if:payment_method,freemopay|string', // Requis pour FreeMoPay
        ]);

        if ($validator->fails()) {
            \Log::warning("[WalletController] ❌ Validation failed", [
                'errors' => $validator->errors()->toArray()
            ]);

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
            $phoneNumber = $request->phone_number;

            \Log::info("[WalletController] 📝 Request details", [
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'phone_number' => $phoneNumber ? substr($phoneNumber, 0, 3) . '****' . substr($phoneNumber, -2) : null,
            ]);

            // Générer l'URL/référence de paiement selon la méthode
            if ($paymentMethod === 'freemopay') {
                \Log::info("[WalletController] 📱 Using FreeMoPay");

                if (!$phoneNumber) {
                    throw new \Exception('Le numéro de téléphone est requis pour Mobile Money');
                }

                $freemopayService = app(\App\Services\Payment\FreeMoPayService::class);

                // FreeMoPay crée le paiement et attend la confirmation (synchrone)
                $payment = $freemopayService->initPayment(
                    $user,
                    $amount,
                    $phoneNumber,
                    'Recharge wallet',
                    null,
                    null,
                    'wallet_recharge'
                );

                \Log::info("[WalletController] ✅ FreeMoPay payment initiated", [
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                ]);

                // Si le paiement est complété, créditer le wallet
                if ($payment->status === 'completed') {
                    $this->completeWalletRecharge($payment);

                    return response()->json([
                        'success' => true,
                        'message' => 'Recharge effectuée avec succès',
                        'data' => [
                            'payment_id' => $payment->id,
                            'payment_url' => null, // Pas d'URL pour FreeMoPay
                            'amount' => $amount,
                            'payment_method' => $paymentMethod,
                            'status' => $payment->status,
                            'new_balance' => $user->wallet_balance,
                        ],
                    ]);
                }

                // Si échec ou autre statut
                return response()->json([
                    'success' => $payment->status !== 'failed',
                    'message' => $payment->status === 'failed'
                        ? 'Le paiement a échoué'
                        : 'Recharge en cours de traitement',
                    'data' => [
                        'payment_id' => $payment->id,
                        'payment_url' => null,
                        'amount' => $amount,
                        'payment_method' => $paymentMethod,
                        'status' => $payment->status,
                        'failure_reason' => $payment->failure_reason,
                    ],
                ]);
            } else {
                \Log::info("[WalletController] 💳 Using PayPal");

                // Créer un paiement selon la méthode choisie
                $payment = \App\Models\Payment::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'fees' => 0,
                    'total' => $amount,
                    'payment_method' => $paymentMethod,
                    'payment_type' => 'wallet_recharge',
                    'status' => 'pending',
                    'currency' => 'XAF',
                    'metadata' => [
                        'wallet_recharge' => true,
                        'requested_amount' => $amount,
                    ],
                ]);

                \Log::info("[WalletController] ✅ Payment record created", [
                    'payment_id' => $payment->id,
                ]);

                $paypalService = app(\App\Services\Payment\PayPalService::class);
                $paymentUrl = $paypalService->createPayment($payment);

                \Log::info("[WalletController] ✅ Payment URL generated", [
                    'payment_url' => $paymentUrl,
                ]);

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
            }
        } catch (\Exception $e) {
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            \Log::error("[WalletController] ❌ WALLET RECHARGE FAILED");
            \Log::error("[WalletController] ❌ Error: {$e->getMessage()}");
            \Log::error("[WalletController] ❌ Trace: {$e->getTraceAsString()}");
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

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
            'provider' => 'nullable|string|in:freemopay,paypal',
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
            $provider = $request->input('provider'); // null = vérifier le total
            $result = $this->walletService->canPayWithWallet($user, $request->amount, $provider);

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
            'payment_provider' => 'required|string|in:freemopay,paypal',
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
            $paymentProvider = $request->payment_provider;

            \Log::info("[WalletController] Payment with wallet requested", [
                'user_id' => $user->id,
                'amount' => $amount,
                'provider' => $paymentProvider,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
            ]);

            // Effectuer le paiement
            $transaction = $this->walletService->debit(
                $user,
                $amount,
                $description,
                $referenceType,
                $referenceId,
                ['paid_via_api' => true],
                $paymentProvider
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

    /**
     * Vérifie le statut d'un paiement de recharge wallet
     *
     * GET /api/wallet/payment-status/{payment_id}
     */
    public function checkPaymentStatus(Request $request, int $paymentId)
    {
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [WalletController] 🔍 CHECKING PAYMENT STATUS                     ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");

        try {
            $user = $request->user();

            \Log::info("[WalletController] 📝 Request details", [
                'user_id' => $user->id,
                'payment_id' => $paymentId,
            ]);

            $payment = \App\Models\Payment::where('id', $paymentId)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                \Log::warning("[WalletController] ❌ Payment not found", [
                    'payment_id' => $paymentId,
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé',
                ], 404);
            }

            \Log::info("[WalletController] 📋 Payment found", [
                'payment_id' => $payment->id,
                'status' => $payment->status,
                'payment_method' => $payment->payment_method,
                'provider' => $payment->provider,
            ]);

            // Si le paiement est encore pending, vérifier avec le provider
            if ($payment->status === 'pending' && $payment->provider_reference) {
                \Log::info("[WalletController] ⏳ Payment is pending, checking with provider...");

                try {
                    if ($payment->provider === 'freemopay' || $payment->payment_method === 'freemopay') {
                        // Vérifier avec FreeMoPay
                        $freemoPayService = app(\App\Services\Payment\FreeMoPayService::class);
                        $statusResponse = $freemoPayService->checkPaymentStatus($payment->provider_reference);

                        $status = strtoupper($statusResponse['status'] ?? '');
                        \Log::info("[WalletController] 📥 FreeMoPay status: {$status}");

                        if (in_array($status, ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'])) {
                            $this->completeWalletRecharge($payment);
                        } elseif (in_array($status, ['FAILED', 'CANCELLED', 'REJECTED'])) {
                            $payment->update([
                                'status' => 'failed',
                                'failure_reason' => $statusResponse['message'] ?? $status,
                                'payment_provider_response' => $statusResponse,
                            ]);
                        }
                    } elseif ($payment->provider === 'paypal' || $payment->payment_method === 'paypal') {
                        // Vérifier avec PayPal
                        $paypalService = app(\App\Services\Payment\PayPalService::class);
                        $statusResponse = $paypalService->getPaymentDetails($payment->provider_reference);

                        if ($statusResponse['success']) {
                            $state = $statusResponse['data']['state'] ?? '';
                            \Log::info("[WalletController] 📥 PayPal status: {$state}");

                            if ($state === 'approved') {
                                $this->completeWalletRecharge($payment);
                            } elseif (in_array($state, ['failed', 'denied', 'expired'])) {
                                $payment->update([
                                    'status' => 'failed',
                                    'failure_reason' => "PayPal payment {$state}",
                                    'payment_provider_response' => $statusResponse['data'],
                                ]);
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning("[WalletController] ⚠️ Could not check payment status with provider: " . $e->getMessage());
                }
            }

            $payment->refresh();

            \Log::info("[WalletController] ✅ Returning payment status", [
                'status' => $payment->status,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Statut récupéré',
                'data' => [
                    'payment_id' => $payment->id,
                    'reference' => $payment->provider_reference,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'payment_method' => $payment->payment_method,
                    'provider' => $payment->provider,
                    'status' => $payment->status,
                    'paid_at' => $payment->paid_at?->toIso8601String(),
                    'failure_reason' => $payment->failure_reason,
                    'metadata' => $payment->metadata,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            \Log::error("[WalletController] ❌ CHECK PAYMENT STATUS FAILED");
            \Log::error("[WalletController] ❌ Error: {$e->getMessage()}");
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exécute un paiement PayPal après approbation de l'utilisateur
     *
     * POST /api/wallet/paypal/execute
     */
    public function executePayPalPayment(Request $request)
    {
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [WalletController] 💳 EXECUTING PAYPAL PAYMENT                    ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");

        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|integer',
            'paymentId' => 'required|string', // PayPal payment ID
            'PayerID' => 'required|string',   // PayPal payer ID
        ]);

        if ($validator->fails()) {
            \Log::warning("[WalletController] ❌ Validation failed", [
                'errors' => $validator->errors()->toArray()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $paymentId = $request->payment_id; // Our payment ID
            $paypalPaymentId = $request->paymentId; // PayPal's payment ID
            $payerId = $request->PayerID; // PayPal's payer ID

            \Log::info("[WalletController] 📝 Request details", [
                'user_id' => $user->id,
                'payment_id' => $paymentId,
                'paypal_payment_id' => $paypalPaymentId,
                'payer_id' => $payerId,
            ]);

            // Récupérer le paiement
            $payment = \App\Models\Payment::where('id', $paymentId)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                \Log::warning("[WalletController] ❌ Payment not found", [
                    'payment_id' => $paymentId,
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé',
                ], 404);
            }

            // Vérifier que c'est bien un paiement PayPal
            if ($payment->payment_method !== 'paypal' && $payment->provider !== 'paypal') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce paiement n\'est pas un paiement PayPal',
                ], 400);
            }

            // Vérifier que le paiement n'est pas déjà complété
            if ($payment->status === 'completed') {
                \Log::info("[WalletController] ⚠️  Payment already completed", [
                    'payment_id' => $payment->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement déjà complété',
                    'data' => [
                        'payment_id' => $payment->id,
                        'status' => $payment->status,
                        'new_balance' => $user->wallet_balance,
                    ],
                ]);
            }

            // Exécuter le paiement PayPal
            $paypalService = app(\App\Services\Payment\PayPalService::class);
            $payment = $paypalService->executePayment($paypalPaymentId, $payerId, $payment);

            // Si le paiement est complété, créditer le wallet
            if ($payment->status === 'completed') {
                $this->completeWalletRecharge($payment);

                \Log::info("[WalletController] ✅ PayPal payment executed and wallet credited", [
                    'payment_id' => $payment->id,
                    'new_balance' => $user->wallet_balance,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement effectué avec succès',
                    'data' => [
                        'payment_id' => $payment->id,
                        'amount' => $payment->amount,
                        'status' => $payment->status,
                        'new_balance' => $user->wallet_balance,
                    ],
                ]);
            }

            // Si échec
            return response()->json([
                'success' => false,
                'message' => 'Le paiement a échoué',
                'data' => [
                    'payment_id' => $payment->id,
                    'status' => $payment->status,
                    'failure_reason' => $payment->failure_reason,
                ],
            ], 400);

        } catch (\Exception $e) {
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            \Log::error("[WalletController] ❌ PAYPAL PAYMENT EXECUTION FAILED");
            \Log::error("[WalletController] ❌ Error: {$e->getMessage()}");
            \Log::error("[WalletController] ❌ Trace: {$e->getTraceAsString()}");
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'exécution du paiement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crée un ordre PayPal natif pour le paiement frontend
     *
     * POST /api/wallet/paypal/create-native-order
     */
    public function createNativePayPalOrder(Request $request)
    {
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [WalletController] 💳 CREATING NATIVE PAYPAL ORDER                ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
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

            \Log::info("[WalletController] 📝 Request details", [
                'user_id' => $user->id,
                'amount' => $amount,
            ]);

            // Créer l'enregistrement de paiement
            $payment = \App\Models\Payment::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'fees' => 0,
                'total' => $amount,
                'payment_method' => 'paypal',
                'payment_type' => 'wallet_recharge',
                'status' => 'pending',
                'currency' => 'XAF',
                'metadata' => [
                    'wallet_recharge' => true,
                    'native_payment' => true,
                    'requested_amount' => $amount,
                ],
            ]);

            // Utiliser le PayPalService pour créer l'ordre
            $paypalService = app(\App\Services\Payment\PayPalService::class);
            $orderData = $paypalService->createNativeOrder($payment);

            \Log::info("[WalletController] ✅ Native PayPal order created", [
                'payment_id' => $payment->id,
                'order_id' => $orderData['order_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ordre PayPal créé avec succès',
                'data' => [
                    'payment_id' => $payment->id,
                    'order_id' => $orderData['order_id'],
                    'amount' => $amount,
                    'amount_usd' => $orderData['amount_usd'],
                    'approval_url' => $orderData['approval_url'],
                    'client_id' => $orderData['client_id'],
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            \Log::error("[WalletController] ❌ CREATE NATIVE PAYPAL ORDER FAILED");
            \Log::error("[WalletController] ❌ Error: {$e->getMessage()}");
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'ordre PayPal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Capture un paiement PayPal natif après approbation de l'utilisateur
     *
     * POST /api/wallet/paypal/capture-native-order
     */
    public function captureNativePayPalOrder(Request $request)
    {
        \Log::info("╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("║ [WalletController] 💰 CAPTURING NATIVE PAYPAL ORDER               ║");
        \Log::info("╚════════════════════════════════════════════════════════════════════╝");

        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|integer',
            'order_id' => 'required|string',
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
            $paymentId = $request->payment_id;
            $orderId = $request->order_id;

            \Log::info("[WalletController] 📝 Request details", [
                'user_id' => $user->id,
                'payment_id' => $paymentId,
                'order_id' => $orderId,
            ]);

            // Récupérer le paiement
            $payment = \App\Models\Payment::where('id', $paymentId)
                ->where('user_id', $user->id)
                ->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé',
                ], 404);
            }

            // Vérifier que le paiement n'est pas déjà complété
            if ($payment->status === 'completed') {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement déjà complété',
                    'data' => [
                        'payment_id' => $payment->id,
                        'new_balance' => $user->wallet_balance,
                    ],
                ]);
            }

            // Capturer le paiement via PayPalService
            $paypalService = app(\App\Services\Payment\PayPalService::class);
            $captureResult = $paypalService->captureNativeOrder($orderId, $payment);

            if ($captureResult['success']) {
                // Créditer le wallet
                $this->completeWalletRecharge($payment);

                \Log::info("[WalletController] ✅ Native PayPal order captured and wallet credited", [
                    'payment_id' => $payment->id,
                    'new_balance' => $user->wallet_balance,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement effectué avec succès',
                    'data' => [
                        'payment_id' => $payment->id,
                        'amount' => $payment->amount,
                        'new_balance' => $user->wallet_balance,
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $captureResult['message'] ?? 'Échec de la capture du paiement',
            ], 400);

        } catch (\Exception $e) {
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            \Log::error("[WalletController] ❌ CAPTURE NATIVE PAYPAL ORDER FAILED");
            \Log::error("[WalletController] ❌ Error: {$e->getMessage()}");
            \Log::error("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la capture du paiement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Complète une recharge de wallet (crédite le solde de l'utilisateur)
     */
    private function completeWalletRecharge(\App\Models\Payment $payment)
    {
        \Log::info("[WalletController] 💰 Completing wallet recharge", [
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

        // Déterminer le provider basé sur le payment_method
        $provider = 'freemopay'; // Par défaut
        if (in_array(strtolower($payment->payment_method), ['paypal', 'paypal_native'])) {
            $provider = 'paypal';
        }

        \Log::info("[WalletController] 💳 Crediting wallet", [
            'payment_method' => $payment->payment_method,
            'determined_provider' => $provider,
            'amount' => $payment->amount,
        ]);

        $this->walletService->credit(
            $user,
            $payment->amount,
            $payment,
            $description,
            ['payment_id' => $payment->id],
            $provider
        );

        \Log::info("[WalletController] ✅ Wallet recharged successfully", [
            'user_id' => $user->id,
            'new_balance' => $user->wallet_balance,
        ]);

        // Envoyer notification FCM pour recharge
        $this->sendWalletRechargeNotification($payment, $user);

        // Traiter la commission de parrainage si applicable
        $this->referralCommissionService->processReferralCommission($user, $payment);
    }

    /**
     * Envoie une notification FCM de succès de recharge wallet
     */
    protected function sendWalletRechargeNotification(\App\Models\Payment $payment, \App\Models\User $user): void
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            $title = "Recharge effectuée";
            $body = "Votre wallet a été crédité de " . number_format($payment->amount, 0, ',', ' ') . " FCFA avec succès.";

            // Créer la notification avec les champs requis (comme les annonces)
            $notification = \App\Models\Notification::create([
                'type' => 'wallet_recharge_success',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'new_balance' => $user->wallet_balance,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'wallet_recharge_success',
                        'payment_id' => $payment->id,
                        'notification_id' => $notification->id,
                    ],
                ]);

            \Log::info("[WalletController] ✅ FCM notification sent for wallet recharge", [
                'user_id' => $user->id,
                'payment_id' => $payment->id,
            ]);

        } catch (\Exception $e) {
            \Log::error("[WalletController] ❌ Failed to send FCM notification for recharge: " . $e->getMessage());
        }
    }

    // ============================================
    // MÉTHODES DE RETRAIT WALLET
    // ============================================

    /**
     * Récupère le solde disponible pour retrait
     *
     * NOTE: Maintenant il y a DEUX wallets séparés (freemopay et paypal)
     * Chaque wallet gère ses propres recharges et retraits
     *
     * GET /api/wallet/withdrawal-balances
     */
    public function getWithdrawalBalances(Request $request)
    {
        try {
            $user = $request->user();

            // Récupérer les soldes séparés
            $freemopayBalance = $user->freemopay_wallet_balance ?? 0;
            $paypalBalance = $user->paypal_wallet_balance ?? 0;
            $totalBalance = $freemopayBalance + $paypalBalance;

            \Log::info('[WalletController] Withdrawal balances calculated', [
                'user_id' => $user->id,
                'freemopay_balance' => $freemopayBalance,
                'paypal_balance' => $paypalBalance,
                'total_balance' => $totalBalance,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'freemopay_balance' => max(0, $freemopayBalance),
                    'paypal_balance' => max(0, $paypalBalance),
                    'total_balance' => max(0, $totalBalance),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('[WalletController] Error getting withdrawal balances: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des soldes',
            ], 500);
        }
    }

    /**
     * Initie un retrait FreeMoPay depuis le wallet
     *
     * POST /api/wallet/withdraw/freemopay
     */
    public function initiateFreeMoPayWithdrawal(Request $request)
    {
        \Log::info("[WalletController] ╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("[WalletController] ║ [FreeMoPay Withdrawal] DEMANDE DE RETRAIT                         ║");
        \Log::info("[WalletController] ╚════════════════════════════════════════════════════════════════════╝");

        $user = $request->user();

        // Validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
            'payment_method' => 'required|in:om,momo',
            'phone' => 'required|string',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            \Log::warning("[WalletController] ❌ Validation failed", $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');
        $phone = $request->input('phone');
        $notes = $request->input('notes');

        // Vérifier le solde FreeMoPay wallet disponible
        $availableBalance = $user->freemopay_wallet_balance ?? 0;

        if ($amount > $availableBalance) {
            \Log::warning("[WalletController] ❌ Insufficient FreeMoPay wallet balance", [
                'available' => $availableBalance,
                'requested_amount' => $amount,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Solde FreeMoPay insuffisant. Disponible: ' . number_format($availableBalance, 0, ',', ' ') . ' FCFA',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Normaliser le numéro de téléphone
            $disbursementService = app(\App\Services\Payment\FreeMoPayDisbursementService::class);
            $normalizedPhone = $disbursementService->normalizePhoneNumber($phone);

            // Créer l'enregistrement de retrait
            $withdrawal = PlatformWithdrawal::create([
                'user_id' => $user->id,
                'admin_id' => null, // Retrait utilisateur
                'amount_requested' => $amount,
                'commission_rate' => 0,
                'commission_amount' => 0,
                'amount_sent' => $amount,
                'currency' => 'XAF',
                'provider' => 'freemopay',
                'payment_method' => $paymentMethod,
                'payment_account' => $normalizedPhone,
                'payment_account_name' => $user->name,
                'status' => 'pending',
                'transaction_reference' => $this->generateTransactionReference(),
                'admin_notes' => $notes,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            \Log::info("[WalletController] ✅ FreeMoPay withdrawal record created", [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount' => $amount,
                'method' => $paymentMethod,
                'phone' => substr($normalizedPhone, 0, 6) . '***',
            ]);

            // Créer une transaction wallet pour ce retrait (type debit, status pending)
            $walletTransaction = \App\Models\WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => -$amount, // Montant négatif pour un retrait
                'balance_before' => $user->freemopay_wallet_balance,
                'balance_after' => $user->freemopay_wallet_balance, // Pas de débit immédiat, on attend la confirmation
                'description' => "Retrait Mobile Money ({$paymentMethod}) - En cours",
                'reference_type' => 'platform_withdrawal',
                'reference_id' => $withdrawal->id,
                'payment_id' => null,
                'provider' => 'freemopay',
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'provider' => 'freemopay',
                    'payment_method' => $paymentMethod,
                    'phone' => substr($normalizedPhone, 0, 6) . '***',
                ],
                'status' => 'pending',
            ]);

            \Log::info("[WalletController] ✅ WalletTransaction created for withdrawal", [
                'transaction_id' => $walletTransaction->id,
                'withdrawal_id' => $withdrawal->id,
            ]);

            // Marquer comme en cours de traitement
            $withdrawal->markAsProcessing();

            // Appeler l'API FreeMoPay pour effectuer le transfert
            $freemoResponse = $this->callFreeMoPayDirectWithdraw(
                $normalizedPhone,
                (int) $amount,
                $withdrawal->transaction_reference,
                $disbursementService
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                throw new \Exception('Pas de référence FreeMoPay dans la réponse');
            }

            $withdrawal->update([
                'freemopay_reference' => $reference,
                'freemopay_response' => $freemoResponse,
            ]);

            // Dispatcher le job de polling asynchrone
            ProcessWithdrawalPolling::dispatch($withdrawal, $reference);

            \Log::info("[WalletController] ✅ Withdrawal polling job dispatched", [
                'withdrawal_id' => $withdrawal->id,
                'reference' => $reference,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Retrait en cours de traitement. Vous recevrez une notification une fois terminé.',
                'data' => [
                    'withdrawal_id' => $withdrawal->id,
                    'transaction_reference' => $withdrawal->transaction_reference,
                    'freemopay_reference' => $reference,
                    'amount' => $withdrawal->amount_requested,
                    'status' => 'processing',
                    'message' => 'Le retrait est en cours. Vous recevrez une notification push dès qu\'il sera complété (environ 1-2 minutes).',
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("[WalletController] ❌ FreeMoPay withdrawal error: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initie un retrait PayPal Payout depuis le wallet
     *
     * POST /api/wallet/withdraw/paypal
     */
    public function initiatePayPalWithdrawal(Request $request)
    {
        \Log::info("[WalletController] ╔════════════════════════════════════════════════════════════════════╗");
        \Log::info("[WalletController] ║ [PayPal Withdrawal] DEMANDE DE RETRAIT                            ║");
        \Log::info("[WalletController] ╚════════════════════════════════════════════════════════════════════╝");

        $user = $request->user();

        // Validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'paypal_email' => 'required|email',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            \Log::warning("[WalletController] ❌ Validation failed", $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors(),
            ], 422);
        }

        $amountUsd = $request->input('amount');
        $paypalEmail = $request->input('paypal_email');
        $notes = $request->input('notes');

        // Convertir le montant USD en XAF (taux approximatif)
        $exchangeRate = 600; // 1 USD = 600 XAF (à ajuster selon le taux réel)
        $amountXaf = $amountUsd * $exchangeRate;

        // Vérifier le solde PayPal wallet disponible
        $availableBalance = $user->paypal_wallet_balance ?? 0;

        if ($amountXaf > $availableBalance) {
            \Log::warning("[WalletController] ❌ Insufficient PayPal wallet balance", [
                'available_xaf' => $availableBalance,
                'requested_xaf' => $amountXaf,
                'requested_usd' => $amountUsd,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Solde PayPal insuffisant. Disponible: ' . number_format($availableBalance, 0, ',', ' ') . ' FCFA (~' . number_format($availableBalance / $exchangeRate, 2) . ' USD)',
            ], 400);
        }

        try {
            DB::beginTransaction();

            $payoutService = app(\App\Services\Payment\PayPalPayoutService::class);

            // Valider l'email PayPal
            if (!$payoutService->validateEmail($paypalEmail)) {
                throw new \Exception('Adresse email PayPal invalide');
            }

            // Créer l'enregistrement de retrait
            $withdrawal = PlatformWithdrawal::create([
                'user_id' => $user->id,
                'admin_id' => null, // Retrait utilisateur
                'amount_requested' => $amountXaf,
                'commission_rate' => 0,
                'commission_amount' => 0,
                'amount_sent' => $amountUsd, // Montant en USD
                'currency' => 'USD',
                'provider' => 'paypal',
                'payment_method' => 'paypal',
                'payment_account' => $paypalEmail,
                'payment_account_name' => $user->name,
                'status' => 'pending',
                'transaction_reference' => $this->generateTransactionReference(),
                'admin_notes' => $notes,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            \Log::info("[WalletController] ✅ PayPal withdrawal record created", [
                'withdrawal_id' => $withdrawal->id,
                'user_id' => $user->id,
                'amount_usd' => $amountUsd,
                'amount_xaf' => $amountXaf,
                'email' => substr($paypalEmail, 0, 3) . '***',
            ]);

            // Créer une transaction wallet pour ce retrait (type debit, status pending)
            $walletTransaction = \App\Models\WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => -$amountXaf, // Montant négatif en XAF
                'balance_before' => $user->paypal_wallet_balance,
                'balance_after' => $user->paypal_wallet_balance, // Pas de débit immédiat, on attend la confirmation
                'description' => "Retrait PayPal (\${$amountUsd} USD) - En cours",
                'reference_type' => 'platform_withdrawal',
                'reference_id' => $withdrawal->id,
                'payment_id' => null,
                'provider' => 'paypal',
                'metadata' => [
                    'withdrawal_id' => $withdrawal->id,
                    'provider' => 'paypal',
                    'amount_usd' => $amountUsd,
                    'amount_xaf' => $amountXaf,
                    'paypal_email' => substr($paypalEmail, 0, 3) . '***',
                ],
                'status' => 'pending',
            ]);

            \Log::info("[WalletController] ✅ WalletTransaction created for PayPal withdrawal", [
                'transaction_id' => $walletTransaction->id,
                'withdrawal_id' => $withdrawal->id,
            ]);

            // Marquer comme en cours de traitement
            $withdrawal->markAsProcessing();

            // Créer le payout PayPal
            $payoutResult = $payoutService->createPayout($withdrawal);

            if (!$payoutResult['success']) {
                throw new \Exception('Échec de la création du payout PayPal');
            }

            $batchId = $payoutResult['batch_id'];

            $withdrawal->update([
                'paypal_batch_id' => $batchId,
                'paypal_response' => $payoutResult['response'],
            ]);

            \Log::info("[WalletController] ✅ PayPal payout created", [
                'batch_id' => $batchId,
            ]);

            // Dispatcher le job de polling asynchrone
            ProcessWithdrawalPolling::dispatch($withdrawal, $batchId);

            \Log::info("[WalletController] ✅ PayPal withdrawal polling job dispatched", [
                'withdrawal_id' => $withdrawal->id,
                'batch_id' => $batchId,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Retrait PayPal en cours de traitement. Vous recevrez une notification une fois terminé.',
                'data' => [
                    'withdrawal_id' => $withdrawal->id,
                    'transaction_reference' => $withdrawal->transaction_reference,
                    'paypal_batch_id' => $batchId,
                    'amount_usd' => $withdrawal->amount_sent,
                    'amount_xaf' => $withdrawal->amount_requested,
                    'status' => 'processing',
                    'message' => 'Le retrait PayPal est en cours. Vous recevrez une notification push dès qu\'il sera complété (environ 2-3 minutes).',
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("[WalletController] ❌ PayPal withdrawal error: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifie le statut d'un retrait
     *
     * GET /api/wallet/withdrawal-status/{withdrawalId}
     */
    public function checkWithdrawalStatus(Request $request, $withdrawalId)
    {
        try {
            $user = $request->user();

            $withdrawal = PlatformWithdrawal::where('id', $withdrawalId)
                ->where('user_id', $user->id)
                ->first();

            if (!$withdrawal) {
                return response()->json([
                    'success' => false,
                    'message' => 'Retrait non trouvé',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'withdrawal_id' => $withdrawal->id,
                    'transaction_reference' => $withdrawal->transaction_reference,
                    'freemopay_reference' => $withdrawal->freemopay_reference,
                    'paypal_batch_id' => $withdrawal->paypal_batch_id,
                    'amount' => $withdrawal->amount_requested,
                    'provider' => $withdrawal->provider,
                    'payment_method' => $withdrawal->payment_method,
                    'payment_account' => substr($withdrawal->payment_account, 0, 6) . '***',
                    'status' => $withdrawal->status,
                    'created_at' => $withdrawal->created_at->toIso8601String(),
                    'completed_at' => $withdrawal->completed_at?->toIso8601String(),
                    'failure_reason' => $withdrawal->failure_reason,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('[WalletController] Error checking withdrawal status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut',
            ], 500);
        }
    }

    /**
     * Récupère l'historique des retraits
     *
     * GET /api/wallet/withdrawals
     */
    public function getWithdrawalHistory(Request $request)
    {
        try {
            $user = $request->user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 20);
            $provider = $request->input('provider');
            $status = $request->input('status');

            $query = PlatformWithdrawal::where('user_id', $user->id)
                ->orderBy('created_at', 'desc');

            if ($provider) {
                $query->where('provider', $provider);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $withdrawals = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => [
                    'data' => $withdrawals->items(),
                    'current_page' => $withdrawals->currentPage(),
                    'last_page' => $withdrawals->lastPage(),
                    'total' => $withdrawals->total(),
                    'per_page' => $withdrawals->perPage(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('[WalletController] Error getting withdrawal history: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'historique',
            ], 500);
        }
    }

    /**
     * Generate transaction reference
     */
    protected function generateTransactionReference(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(\Illuminate\Support\Str::random(4));
        return "WTH-{$timestamp}-{$random}";
    }

    /**
     * Appel API FreeMoPay pour retrait direct
     */
    protected function callFreeMoPayDirectWithdraw(
        string $receiver,
        int $amount,
        string $externalId,
        $disbursementService
    ): array {
        $config = \App\Models\ServiceConfiguration::getFreeMoPayConfig();

        if (!$config || !$config->isConfigured()) {
            throw new \Exception('FreeMoPay n\'est pas configuré');
        }

        $baseUrl = rtrim($config->freemopay_base_url ?? 'https://api-v2.freemopay.com', '/');
        $appKey = $config->freemopay_app_key;
        $secretKey = $config->freemopay_secret_key;
        $callbackUrl = $config->freemopay_callback_url ?? config('app.url') . '/api/webhooks/freemopay';

        if (empty($appKey) || empty($secretKey)) {
            throw new \Exception('Les clés API FreeMoPay sont manquantes');
        }

        $endpoint = "{$baseUrl}/api/v2/payment/direct-withdraw";

        $payload = [
            'receiver' => $receiver,
            'amount' => (string) $amount,
            'externalId' => $externalId,
            'callback' => $callbackUrl,
        ];

        \Log::info("[WalletController] Appel API FreeMoPay Direct Withdraw", [
            'endpoint' => $endpoint,
            'receiver' => substr($receiver, 0, 6) . '***',
            'amount' => $amount,
            'externalId' => $externalId,
        ]);

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($appKey, $secretKey)
            ->timeout(60)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($endpoint, $payload);

        if (!$response->successful()) {
            $errorBody = $response->json() ?? ['message' => $response->body()];
            $rawMessage = $errorBody['message'] ?? "Erreur HTTP {$response->status()}";
            $errorMessage = is_array($rawMessage) ? implode(', ', $rawMessage) : $rawMessage;
            throw new \Exception("Erreur FreeMoPay: {$errorMessage}");
        }

        return $response->json();
    }

    /**
     * Attendre la confirmation du retrait utilisateur avec polling
     */
    protected function waitForUserWithdrawalCompletion(
        PlatformWithdrawal $withdrawal,
        string $reference,
        $disbursementService
    ): PlatformWithdrawal {
        \Log::info("[WalletController] Démarrage polling pour référence: {$reference}");

        $startTime = time();
        $attempts = 0;
        $pollingInterval = 3;
        $pollingTimeout = 90;
        $maxPollingAttempts = 30;

        $successStatuses = ['SUCCESS', 'SUCCESSFUL', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'ERROR', 'REJECTED', 'CANCELLED', 'CANCELED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            if ($elapsed >= $pollingTimeout || $attempts > $maxPollingAttempts) {
                \Log::warning("[WalletController] Timeout polling - Référence: {$reference}");
                return $withdrawal->fresh();
            }

            try {
                $statusResponse = $disbursementService->checkWithdrawalStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                \Log::info("[WalletController] Poll {$attempts}: Status = {$currentStatus}");

                if (in_array($currentStatus, $successStatuses)) {
                    \Log::info("[WalletController] Transfert RÉUSSI - Référence: {$reference}");
                    $withdrawal->markAsCompleted($reference, $statusResponse);

                    // Envoyer notification FCM
                    $this->sendWithdrawalSuccessNotification($withdrawal);

                    return $withdrawal->fresh();
                }

                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Transfert échoué';
                    \Log::error("[WalletController] Transfert ÉCHOUÉ - Référence: {$reference}");
                    $withdrawal->markAsFailed('disbursement_failed', $message);
                    throw new \Exception("Transfert échoué: {$message}");
                }

                sleep($pollingInterval);

            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Transfert échoué:')) {
                    throw $e;
                }
                \Log::warning("[WalletController] Erreur polling: " . $e->getMessage());
                sleep($pollingInterval);
            }
        }
    }

    /**
     * Attendre la confirmation du payout PayPal avec polling
     */
    protected function waitForPayPalPayoutCompletion(
        PlatformWithdrawal $withdrawal,
        string $batchId,
        $payoutService
    ): PlatformWithdrawal {
        \Log::info("[WalletController] Démarrage polling PayPal pour batch: {$batchId}");

        $startTime = time();
        $attempts = 0;
        $pollingInterval = 5; // PayPal peut être plus lent
        $pollingTimeout = 120; // 2 minutes
        $maxPollingAttempts = 24; // 24 * 5s = 120s

        $successStatuses = ['SUCCESS', 'COMPLETE', 'COMPLETED'];
        $failedStatuses = ['FAILED', 'FAILURE', 'DENIED', 'BLOCKED', 'REFUNDED', 'RETURNED', 'REVERSED', 'UNCLAIMED'];

        while (true) {
            $attempts++;
            $elapsed = time() - $startTime;

            if ($elapsed >= $pollingTimeout || $attempts > $maxPollingAttempts) {
                \Log::warning("[WalletController] Timeout polling PayPal - Batch: {$batchId}");
                return $withdrawal->fresh();
            }

            try {
                $statusResponse = $payoutService->checkPayoutStatus($batchId);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                \Log::info("[WalletController] Poll PayPal {$attempts}: Status = {$currentStatus}");

                if (in_array($currentStatus, $successStatuses)) {
                    \Log::info("[WalletController] Payout PayPal RÉUSSI - Batch: {$batchId}");
                    $withdrawal->markAsCompleted($batchId, $statusResponse['response']);

                    // Envoyer notification FCM
                    $this->sendWithdrawalSuccessNotification($withdrawal);

                    return $withdrawal->fresh();
                }

                if (in_array($currentStatus, $failedStatuses)) {
                    $message = 'Payout échoué: ' . $currentStatus;
                    \Log::error("[WalletController] Payout PayPal ÉCHOUÉ - Batch: {$batchId}");
                    $withdrawal->markAsFailed('payout_failed', $message);
                    throw new \Exception($message);
                }

                sleep($pollingInterval);

            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Payout échoué:')) {
                    throw $e;
                }
                \Log::warning("[WalletController] Erreur polling PayPal: " . $e->getMessage());
                sleep($pollingInterval);
            }
        }
    }

    /**
     * Envoie une notification FCM de succès de retrait
     */
    protected function sendWithdrawalSuccessNotification(PlatformWithdrawal $withdrawal): void
    {
        try {
            if (!$withdrawal->user_id) {
                return; // Retrait admin, pas de notification
            }

            $user = \App\Models\User::find($withdrawal->user_id);

            if (!$user || !$user->fcm_token) {
                return;
            }

            $title = "Retrait effectué";

            // Message différent selon le provider
            if ($withdrawal->provider === 'paypal') {
                $body = "Votre retrait PayPal de " . number_format($withdrawal->amount_sent, 2) . " USD (" . number_format($withdrawal->amount_requested, 0, ',', ' ') . " FCFA) a été effectué avec succès.";
            } else {
                $body = "Votre retrait de " . number_format($withdrawal->amount_requested, 0, ',', ' ') . " FCFA a été effectué avec succès.";
            }

            // ✅ Créer la notification avec la structure correcte (notifiable_type + notifiable_id)
            $notification = \App\Models\Notification::create([
                'type' => 'wallet_withdrawal_success',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $withdrawal->amount_requested,
                    'provider' => $withdrawal->provider,
                    'reference' => $withdrawal->freemopay_reference ?? $withdrawal->paypal_batch_id,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'wallet_withdrawal_success',
                        'withdrawal_id' => $withdrawal->id,
                        'notification_id' => $notification->id,
                    ],
                ]);

            \Log::info("[WalletController] ✅ FCM notification sent for withdrawal success", [
                'user_id' => $user->id,
                'withdrawal_id' => $withdrawal->id,
                'provider' => $withdrawal->provider,
            ]);

        } catch (\Exception $e) {
            \Log::error("[WalletController] ❌ Failed to send FCM notification: " . $e->getMessage());
        }
    }

    /**
     * Envoie une notification FCM pour un achat via wallet
     */
    protected function sendWalletPurchaseNotification(\App\Models\User $user, string $serviceName, float $amount, string $provider): void
    {
        try {
            if (!$user->fcm_token) {
                return;
            }

            $providerName = $provider === 'paypal' ? 'PayPal' : 'FreeMoPay';
            $title = "Achat effectué";
            $body = "Votre achat de {$serviceName} pour " . number_format($amount, 0, ',', ' ') . " FCFA via wallet {$providerName} a été effectué avec succès.";

            // Créer la notification avec la structure correcte
            $notification = \App\Models\Notification::create([
                'type' => 'wallet_purchase',
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'service_name' => $serviceName,
                    'amount' => $amount,
                    'provider' => $provider,
                    'new_balance' => $provider === 'paypal' ? $user->paypal_wallet_balance : $user->freemopay_wallet_balance,
                ],
            ]);

            // Envoyer via FCM
            \Illuminate\Support\Facades\Http::withToken(config('services.fcm.server_key'))
                ->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $user->fcm_token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                    ],
                    'data' => [
                        'type' => 'wallet_purchase',
                        'service_name' => $serviceName,
                        'notification_id' => $notification->id,
                    ],
                ]);

            \Log::info("[WalletController] ✅ FCM notification sent for wallet purchase", [
                'user_id' => $user->id,
                'service_name' => $serviceName,
                'amount' => $amount,
                'provider' => $provider,
            ]);

        } catch (\Exception $e) {
            \Log::error("[WalletController] ❌ Failed to send FCM notification for purchase: " . $e->getMessage());
        }
    }
}
