<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Reporting des wallets et transactions agrégés PAR PAYS.
 *
 * Le « wallet d'un pays » est un agrégat calculé à la volée : somme des soldes
 * et des transactions des utilisateurs rattachés à ce pays (users.country, code
 * ISO alpha-2). La devise affichée est celle du pays (countries.currency).
 * Aucune table de solde par pays n'est stockée → pas de risque de désync.
 */
class CountryReportController extends Controller
{
    /**
     * Pays supportés par KPay (codes ISO 3166-1 alpha-2). Toujours affichés
     * dans le reporting, même sans utilisateur ni transaction (0), afin de
     * piloter le déploiement multi-pays.
     */
    private const KPAY_COUNTRIES = [
        'BJ', // Bénin
        'BF', // Burkina Faso
        'CM', // Cameroun
        'CI', // Côte d'Ivoire
        'CD', // RDC
        'GA', // Gabon
        'GH', // Ghana
        'KE', // Kenya
        'MW', // Malawi
        'MZ', // Mozambique
        'NG', // Nigeria
        'CG', // Congo
        'RW', // Rwanda
        'SN', // Sénégal
        'SL', // Sierra Leone
        'UG', // Ouganda
        'TZ', // Tanzanie
        'ZM', // Zambie
    ];

    /**
     * Vue d'ensemble : un pays par ligne avec ses agrégats.
     *
     * GET /admin/wallets/countries
     */
    public function index(Request $request)
    {
        // Agrégats par code pays depuis la table users.
        $userAgg = User::query()
            ->select([
                'country',
                DB::raw('COUNT(*) as users_count'),
                DB::raw('COALESCE(SUM(wallet_balance), 0) as wallet_total'),
                DB::raw('COALESCE(SUM(freemopay_wallet_balance), 0) as freemopay_total'),
                DB::raw('COALESCE(SUM(paypal_wallet_balance), 0) as paypal_total'),
            ])
            ->whereNotNull('country')
            ->groupBy('country')
            ->get()
            ->keyBy('country');

        // Volume de transactions complétées, par pays (via jointure sur le user).
        $txAgg = WalletTransaction::query()
            ->join('users', 'users.id', '=', 'wallet_transactions.user_id')
            ->where('wallet_transactions.status', 'completed')
            ->whereNotNull('users.country')
            ->select([
                'users.country',
                DB::raw('COUNT(*) as tx_count'),
                DB::raw('COALESCE(SUM(CASE WHEN wallet_transactions.amount > 0 THEN wallet_transactions.amount ELSE 0 END), 0) as credits_total'),
                DB::raw('COALESCE(SUM(CASE WHEN wallet_transactions.amount < 0 THEN -wallet_transactions.amount ELSE 0 END), 0) as debits_total'),
            ])
            ->groupBy('users.country')
            ->get()
            ->keyBy('country');

        // Uniquement les pays supportés par KPay (toujours affichés, même à 0).
        $countries = Country::whereIn('code', self::KPAY_COUNTRIES)
            ->get()
            ->map(function (Country $country) use ($userAgg, $txAgg) {
                $u = $userAgg->get($country->code);
                $t = $txAgg->get($country->code);

                return (object) [
                    'code'            => $country->code,
                    'name'            => $country->name,
                    'flag'            => $country->flag,
                    'currency'        => $country->currency,
                    'is_active'       => $country->is_active,
                    'users_count'     => (int) ($u->users_count ?? 0),
                    'wallet_total'    => (float) ($u->wallet_total ?? 0),
                    'freemopay_total' => (float) ($u->freemopay_total ?? 0),
                    'paypal_total'    => (float) ($u->paypal_total ?? 0),
                    'tx_count'        => (int) ($t->tx_count ?? 0),
                    'credits_total'   => (float) ($t->credits_total ?? 0),
                    'debits_total'    => (float) ($t->debits_total ?? 0),
                ];
            })
            // Pays avec activité en premier (solde décroissant), puis alphabétique.
            ->sortBy([
                fn ($a, $b) => $b->wallet_total <=> $a->wallet_total,
                fn ($a, $b) => strcmp($a->name, $b->name),
            ])
            ->values();

        $totalCountries = $countries->count();
        $totalUsers = $countries->sum('users_count');
        $totalTx = $countries->sum('tx_count');

        return view('admin.wallets.countries.index', compact(
            'countries',
            'totalCountries',
            'totalUsers',
            'totalTx'
        ));
    }

    /**
     * Détail d'un pays : ses utilisateurs et ses transactions.
     *
     * GET /admin/wallets/countries/{code}
     */
    public function show(Request $request, string $code)
    {
        $code = strtoupper($code);
        $country = Country::where('code', $code)->firstOrFail();

        $type = $request->input('type');
        $perPage = $request->input('per_page', 50);

        // Transactions des users de ce pays.
        $query = WalletTransaction::query()
            ->with(['user', 'payment', 'admin'])
            ->whereHas('user', fn ($q) => $q->where('country', $code))
            ->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('type', $type);
        }

        $transactions = $query->paginate($perPage)->withQueryString();

        // Agrégats du pays.
        $usersCount = User::where('country', $code)->count();
        $walletTotal = (float) User::where('country', $code)->sum('wallet_balance');

        $creditsTotal = (float) WalletTransaction::query()
            ->whereHas('user', fn ($q) => $q->where('country', $code))
            ->where('status', 'completed')
            ->where('amount', '>', 0)
            ->sum('amount');

        $debitsTotal = abs((float) WalletTransaction::query()
            ->whereHas('user', fn ($q) => $q->where('country', $code))
            ->where('status', 'completed')
            ->where('amount', '<', 0)
            ->sum('amount'));

        return view('admin.wallets.countries.show', compact(
            'country',
            'transactions',
            'usersCount',
            'walletTotal',
            'creditsTotal',
            'debitsTotal',
            'type'
        ));
    }
}
