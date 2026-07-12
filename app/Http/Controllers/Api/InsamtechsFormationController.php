<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InsamtechsFormationPricing;
use App\Models\InsamtechsFormationPurchase;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InsamtechsFormationController extends Controller
{
    /**
     * Retourne les prix actifs pour toutes les formations InsamTechs
     * Le Flutter merge ensuite ces prix avec les formations récupérées depuis InsamTechs
     */
    public function pricing(Request $request)
    {
        $pricings = InsamtechsFormationPricing::active()->get();

        $userId = Auth::id();
        $purchasedIds = [];
        if ($userId) {
            $purchasedIds = InsamtechsFormationPurchase::where('user_id', $userId)
                ->completed()
                ->pluck('insamtechs_formation_id')
                ->toArray();
        }

        $currency = app(\App\Services\CurrencyService::class);
        $target = $currency->resolveCurrency(auth()->user());

        $data = $pricings->map(function ($p) use ($purchasedIds, $currency, $target) {
            // Affichage du prix (base price_xaf) dans la devise du user.
            $display = $currency->displayFor((float) $p->price_xaf, $target);

            return [
                'insamtechs_formation_id' => $p->insamtechs_formation_id,
                'price_xaf' => (float) $p->price_xaf,
                'price_usd' => (float) $p->price_usd,
                'price_eur' => (float) $p->price_eur,
                'base_currency' => $display['base_currency'],
                'display_currency' => $display['display_currency'],
                'display_price' => $display['display_price'],
                'display_price_formatted' => $display['display_price_formatted'],
                'is_purchased' => in_array($p->insamtechs_formation_id, $purchasedIds),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'purchased_ids' => $purchasedIds,
        ]);
    }

    /**
     * Liste des formations InsamTechs achetées par l'utilisateur connecté
     */
    public function myPurchases()
    {
        $userId = Auth::id();
        $purchases = InsamtechsFormationPurchase::where('user_id', $userId)
            ->completed()
            ->latest('purchased_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $purchases,
        ]);
    }

    /**
     * Acheter une formation InsamTechs via wallet
     * POST /api/insamtechs-formations/{formationId}/purchase
     */
    public function purchase(Request $request, int $formationId)
    {
        $request->validate([
            'payment_method' => 'required|in:wallet',
            'currency' => 'nullable|in:XAF,USD,EUR',
            'payment_provider' => 'nullable|in:kpay,freemopay,paypal',
            'formation_title' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $currency = $request->input('currency', 'XAF');
        $paymentProvider = $request->input('payment_provider', 'kpay');

        // Récupérer le prix de la formation
        $pricing = InsamtechsFormationPricing::where('insamtechs_formation_id', $formationId)
            ->active()
            ->first();

        if (!$pricing) {
            return response()->json([
                'success' => false,
                'message' => __('insamtechs_formation.not_purchasable'),
            ], 400);
        }

        // Prix d'AFFICHAGE (devise demandée) — pour le record d'achat uniquement.
        $price = $pricing->getPrice($currency);

        // Prix de DÉBIT : toujours en XAF (les wallets sont libellés en XAF).
        // Débiter price_usd/price_eur sur un solde XAF serait un sous/sur-paiement
        // (money-critical). Source de vérité = price_xaf.
        $priceXaf = $pricing->getPrice('XAF');

        if ($price <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('insamtechs_formation.invalid_price'),
            ], 400);
        }

        // Vérifier si déjà achetée
        $existing = InsamtechsFormationPurchase::where('user_id', $user->id)
            ->where('insamtechs_formation_id', $formationId)
            ->completed()
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => __('insamtechs_formation.already_purchased'),
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Déterminer le wallet à utiliser
            $walletField = $paymentProvider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';
            $currentBalance = $user->{$walletField} ?? 0;

            if ($currentBalance < $priceXaf) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => __('insamtechs_formation.insufficient_wallet', ['provider' => ucfirst($paymentProvider)]),
                    'required' => $priceXaf,
                    'available' => $currentBalance,
                ], 400);
            }

            $balanceBefore = $currentBalance;
            $balanceAfter = $currentBalance - $priceXaf;

            // Débiter le wallet (montant XAF)
            $user->decrement($walletField, $priceXaf);

            $formationTitle = $request->input('formation_title', $pricing->formation_title ?? "Formation #{$formationId}");

            // Transaction wallet (montant XAF, cohérent avec le solde)
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $priceXaf,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => "Achat de la formation: {$formationTitle}",
                'reference_type' => InsamtechsFormationPurchase::class,
                'reference_id' => $formationId,
                'status' => 'completed',
                'provider' => $paymentProvider,
            ]);

            // Créer l'achat
            $purchase = InsamtechsFormationPurchase::create([
                'user_id' => $user->id,
                'insamtechs_formation_id' => $formationId,
                'formation_title' => $formationTitle,
                'amount_paid' => $price,
                'currency' => $currency,
                'payment_method' => 'wallet',
                'payment_provider' => $paymentProvider,
                'status' => 'completed',
                'purchased_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('insamtechs_formation.purchased'),
                'data' => [
                    'purchase' => $purchase,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => __('insamtechs_formation.purchase_error', ['error' => $e->getMessage()]),
            ], 500);
        }
    }

    /**
     * Vérifier si l'utilisateur a accès à une formation
     */
    public function checkAccess(int $formationId)
    {
        $userId = Auth::id();

        // Formation gratuite si pas de prix actif
        $pricing = InsamtechsFormationPricing::where('insamtechs_formation_id', $formationId)
            ->active()
            ->first();

        if (!$pricing || $pricing->getPrice('XAF') <= 0) {
            return response()->json([
                'success' => true,
                'has_access' => true,
                'is_free' => true,
            ]);
        }

        $purchased = InsamtechsFormationPurchase::where('user_id', $userId)
            ->where('insamtechs_formation_id', $formationId)
            ->completed()
            ->exists();

        $currency = app(\App\Services\CurrencyService::class);
        $display = $currency->displayFor(
            (float) $pricing->price_xaf,
            $currency->resolveCurrency(auth()->user())
        );

        return response()->json([
            'success' => true,
            'has_access' => $purchased,
            'is_free' => false,
            'price_xaf' => (float) $pricing->price_xaf,
            'price_usd' => (float) $pricing->price_usd,
            'price_eur' => (float) $pricing->price_eur,
            'base_currency' => $display['base_currency'],
            'display_currency' => $display['display_currency'],
            'display_price' => $display['display_price'],
            'display_price_formatted' => $display['display_price_formatted'],
        ]);
    }
}
