<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContractType;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('jobs')->get();
        $locations = Location::withCount('jobs')->get();
        $contractTypes = ContractType::withCount('jobs')->get();

        return view('admin.settings.index', compact('categories', 'locations', 'contractTypes'));
    }

    public function categories(): View
    {
        $categories = Category::withCount('jobs')->get();
        $locations = Location::withCount('jobs')->get();
        $contractTypes = ContractType::withCount('jobs')->get();

        return view('admin.settings.index', compact('categories', 'locations', 'contractTypes'));
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:category,location,contract_type',
        ]);

        $slug = Str::slug($validated['name']);

        switch ($validated['type']) {
            case 'category':
                Category::create([
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                ]);
                break;

            case 'location':
                Location::create([
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'country' => $request->country ?? 'Cameroun',
                ]);
                break;

            case 'contract_type':
                ContractType::create([
                    'name' => $validated['name'],
                    'slug' => $slug,
                ]);
                break;
        }

        return redirect()->back()
            ->with('success', 'Élément ajouté avec succès');
    }

    public function deleteCategory(Request $request, $id): RedirectResponse
    {
        $type = $request->input('type');

        switch ($type) {
            case 'category':
                Category::findOrFail($id)->delete();
                break;

            case 'location':
                Location::findOrFail($id)->delete();
                break;

            case 'contract_type':
                ContractType::findOrFail($id)->delete();
                break;
        }

        return redirect()->back()
            ->with('success', 'Élément supprimé avec succès');
    }
}
