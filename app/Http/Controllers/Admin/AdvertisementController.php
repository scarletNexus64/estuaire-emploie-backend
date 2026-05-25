<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.monetization.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        return view('admin.monetization.advertisements.form', [
            'ad' => null,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_color' => 'required|string|max:7',
            'ad_type' => 'required|in:homepage_banner,search_banner,featured_company,sidebar,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'display_order' => 'required|integer|min:0',
        ]);

        // Upload de l'image si fournie
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('advertisements', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['status'] = 'active';

        Advertisement::create($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité créée avec succès');
    }

    public function edit($id)
    {
        $ad = Advertisement::findOrFail($id);

        return view('admin.monetization.advertisements.form', [
            'ad' => $ad,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'background_color' => 'required|string|max:7',
            'ad_type' => 'required|in:homepage_banner,search_banner,featured_company,sidebar,custom',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'display_order' => 'required|integer|min:0',
        ]);

        // Upload de l'image si fournie
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $validated['image'] = $request->file('image')->store('advertisements', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $ad->update($validated);

        return redirect()->route('admin.advertisements.index')
            ->with('success', 'Publicité modifiée avec succès');
    }

    public function destroy($id)
    {
        $ad = Advertisement::findOrFail($id);

        // Supprimer l'image si elle existe
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }

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
