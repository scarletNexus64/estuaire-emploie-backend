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

        $this->walletService->credit(
            $user,
            $payment->amount,
            $payment,
            $description,
            ['payment_id' => $payment->id]
        );

        \Log::info("[WalletController] ✅ Wallet recharged successfully", [
            'user_id' => $user->id,
            'new_balance' => $user->wallet_balance,
        ]);
    }
}
