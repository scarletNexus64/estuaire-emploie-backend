<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalizationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DigitalizationRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = DigitalizationRequest::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', '%' . $search . '%')
                  ->orWhere('company_name', 'like', '%' . $search . '%')
                  ->orWhere('contact', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(20)->withQueryString();

        return view('admin.digitalization-requests.index', compact('requests'));
    }

    public function show(DigitalizationRequest $digitalizationRequest): View
    {
        $digitalizationRequest->load('user');

        return view('admin.digitalization-requests.show', compact('digitalizationRequest'));
    }

    public function process(Request $request, DigitalizationRequest $digitalizationRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $previousStatus = $digitalizationRequest->status;

        $digitalizationRequest->update([
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'] ?? $digitalizationRequest->admin_notes,
            'processed_at' => $validated['status'] === 'pending' ? null : now(),
        ]);

        // Notifier le demandeur lorsque le statut passe à traité / rejeté.
        if (
            $previousStatus !== $validated['status']
            && in_array($validated['status'], ['processed', 'rejected'], true)
            && $digitalizationRequest->user
        ) {
            $isProcessed = $validated['status'] === 'processed';
            $title = $isProcessed
                ? 'Demande de digitalisation traitée ✅'
                : 'Demande de digitalisation';
            $message = $isProcessed
                ? "Votre demande « {$digitalizationRequest->project_name} » a été traitée. Notre équipe vous recontacte."
                : "Votre demande « {$digitalizationRequest->project_name} » n'a pas été retenue.";

            app(\App\Services\NotificationService::class)->sendToUser(
                $digitalizationRequest->user,
                $title,
                $message,
                'digitalization_status_changed',
                [
                    'digitalization_request_id' => $digitalizationRequest->id,
                    'project_name' => $digitalizationRequest->project_name,
                    'status' => $validated['status'],
                ]
            );
        }

        return redirect()->route('admin.digitalization-requests.show', $digitalizationRequest)
            ->with('success', 'Demande mise à jour avec succès.');
    }

    public function destroy(DigitalizationRequest $digitalizationRequest): RedirectResponse
    {
        $digitalizationRequest->delete();

        return redirect()->route('admin.digitalization-requests.index')
            ->with('success', 'Demande supprimée avec succès.');
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (!empty($ids)) {
            DigitalizationRequest::whereIn('id', $ids)->delete();
        }

        return redirect()->route('admin.digitalization-requests.index')
            ->with('success', count($ids) . ' demande(s) supprimée(s).');
    }
}
