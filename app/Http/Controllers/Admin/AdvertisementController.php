<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Company;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::with('company')
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.monetization.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        $companies = Company::where('is_verified', true)->get();
        return view('admin.monetization.advertisements.form', [
            'ad' => null,
            'isEdit' => false,
            'companies' => $companies,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'ad_type' => 'required|in:homepage_banner,search_banner,featured_company,sidebar,custom',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'target_url' => 'nullable|url|max:500',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'display_order' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['status'] = 'pending';

        Advertisement::create($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité créée avec succès');
    }

    public function edit($id)
    {
        $ad = Advertisement::findOrFail($id);
        $companies = Company::where('is_verified', true)->get();

        return view('admin.monetization.advertisements.form', [
            'ad' => $ad,
            'isEdit' => true,
            'companies' => $companies,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'ad_type' => 'required|in:homepage_banner,search_banner,featured_company,sidebar,custom',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string|max:500',
            'target_url' => 'nullable|url|max:500',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'display_order' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $ad->update($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité modifiée avec succès');
    }

    public function destroy($id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->delete();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité supprimée avec succès');
    }

    public function toggle($id)
    {
        $ad = Advertisement::findOrFail($id);
        $ad->is_active = !$ad->is_active;
        $ad->save();

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité ' . ($ad->is_active ? 'activée' : 'désactivée'));
    }
}
