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

        $data = $pricings->map(function ($p) use ($purchasedIds) {
            return [
                'insamtechs_formation_id' => $p->insamtechs_formation_id,
                'price_xaf' => (float) $p->price_xaf,
                'price_usd' => (float) $p->price_usd,
                'price_eur' => (float) $p->price_eur,
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
            'payment_provider' => 'nullable|in:freemopay,paypal',
            'formation_title' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $currency = $request->input('currency', 'XAF');
        $paymentProvider = $request->input('payment_provider', 'freemopay');

        // Récupérer le prix de la formation
        $pricing = InsamtechsFormationPricing::where('insamtechs_formation_id', $formationId)
            ->active()
            ->first();

        if (!$pricing) {
            return response()->json([
                'success' => false,
                'message' => 'Cette formation est gratuite ou non disponible à l\'achat',
            ], 400);
        }

        $price = $pricing->getPrice($currency);

        if ($price <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Prix invalide pour cette formation',
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
                'message' => 'Vous avez déjà acheté cette formation',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Déterminer le wallet à utiliser
            $walletField = $paymentProvider === 'paypal' ? 'paypal_wallet_balance' : 'freemopay_wallet_balance';
            $currentBalance = $user->{$walletField} ?? 0;

            if ($currentBalance < $price) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Solde insuffisant dans votre wallet " . ucfirst($paymentProvider),
                    'required' => $price,
                    'available' => $currentBalance,
                ], 400);
            }

            $balanceBefore = $currentBalance;
            $balanceAfter = $currentBalance - $price;

            // Débiter le wallet
            $user->decrement($walletField, $price);

            $formationTitle = $request->input('formation_title', $pricing->formation_title ?? "Formation #{$formationId}");

            // Transaction wallet
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $price,
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
                'message' => 'Formation achetée avec succès',
                'data' => [
                    'purchase' => $purchase,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'achat: ' . $e->getMessage(),
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

        return response()->json([
            'success' => true,
            'has_access' => $purchased,
            'is_free' => false,
            'price_xaf' => (float) $pricing->price_xaf,
            'price_usd' => (float) $pricing->price_usd,
            'price_eur' => (float) $pricing->price_eur,
        ]);
    }
}
