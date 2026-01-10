<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Company::withCount(['jobs', 'recruiters']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('sector', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Plan filter
        if ($request->filled('plan')) {
            $query->where('subscription_plan', $request->plan);
        }

        $companies = $query->latest()->paginate(20)->withQueryString();

        return view('admin.companies.index', compact('companies'));
    }

    public function create(): View
    {
        return view('admin.companies.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sector' => 'required|string|max:255',
            'website' => 'nullable|url',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,verified,suspended',
            'subscription_plan' => 'required|in:free,premium',
        ]);

        // Gérer l'upload du logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        if ($validated['status'] === 'verified') {
            $validated['verified_at'] = now();
        }

        Company::create($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Entreprise créée avec succès');
    }

    public function show(Company $company): View
    {
        $company->load(['jobs', 'recruiters.user']);

        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company): View
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'phone' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sector' => 'required|string|max:255',
            'website' => 'nullable|url',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,verified,suspended',
            'subscription_plan' => 'required|in:free,premium',
        ]);

        // Gérer l'upload du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si existant
            if ($company->logo && \Storage::disk('public')->exists($company->logo)) {
                \Storage::disk('public')->delete($company->logo);
            }

            // Sauvegarder le nouveau logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        if ($validated['status'] === 'verified' && !$company->verified_at) {
            $validated['verified_at'] = now();
        }

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Entreprise mise à jour avec succès');
    }

    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('admin.companies.index')
            ->with('success', 'Entreprise supprimée avec succès');
    }

    public function verify(Company $company): RedirectResponse
    {
        try {
            $company->update([
                'status' => 'verified',
                'verified_at' => now(),
            ]);

            // Envoyer une notification push à tous les recruteurs de l'entreprise
            $company->load('recruiters.user');

            $notificationService = app(\App\Services\NotificationService::class);

            $title = "Entreprise vérifiée";
            $message = "Félicitations ! Votre entreprise {$company->name} a été vérifiée et approuvée.";

            $sent = 0;
            $failed = 0;

            foreach ($company->recruiters as $recruiter) {
                if ($recruiter->user) {
                    try {
                        // 1. Envoyer la notification push
                        if ($recruiter->user->fcm_token) {
                            $success = $notificationService->sendToUser(
                                $recruiter->user,
                                $title,
                                $message,
                                'company_verified',
                                [
                                    'company_id' => $company->id,
                                    'company_name' => $company->name,
                                ]
                            );

                            if ($success) {
                                $sent++;
                            } else {
                                $failed++;
                            }
                        }

                        // 2. Envoyer l'email (synchrone)
                        $recruiter->user->notify(new \App\Notifications\CompanyVerifiedNotification($company));

                    } catch (\Exception $e) {
                        $failed++;
                        \Log::error('Erreur envoi notification vérification entreprise', [
                            'company_id' => $company->id,
                            'recruiter_id' => $recruiter->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            $message = 'Entreprise vérifiée avec succès';
            if ($sent > 0) {
                $message .= " - {$sent} notification(s) envoyée(s)";
            }
            if ($failed > 0) {
                $message .= " ({$failed} échec(s))";
            }

            return redirect()->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la vérification d\'entreprise', [
                'company_id' => $company->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Erreur lors de la vérification: ' . $e->getMessage());
        }
    }

    public function suspend(Company $company): RedirectResponse
    {
        $company->update([
            'status' => 'suspended',
        ]);

        return redirect()->back()
            ->with('success', 'Entreprise suspendue avec succès');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = Company::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
