<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\User;
use App\Services\CurrencyService;
use App\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Dashboard de parrainage côté mobile.
 *
 * Le parrain consulte ici : son code de parrainage, ses filleuls, les
 * commissions gagnées et l'évolution mensuelle. Les montants sont stockés en
 * XAF (devise de base du ledger) et décorés avec la devise d'affichage du user
 * via CurrencyService, exactement comme les prix ailleurs dans l'app.
 *
 * Les commissions s'accumulent sur `users.referral_balance` (XAF). Le parrain
 * les transfère explicitement vers son wallet (KPay/PayPal) via
 * transferToWallet(), ce qui crée une transaction wallet.
 */
class ReferralController extends Controller
{
    protected CurrencyService $currencyService;
    protected WalletService $walletService;

    public function __construct(CurrencyService $currencyService, WalletService $walletService)
    {
        $this->currencyService = $currencyService;
        $this->walletService = $walletService;
    }

    /**
     * Vue d'ensemble du dashboard de parrainage.
     *
     * GET /api/referral/dashboard
     */
    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $currency = $this->currencyService->resolveCurrency($user);

        $referralsCount = $user->referrals()->count();
        // Filleuls "actifs" = ceux qui ont généré au moins une commission.
        $activeReferralsCount = ReferralCommission::where('referrer_id', $user->id)
            ->distinct('referred_id')
            ->count('referred_id');

        $totalEarnedXaf = (float) $user->earnedCommissions()->sum('commission_amount');
        $commissionsCount = $user->earnedCommissions()->count();

        // Gains du mois en cours.
        $thisMonthXaf = (float) $user->earnedCommissions()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('commission_amount');

