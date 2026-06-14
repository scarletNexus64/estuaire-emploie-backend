<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdPricingConfig;
use Illuminate\Http\Request;

class AdPricingController extends Controller
{
    /**
     * Affiche la grille tarifaire du sponsoring (Marketing Digital).
     */
    public function index()
    {
        $segments = ['all', 'student', 'candidate', 'recruiter'];

        // Garantit une ligne par segment (création à la volée si manquante)
        foreach ($segments as $segment) {
            AdPricingConfig::firstOrCreate(
                ['audience_segment' => $segment],
                ['price_per_user' => 2, 'min_budget' => 500, 'max_budget' => 500000, 'is_active' => true]
            );
        }

        $configs = AdPricingConfig::orderBy('audience_segment')->get();

        return view('admin.monetization.ad-pricing.index', compact('configs'));
    }

    /**
     * Met à jour le tarif d'un segment.
     */
    public function update(Request $request, AdPricingConfig $adPricing)
    {
        $validated = $request->validate([
            'price_per_user' => 'required|numeric|min:0',
            'min_budget' => 'required|numeric|min:0',
            'max_budget' => 'required|numeric|gte:min_budget',
            'is_active' => 'nullable|boolean',
        ]);

        $adPricing->update([
            'price_per_user' => $validated['price_per_user'],
            'min_budget' => $validated['min_budget'],
            'max_budget' => $validated['max_budget'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.ad-pricing.index')
            ->with('success', 'Tarif du sponsoring mis à jour.');
    }
}
