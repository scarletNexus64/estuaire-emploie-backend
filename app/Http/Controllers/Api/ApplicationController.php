<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\ViewedContact;
use App\Services\FirebaseNotificationService;
use App\Services\NotificationService;
use App\Services\Recruiter\RecruiterServicePurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Applications",
 *     description="API Endpoints pour la gestion des candidatures"
 * )
 */
class ApplicationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/jobs/{id}/apply",
     *     summary="Postuler à une offre d'emploi",
     *     description="Permet à un candidat de postuler à une offre avec CV, lettre de motivation et portfolio",
     *     tags={"Applications"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="cv", type="string", format="binary", description="Fichier CV (PDF, DOC, DOCX - max 5MB)"),
     *                 @OA\Property(property="cover_letter", type="string", description="Lettre de motivation"),
     *                 @OA\Property(property="portfolio_url", type="string", description="URL du portfolio")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Candidature soumise avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Candidature soumise avec succès")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Vous avez déjà postulé à cette offre"),
     *     @OA\Response(response=404, description="Offre non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation (CV requis, format invalide, etc.)")
     * )
     */
    public function apply(Request $request, Job $job): JsonResponse
    {
        if ($job->status !== 'published') {
            return response()->json([
                'message' => 'Cette offre n\'est plus disponible',
            ], 404);
        }

        // Vérifier si l'utilisateur a déjà postulé (incluant les candidatures soft deleted)
        $existingApplication = Application::withTrashed()
            ->where('job_id', $job->id)
            ->where('user_id', $request->user()->id)
            ->first();

        // Si une candidature active existe, bloquer
        if ($existingApplication && !$existingApplication->trashed()) {
            return response()->json([
                'message' => 'Vous avez déjà postulé à cette offre',
            ], 400);
        }

        Log::info('Nouvelle candidature pour l\'offre ID: ' . $job->id . ' par l\'utilisateur ID: ' . $request->user()->id);

        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'cover_letter' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
            'portfolio_id' => 'nullable|exists:portfolios,id',
        ]);

        // Upload du CV
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        try {
            // Si une candidature soft deleted existe, la restaurer et la mettre à jour
            if ($existingApplication && $existingApplication->trashed()) {
                Log::info('Restauration de la candidature soft deleted ID: ' . $existingApplication->id);

                // Supprimer l'ancien CV si différent
                if ($existingApplication->cv_path && \Storage::disk('public')->exists($existingApplication->cv_path)) {
                    \Storage::disk('public')->delete($existingApplication->cv_path);
                }

                // Restaurer et mettre à jour
                $existingApplication->restore();
                $existingApplication->update([
                    'cv_path' => $cvPath,
                    'cover_letter' => $validated['cover_letter'] ?? null,
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                    'portfolio_id' => $validated['portfolio_id'] ?? null,
                    'status' => 'pending',
                    'viewed_at' => null,
                    'responded_at' => null,
                    'internal_notes' => null,
                ]);

                $application = $existingApplication;
            } else {
                // Créer une nouvelle candidature
                $application = Application::create([
                    'job_id' => $job->id,
                    'user_id' => $request->user()->id,
                    'cv_path' => $cvPath,
                    'cover_letter' => $validated['cover_letter'] ?? null,
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                    'portfolio_id' => $validated['portfolio_id'] ?? null,
                    'status' => 'pending',
                ]);
            }

            $application->load(['job.company', 'user', 'portfolio']);

            // Envoi de notification à tous les recruteurs de l'entreprise
            // Utilise le NotificationService pour envoyer ET enregistrer en BDD
            $recruiters = $application->job->company->recruiters()->with('user')->get();
            $notificationService = app(NotificationService::class);

            foreach ($recruiters as $recruiter) {
                $recruiterUser = $recruiter->user;
                if ($recruiterUser) {
                    // 1. Envoyer la notification push
                    $notificationService->sendToUser(
                        $recruiterUser,
                        "Nouvelle candidature",
                        "{$request->user()->name} a postulé à votre offre: {$application->job->title}",
                        'application_received',
                        [
                            'job_id' => $application->job->id,
                            'job_title' => $application->job->title,
                            'applicant_id' => $request->user()->id,
                            'applicant_name' => $request->user()->name,
                            'application_id' => $application->id,
                        ]
                    );

                    // 2. Envoyer l'email (synchrone)
                    try {
                        $recruiterUser->notify(new \App\Notifications\NewApplicationReceivedNotification($application));
                    } catch (\Exception $e) {
                        \Log::error('Erreur envoi email nouvelle candidature', [
                            'application_id' => $application->id,
                            'recruiter_id' => $recruiterUser->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            return response()->json([
                'data' => $application,
                'message' => 'Candidature soumise avec succès',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la candidature: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la soumission de la candidature',
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/jobs/{id}/apply-with-test",
     *     summary="Postuler à une offre avec test de compétences obligatoire",
     *     description="Permet à un candidat de postuler avec CV et résultats de test en une seule requête atomique",
     *     tags={"Applications"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"cv", "test_id", "test_answers", "test_started_at"},
     *                 @OA\Property(property="cv", type="string", format="binary", description="Fichier CV (PDF, DOC, DOCX - max 5MB)"),
     *                 @OA\Property(property="cover_letter", type="string", description="Lettre de motivation"),
     *                 @OA\Property(property="portfolio_url", type="string", description="URL du portfolio"),
     *                 @OA\Property(property="portfolio_id", type="integer", description="ID du portfolio"),
     *                 @OA\Property(property="test_id", type="integer", description="ID du test de compétences"),
     *                 @OA\Property(property="test_answers", type="array", @OA\Items(type="string"), description="Réponses du candidat (ex: ['A', 'B', 'C'])"),
     *                 @OA\Property(property="test_started_at", type="string", format="date-time", description="Date/heure de début du test")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Candidature soumise avec succès avec test réussi",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="test_result", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Test échoué - score insuffisant",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Score insuffisant pour postuler"),
     *             @OA\Property(property="test_failed", type="boolean", example=true),
     *             @OA\Property(property="score", type="integer"),
     *             @OA\Property(property="passing_score", type="integer")
     *         )
     *     )
     * )
     */
    public function applyWithTest(Request $request, Job $job): JsonResponse
    {
        if ($job->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Cette offre n\'est plus disponible',
            ], 404);
        }

        // Vérifier si l'utilisateur a déjà postulé
        $existingApplication = Application::withTrashed()
            ->where('job_id', $job->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingApplication && !$existingApplication->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous avez déjà postulé à cette offre',
            ], 400);
        }

        Log::info('Nouvelle candidature avec test pour l\'offre ID: ' . $job->id);

        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
            'portfolio_id' => 'nullable|exists:portfolios,id',
            'test_id' => 'required|exists:recruiter_skill_tests,id',
            'test_answers' => 'required|array',
            'test_started_at' => 'required|date',
        ]);

        // Charger le test
        $test = \App\Models\RecruiterSkillTest::where('is_active', true)
            ->where('job_id', $job->id)
            ->findOrFail($validated['test_id']);

        // Calculer le score
        $score = $this->calculateTestScore($test->questions, $validated['test_answers']);
        $passed = $score >= $test->passing_score;

        // Si le test est échoué, ne pas créer l'application
        if (!$passed) {
            Log::info('Test échoué - candidature refusée', [
                'user_id' => $request->user()->id,
                'job_id' => $job->id,
                'score' => $score,
                'passing_score' => $test->passing_score,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Score insuffisant pour postuler à cette offre',
                'test_failed' => true,
                'score' => $score,
                'passing_score' => $test->passing_score,
            ], 400);
        }

        // Upload du CV
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        try {
            \DB::beginTransaction();

            // Si une candidature soft deleted existe, la restaurer
            if ($existingApplication && $existingApplication->trashed()) {
                Log::info('Restauration de la candidature soft deleted ID: ' . $existingApplication->id);

                if ($existingApplication->cv_path && \Storage::disk('public')->exists($existingApplication->cv_path)) {
                    \Storage::disk('public')->delete($existingApplication->cv_path);
                }

                $existingApplication->restore();
                $existingApplication->update([
                    'cv_path' => $cvPath,
                    'cover_letter' => $validated['cover_letter'] ?? null,
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                    'portfolio_id' => $validated['portfolio_id'] ?? null,
                    'status' => 'pending',
                    'viewed_at' => null,
                    'responded_at' => null,
                    'internal_notes' => null,
                ]);

                $application = $existingApplication;
            } else {
                // Créer la candidature
                $application = Application::create([
                    'job_id' => $job->id,
                    'user_id' => $request->user()->id,
                    'cv_path' => $cvPath,
                    'cover_letter' => $validated['cover_letter'] ?? null,
                    'portfolio_url' => $validated['portfolio_url'] ?? null,
                    'portfolio_id' => $validated['portfolio_id'] ?? null,
                    'status' => 'pending',
                ]);
            }

            // Créer le résultat du test
            $testResult = \App\Models\ApplicationTestResult::create([
                'application_id' => $application->id,
                'recruiter_skill_test_id' => $test->id,
                'answers' => $validated['test_answers'],
                'score' => $score,
                'passed' => true, // Forcément true car on ne crée l'application que si réussi
                'started_at' => $validated['test_started_at'],
                'completed_at' => now(),
                'duration_seconds' => now()->diffInSeconds($validated['test_started_at']),
            ]);

            // Incrémenter l'usage du test
            $test->increment('times_used');

            $application->load(['job.company', 'user', 'portfolio', 'testResults']);

            // Envoi de notifications aux recruteurs
            $recruiters = $application->job->company->recruiters()->with('user')->get();
            $notificationService = app(\App\Services\NotificationService::class);

            foreach ($recruiters as $recruiter) {
                $recruiterUser = $recruiter->user;
                if ($recruiterUser) {
                    $notificationService->sendToUser(
                        $recruiterUser,
                        "Nouvelle candidature qualifiée",
                        "{$request->user()->name} a postulé avec succès (test réussi: {$score}/{$test->passing_score}) à votre offre: {$application->job->title}",
                        'application_received',
                        [
                            'job_id' => $application->job->id,
                            'job_title' => $application->job->title,
                            'applicant_id' => $request->user()->id,
                            'applicant_name' => $request->user()->name,
                            'application_id' => $application->id,
                            'test_score' => $score,
                            'test_passed' => true,
                        ]
                    );

                    try {
                        $recruiterUser->notify(new \App\Notifications\NewApplicationReceivedNotification($application));
                    } catch (\Exception $e) {
                        \Log::error('Erreur envoi email', ['error' => $e->getMessage()]);
                    }
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Candidature soumise avec succès',
                'data' => $application,
                'test_result' => [
                    'score' => $score,
                    'passed' => true,
                    'passing_score' => $test->passing_score,
                ],
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Erreur lors de la candidature avec test: ' . $e->getMessage());

            // Supprimer le CV uploadé en cas d'erreur
            if ($cvPath && \Storage::disk('public')->exists($cvPath)) {
                \Storage::disk('public')->delete($cvPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission de la candidature',
            ], 500);
        }
    }

    /**
     * Calculer le score d'un test
     */
    protected function calculateTestScore(array $questions, array $answers): int
    {
        $totalQuestions = count($questions);
        if ($totalQuestions === 0) {
            return 0;
        }

        $correctAnswers = 0;

        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            $correctAnswer = $question['correct_answer'] ?? null;

            if ($userAnswer !== null && $correctAnswer !== null) {
                if ($userAnswer === $correctAnswer) {
                    $correctAnswers++;
                }
            }
        }

        return (int) (($correctAnswers / $totalQuestions) * 100);
    }

    /**
     * @OA\Get(
     *     path="/api/my-applications/stats",
     *     summary="Statistiques de mes candidatures",
     *     description="Retourne les statistiques des candidatures de l'utilisateur connecté",
     *     tags={"Applications"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques des candidatures",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="total", type="integer", example=15),
     *                 @OA\Property(property="pending", type="integer", example=5),
     *                 @OA\Property(property="accepted", type="integer", example=3),
     *                 @OA\Property(property="rejected", type="integer", example=7)
     *             )
     *         )
     *     )
     * )
     */
    public function myApplicationsStats(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $stats = [
            'total' => Application::where('user_id', $userId)->count(),
            'pending' => Application::where('user_id', $userId)->where('status', 'pending')->count(),
            'accepted' => Application::where('user_id', $userId)->where('status', 'accepted')->count(),
            'rejected' => Application::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/my-applications",
     *     summary="Mes candidatures",
     *     tags={"Applications"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut",
     *         required=false,
     *         @OA\Schema(type="string", enum={"accepted", "rejected"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste de mes candidatures",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function myApplications(Request $request): JsonResponse
    {
        $query = Application::with(['job.company', 'job.location', 'job.category', 'job.contractType', 'conversation', 'portfolio'])
            ->where('user_id', $request->user()->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20);

        return response()->json($applications);
    }

    /**
     * @OA\Get(
     *     path="/api/applications/{id}",
     *     summary="Détails d'une candidature",
     *     tags={"Applications"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la candidature",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Non autorisé"),
     *     @OA\Response(response=404, description="Candidature non trouvée")
     * )
     */
    public function show(Request $request, Application $application): JsonResponse
    {
        if ($application->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Non autorisé',
            ], 403);
        }

        $application->load(['job.company', 'job.category', 'job.location', 'portfolio']);

        return response()->json([
            'data' => $application,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/recruiter/applications",
     *     summary="Candidatures reçues (Recruteur)",
     *     description="Récupère toutes les candidatures reçues pour les offres d'emploi de l'entreprise du recruteur connecté",
     *     operationId="getReceivedApplications",
     *     tags={"Applications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut",
     *         required=false,
     *         @OA\Schema(type="string", enum={"accepted", "rejected"})
     *     ),
     *     @OA\Parameter(
     *         name="job_id",
     *         in="query",
     *         description="Filtrer par offre d'emploi",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des candidatures reçues",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(
     *         response=403,
     *         description="Vous n'êtes pas recruteur",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'êtes pas recruteur")
     *         )
     *     )
     * )
     */
    public function receivedApplications(Request $request): JsonResponse
    {
        $user = auth()->user();
        $recruiter = $user->recruiter;

        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        // Vérifier l'abonnement actif
        if (!$user->hasActiveSubscription()) {
            return response()->json([
                'message' => 'Vous devez avoir un abonnement actif pour voir les candidatures',
                'error_code' => 'NO_SUBSCRIPTION',
                'subscription_required' => true,
            ], 403);
        }

        // === DEBUG LOGS ===
        \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        \Log::info('[ReceivedApplications] 🔍 DEBUG INFO');
        \Log::info('[ReceivedApplications] User ID: ' . $user->id);
        \Log::info('[ReceivedApplications] User Name: ' . $user->name);
        \Log::info('[ReceivedApplications] Recruiter ID: ' . ($recruiter->id ?? 'NULL'));
        \Log::info('[ReceivedApplications] Company ID: ' . ($recruiter->company_id ?? 'NULL'));

        // Vérifier combien de jobs existent pour cette company
        $jobsCount = \App\Models\Job::where('company_id', $recruiter->company_id)->count();
        \Log::info('[ReceivedApplications] Jobs count for company: ' . $jobsCount);

        // Vérifier combien d'applications existent au total
        $totalApplications = \App\Models\Application::count();
        \Log::info('[ReceivedApplications] Total applications in DB: ' . $totalApplications);

        // Vérifier les applications pour les jobs de cette company
        $applicationsForCompanyJobs = \App\Models\Application::whereHas('job', function ($q) use ($recruiter) {
            $q->where('company_id', $recruiter->company_id);
        })->count();
        \Log::info('[ReceivedApplications] Applications for company jobs: ' . $applicationsForCompanyJobs);

        // Lister les job_ids qui ont des candidatures
        $jobIdsWithApplications = \App\Models\Application::whereHas('job', function ($q) use ($recruiter) {
            $q->where('company_id', $recruiter->company_id);
        })->pluck('job_id')->unique()->toArray();
        \Log::info('[ReceivedApplications] Job IDs with applications: ' . json_encode($jobIdsWithApplications));

        // Vérifier si le job_id demandé existe et appartient à cette company
        if ($request->has('job_id')) {
            $requestedJobBelongsToCompany = \App\Models\Job::where('id', $request->job_id)
                ->where('company_id', $recruiter->company_id)
                ->exists();
            \Log::info('[ReceivedApplications] Job ID ' . $request->job_id . ' belongs to company: ' . ($requestedJobBelongsToCompany ? 'YES' : 'NO'));
        }

        // Vérifier si la candidature a un utilisateur valide
        $applicationsWithUsers = \App\Models\Application::whereHas('user')
            ->whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->count();
        \Log::info('[ReceivedApplications] Applications with valid users: ' . $applicationsWithUsers);

        // Vérifier les candidatures SANS utilisateur (utilisateurs supprimés)
        $applicationsWithoutUsers = \App\Models\Application::whereDoesntHave('user')
            ->whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->count();
        \Log::info('[ReceivedApplications] Applications with deleted users: ' . $applicationsWithoutUsers);

        \Log::info('[ReceivedApplications] Request job_id filter: ' . ($request->job_id ?? 'NULL'));
        \Log::info('[ReceivedApplications] Request status filter: ' . ($request->status ?? 'NULL'));
        \Log::info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        // === END DEBUG LOGS ===

        $query = Application::whereHas('user')  // Exclure les candidatures dont l'utilisateur a été supprimé
            ->whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->with(['job.company', 'job.location', 'job.category', 'job.contractType', 'conversation', 'portfolio']);

        // Charger les infos utilisateur de base (sans contact sensible)
        $query->with(['user' => function ($q) {
            $q->select('id', 'name', 'profile_photo', 'experience_level', 'created_at');
        }]);

        // Filtrer par statut si fourni
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtrer par job_id si fourni
        if ($request->has('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Check access to full candidate info (if recruiter purchased candidate_contact service)
        $purchaseService = app(RecruiterServicePurchaseService::class);
        $company = $recruiter->company;

        // Transform applications to add access info
        $applications->getCollection()->transform(function ($application) use ($company, $purchaseService) {
            // Skip if user is null (should not happen with whereHas filter, but safety check)
            if (!$application->user) {
                return $application;
            }

            $hasFullAccess = $purchaseService->hasAccessToCandidateContact($company, $application->user);

            // If has access, load full user info
            if ($hasFullAccess) {
                $application->load(['user' => function ($q) {
                    $q->select('id', 'name', 'email', 'phone', 'profile_photo', 'experience_level', 'skills', 'created_at');
                }]);
                $application->has_full_access = true;
            } else {
                $application->has_full_access = false;
            }

            // Add diploma verification status
            $application->diploma_verification_requested = $purchaseService->hasRequestedDiplomaVerification($company, $application->user);

            // Add test results if any
            $application->load(['testResults.test:id,title,passing_score']);

            return $application;
        });

        // Ajouter les infos d'abonnement dans la réponse
        $response = $applications->toArray();
        $response['subscription_info'] = [
            'contacts_remaining' => $user->remainingContactsCount(),
            'contacts_limit' => $user->currentPlan()->contacts_limit,
        ];

        return response()->json($response);
    }

    /**
     * @OA\Patch(
     *     path="/api/applications/{id}/status",
     *     summary="Mettre à jour le statut d'une candidature (Recruteur)",
     *     description="Permet au recruteur de changer le statut d'une candidature (acceptée, refusée, en révision, etc.)",
     *     operationId="updateApplicationStatus",
     *     tags={"Applications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"accepted", "rejected"}, example="accepted"),
     *             @OA\Property(property="internal_notes", type="string", example="Candidat retenu pour le poste")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Statut de la candidature mis à jour"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé - Cette candidature ne concerne pas votre entreprise"
     *     ),
     *     @OA\Response(response=404, description="Candidature non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateStatus(Request $request, Application $application): JsonResponse
    {
        $recruiter = auth()->user()->recruiter;

        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        // Vérifier que la candidature concerne l'entreprise du recruteur
        if ($application->job->company_id !== $recruiter->company_id) {
            return response()->json([
                'message' => 'Cette candidature ne concerne pas votre entreprise',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
            'internal_notes' => 'nullable|string',
        ]);

        // Charger les relations nécessaires pour la notification
        $application->load(['user', 'job.company']);

        $application->update($validated);

        // Marquer comme "viewed" si ce n'est pas déjà fait
        if (!$application->viewed_at && $validated['status'] !== 'pending') {
            $application->viewed_at = now();
            $application->save();
        }

        // Marquer comme "responded" si accepté ou refusé
        if (in_array($validated['status'], ['accepted', 'rejected']) && !$application->responded_at) {
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
                } else if ($status === 'rejected') {
                    $application->user->notify(new \App\Notifications\ApplicationRejectedNotification($application));
                }

                Log::info('Notification + Email candidature envoyés', [
                    'application_id' => $application->id,
                    'user_id' => $application->user->id,
                    'status' => $status,
                ]);
            }
        } catch (\Exception $e) {
            // Log l'erreur mais ne bloque pas le processus
            Log::error('Erreur envoi notification candidature', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'message' => 'Statut de la candidature mis à jour',
            'data' => $application->fresh(['user', 'job']),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/applications/{id}/unlock-contact",
     *     summary="Débloquer les coordonnées d'un candidat (Recruteur)",
     *     description="Permet au recruteur de voir les coordonnées complètes d'un candidat. Consomme 1 contact du quota mensuel.",
     *     operationId="unlockCandidateContact",
     *     tags={"Applications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Coordonnées débloquées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="contact", type="object",
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string")
     *             ),
     *             @OA\Property(property="already_unlocked", type="boolean"),
     *             @OA\Property(property="contacts_remaining", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Limite de contacts atteinte ou pas d'abonnement"),
     *     @OA\Response(response=404, description="Candidature non trouvée")
     * )
     */
    public function unlockContact(Application $application): JsonResponse
    {
        $user = auth()->user();
        $recruiter = $user->recruiter;

        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        // Vérifier que la candidature concerne l'entreprise du recruteur
        if ($application->job->company_id !== $recruiter->company_id) {
            return response()->json([
                'message' => 'Cette candidature ne concerne pas votre entreprise',
            ], 403);
        }

        // Vérifier l'abonnement actif
        if (!$user->hasActiveSubscription()) {
            return response()->json([
                'message' => 'Vous devez avoir un abonnement actif pour voir les coordonnées des candidats',
                'error_code' => 'NO_SUBSCRIPTION',
                'subscription_required' => true,
            ], 403);
        }

        $candidateId = $application->user_id;

        // Vérifier si déjà débloqué
        $alreadyUnlocked = $user->viewedContacts()
            ->where('candidate_user_id', $candidateId)
            ->exists();

        if ($alreadyUnlocked) {
            // Déjà débloqué, retourner les infos sans consommer de quota
            $candidate = $application->user;
            return response()->json([
                'success' => true,
                'message' => 'Coordonnées déjà débloquées',
                'contact' => [
                    'name' => $candidate->name,
                    'email' => $candidate->email,
                    'phone' => $candidate->phone,
                ],
                'already_unlocked' => true,
                'contacts_remaining' => $user->remainingContactsCount(),
            ]);
        }

        // Vérifier la limite de contacts
        if (!$user->canViewContact()) {
            $plan = $user->currentPlan();
            return response()->json([
                'message' => "Vous avez atteint la limite de {$plan->contacts_limit} contacts de votre plan {$plan->name} ce mois-ci. Passez à un plan supérieur pour voir plus de contacts.",
                'error_code' => 'CONTACTS_LIMIT_REACHED',
                'limit' => $plan->contacts_limit,
                'used' => $user->viewedContacts()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'upgrade_required' => true,
            ], 403);
        }

        // Enregistrer le contact vu
        $user->viewedContacts()->create([
            'candidate_user_id' => $candidateId,
        ]);

        // 🎯 Incrémenter le compteur de contacts utilisés dans l'abonnement recruteur
        $subscription = $user->activeSubscription($user->role);
        if ($subscription) {
            $subscription->incrementContactsUsed();
        }

        // Retourner les coordonnées
        $candidate = $application->user;
        return response()->json([
            'success' => true,
            'message' => 'Coordonnées débloquées avec succès',
            'contact' => [
                'name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
            ],
            'already_unlocked' => false,
            'contacts_remaining' => $user->remainingContactsCount(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/applications/{id}/contact-status",
     *     summary="Vérifier si les coordonnées d'un candidat sont débloquées (Recruteur)",
     *     description="Vérifie si le recruteur a déjà débloqué les coordonnées de ce candidat",
     *     operationId="checkContactStatus",
     *     tags={"Applications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut du contact",
     *         @OA\JsonContent(
     *             @OA\Property(property="is_unlocked", type="boolean"),
     *             @OA\Property(property="contact", type="object", nullable=true),
     *             @OA\Property(property="can_unlock", type="boolean"),
     *             @OA\Property(property="contacts_remaining", type="integer")
     *         )
     *     )
     * )
     */
    public function contactStatus(Application $application): JsonResponse
    {
        $user = auth()->user();
        $recruiter = $user->recruiter;

        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        // Vérifier que la candidature concerne l'entreprise du recruteur
        if ($application->job->company_id !== $recruiter->company_id) {
            return response()->json([
                'message' => 'Cette candidature ne concerne pas votre entreprise',
            ], 403);
        }

        $candidateId = $application->user_id;

        // Vérifier si déjà débloqué
        $isUnlocked = $user->viewedContacts()
            ->where('candidate_user_id', $candidateId)
            ->exists();

        $response = [
            'is_unlocked' => $isUnlocked,
            'can_unlock' => $user->canViewContact(),
            'contacts_remaining' => $user->remainingContactsCount(),
            'contacts_limit' => $user->currentPlan()?->contacts_limit,
        ];

        if ($isUnlocked) {
            $candidate = $application->user;
            $response['contact'] = [
                'name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
            ];
        } else {
            $response['contact'] = null;
        }

        return response()->json($response);
    }

    /**
     * @OA\Delete(
     *     path="/api/applications/{id}",
     *     summary="Supprimer/Annuler une candidature (Candidat)",
     *     description="Permet au candidat de supprimer sa propre candidature. Seulement possible si le statut est 'pending'.",
     *     operationId="deleteApplication",
     *     tags={"Applications"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la candidature",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Candidature supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé ou candidature déjà traitée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Candidature non trouvée")
     * )
     */
    public function destroy(Request $request, Application $application): JsonResponse
    {
        // Vérifier que c'est bien le candidat qui a créé cette candidature
        if ($application->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Non autorisé. Cette candidature ne vous appartient pas.',
            ], 403);
        }

        // Vérifier que la candidature est toujours en statut 'pending'
        // On ne peut pas supprimer une candidature déjà acceptée/rejetée
        if ($application->status !== 'pending') {
            return response()->json([
                'message' => 'Vous ne pouvez supprimer que les candidatures en attente. Cette candidature a déjà été traitée.',
                'current_status' => $application->status,
            ], 403);
        }

        try {
            // Supprimer le fichier CV du stockage
            if ($application->cv_path && \Storage::disk('public')->exists($application->cv_path)) {
                \Storage::disk('public')->delete($application->cv_path);
                Log::info('CV supprimé du stockage', ['cv_path' => $application->cv_path]);
            }

            // Supprimer la candidature (soft delete)
            $application->delete();

            Log::info('Candidature supprimée', [
                'application_id' => $application->id,
                'user_id' => $request->user()->id,
                'job_id' => $application->job_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Candidature supprimée avec succès',
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la candidature: ' . $e->getMessage());
            return response()->json([
                'message' => 'Erreur lors de la suppression de la candidature',
            ], 500);
        }
    }
}