<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdPricingConfig;
use App\Models\Advertisement;
use App\Services\MarketingCampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Sponsoring self-service côté entreprise (Marketing Digital).
 * Routes protégées par auth:sanctum.
 */
class MarketingCampaignController extends Controller
{
    public function __construct(protected MarketingCampaignService $service)
    {
    }

    /**
     * Grille tarifaire publique (prix par utilisateur par segment).
     * GET /api/marketing/pricing
     */
    public function pricing(): JsonResponse
    {
        $currency = app(\App\Services\CurrencyService::class);
        $target = $currency->resolveCurrency(auth()->user());

        $configs = AdPricingConfig::where('is_active', true)
            ->get()
            ->map(fn ($c) => [
                'audience_segment' => $c->audience_segment,
                // Montants de base (XAF) — source de vérité pour la validation.
                'price_per_user' => (float) $c->price_per_user,
                'min_budget' => (float) $c->min_budget,
                'max_budget' => (float) $c->max_budget,
                // Affichage dans la devise du user (informatif).
                'base_currency' => \App\Services\CurrencyService::BASE_CURRENCY,
                'display_currency' => $target,
                'display_price_per_user' => $currency->displayFor((float) $c->price_per_user, $target)['display_price'],
                'display_min_budget' => $currency->displayFor((float) $c->min_budget, $target)['display_price'],
                'display_min_budget_formatted' => $currency->displayFor((float) $c->min_budget, $target)['display_price_formatted'],
                'display_max_budget' => $currency->displayFor((float) $c->max_budget, $target)['display_price'],
                'display_max_budget_formatted' => $currency->displayFor((float) $c->max_budget, $target)['display_price_formatted'],
            ]);

        return response()->json(['success' => true, 'data' => $configs]);
    }

    /**
     * Estimation budget => nombre d'utilisateurs touchables.
     * POST /api/marketing/estimate
     */
    public function estimate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'target_audience' => 'required|in:student,candidate,recruiter,all',
            'budget' => 'required|numeric|min:0',
        ]);

        try {
            $estimate = $this->service->estimate($validated['target_audience'], (float) $validated['budget']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json(['success' => true, 'data' => $estimate]);
    }

    /**
     * Liste des campagnes de l'entreprise connectée.
     * GET /api/marketing/campaigns
     */
    public function index(Request $request): JsonResponse
    {
        $company = $request->user()->currentCompany;
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune entreprise active sélectionnée.',
            ], 422);
        }

        $campaigns = Advertisement::where('company_id', $company->id)
            ->where('source', 'self_service')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($ad) => $this->service->stats($ad));

        return response()->json(['success' => true, 'data' => $campaigns]);
    }

    /**
     * Création d'une campagne (multipart) + débit wallet + auto-publication.
     * POST /api/marketing/campaigns
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content_type' => 'required|in:text,flyer,logo',
            'target_audience' => 'required|in:student,candidate,recruiter,all',
            'countries' => 'nullable|array', // codes pays ISO ; vide/absent = tous les pays
            'countries.*' => 'string|size:2|exists:countries,code',
            'budget' => 'required|numeric|min:1',
            'background_color' => 'nullable|string|max:20',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'duration_days' => 'nullable|integer|min:1|max:365',
            'payment_provider' => 'nullable|in:kpay,freemopay,paypal',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $user = $request->user();
        $company = $user->currentCompany;
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune entreprise active sélectionnée.',
            ], 422);
        }

        // Le contenu flyer/logo exige une image
        if (in_array($validated['content_type'], ['flyer', 'logo']) && !$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'message' => 'Une image est requise pour ce type de contenu.',
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('advertisements', 'public');
        }

        // Le provider FreeMoPay/KPay partage le même wallet (freemopay_wallet_balance)
        $provider = $validated['payment_provider'] ?? 'freemopay';
        if ($provider === 'kpay') {
            $provider = 'freemopay';
        }

        try {
            $advertisement = $this->service->createCampaign($user, $company, [
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'content_type' => $validated['content_type'],
                'target_audience' => $validated['target_audience'],
                'target_countries' => $validated['countries'] ?? null,
                'budget' => (float) $validated['budget'],
                'background_color' => $validated['background_color'] ?? '#0277BD',
                'overlay_opacity' => $validated['overlay_opacity'] ?? 60,
                'duration_days' => $validated['duration_days'] ?? 30,
                'image' => $imagePath,
                'provider' => $provider,
            ]);
        } catch (\Throwable $e) {
            // Nettoyer l'image uploadée si la campagne échoue (ex: solde insuffisant)
            if ($imagePath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Campagne publiée avec succès.',
            'data' => $this->service->stats($advertisement),
        ], 201);
    }

    /**
     * KPI d'une campagne.
     * GET /api/marketing/campaigns/{id}/stats
     */
    public function stats(Request $request, int $id): JsonResponse
    {
        $ad = $this->ownedCampaign($request, $id);
        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Campagne introuvable.'], 404);
        }

        return response()->json(['success' => true, 'data' => $this->service->stats($ad)]);
    }

    /**
     * Rapport d'une campagne (alias enrichi des stats).
     * GET /api/marketing/campaigns/{id}/report
     */
    public function report(Request $request, int $id): JsonResponse
    {
        $ad = $this->ownedCampaign($request, $id);
        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Campagne introuvable.'], 404);
        }

        $stats = $this->service->stats($ad);
        $stats['content_type'] = $ad->content_type;
        $stats['image_url'] = $ad->image_url;
        $stats['description'] = $ad->description;
        $stats['generated_at'] = now()->toISOString();

        return response()->json(['success' => true, 'data' => $stats]);
    }

    /**
     * Supprime une campagne de l'entreprise (soft delete).
     * L'annonce disparaît immédiatement des bannières et de la liste.
     * Pas de remboursement : le budget de sponsoring est consommé.
     * DELETE /api/marketing/campaigns/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $ad = $this->ownedCampaign($request, $id);
        if (!$ad) {
            return response()->json(['success' => false, 'message' => 'Campagne introuvable.'], 404);
        }

        // Retirer l'image du stockage si présente.
        if ($ad->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($ad->image);
        }

        $ad->delete();

        return response()->json([
            'success' => true,
            'message' => 'Campagne supprimée.',
        ]);
    }

    /**
     * Récupère une campagne appartenant à l'entreprise active de l'utilisateur.
     */
    protected function ownedCampaign(Request $request, int $id): ?Advertisement
    {
        $company = $request->user()->currentCompany;
        if (!$company) {
            return null;
        }

        return Advertisement::where('id', $id)
            ->where('company_id', $company->id)
            ->where('source', 'self_service')
            ->first();
    }
}
