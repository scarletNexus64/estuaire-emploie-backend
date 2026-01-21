<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobSeekerSubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::jobSeeker()
            ->withCount('activeSubscriptions')
            ->orderBy('display_order')
            ->get();

        return view('admin.monetization.subscription-plans-job-seekers.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.monetization.subscription-plans-job-seekers.form', [
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
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        // Générer le slug automatiquement
        $validated['slug'] = Str::slug($validated['name']);
        $validated['plan_type'] = 'job_seeker';

        // Pour les plans chercheurs d'emploi, les champs recruteurs sont NULL
        $validated['jobs_limit'] = null;
        $validated['contacts_limit'] = null;
        $validated['can_access_cvtheque'] = null;
        $validated['can_boost_jobs'] = null;
        $validated['can_see_analytics'] = null;
        $validated['priority_support'] = null;
        $validated['featured_company_badge'] = null;
        $validated['custom_company_page'] = null;

        // Gérer les checkboxes booléennes
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

        return redirect()->route('admin.subscription-plans.job-seekers.index')
            ->with('success', 'Plan d\'abonnement candidat créé avec succès');
    }

    public function edit($id)
    {
        $plan = SubscriptionPlan::jobSeeker()->findOrFail($id);

        return view('admin.monetization.subscription-plans-job-seekers.form', [
            'plan' => $plan,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::jobSeeker()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        // Mettre à jour le slug si le nom change
        if ($validated['name'] !== $plan->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Gérer les checkboxes
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

        return redirect()->route('admin.subscription-plans.job-seekers.index')
            ->with('success', 'Plan d\'abonnement candidat modifié avec succès');
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::jobSeeker()->findOrFail($id);

        // Vérifier s'il y a des abonnements actifs
        $activeCount = $plan->activeSubscriptions()->count();

        if ($activeCount > 0) {
            return redirect()->route('admin.subscription-plans.job-seekers.index')
                ->with('error', "Impossible de supprimer ce plan car {$activeCount} abonnement(s) actif(s) l'utilisent.");
        }

        $plan->delete();

        return redirect()->route('admin.subscription-plans.job-seekers.index')
            ->with('success', 'Plan d\'abonnement candidat supprimé avec succès');
    }
}
