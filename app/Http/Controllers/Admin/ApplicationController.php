<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = Application::with(['job.company', 'user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    public function show(Application $application): View
    {
        $application->load(['job.company', 'user']);
        $application->markAsViewed();

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,viewed,shortlisted,rejected,interview,accepted',
            'internal_notes' => 'nullable|string',
        ]);

        if ($request->filled('internal_notes')) {
            $validated['internal_notes'] = $request->internal_notes;
        }

        $application->update($validated);

        return redirect()->back()
            ->with('success', 'Statut de la candidature mis à jour');
    }
}
