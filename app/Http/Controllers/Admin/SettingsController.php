<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanyCategory;
use App\Models\ContractType;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\Specialty;
use App\Models\TrainingCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount('jobs')->get();
        $contractTypes = ContractType::withCount('jobs')->get();
        $specialties = Specialty::withCount(['examPapers', 'examPacks'])->ordered()->get();
        $trainingCategories = TrainingCategory::withCount('trainingPacks')->ordered()->get();
        $serviceCategories = ServiceCategory::withCount('quickServices')->ordered()->get();

        // Pagination des utilisateurs pour la section parrainage
        $users = User::with(['referrer', 'referrals'])
            ->withCount('referrals')
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'users_page');

        // Pagination des commissions
        $commissions = \App\Models\ReferralCommission::with(['referrer', 'referred'])
            ->latest()
            ->paginate(20, ['*'], 'commissions_page');

        // Company categories with search and filter
        $companyCategoriesQuery = CompanyCategory::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $companyCategoriesQuery->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('level_1', 'like', "%{$search}%")
                    ->orWhere('level_2', 'like', "%{$search}%")
                    ->orWhere('level_3', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level_1')) {
            $companyCategoriesQuery->where('level_1', $request->input('level_1'));
        }

        if ($request->filled('is_active')) {
            $companyCategoriesQuery->where('is_active', $request->input('is_active') === '1');
        }

        $companyCategories = $companyCategoriesQuery->orderBy('code')->paginate(20, ['*'], 'company_categories_page');
        $level1Options = CompanyCategory::getLevel1Options();

        return view('admin.settings.index', compact(
            'categories',
            'contractTypes',
            'specialties',
            'trainingCategories',
            'serviceCategories',
            'users',
            'commissions',
            'companyCategories',
            'level1Options'
        ));
    }

    public function categories(): View
    {
        $categories = Category::withCount('jobs')->get();
        $contractTypes = ContractType::withCount('jobs')->get();

        return view('admin.settings.index', compact('categories', 'contractTypes'));
    }

    public function update(Request $request): RedirectResponse
    {
        // Handle general settings update
        return redirect()->back()
            ->with('success', 'Paramètres mis à jour avec succès');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required_without:code|string|max:255',
            'code' => 'required_if:type,company_category|string|max:50',
            'level_1' => 'required_if:type,company_category|string|max:255',
            'level_2' => 'nullable|string|max:255',
            'level_3' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:category,location,contract_type,specialty,training_category,service_category,company_category',
            'id' => 'nullable|integer',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:20',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $isUpdate = $request->has('id') && $request->id;

        switch ($validated['type']) {
            case 'category':
                if ($isUpdate) {
                    $category = Category::findOrFail($request->id);
                    $category->update([
                        'name' => $validated['name'],
                        'slug' => $slug,
                        'description' => $validated['description'] ?? null,
                    ]);
                } else {
                    Category::create([
                        'name' => $validated['name'],
                        'slug' => $slug,
                        'description' => $validated['description'] ?? null,
                    ]);
                }
                break;

            case 'contract_type':
                if ($isUpdate) {
                    $contractType = ContractType::findOrFail($request->id);
                    $contractType->update([
                        'name' => $validated['name'],
                        'slug' => $slug,
                    ]);
                } else {
                    ContractType::create([
                        'name' => $validated['name'],
                        'slug' => $slug,
                    ]);
                }
                break;

            case 'specialty':
                $data = [
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'icon' => $validated['icon'] ?? null,
                    'color' => $validated['color'] ?? null,
                    'display_order' => $validated['display_order'] ?? 0,
                    'is_active' => $validated['is_active'] ?? true,
                ];

                if ($isUpdate) {
                    $specialty = Specialty::findOrFail($request->id);
                    $specialty->update($data);
                } else {
                    Specialty::create($data);
                }
                break;

            case 'training_category':
                $data = [
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'icon' => $validated['icon'] ?? null,
                    'color' => $validated['color'] ?? null,
                    'display_order' => $validated['display_order'] ?? 0,
                    'is_active' => $validated['is_active'] ?? true,
                ];

                if ($isUpdate) {
                    $trainingCategory = TrainingCategory::findOrFail($request->id);
                    $trainingCategory->update($data);
                } else {
                    TrainingCategory::create($data);
                }
                break;

            case 'service_category':
                $data = [
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'icon' => $validated['icon'] ?? null,
                    'color' => $validated['color'] ?? null,
                    'display_order' => $validated['display_order'] ?? 0,
                    'is_active' => $validated['is_active'] ?? true,
                ];

                if ($isUpdate) {
                    $serviceCategory = ServiceCategory::findOrFail($request->id);
                    $serviceCategory->update($data);
                } else {
                    ServiceCategory::create($data);
                }
                break;

            case 'company_category':
                $deepestLevel = $validated['level_3'] ?? ($validated['level_2'] ?? $validated['level_1']);
                $categorySlug = Str::slug($deepestLevel);

                $data = [
                    'code' => $validated['code'],
                    'level_1' => $validated['level_1'],
                    'level_2' => $validated['level_2'] ?? null,
                    'level_3' => $validated['level_3'] ?? null,
                    'slug' => $categorySlug,
                    'description' => $validated['description'] ?? null,
                    'is_active' => $request->has('is_active'),
                ];

                if ($isUpdate) {
                    $companyCategory = CompanyCategory::findOrFail($request->id);
                    $companyCategory->update($data);
                } else {
                    CompanyCategory::create($data);
                }
                break;
        }

        $message = $isUpdate ? 'Élément modifié avec succès' : 'Élément ajouté avec succès';

        return redirect()->back()
            ->with('success', $message);
    }

    public function deleteCategory(Request $request, $id): RedirectResponse
    {
        $type = $request->input('type');

        switch ($type) {
            case 'category':
                Category::findOrFail($id)->delete();
                break;

            case 'contract_type':
                ContractType::findOrFail($id)->delete();
                break;

            case 'specialty':
                Specialty::findOrFail($id)->delete();
                break;

            case 'training_category':
                TrainingCategory::findOrFail($id)->delete();
                break;

            case 'service_category':
                ServiceCategory::findOrFail($id)->delete();
                break;

            case 'company_category':
                CompanyCategory::findOrFail($id)->delete();
                break;
        }

        return redirect()->back()
            ->with('success', 'Élément supprimé avec succès');
    }

    /**
     * Mettre à jour les paramètres de parrainage
     */
    public function updateReferralSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'referral_enabled' => 'nullable|boolean',
            'referral_commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        // Sauvegarder les settings
        Setting::setMany([
            'referral_enabled' => $request->has('referral_enabled') ? '1' : '0',
            'referral_commission_percentage' => $validated['referral_commission_percentage'],
        ]);

        return redirect()->back()
            ->with('success', 'Paramètres de parrainage mis à jour avec succès');
    }

    /**
     * Mettre à jour les paramètres du wallet (frais de retrait)
     */
    public function updateWalletSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'withdrawal_fee_percentage' => 'required|numeric|min:0|max:100',
        ]);

        Setting::set('withdrawal_fee_percentage', $validated['withdrawal_fee_percentage']);

        return redirect()->back()
            ->with('success', 'Paramètres du wallet mis à jour avec succès');
    }
}