        return response()->json([
            'success' => true,
            'data' => [
                'referral_code' => $user->referral_code,
                'referral_enabled' => (bool) settings('referral_enabled', false),
                'commission_percentage' => (float) settings('referral_commission_percentage', 5),
                'currency' => $currency,
                'stats' => [
                    'referrals_count' => $referralsCount,
                    'active_referrals_count' => $activeReferralsCount,
                    'commissions_count' => $commissionsCount,
                    'total_earned' => $this->money($totalEarnedXaf, $user),
                    'this_month_earned' => $this->money($thisMonthXaf, $user),
                    // Solde de parrainage disponible, transférable vers le wallet.
                    'available_balance' => $this->money((float) $user->referral_balance, $user),
                ],
                'monthly_earnings' => $this->monthlyEarnings($user),
            ],
        ]);
    }

    /**
     * Liste paginée des filleuls du user, avec le total des commissions
     * qu'ils ont générées pour lui.
     *
     * GET /api/referral/filleuls
     */
    public function filleuls(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = (int) $request->input('per_page', 15);

        $filleuls = $user->referrals()
            ->select(['id', 'name', 'email', 'profile_photo', 'created_at'])
            ->withCount(['generatedCommissions as commissions_count'])
            ->withSum(['generatedCommissions as total_generated'], 'commission_amount')
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $data = $filleuls->getCollection()->map(function (User $filleul) use ($user) {
            $generated = (float) ($filleul->total_generated ?? 0);

            return [
                'id' => $filleul->id,
                'name' => $filleul->name,
                'email' => $filleul->email,
                'profile_photo' => $filleul->profile_photo,
                'joined_at' => optional($filleul->created_at)->toISOString(),
                'commissions_count' => (int) $filleul->commissions_count,
                'is_active' => (int) $filleul->commissions_count > 0,
                'total_generated' => $this->money($generated, $user),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $filleuls->currentPage(),
                'last_page' => $filleuls->lastPage(),
                'per_page' => $filleuls->perPage(),
                'total' => $filleuls->total(),
            ],
        ]);
    }

    /**
     * Historique paginé des commissions gagnées par le parrain.
     *
     * GET /api/referral/commissions
     */
    public function commissions(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = (int) $request->input('per_page', 15);

        $commissions = $user->earnedCommissions()
            ->with(['referred:id,name,profile_photo'])
            ->orderByDesc('created_at')
            ->paginate($perPage);

        $data = $commissions->getCollection()->map(function (ReferralCommission $commission) use ($user) {
            return [
                'id' => $commission->id,
                'referred' => $commission->referred ? [
                    'id' => $commission->referred->id,
                    'name' => $commission->referred->name,
                    'profile_photo' => $commission->referred->profile_photo,
                ] : null,
                'transaction_type' => $commission->transaction_type,
                'transaction_reference' => $commission->transaction_reference,
                'commission_percentage' => (float) $commission->commission_percentage,
                'transaction_amount' => $this->money((float) $commission->transaction_amount, $user),
                'commission_amount' => $this->money((float) $commission->commission_amount, $user),
                'created_at' => optional($commission->created_at)->toISOString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $commissions->currentPage(),
                'last_page' => $commissions->lastPage(),
                'per_page' => $commissions->perPage(),
                'total' => $commissions->total(),
            ],
        ]);
    }

    /**
     * Transfère l'intégralité du solde de parrainage vers le wallet du user.
     *
     * POST /api/referral/transfer-to-wallet
     * Body: { "provider": "freemopay" | "paypal" }
     *
     * Money-critical : opération atomique sous verrou de ligne. Le solde de
     * parrainage (XAF) est crédité sur le wallet du provider choisi (ce qui
     * crée une WalletTransaction), puis remis à zéro. Les commissions non
     * encore transférées sont marquées `transferred_to_wallet_at`.
     */
    public function transferToWallet(Request $request): JsonResponse
    {
        $provider = strtolower((string) $request->input('provider', 'freemopay'));
        // 'kpay' est un alias mobile money → même wallet que freemopay.
        if ($provider === 'kpay') {
            $provider = 'freemopay';
        }

        if (!in_array($provider, ['freemopay', 'paypal'], true)) {
            return response()->json([
                'success' => false,
                'message' => __('referral.transfer.invalid_provider'),
            ], 422);
        }

        $userId = $request->user()->id;

        try {
            $result = DB::transaction(function () use ($userId, $provider) {
                /** @var User $user */
                $user = User::whereKey($userId)->lockForUpdate()->first();

                $balance = (float) $user->referral_balance;
                if ($balance <= 0) {
                    return ['empty' => true, 'user' => $user];
                }

                // Crédite le wallet (crée la WalletTransaction) — solde en XAF.
                $transaction = $this->walletService->credit(
                    $user,
                    $balance,
                    null,
                    'Transfert des gains de parrainage vers le wallet',
                    ['source' => 'referral_balance_transfer'],
                    $provider
                );

                // Vide le solde de parrainage et marque les commissions transférées.
                $user->referral_balance = 0;
                $user->save();

                ReferralCommission::where('referrer_id', $user->id)
                    ->whereNull('transferred_to_wallet_at')
                    ->update(['transferred_to_wallet_at' => now()]);

                return [
                    'empty' => false,
                    'user' => $user->fresh(),
                    'amount' => $balance,
                    'transaction' => $transaction,
                    'provider' => $provider,
                ];
            });

            if ($result['empty']) {
                return response()->json([
                    'success' => false,
                    'message' => __('referral.transfer.empty_balance'),
                ], 422);
            }

            /** @var User $user */
            $user = $result['user'];

            Log::info('[Referral] ✅ Solde de parrainage transféré vers le wallet', [
                'user_id' => $user->id,
                'amount' => $result['amount'],
                'provider' => $result['provider'],
                'transaction_id' => $result['transaction']->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('referral.transfer.success'),
                'data' => [
                    'provider' => $result['provider'],
                    'transferred' => $this->money((float) $result['amount'], $user),
                    'referral_balance' => $this->money(0.0, $user),
                    'transaction_id' => $result['transaction']->id,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('[Referral] ❌ Échec du transfert du solde de parrainage', [
                'user_id' => $userId,
                'provider' => $provider,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('referral.transfer.error'),
            ], 500);
        }
    }

    /**
     * Gains agrégés par mois sur les 6 derniers mois (pour le graphique).
     *
     * @return array<int, array{month:string, label:string, amount:array}>
     */
    private function monthlyEarnings(User $user, int $months = 6): array
    {
        $start = now()->startOfMonth()->subMonths($months - 1);

        // Somme des commissions groupée par mois (période bornée).
        $rows = $user->earnedCommissions()
            ->where('created_at', '>=', $start)
            ->get(['commission_amount', 'created_at'])
            ->groupBy(fn (ReferralCommission $c) => $c->created_at->format('Y-m'))
            ->map(fn ($group) => (float) $group->sum('commission_amount'));

        $result = [];
        for ($i = 0; $i < $months; $i++) {
            $date = (clone $start)->addMonths($i);
            $key = $date->format('Y-m');
            $amountXaf = (float) ($rows[$key] ?? 0);

            $result[] = [
                'month' => $key,
                'label' => $date->translatedFormat('M'),
                'amount' => $this->money($amountXaf, $user),
            ];
        }

        return $result;
    }

    /**
     * Décore un montant XAF avec sa valeur convertie et formatée dans la
     * devise d'affichage du user. `base_amount` reste la source de vérité.
     *
     * @return array{base_amount:float, base_currency:string, display_amount:float, display_currency:string, formatted:string}
     */
    private function money(float $baseAmountXaf, User $user): array
    {
        $display = $this->currencyService->displayFor($baseAmountXaf, $user);

        return [
            'base_amount' => round($baseAmountXaf, 2),
            'base_currency' => $display['base_currency'],
            'display_amount' => $display['display_price'],
            'display_currency' => $display['display_currency'],
            'formatted' => $display['display_price_formatted'],
        ];
    }
}
