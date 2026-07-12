<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

/**
 * Liste des pays (table de référence) pour les sélecteurs côté mobile :
 * pays de résidence à l'inscription et ciblage géographique des campagnes.
 */
class CountryController extends Controller
{
    /**
     * GET /api/countries
     * Liste publique des pays actifs (nom traduit selon la locale).
     */
    public function index(): JsonResponse
    {
        $countries = Country::active()
            ->orderBy('name')
            ->get()
            ->map(fn ($c) => [
                'code' => $c->code,
                'iso3' => $c->iso3,
                'dial_code' => $c->dial_code,
                'flag' => $c->flag,
                'currency' => $c->currency,
                'supports_kpay' => $c->supports_kpay,
                'name' => $c->name,
            ]);

        return response()->json(['success' => true, 'data' => $countries]);
    }
}
