<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CurrencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Liste des devises disponibles
     *
     * GET /api/currencies
     */
    public function index()
    {
        try {
            $currencies = $this->currencyService->getAvailableCurrencies();

            return response()->json([
                'success' => true,
                'data' => $currencies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('currency.fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tous les taux de change
     *
     * GET /api/currencies/rates
     */
    public function rates()
    {
        try {
            $rates = $this->currencyService->getAllRates();

            return response()->json([
                'success' => true,
                'data' => $rates,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('currency.rates_fetch_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Convertir un montant d'une devise à une autre
     *
     * POST /api/currencies/convert
     * Body: { "amount": 1000, "from": "XAF", "to": "USD" }
     */
    public function convert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string|size:3',
            'to' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('common.invalid_data'),
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $amount = $request->amount;
            $from = strtoupper($request->from);
            $to = strtoupper($request->to);

            $converted = $this->currencyService->convert($amount, $from, $to);
            $formatted = $this->currencyService->format($converted, $to);

            return response()->json([
                'success' => true,
                'data' => [
                    'original_amount' => $amount,
                    'original_currency' => $from,
                    'converted_amount' => $converted,
                    'target_currency' => $to,
                    'formatted' => $formatted,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('currency.conversion_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mettre à jour la devise préférée de l'utilisateur
     *
     * PUT /api/user/currency
     * Body: { "currency": "USD" }
     */
    public function updateUserCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency' => 'required|string|size:3|in:XAF,USD,EUR',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('currency.invalid_currency'),
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = $request->user();
            $currency = strtoupper($request->currency);

            $user->preferred_currency = $currency;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => __('currency.updated'),
                'data' => [
                    'preferred_currency' => $currency,
                    'currency_symbol' => \App\Models\CurrencyRate::getCurrencySymbol($currency),
                    'currency_name' => \App\Models\CurrencyRate::getCurrencyName($currency),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('currency.update_error'),
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
