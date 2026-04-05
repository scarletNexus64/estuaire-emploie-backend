<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsamtechsFormationPricing;
use App\Models\InsamtechsFormationPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InsamtechsFormationPricingController extends Controller
{
    /**
     * URL de l'API InsamTechs
     */
    private function insamtechsApiUrl(): string
    {
        return config('services.insamtechs.api_url', 'http://127.0.0.1:8001/api');
    }

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
        // Récupérer les formations depuis l'API InsamTechs
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
     * Récupérer les formations depuis l'API InsamTechs
     */
    private function fetchInsamtechsFormations(): array
    {
        try {
            $response = Http::timeout(10)->get($this->insamtechsApiUrl() . '/formations', [
                'per_page' => 500,
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            $formations = $data['data'] ?? [];

            // Extraire les champs pertinents et décoder les titres translatables
            return array_map(function ($f) {
                $title = $f['intitule'] ?? '';
                if (is_array($title) || (is_string($title) && str_starts_with($title, '{'))) {
                    $decoded = is_string($title) ? json_decode($title, true) : $title;
                    $title = $decoded['fr'] ?? $decoded['en'] ?? 'Sans titre';
                }
                return [
                    'id' => $f['id'],
                    'title' => $title,
                    'slug' => $f['slug'] ?? null,
                ];
            }, $formations);
        } catch (\Exception $e) {
            \Log::error('Erreur récupération formations InsamTechs: ' . $e->getMessage());
            return [];
        }
    }
}
