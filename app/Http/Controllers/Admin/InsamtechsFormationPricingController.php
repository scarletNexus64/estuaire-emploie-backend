<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsamtechsFormationPricing;
use App\Models\InsamtechsFormationPurchase;
use Illuminate\Http\Request;
use App\Services\InsamtechsCatalogService;

class InsamtechsFormationPricingController extends Controller
{
    /**
     * Liste des prix configurés pour les formations InsamTechs
     */
    public function index(Request $request)
    {
        $query = InsamtechsFormationPricing::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('formation_title', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $pricings = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Stats
        $totalActive = InsamtechsFormationPricing::where('is_active', true)->count();
        $totalRevenue = InsamtechsFormationPurchase::where('status', 'completed')
            ->where('currency', 'XAF')
            ->sum('amount_paid');
        $totalPurchases = InsamtechsFormationPurchase::where('status', 'completed')->count();

        return view('admin.insamtechs-pricing.index', compact(
            'pricings',
            'totalActive',
            'totalRevenue',
            'totalPurchases'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create(Request $request)
    {
        // Récupérer les formations du catalogue local
        $formations = $this->fetchInsamtechsFormations();

        // Exclure celles qui ont déjà un prix configuré
        $configuredIds = InsamtechsFormationPricing::pluck('insamtechs_formation_id')->toArray();
        $available = array_filter($formations, fn($f) => !in_array($f['id'], $configuredIds));

        return view('admin.insamtechs-pricing.form', [
            'pricing' => null,
            'formations' => array_values($available),
        ]);
    }

    /**
     * Enregistrer un nouveau prix
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'insamtechs_formation_id' => 'required|integer|unique:insamtechs_formation_pricing,insamtechs_formation_id',
            'formation_title' => 'required|string|max:255',
            'price_xaf' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['price_usd'] = $validated['price_usd'] ?? 0;
        $validated['price_eur'] = $validated['price_eur'] ?? 0;

        InsamtechsFormationPricing::create($validated);

        return redirect()->route('admin.insamtechs-pricing.index')
            ->with('success', 'Prix configuré avec succès');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(InsamtechsFormationPricing $pricing)
    {
        return view('admin.insamtechs-pricing.form', [
            'pricing' => $pricing,
            'formations' => [],
        ]);
    }

    /**
     * Mettre à jour un prix
     */
    public function update(Request $request, InsamtechsFormationPricing $pricing)
    {
        $validated = $request->validate([
            'formation_title' => 'required|string|max:255',
            'price_xaf' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['price_usd'] = $validated['price_usd'] ?? 0;
        $validated['price_eur'] = $validated['price_eur'] ?? 0;

        $pricing->update($validated);

        return redirect()->route('admin.insamtechs-pricing.index')
            ->with('success', 'Prix mis à jour');
    }

    /**
     * Basculer l'état actif/inactif
     */
    public function toggle(InsamtechsFormationPricing $pricing)
    {
        $pricing->update(['is_active' => !$pricing->is_active]);

        return back()->with('success', $pricing->is_active ? 'Formation activée' : 'Formation désactivée');
    }

    /**
     * Supprimer une configuration de prix
     */
    public function destroy(InsamtechsFormationPricing $pricing)
    {
        $pricing->delete();

        return redirect()->route('admin.insamtechs-pricing.index')
            ->with('success', 'Configuration de prix supprimée');
    }

    /**
     * Récupérer les formations du catalogue local synchronisé
     */
    private function fetchInsamtechsFormations(): array
    {
        return collect(app(InsamtechsCatalogService::class)->catalog())
            ->filter(fn ($formation) => $formation['etat'] ?? true)
            ->map(fn ($formation) => [
                'id' => $formation['id'],
                'title' => InsamtechsCatalogService::text($formation['intitule'] ?? '') ?: 'Sans titre',
                'slug' => $formation['slug'] ?? null,
            ])->values()->all();
    }
}
