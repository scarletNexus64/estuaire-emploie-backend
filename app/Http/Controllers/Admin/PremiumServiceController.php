<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumServiceConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PremiumServiceController extends Controller
{
    public function index()
    {
        $services = PremiumServiceConfig::orderBy('display_order')->get();
        Log::info('Admin viewed premium services list', ['admin' => $services]);
        return view('admin.monetization.premium-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.monetization.premium-services.form', [
            'service' => null,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'service_type' => 'required|in:cv_premium,verified_badge,sms_alerts,cv_review,interview_coaching,custom',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_popular'] = $request->boolean('is_popular');

        PremiumServiceConfig::create($validated);

        return redirect()->route('admin.premium-services.index')
            ->with('success', 'Service premium créé avec succès');
    }

    public function edit($id)
    {
        $service = PremiumServiceConfig::findOrFail($id);
        return view('admin.monetization.premium-services.form', [
            'service' => $service,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $service = PremiumServiceConfig::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'service_type' => 'required|in:cv_premium,verified_badge,sms_alerts,cv_review,interview_coaching,custom',
            'display_order' => 'required|integer|min:0',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:10',
        ]);

        if ($validated['name'] !== $service->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_popular'] = $request->boolean('is_popular');

        $service->update($validated);

        return redirect()->route('admin.premium-services.index')
            ->with('success', 'Service premium modifié avec succès');
    }

    public function destroy($id)
    {
        $service = PremiumServiceConfig::findOrFail($id);
        $service->delete();

        return redirect()->route('admin.premium-services.index')
            ->with('success', 'Service premium supprimé avec succès');
    }

    public function show($id)
    {
        $service = PremiumServiceConfig::with('userServices')->findOrFail($id);
        return view('admin.monetization.premium-services.show', compact('service'));
    }

    public function toggle($id)
    {
        $service = PremiumServiceConfig::findOrFail($id);
        $service->is_active = !$service->is_active;
        $service->save();

        return redirect()->route('admin.premium-services.index')
            ->with('success', 'Service ' . ($service->is_active ? 'activé' : 'désactivé'));
    }
}
