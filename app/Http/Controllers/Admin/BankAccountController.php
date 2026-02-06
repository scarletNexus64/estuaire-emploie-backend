<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PlatformWithdrawal;
use App\Services\Payment\FreeMoPayDisbursementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BankAccountController extends Controller
{
    protected FreeMoPayDisbursementService $disbursementService;

    public function __construct(FreeMoPayDisbursementService $disbursementService)
    {
        $this->disbursementService = $disbursementService;
    }

    /**
     * Display bank account dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is admin
        if (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        // Calculate total revenue from PLATFORM EARNINGS ONLY
        // Exclut les recharges wallet (argent des utilisateurs)
        // Inclut: subscriptions, commissions, et autres revenus
        $totalRevenue = Payment::where('status', 'completed')
            ->where(function($query) {
                $query->whereNotIn('payment_type', ['wallet_recharge'])
                      ->orWhereNull('payment_type');
            })
            ->sum('amount');

        // Calculate total withdrawn by platform
        $totalWithdrawn = PlatformWithdrawal::where('status', 'completed')
            ->sum('amount_requested');

        // Calculate available balance (only credits - withdrawals)
        $availableBalance = $totalRevenue - $totalWithdrawn;

        // Get recent withdrawals
        $recentWithdrawals = PlatformWithdrawal::with('admin')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($withdrawal) {
                return [
                    'id' => $withdrawal->id,
                    'amount' => (float) $withdrawal->amount_requested,
                    'amount_sent' => (float) $withdrawal->amount_sent,
                    'status' => $withdrawal->status,
                    'payment_method' => $withdrawal->payment_method,
                    'payment_account' => substr($withdrawal->payment_account, 0, 6) . '***',
                    'admin_name' => $withdrawal->admin?->name ?? 'N/A',
                    'created_at' => $withdrawal->created_at->format('d/m/Y H:i'),
                ];
            });

        // Get withdrawal statistics
        $stats = [
            'total_revenue' => (float) $totalRevenue,
            'total_withdrawn' => (float) $totalWithdrawn,
            'available_balance' => (float) $availableBalance,
            'pending_withdrawals' => PlatformWithdrawal::where('status', 'pending')->count(),
            'completed_withdrawals' => PlatformWithdrawal::where('status', 'completed')->count(),
            'failed_withdrawals' => PlatformWithdrawal::where('status', 'failed')->count(),
        ];

        // Revenue breakdown by type
        $revenueBreakdown = Payment::where('status', 'completed')
            ->selectRaw('payment_type, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $type = $item->payment_type ?: 'other';
                return [$type => [
                    'total' => (float) $item->total,
                    'count' => $item->count,
                ]];
            });

        // Last 7 days revenue trend
        $revenueTrend = Payment::where('status', 'completed')
            ->where(function($query) {
                $query->whereNotIn('payment_type', ['wallet_recharge'])
                      ->orWhereNull('payment_type');
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Monthly statistics (last 6 months)
        $monthlyStats = Payment::where('status', 'completed')
            ->where(function($query) {
                $query->whereNotIn('payment_type', ['wallet_recharge'])
                      ->orWhereNull('payment_type');
            })
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.bank-account.index', compact('stats', 'recentWithdrawals', 'revenueBreakdown', 'revenueTrend', 'monthlyStats'));
    }

    /**
     * Verify PIN code
     */
    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string',
        ]);

        $correctPin = env('BANK_ACCOUNT_PIN', '1234');

        if ($request->pin !== $correctPin) {
            return response()->json([
                'success' => false,
                'message' => 'Code PIN incorrect',
            ], 401);
        }

        // Store PIN verification in session
        session(['bank_pin_verified' => true, 'bank_pin_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Code PIN vérifié avec succès',
        ]);
    }

    /**
     * Display withdrawal form
     */
    public function showWithdrawalForm()
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        // Check if PIN is verified and still valid (valid for 30 minutes)
        $pinVerified = session('bank_pin_verified', false);
        $pinVerifiedAt = session('bank_pin_verified_at');

        if (!$pinVerified || !$pinVerifiedAt || now()->diffInMinutes($pinVerifiedAt) > 30) {
            return redirect()->route('admin.bank-account.index')
                ->withErrors(['pin' => 'Veuillez vérifier votre code PIN']);
        }

        // Calculate available balance (platform revenue only, excludes wallet recharges)
        $totalRevenue = Payment::where('status', 'completed')
            ->where(function($query) {
                $query->whereNotIn('payment_type', ['wallet_recharge'])
                      ->orWhereNull('payment_type');
            })
            ->sum('amount');

        $totalWithdrawn = PlatformWithdrawal::where('status', 'completed')
            ->sum('amount_requested');

        $availableBalance = $totalRevenue - $totalWithdrawn;

        return view('admin.bank-account.withdrawal-form', [
            'available_balance' => (float) $availableBalance,
            'min_amount' => 50,
        ]);
    }

    /**
     * Initiate a withdrawal
     */
    public function initiateWithdrawal(Request $request)
    {
        Log::info("[Platform Withdrawal] Début de la requête de retrait");
        Log::info("[Platform Withdrawal] Request data: " . json_encode($request->all()));

        $user = Auth::user();

        if (!$user->isAdmin()) {
            Log::warning("[Platform Withdrawal] Accès refusé - utilisateur non admin: {$user->id}");
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé',
            ], 403);
        }

        // Check if PIN is verified
        $pinVerified = session('bank_pin_verified', false);
        $pinVerifiedAt = session('bank_pin_verified_at');

        if (!$pinVerified || !$pinVerifiedAt || now()->diffInMinutes($pinVerifiedAt) > 30) {
            Log::warning("[Platform Withdrawal] PIN non vérifié ou expiré");
            return response()->json([
                'success' => false,
                'message' => 'Session expirée. Veuillez vérifier votre code PIN',
            ], 401);
        }

        try {
            $request->validate([
                'amount' => 'required|numeric|min:50',
                'payment_method' => 'required|in:om,momo',
                'phone' => 'required|string',
                'notes' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("[Platform Withdrawal] Validation failed: " . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation échouée: ' . json_encode($e->errors()),
            ], 422);
        }

        // Calculate available balance (platform revenue only, excludes wallet recharges)
        $totalRevenue = Payment::where('status', 'completed')
            ->where(function($query) {
                $query->whereNotIn('payment_type', ['wallet_recharge'])
                      ->orWhereNull('payment_type');
            })
            ->sum('amount');

        $totalWithdrawn = PlatformWithdrawal::where('status', 'completed')
            ->sum('amount_requested');

        $availableBalance = $totalRevenue - $totalWithdrawn;

        if ($request->amount > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Solde insuffisant. Disponible: ' . number_format($availableBalance, 0, ',', ' ') . ' XAF',
            ], 400);
        }

        // Normalize phone number
        try {
            $phone = $this->disbursementService->normalizePhoneNumber($request->phone);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }

        // Calculate commission (no commission for platform withdrawals)
        $commissionRate = 0;
        $commissionAmount = 0;
        $amountToSend = $request->amount;

        try {
            DB::beginTransaction();

            // Create withdrawal record
            $withdrawal = PlatformWithdrawal::create([
                'admin_id' => $user->id,
                'amount_requested' => $request->amount,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'amount_sent' => $amountToSend,
                'currency' => 'XAF',
                'payment_method' => $request->payment_method,
                'payment_account' => $phone,
                'payment_account_name' => $user->name,
                'status' => 'pending',
                'transaction_reference' => $this->generateTransactionReference(),
                'admin_notes' => $request->notes,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Log::info("[Platform Withdrawal] Retrait créé - ID: {$withdrawal->id}, Admin: {$user->id}, Amount: {$request->amount}");

            // Process withdrawal with FreemoPay
            $processedWithdrawal = $this->processPlatformWithdrawal($withdrawal);

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $processedWithdrawal->id,
                'reference' => $processedWithdrawal->freemopay_reference,
                'message' => 'Retrait initié avec succès',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[Platform Withdrawal] Erreur: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process platform withdrawal using FreemoPay
     */
    protected function processPlatformWithdrawal(PlatformWithdrawal $withdrawal): PlatformWithdrawal
    {
        Log::info("[Platform Withdrawal] Traitement du retrait ID: {$withdrawal->id}");

        try {
            // Mark as processing
            $withdrawal->markAsProcessing();

            // Call FreeMoPay API to initiate withdrawal
            // Get callback URL from config or use default
            $config = \App\Models\ServiceConfiguration::getFreeMoPayConfig();
            $callbackUrl = $config->freemopay_callback_url ?? config('app.url') . '/api/webhooks/freemopay';

            Log::info("[Platform Withdrawal] Using callback URL: {$callbackUrl}");

            $freemoResponse = $this->callDirectWithdrawAPI(
                $withdrawal->payment_account,
                (int) $withdrawal->amount_sent,
                $withdrawal->transaction_reference,
                $callbackUrl
            );

            $reference = $freemoResponse['reference'] ?? null;

            if (!$reference) {
                Log::error("[Platform Withdrawal] Pas de référence dans la réponse: " . json_encode($freemoResponse));
                $withdrawal->markAsFailed('no_reference', 'Pas de référence FreeMoPay dans la réponse');
                throw new \Exception('Erreur lors de l\'initialisation du transfert');
            }

            $withdrawal->update([
                'freemopay_reference' => $reference,
                'freemopay_response' => $freemoResponse,
            ]);

            Log::info("[Platform Withdrawal] Transfert initié - Référence: {$reference}");

            // Wait for disbursement completion (polling)
            $finalWithdrawal = $this->waitForDisbursementCompletion($withdrawal, $reference);

            return $finalWithdrawal;

        } catch (\Exception $e) {
            if ($withdrawal->status !== 'failed') {
                $withdrawal->markAsFailed('api_error', $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Call FreeMoPay API to initiate withdrawal
     */
    protected function callDirectWithdrawAPI(string $receiver, int $amount, string $externalId, string $callback): array
    {
        // Get FreeMoPay configuration from ServiceConfiguration
        $config = \App\Models\ServiceConfiguration::getFreeMoPayConfig();

        if (!$config || !$config->isConfigured()) {
            Log::error("[Platform Withdrawal] FreeMoPay non configuré");
            throw new \Exception('FreeMoPay n\'est pas configuré. Veuillez configurer les clés API dans les paramètres.');
        }

        $baseUrl = rtrim($config->freemopay_base_url ?? 'https://api-v2.freemopay.com', '/');
        $appKey = $config->freemopay_app_key;
        $secretKey = $config->freemopay_secret_key;

        // Validate credentials
        if (empty($appKey) || empty($secretKey)) {
            Log::error("[Platform Withdrawal] Clés API FreeMoPay manquantes");
            throw new \Exception('Les clés API FreeMoPay sont manquantes. Veuillez les configurer dans les paramètres.');
        }

        $endpoint = "{$baseUrl}/api/v2/payment/direct-withdraw";

        $payload = [
            'receiver' => $receiver,
            'amount' => (string) $amount,
            'externalId' => $externalId,
            'callback' => $callback,
        ];

        Log::info("[Platform Withdrawal] Appel API FreeMoPay v2 Direct Withdraw");
        Log::info("[Platform Withdrawal] URL: {$endpoint}");
        Log::info("[Platform Withdrawal] Payload: " . json_encode([
            'receiver' => substr($receiver, 0, 6) . '***',
            'amount' => $amount,
            'externalId' => $externalId,
        ]));

        $response = \Illuminate\Support\Facades\Http::withBasicAuth($appKey, $secretKey)
            ->timeout(60)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post($endpoint, $payload);

        Log::info("[Platform Withdrawal] HTTP Status: {$response->status()}");

        if (!$response->successful()) {
            $errorBody = $response->json() ?? ['message' => $response->body()];
            $rawMessage = $errorBody['message'] ?? "Erreur HTTP {$response->status()}";
            $errorMessage = is_array($rawMessage) ? implode(', ', $rawMessage) : $rawMessage;
            Log::error("[Platform Withdrawal] Erreur API: {$errorMessage}");
            throw new \Exception("Erreur FreeMoPay: {$errorMessage}");
        }

        return $response->json();
    }

    /**
     * Wait for disbursement completion
     */
    protected function waitForDisbursementCompletion(PlatformWithdrawal $withdrawal, string $reference): PlatformWithdrawal
    {
        Log::info("[Platform Withdrawal] Démarrage polling pour référence: {$reference}");

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

            if ($elapsed >= $pollingTimeout) {
                Log::warning("[Platform Withdrawal] Timeout polling après {$elapsed}s - Référence: {$reference}");
                return $withdrawal->fresh();
            }

            if ($attempts > $maxPollingAttempts) {
                Log::warning("[Platform Withdrawal] Max tentatives ({$maxPollingAttempts}) atteintes - Référence: {$reference}");
                break;
            }

            try {
                $statusResponse = $this->disbursementService->checkWithdrawalStatus($reference);
                $currentStatus = strtoupper($statusResponse['status'] ?? '');

                Log::info("[Platform Withdrawal] Poll {$attempts}: Status = {$currentStatus}");

                if (in_array($currentStatus, $successStatuses)) {
                    Log::info("[Platform Withdrawal] Transfert RÉUSSI - Référence: {$reference}");
                    $withdrawal->markAsCompleted($reference, $statusResponse);
                    return $withdrawal->fresh();
                }

                if (in_array($currentStatus, $failedStatuses)) {
                    $message = $statusResponse['message'] ?? 'Transfert échoué ou annulé';
                    Log::error("[Platform Withdrawal] Transfert ÉCHOUÉ - Référence: {$reference}, Raison: {$message}");
                    $withdrawal->markAsFailed('disbursement_failed', $message);
                    throw new \Exception("Transfert échoué: {$message}");
                }

                Log::debug("[Platform Withdrawal] Transfert en attente, pause de {$pollingInterval}s...");
                sleep($pollingInterval);

            } catch (\Exception $e) {
                if (str_starts_with($e->getMessage(), 'Transfert échoué:')) {
                    throw $e;
                }

                Log::warning("[Platform Withdrawal] Erreur polling (tentative {$attempts}): " . $e->getMessage());
                sleep($pollingInterval);
            }
        }

        return $withdrawal->fresh();
    }

    /**
     * Check withdrawal status
     */
    public function checkWithdrawalStatus(Request $request, int $id)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé',
            ], 403);
        }

        $withdrawal = PlatformWithdrawal::where('id', $id)->firstOrFail();

        return response()->json([
            'success' => true,
            'status' => $withdrawal->status,
            'freemopay_reference' => $withdrawal->freemopay_reference,
            'completed_at' => $withdrawal->completed_at?->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Get withdrawal history
     */
    public function history(Request $request)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $query = PlatformWithdrawal::with('admin')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->paginate(20)->through(function ($withdrawal) {
            return [
                'id' => $withdrawal->id,
                'amount' => (float) $withdrawal->amount_requested,
                'amount_sent' => (float) $withdrawal->amount_sent,
                'status' => $withdrawal->status,
                'payment_method' => $withdrawal->payment_method,
                'payment_account' => substr($withdrawal->payment_account, 0, 6) . '***',
                'admin_name' => $withdrawal->admin?->name ?? 'N/A',
                'freemopay_reference' => $withdrawal->freemopay_reference,
                'admin_notes' => $withdrawal->admin_notes,
                'created_at' => $withdrawal->created_at->format('d/m/Y H:i'),
                'completed_at' => $withdrawal->completed_at?->format('d/m/Y H:i'),
            ];
        });

        return view('admin.bank-account.history', compact('withdrawals'));
    }

    /**
     * Generate transaction reference
     */
    protected function generateTransactionReference(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(4));
        return "PLAT-{$timestamp}-{$random}";
    }
}
