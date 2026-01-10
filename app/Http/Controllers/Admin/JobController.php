<?php

namespace App\Http\Controllers\Admin;

use App\Events\JobPublished;
use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Company;
use App\Models\Category;
use App\Models\Location;
use App\Models\ContractType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $query = Job::with(['company', 'category', 'location'])
            ->withCount('applications');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Company filter
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $jobs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        $companies = Company::where('status', 'verified')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $contractTypes = ContractType::orderBy('name')->get();

        return view('admin.jobs.create', compact('companies', 'categories', 'locations', 'contractTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'experience_level' => 'nullable|string|max:50',
            'status' => 'required|in:draft,pending,published,closed,expired',
            'application_deadline' => 'nullable|date|after:today',
        ]);

        // Auteur de l'offre
        $validated['posted_by'] = Auth::id();

        // Gestion propre des checkbox
        $validated['salary_negotiable'] = $request->boolean('salary_negotiable');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Publication auto
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Création
        $job = Job::create($validated);

        // Dispatcher l'événement si le job est publié
        if ($job->status === 'published') {
            JobPublished::dispatch($job);
        }

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Offre créée avec succès');
    }

    public function show(Job $job): View
    {
        $job->load(['company', 'category', 'location', 'contractType', 'postedBy', 'applications.user']);

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job): View
    {
        $companies = Company::where('status', 'verified')->orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $contractTypes = ContractType::orderBy('name')->get();

        return view('admin.jobs.edit', compact('job', 'companies', 'categories', 'locations', 'contractTypes'));
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'experience_level' => 'nullable|string|max:50',
            'status' => 'required|in:draft,pending,published,closed,expired',
            'application_deadline' => 'nullable|date',
        ]);

        $validated['salary_negotiable'] = $request->boolean('salary_negotiable');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Vérifier si le job passe à "published" pour la première fois
        $wasNotPublished = $job->status !== 'published';

        if ($validated['status'] === 'published' && !$job->published_at) {
            $validated['published_at'] = now();
        }

        $job->update($validated);

        // Dispatcher l'événement si le job vient d'être publié
        if ($wasNotPublished && $job->status === 'published') {
            JobPublished::dispatch($job);
        }

        return redirect()
            ->route('admin.jobs.index')
            ->with('success', 'Offre mise à jour avec succès');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Offre supprimée avec succès');
    }

    public function publish(Job $job)
    {
        // Vérifier si le job n'était pas déjà publié
        $wasNotPublished = $job->status !== 'published';

        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Dispatcher l'événement si le job vient d'être publié
        if ($wasNotPublished) {
            JobPublished::dispatch($job);
        }

        // Rediriger vers la page d'envoi de notifications avec progress bar
        return redirect()->route('admin.jobs.send-notifications', $job);
    }

    /**
     * Affiche la page d'envoi de notifications avec progress bar
     */
    public function showSendNotifications(Job $job): View
    {
        $job->load(['company', 'location', 'category']);

        // Compter les utilisateurs pour les PUSH (candidats + recruteurs) sauf l'auteur
        $totalPushUsers = \App\Models\User::whereIn('role', ['candidate', 'recruiter'])
            ->whereNotNull('fcm_token')
            ->when($job->posted_by, function ($query) use ($job) {
                $query->where('id', '!=', $job->posted_by);
            })
            ->count();

        // Compter les utilisateurs pour les EMAILS (candidats actifs avec email vérifié)
        $totalEmailUsers = \App\Models\User::where('role', 'candidate')
            ->where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->count();

        return view('admin.jobs.send-notifications', compact('job', 'totalPushUsers', 'totalEmailUsers'));
    }

    /**
     * Envoie les notifications par lots (appelé via AJAX)
     */
    public function sendNotificationsBatch(Request $request, Job $job)
    {
        $validated = $request->validate([
            'batch' => 'required|integer|min:0',
            'batch_size' => 'required|integer|min:1|max:100',
        ]);

        $batchNumber = $validated['batch'];
        $batchSize = $validated['batch_size'];

        $job->load(['company', 'location', 'category']);

        // Récupérer TOUS les utilisateurs (candidats + recruteurs) pour ce lot, SAUF l'auteur du job
        $users = \App\Models\User::whereIn('role', ['candidate', 'recruiter'])
            ->whereNotNull('fcm_token')
            ->when($job->posted_by, function ($query) use ($job) {
                $query->where('id', '!=', $job->posted_by);
            })
            ->skip($batchNumber * $batchSize)
            ->take($batchSize)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Tous les utilisateurs ont reçu la notification',
                'sent' => 0,
                'failed' => 0,
            ]);
        }

        $notificationService = app(\App\Services\NotificationService::class);

        $title = "Nouvelle offre : {$job->title}";
        $message = "{$job->company->name} recrute à {$job->location->name}";

        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($users as $user) {
            try {
                $success = $notificationService->sendToUser(
                    $user,
                    $title,
                    $message,
                    'job_published',
                    [
                        'job_id' => $job->id,
                        'job_title' => $job->title,
                        'company_name' => $job->company->name,
                        'location' => $job->location->name,
                        'category' => $job->category->name ?? null,
                    ]
                );

                if ($success) {
                    $sent++;
                } else {
                    $failed++;
                    $errors[] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'error' => 'Échec de l\'envoi',
                    ];
                }
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage(),
                ];
                \Log::error('Erreur envoi notification job', [
                    'job_id' => $job->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Calculer la progression
        $totalUsers = \App\Models\User::whereIn('role', ['candidate', 'recruiter'])
            ->whereNotNull('fcm_token')
            ->when($job->posted_by, function ($query) use ($job) {
                $query->where('id', '!=', $job->posted_by);
            })
            ->count();

        $processed = ($batchNumber + 1) * $batchSize;
        $completed = $processed >= $totalUsers;

        Log::info('Lot de notifications envoyé pour job', [
            'job_id' => $job->id,
            'batch' => $batchNumber,
            'sent' => $sent,
            'failed' => $failed,
            'total' => $totalUsers,
        ]);

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors,
            'progress' => [
                'current' => min($processed, $totalUsers),
                'total' => $totalUsers,
                'percentage' => min(100, round(($processed / $totalUsers) * 100, 2)),
            ],
        ]);
    }

    /**
     * Envoie les emails par lots (appelé via AJAX)
     */
    public function sendEmailsBatch(Request $request, Job $job)
    {
        $validated = $request->validate([
            'batch' => 'required|integer|min:0',
            'batch_size' => 'required|integer|min:1|max:50',
        ]);

        $batchNumber = $validated['batch'];
        $batchSize = $validated['batch_size'];

        $job->load(['company', 'location', 'category']);

        // Récupérer les candidats actifs avec email vérifié pour ce lot
        $users = \App\Models\User::where('role', 'candidate')
            ->where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->skip($batchNumber * $batchSize)
            ->take($batchSize)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Tous les emails ont été envoyés',
                'sent' => 0,
                'failed' => 0,
            ]);
        }

        $sent = 0;
        $failed = 0;
        $errors = [];

        // Envoyer les emails directement (sans queue)
        foreach ($users as $user) {
            try {
                $user->notify(new \App\Notifications\NewJobNotification($job));
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage(),
                ];
                \Log::error('Erreur envoi email nouveau job', [
                    'job_id' => $job->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Calculer la progression
        $totalUsers = \App\Models\User::where('role', 'candidate')
            ->where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->count();

        $processed = ($batchNumber + 1) * $batchSize;
        $completed = $processed >= $totalUsers;

        Log::info('Lot d\'emails envoyé pour job', [
            'job_id' => $job->id,
            'batch' => $batchNumber,
            'sent' => $sent,
            'failed' => $failed,
            'total' => $totalUsers,
        ]);

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors,
            'progress' => [
                'current' => min($processed, $totalUsers),
                'total' => $totalUsers,
                'percentage' => min(100, round(($processed / $totalUsers) * 100, 2)),
            ],
        ]);
    }

    public function feature(Job $job): RedirectResponse
    {
        $job->update([
            'is_featured' => !$job->is_featured,
        ]);

        $message = $job->is_featured
            ? 'Offre mise en avant avec succès'
            : 'Offre retirée de la mise en avant';

        return redirect()->back()
            ->with('success', $message);
    }

    public function bulkDelete(Request $request)
    {
        try {
            $ids = json_decode($request->input('ids'), true);

            if (!is_array($ids) || empty($ids)) {
                return redirect()->back()->with('error', 'Aucun élément sélectionné');
            }

            $count = Job::whereIn('id', $ids)->delete();

            return redirect()->back()->with('success', "$count élément(s) supprimé(s) avec succès");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}