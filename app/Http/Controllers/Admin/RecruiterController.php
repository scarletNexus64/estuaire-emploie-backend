<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecruiterController extends Controller
{
    public function index(): View
    {
        $recruiters = Recruiter::with(['user', 'company'])
            ->latest()
            ->paginate(20);

        return view('admin.recruiters.index', compact('recruiters'));
    }

    public function create(): View
    {
        $companies = Company::where('status', 'verified')->get();
        $users = User::where('role', 'candidate')->get();

        return view('admin.recruiters.create', compact('companies', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
            'position' => 'nullable|string|max:255',
            'can_publish' => 'boolean',
            'can_view_applications' => 'boolean',
            'can_modify_company' => 'boolean',
        ]);

        Recruiter::create($validated);

        return redirect()->route('admin.recruiters.index')
            ->with('success', 'Recruteur créé avec succès');
    }

    public function edit(Recruiter $recruiter): View
    {
        $companies = Company::where('status', 'verified')->get();

        return view('admin.recruiters.edit', compact('recruiter', 'companies'));
    }

    public function update(Request $request, Recruiter $recruiter): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'position' => 'nullable|string|max:255',
            'can_publish' => 'boolean',
            'can_view_applications' => 'boolean',
            'can_modify_company' => 'boolean',
        ]);

        $recruiter->update($validated);

        return redirect()->route('admin.recruiters.index')
            ->with('success', 'Recruteur mis à jour avec succès');
    }

    public function destroy(Recruiter $recruiter): RedirectResponse
    {
        $recruiter->delete();

        return redirect()->route('admin.recruiters.index')
            ->with('success', 'Recruteur supprimé avec succès');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = Recruiter::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}