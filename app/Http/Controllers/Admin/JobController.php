<?php

namespace App\Http\Controllers\Admin;

use App\Events\JobPublished;
use App\Http\Controllers\Controller;
use App\Jobs\SendJobPublishedNotification;
use App\Models\Job;
use App\Models\Company;
use App\Models\CompanyCategory;
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
        $query = Job::with(['company', 'category'])
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
        $companies = Company::where('status', 'verified')
            ->with(['categories:id,level_1,level_2,level_3'])
            ->orderBy('name')
            ->get();
        $contractTypes = ContractType::orderBy('name')->get();

        // Carte company_id => liste des CompanyCategory level_3 dont le level_2
        // fait partie des catégories de l'entreprise (même règle que API
        // myCompanyLevel3Sectors). Sérialisée en JSON dans la vue pour le filtre JS.
        $companyLevel3Map = $this->buildCompanyLevel3Map($companies);

        return view('admin.jobs.create', compact('companies', 'contractTypes', 'companyLevel3Map'));
    }

    /**
     * Construit une map [company_id => [CompanyCategory level_3...]] pour le JS,
     * en se basant sur les niveau 2 des catégories rattachées à chaque entreprise.
     */
    private function buildCompanyLevel3Map($companies): array
    {
        $map = [];
        // Pré-charger tous les level_3 actifs une seule fois pour éviter N+1
        $allLevel3 = CompanyCategory::where('is_active', true)
            ->whereNotNull('level_3')
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get(['id', 'level_1', 'level_2', 'level_3']);

        foreach ($companies as $company) {
            $companyLevel2 = $company->categories->pluck('level_2')->filter()->unique()->values();
            if ($companyLevel2->isEmpty()) {
                $map[$company->id] = [];
                continue;
            }
            $map[$company->id] = $allLevel3
                ->whereIn('level_2', $companyLevel2)
                ->map(fn ($c) => [
                    'id' => $c->id,
                    'label' => trim(($c->level_2 ? $c->level_2 . ' — ' : '') . $c->level_3),
                ])
                ->values()
                ->all();
        }
        return $map;
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'nullable|exists:company_categories,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'experience_level' => 'nullable|in:junior,intermediaire,senior,expert',
            'visibility' => 'required|in:national,local',
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

        // Créer l'offre
        $job = Job::create($validated);

        // Dispatcher l'événement si le job est publié
        if ($job->status === 'published') {
            JobPublished::dispatch($job);
        }

        // Rediriger vers la page de proposition de test de compétences
        // (équivalent du dialog "Configurer un test ?" côté Flutter)
        return redirect()
            ->route('admin.jobs.skill-test-prompt', $job)
            ->with('success', 'Offre créée avec succès. Voulez-vous y associer un test de compétences ?');
    }

    /**
     * Affiche la page proposant de créer un test de compétences pour le job
     * qui vient d'être créé (équivalent du dialog Flutter post-publication).
     */
    public function skillTestPrompt(Job $job): View
    {
        $job->load('company');
        return view('admin.jobs.skill-test-prompt', compact('job'));
    }

    public function show(Job $job): View
    {
        $job->load(['company', 'category', 'contractType', 'postedBy', 'applications.user']);

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job): View
    {
        $companies = Company::where('status', 'verified')
            ->with(['categories:id,level_1,level_2,level_3'])
            ->orderBy('name')
            ->get();
        $contractTypes = ContractType::orderBy('name')->get();
        $companyLevel3Map = $this->buildCompanyLevel3Map($companies);

        return view('admin.jobs.edit', compact('job', 'companies', 'contractTypes', 'companyLevel3Map'));
    }

    public function update(Request $request, Job $job): RedirectResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'nullable|exists:company_categories,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'experience_level' => 'nullable|in:junior,intermediaire,senior,expert',
            'visibility' => 'required|in:national,local',
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

        // Dispatcher le job asynchrone pour envoyer les notifications en arrière-plan
        if ($wasNotPublished) {
            SendJobPublishedNotification::dispatch($job);

            return redirect()->route('admin.jobs.index')
                ->with('success', 'Offre publiée avec succès ! Les notifications sont en cours d\'envoi en arrière-plan.');
        }

        return redirect()->route('admin.jobs.index')
            ->with('info', 'Cette offre était déjà publiée.');
    }

    /**
     * Affiche la page d'envoi de notifications avec progress bar
     */
    public function showSendNotifications(Job $job): View
    {
        $job->load(['company', 'category']);

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

        $job->load(['company', 'category']);

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
                'message' => __('job.all_users_notified'),
                'sent' => 0,
                'failed' => 0,
            ]);
        }

        $notificationService = app(\App\Services\NotificationService::class);

        $title = "Nouvelle offre : {$job->title}";
        $message = "{$job->company->name} recrute à {$job->company?->city}";

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
                        'location' => $job->company?->city,
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

        $job->load(['company', 'category']);

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
                'message' => __('job.all_notifications_sent'),
                'sent' => 0,
                'failed' => 0,
            ]);
        }

        $sent = 0;
        $failed = 0;
        $errors = [];

        // Envoyer les notifications directement (BULK - database uniquement, FCM géré par SendJobPublishedNotification)
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
                \Log::error('Erreur envoi notification nouveau job', [
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

        Log::info('Lot de notifications envoyé pour job (database only)', [
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