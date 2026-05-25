<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonServiceConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecruiterServiceController extends Controller
{
    public function index()
    {
        $services = AddonServiceConfig::orderBy('display_order')->get();
        return view('admin.monetization.recruiter-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.monetization.recruiter-services.form', ['service' => null, 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'service_type' => 'required|in:extra_job_posting,job_boost,candidate_contact,diploma_verification,skills_test,custom',
            'boost_multiplier' => 'nullable|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
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

        AddonServiceConfig::create($validated);

        return redirect()->route('admin.recruiter-services.index')->with('success', 'Service recruteur créé avec succès');
    }

    public function edit($id)
    {
        $service = AddonServiceConfig::findOrFail($id);
        return view('admin.monetization.recruiter-services.form', ['service' => $service, 'isEdit' => true]);
    }

    public function update(Request $request, $id)
    {
        $service = AddonServiceConfig::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'service_type' => 'required|in:extra_job_posting,job_boost,candidate_contact,diploma_verification,skills_test,custom',
            'boost_multiplier' => 'nullable|integer|min:1',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        if ($validated['name'] !== $service->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

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

        $service->update($validated);

        return redirect()->route('admin.recruiter-services.index')->with('success', 'Service recruteur modifié avec succès');
    }

    public function destroy($id)
    {
        $service = AddonServiceConfig::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.recruiter-services.index')->with('success', 'Service recruteur supprimé avec succès');
    }

    public function toggle($id)
    {
        $service = AddonServiceConfig::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        $status = $service->is_active ? 'activé' : 'désactivé';
        return redirect()->route('admin.recruiter-services.index')->with('success', "Service {$status} avec succès");
    }

    public function show($id)
    {
        $service = AddonServiceConfig::findOrFail($id);
        return view('admin.monetization.recruiter-services.show', compact('service'));
    }
}
