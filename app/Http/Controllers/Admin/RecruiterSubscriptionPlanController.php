<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecruiterSubscriptionPlanController extends Controller
{
    /**
     * Définir toutes les fonctionnalités disponibles pour les plans recruteurs
     */
    private function getAvailableFeatures(): array
    {
        return [
            'post_jobs' => 'Publication d\'offres d\'emploi',
            'push_notifications' => 'Notifications push pour nouvelles candidatures',
            'application_management' => 'Gestion des candidatures (accepter/rejeter/contacter)',
            'international_training' => 'Formation internationale',
            'company_geolocation' => 'Géolocalisation de votre entreprise',
            'ecommerce_promotion' => 'Promotion de tous vos produits et services en ligne (e-commerce)',
            'cv_database_access' => 'Accès à de nombreux CV des demandeurs d\'emploi',
            'featured_listings' => 'Mise en avant des offres dans les résultats de recherche',
            'performance_stats' => 'Statistiques de performance des annonces',
            'company_digitalization' => 'Digitalisation de votre entreprise',
            'hr_outsourcing' => 'Externalisation du service RH',
            'custom_company_page' => 'Page entreprise personnalisée',
            'priority_support' => 'Support client prioritaire',
            'marketing_assistance' => 'Assistance en communication/marketing pour la promotion de vos produits et services',
            'website_app_design' => 'Conception du site web et applications mobile de votre entreprise',
            'call_center_24_7' => 'Call center 24h/7',
        ];
    }

    public function index()
    {
        $plans = SubscriptionPlan::recruiter()
            ->withCount('activeSubscriptions')
            ->orderBy('display_order')
            ->get();

        $availableFeatures = $this->getAvailableFeatures();

        return view('admin.monetization.subscription-plans-recruiters.index', compact('plans', 'availableFeatures'));
    }

    public function create()
    {
        return view('admin.monetization.subscription-plans-recruiters.form', [
            'plan' => null,
            'isEdit' => false,
            'availableFeatures' => $this->getAvailableFeatures(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'jobs_limit' => 'nullable|integer|min:1',
            'contacts_limit' => 'nullable|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        // Générer le slug automatiquement
        $validated['slug'] = Str::slug($validated['name']);
        $validated['plan_type'] = 'recruiter';

        // Gérer les checkboxes booléennes
        $validated['can_access_cvtheque'] = $request->boolean('can_access_cvtheque');
        $validated['can_boost_jobs'] = $request->boolean('can_boost_jobs');
        $validated['can_see_analytics'] = $request->boolean('can_see_analytics');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['featured_company_badge'] = $request->boolean('featured_company_badge');
        $validated['custom_company_page'] = $request->boolean('custom_company_page');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_popular'] = $request->boolean('is_popular');

        // Gérer les features (JSON avec clés et valeurs)
        $features = [];
        foreach ($this->getAvailableFeatures() as $key => $label) {
            $features[$key] = $request->boolean('feature_' . $key);
        }
        $validated['features'] = $features;

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscription-plans.recruiters.index')
            ->with('success', 'Plan d\'abonnement recruteur créé avec succès');
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::recruiter()->findOrFail($id);

        return view('admin.monetization.subscription-plans-recruiters.form', [
            'plan' => $plan,
            'isEdit' => true,
            'availableFeatures' => $this->getAvailableFeatures(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::recruiter()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'jobs_limit' => 'nullable|integer|min:1',
            'contacts_limit' => 'nullable|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        // Mettre à jour le slug si le nom change
        if ($validated['name'] !== $plan->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Gérer les checkboxes
        $validated['can_access_cvtheque'] = $request->boolean('can_access_cvtheque');
        $validated['can_boost_jobs'] = $request->boolean('can_boost_jobs');
        $validated['can_see_analytics'] = $request->boolean('can_see_analytics');
        $validated['priority_support'] = $request->boolean('priority_support');
        $validated['featured_company_badge'] = $request->boolean('featured_company_badge');
        $validated['custom_company_page'] = $request->boolean('custom_company_page');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_popular'] = $request->boolean('is_popular');

        // Gérer les features (JSON avec clés et valeurs)
        $features = [];
        foreach ($this->getAvailableFeatures() as $key => $label) {
            $features[$key] = $request->boolean('feature_' . $key);
        }
        $validated['features'] = $features;

        $plan->update($validated);

        return redirect()->route('admin.subscription-plans.recruiters.index')
            ->with('success', 'Plan d\'abonnement recruteur modifié avec succès');
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::recruiter()->findOrFail($id);

        // Vérifier s'il y a des abonnements actifs
        $activeCount = $plan->activeSubscriptions()->count();

        if ($activeCount > 0) {
            return redirect()->route('admin.subscription-plans.recruiters.index')
                ->with('error', "Impossible de supprimer ce plan car {$activeCount} abonnement(s) actif(s) l'utilisent.");
        }

        $plan->delete();

        return redirect()->route('admin.subscription-plans.recruiters.index')
            ->with('success', 'Plan d\'abonnement recruteur supprimé avec succès');
    }
}
