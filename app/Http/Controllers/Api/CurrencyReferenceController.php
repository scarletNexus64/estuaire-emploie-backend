<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\JsonResponse;

/**
 * Référentiel complet des devises mondiales (table `currencies`).
 *
 * Distinct du CurrencyController existant qui gère uniquement la conversion
 * de taux pour les 3 devises supportées (XAF/USD/EUR). Ici on expose la
 * liste complète pour le choix de devise lors de la création d'un produit.
 */
class CurrencyReferenceController extends Controller
{
    /**
     * GET /api/currencies/all
     */
    public function index(): JsonResponse
    {
        try {
            $currencies = Currency::active()
                ->with('translations')
                ->orderBy('id') // Ordre du seeder : devises locales/principales en premier
                ->get();

            return response()->json([
                'success' => true,
                'data' => $currencies->map(fn ($currency) => [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'name' => $currency->t('name'),
                    'symbol' => $currency->symbol,
                ]),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('currency.fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
