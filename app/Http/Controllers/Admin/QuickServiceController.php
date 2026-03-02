<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuickService;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuickServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = QuickService::with(['user', 'category'])
            ->withCount('responses');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('service_category_id', $request->category_id);
        }

        // Urgency filter
        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $services = $query->latest()->paginate(20)->withQueryString();
        $categories = ServiceCategory::ordered()->get();

        return view('admin.quick-services.index', compact('services', 'categories'));
    }

    public function show($id): View
    {
        $service = QuickService::with(['user', 'category', 'responses.user'])
            ->withCount('responses')
            ->findOrFail($id);

        return view('admin.quick-services.show', compact('service'));
    }

    public function destroy($id): RedirectResponse
    {
        $service = QuickService::findOrFail($id);

        // Supprimer les images
        if ($service->images) {
            foreach ($service->images as $image) {
                \Storage::disk('public')->delete($image);
            }
        }

        $service->delete();

        return redirect()->route('admin.quick-services.index')
            ->with('success', 'Service supprimé avec succès');
    }

    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,completed,cancelled',
        ]);

        $service = QuickService::findOrFail($id);
        $service->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Statut mis à jour avec succès');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:quick_services,id',
        ]);

        $services = QuickService::whereIn('id', $validated['ids'])->get();

        foreach ($services as $service) {
            // Supprimer les images
            if ($service->images) {
                foreach ($service->images as $image) {
                    \Storage::disk('public')->delete($image);
                }
            }
            $service->delete();
        }

        return redirect()->route('admin.quick-services.index')
            ->with('success', count($validated['ids']) . ' service(s) supprimé(s) avec succès');
    }

    public function approve($id): RedirectResponse
    {
        $service = QuickService::findOrFail($id);

        // Vérifier si le service n'était pas déjà approuvé
        $wasNotApproved = $service->status === 'pending';

        $service->update([
            'status' => 'open',
            'approved_at' => now(),
        ]);

        // Dispatcher le job asynchrone pour envoyer les notifications en arrière-plan
        if ($wasNotApproved) {
            \App\Jobs\SendQuickServiceNotification::dispatch($service);

            return redirect()->route('admin.quick-services.index')
                ->with('success', 'Service approuvé avec succès ! Les notifications sont en cours d\'envoi en arrière-plan.');
        }

        return redirect()->route('admin.quick-services.index')
            ->with('info', 'Ce service était déjà approuvé.');
    }
}
