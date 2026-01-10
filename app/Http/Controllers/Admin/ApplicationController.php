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
            'status' => 'required|in:accepted,rejected',
            'internal_notes' => 'nullable|string',
        ]);

        if ($request->filled('internal_notes')) {
            $validated['internal_notes'] = $request->internal_notes;
        }

        // Charger les relations nécessaires pour la notification
        $application->load(['user', 'job.company']);

        $application->update($validated);

        // Marquer comme répondu
        if (!$application->responded_at) {
            $application->responded_at = now();
            $application->save();
        }

        // Envoyer notification push + email au candidat
        try {
            $status = $validated['status'];

            if ($application->user) {
                // 1. Envoyer la notification push
                if ($application->user->fcm_token) {
                    $notificationService = app(\App\Services\NotificationService::class);

                    if ($status === 'accepted') {
                        $title = "Candidature acceptée 🎉";
                        $message = "Félicitations ! Votre candidature pour {$application->job->title} chez {$application->job->company->name} a été acceptée.";
                        $type = 'application_accepted';
                    } else {
                        $title = "Candidature non retenue";
                        $message = "Votre candidature pour {$application->job->title} chez {$application->job->company->name} n'a pas été retenue cette fois.";
                        $type = 'application_rejected';
                    }

                    $notificationService->sendToUser(
                        $application->user,
                        $title,
                        $message,
                        $type,
                        [
                            'application_id' => $application->id,
                            'job_id' => $application->job->id,
                            'job_title' => $application->job->title,
                            'company_name' => $application->job->company->name,
                            'status' => $status,
                        ]
                    );
                }

                // 2. Envoyer l'email (synchrone)
                if ($status === 'accepted') {
                    $application->user->notify(new \App\Notifications\ApplicationAcceptedNotification($application));
                } else {
                    $application->user->notify(new \App\Notifications\ApplicationRejectedNotification($application));
                }

                \Log::info('Notification + Email candidature envoyés', [
                    'application_id' => $application->id,
                    'user_id' => $application->user->id,
                    'status' => $status,
                ]);
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas le processus
            \Log::error('Erreur envoi notification candidature', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->back()
            ->with('success', 'Statut de la candidature mis à jour avec succès');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = Application::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
