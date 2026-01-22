<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobSeekerSubscriptionPlanController extends Controller
{
    /**
     * Définir toutes les fonctionnalités disponibles pour les plans chercheurs d'emploi
     */
    private function getAvailableFeatures(): array
    {
        return [
            'free_cv_creation' => 'Conception gratuite de votre CV',
            'cv_accessible_recruiters' => 'Votre CV accessible par les recruteurs à l\'échelle nationale et internationale',
            'free_regional_jobs' => 'De multiples offres d\'emploi de votre région accessibles gratuitement',
            'free_certifications' => 'Des formations certifiantes internationales gratuites (une attestation ou une certification)',
            'transformation_program' => 'L\'établissement de votre programme de transformation professionnelle et personnel',
            'portfolio_creation' => 'Création de votre portfolio',
            'premium_cv' => 'CV Premium (mise en avant)',
            'verified_badge' => 'Badge "Profil Vérifié"',
            'cv_review' => 'Révision CV par expert',
            'interview_coaching' => 'Coaching entretien',
            'job_alerts' => 'Alertes emploi SMS/WhatsApp',
            'immersion_program' => 'Programme d\'immersion professionnelle',
            'entrepreneurship' => 'Programme en entreprenariat',
            'international_internship' => 'Stage à l\'international',
            // Student Pack specific features
            'past_exam_subjects' => 'Anciens sujets d\'examen',
            'professional_orientation' => 'Orientation professionnelle (spécialité/métier)',
            'local_internship' => 'Stage local',
        ];
    }

    public function index()
    {
        $plans = SubscriptionPlan::jobSeeker()
            ->withCount('activeSubscriptions')
            ->orderBy('display_order')
            ->get();

        $availableFeatures = $this->getAvailableFeatures();

        return view('admin.monetization.subscription-plans-job-seekers.index', compact('plans', 'availableFeatures'));
    }

    public function create()
    {
        return view('admin.monetization.subscription-plans-job-seekers.form', [
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

        // Gérer les features (JSON avec clés et valeurs)
        $features = [];
        foreach ($this->getAvailableFeatures() as $key => $label) {
            $features[$key] = $request->boolean('feature_' . $key);
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
            'availableFeatures' => $this->getAvailableFeatures(),
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

        // Gérer les features (JSON avec clés et valeurs)
        $features = [];
        foreach ($this->getAvailableFeatures() as $key => $label) {
            $features[$key] = $request->boolean('feature_' . $key);
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
