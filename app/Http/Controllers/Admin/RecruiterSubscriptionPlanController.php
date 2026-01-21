<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecruiterSubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::recruiter()
            ->withCount('activeSubscriptions')
            ->orderBy('display_order')
            ->get();

        return view('admin.monetization.subscription-plans-recruiters.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.monetization.subscription-plans-recruiters.form', [
            'plan' => null,
            'isEdit' => false,
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

        // Gérer les features (JSON)
        $features = [];
        if ($request->has('features')) {
            $featuresArray = explode("\n", $request->input('features'));
            foreach ($featuresArray as $feature) {
                $feature = trim($feature);
                if (!empty($feature)) {
                    $features[] = $feature;
                }
            }
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

        // Gérer les features (JSON)
        $features = [];
        if ($request->has('features')) {
            $featuresArray = explode("\n", $request->input('features'));
            foreach ($featuresArray as $feature) {
                $feature = trim($feature);
                if (!empty($feature)) {
                    $features[] = $feature;
                }
            }
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
