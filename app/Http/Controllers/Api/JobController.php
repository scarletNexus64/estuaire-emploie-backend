<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Jobs",
 *     description="API Endpoints pour la gestion des offres d'emploi"
 * )
 */
class JobController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/jobs",
     *     summary="Liste des offres d'emploi publiées",
     *     tags={"Jobs"},
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filtrer par catégorie",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="location_id",
     *         in="query",
     *         description="Filtrer par localisation",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="contract_type_id",
     *         in="query",
     *         description="Filtrer par type de contrat",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="experience_level",
     *         in="query",
     *         description="Filtrer par niveau d'expérience",
     *         required=false,
     *         @OA\Schema(type="string", enum={"junior", "intermediaire", "senior", "expert"})
     *     ),
     *     @OA\Parameter(
     *         name="min_salary",
     *         in="query",
     *         description="Salaire minimum",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="max_salary",
     *         in="query",
     *         description="Salaire maximum",
     *         required=false,
     *         @OA\Schema(type="number")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche par mots-clés",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des offres",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="meta", type="object"),
     *             @OA\Property(property="links", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Job::with([
                'company',
                'category',
                'location',
                'contractType',
                'skillTests' => function ($query) {
                    $query->where('is_active', true)
                          ->select('id', 'job_id', 'title', 'description', 'duration_minutes', 'passing_score');
                }
            ])
            ->withCount(['skillTests' => function ($query) {
                $query->where('is_active', true);
            }])
            ->where('status', 'published');

        // Filtres de base
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->has('contract_type_id')) {
            $query->where('contract_type_id', $request->contract_type_id);
        }

        if ($request->has('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        // Filtre par fourchette de salaire
        if ($request->has('min_salary')) {
            $query->where(function ($q) use ($request) {
                $q->where('salary_max', '>=', $request->min_salary)
                    ->orWhereNull('salary_max');
            });
        }

        if ($request->has('max_salary')) {
            $query->where(function ($q) use ($request) {
                $q->where('salary_min', '<=', $request->max_salary)
                    ->orWhereNull('salary_min');
            });
        }

        // Recherche améliorée avec support des accents
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            // Normaliser la recherche (retirer les accents pour meilleure correspondance)
            $normalizedSearch = $this->normalizeString($search);

            $query->where(function ($q) use ($search, $normalizedSearch) {
                // Recherche dans le titre (insensible aux accents via COLLATE)
                $q->whereRaw('LOWER(title) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    // Recherche dans la description
                    ->orWhereRaw('LOWER(description) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    // Recherche dans les exigences
                    ->orWhereRaw('LOWER(requirements) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"])
                    // Recherche dans le nom de l'entreprise
                    ->orWhereHas('company', function ($companyQuery) use ($normalizedSearch) {
                        $companyQuery->whereRaw('LOWER(name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"]);
                    })
                    // Recherche dans la catégorie
                    ->orWhereHas('category', function ($categoryQuery) use ($normalizedSearch) {
                        $categoryQuery->whereRaw('LOWER(name) COLLATE utf8mb4_general_ci LIKE ?', ["%{$normalizedSearch}%"]);
                    });
            });
        }

        // Limitation pour les candidats en mode preview
        $user = Auth::user();
        $previewLimit = null;

        if ($user && $user->isCandidate() && $user->isCandidateInPreviewMode()) {
            // Les candidats sans abonnement ne voient que 5 offres
            $previewLimit = 5;
            Log::info("[JobController] Candidate in preview mode - limiting to {$previewLimit} jobs", [
                'user_id' => $user->id,
            ]);
        }

        // Appliquer la limite si nécessaire
        if ($previewLimit !== null) {
            $jobs = $query->latest()
                ->limit($previewLimit)
                ->get();

            // Retourner au format paginé pour cohérence avec l'API
            return response()->json([
                'data' => $jobs,
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $previewLimit,
                'total' => $previewLimit,
                'is_preview_mode' => true,
                'message' => 'Souscrivez à un forfait pour accéder à toutes les offres d\'emploi',
            ]);
        }

        // Utilisateurs avec abonnement ou recruteurs : accès complet
        $jobs = $query->latest()
            ->paginate(10);

        $response = $jobs->toArray();
        $response['is_preview_mode'] = false;

        return response()->json($response);
    }

    /**
     * Normalise une chaîne pour la recherche (retire les accents, convertit en minuscules)
     */
    private function normalizeString(string $str): string
    {
        // Convertir en minuscules
        $str = mb_strtolower($str, 'UTF-8');

        // Tableau de correspondance des caractères accentués (majuscules et minuscules)
        $unwanted = [
            // Minuscules
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'œ' => 'oe',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ý' => 'y', 'ÿ' => 'y',
            'ñ' => 'n', 'ç' => 'c',
            // Majuscules (au cas où)
            'À' => 'a', 'Á' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a', 'Å' => 'a', 'Æ' => 'ae',
            'È' => 'e', 'É' => 'e', 'Ê' => 'e', 'Ë' => 'e',
            'Ì' => 'i', 'Í' => 'i', 'Î' => 'i', 'Ï' => 'i',
            'Ò' => 'o', 'Ó' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o', 'Ø' => 'o', 'Œ' => 'oe',
            'Ù' => 'u', 'Ú' => 'u', 'Û' => 'u', 'Ü' => 'u',
            'Ý' => 'y', 'Ÿ' => 'y',
            'Ñ' => 'n', 'Ç' => 'c',
        ];

        $str = strtr($str, $unwanted);

        // Utiliser iconv pour retirer les accents restants
        $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);

        // Nettoyer les caractères non alphanumériques sauf espaces
        $str = preg_replace('/[^a-z0-9\s]/i', '', $str);

        return $str;
    }

    /**
     * @OA\Get(
     *     path="/api/jobs/{id}",
     *     summary="Détails d'une offre d'emploi",
     *     tags={"Jobs"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'offre",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Offre non trouvée")
     * )
     */
    public function show(Job $job): JsonResponse
    {
        $job->incrementViews();

        $job->load([
            'company',
            'category',
            'location',
            'contractType',
            'postedBy',
            'skillTests' => function ($query) {
                $query->where('is_active', true)
                      ->select('id', 'job_id', 'title', 'description', 'duration_minutes', 'passing_score');
            }
        ]);

        return response()->json([
            'data' => $job,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/jobs/featured",
     *     summary="Offres mises en avant",
     *     tags={"Jobs"},
     *     @OA\Response(
     *         response=200,
     *         description="Offres en vedette",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function featured(): JsonResponse
    {
        $jobs = Job::with(['company', 'category', 'location', 'contractType'])
            ->where('status', 'published')
            ->where('is_featured', true)
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'data' => $jobs,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/jobs",
     *     summary="Créer une nouvelle offre d'emploi",
     *     description="Permet à un recruteur de créer une nouvelle offre d'emploi pour son entreprise. L'offre sera en attente de validation par l'administrateur.",
     *     operationId="createJob",
     *     tags={"Jobs"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","description","category_id","location_id","contract_type_id","experience_level"},
     *             @OA\Property(property="title", type="string", example="Développeur Full Stack Senior"),
     *             @OA\Property(property="description", type="string", example="Nous recherchons un développeur Full Stack avec expertise Laravel et Vue.js"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="location_id", type="integer", example=1),
     *             @OA\Property(property="contract_type_id", type="integer", example=1),
     *             @OA\Property(property="salary_min", type="number", example=500000, nullable=true),
     *             @OA\Property(property="salary_max", type="number", example=800000, nullable=true),
     *             @OA\Property(property="salary_negotiable", type="boolean", example=false),
     *             @OA\Property(property="experience_level", type="string", enum={"junior", "intermediaire", "senior", "expert"}, example="senior"),
     *             @OA\Property(property="requirements", type="string", example="3+ ans d'expérience en PHP/Laravel", nullable=true),
     *             @OA\Property(property="benefits", type="string", example="Assurance santé, primes de performance", nullable=true),
     *             @OA\Property(property="application_deadline", type="string", format="date", example="2025-01-15", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Offre créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offre créée avec succès. En attente de validation."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à publier des offres",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'êtes pas autorisé à publier des offres")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        $recruiter = $user->recruiter;

        if (! $recruiter || ! $recruiter->can_publish) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à publier des offres',
            ], 403);
        }

        // 🎯 Vérifier l'abonnement recruteur actif (pas candidat)
        $subscription = $user->activeSubscription($user->role);
        if (!$subscription || !$subscription->isValid()) {
            return response()->json([
                'message' => 'Vous devez avoir un abonnement actif pour publier des offres',
                'error_code' => 'NO_SUBSCRIPTION',
                'subscription_required' => true,
            ], 403);
        }

        // Vérifier la limite de jobs (utilise les limites effectives cumulées)
        if (!$subscription->canPostJob()) {
            $effectiveJobsLimit = $subscription->getEffectiveJobsLimit();
            return response()->json([
                'message' => "Vous avez atteint la limite de {$effectiveJobsLimit} offres. Passez à un plan supérieur pour publier plus d'offres.",
                'error_code' => 'JOBS_LIMIT_REACHED',
                'limit' => $effectiveJobsLimit,
                'used' => $subscription->jobs_used,
                'upgrade_required' => true,
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'contract_type_id' => 'required|exists:contract_types,id',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_negotiable' => 'sometimes|boolean',
            'experience_level' => 'required|in:junior,intermediaire,senior,expert',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date|after:today',
        ]);

        $job = Job::create(array_merge($validated, [
            'company_id' => $recruiter->company_id,
            'posted_by' => Auth::id(),
            'status' => 'pending', // Admin doit approuver
        ]));

        // Incrémenter le compteur de jobs utilisés dans l'abonnement
        // $subscription est déjà défini plus haut
        $subscription->incrementJobsUsed();

        return response()->json([
            'message' => 'Offre créée avec succès. En attente de validation.',
            'data' => $job->load(['company', 'category', 'location', 'contractType']),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/recruiter/jobs/{id}",
     *     summary="Détails d'une offre d'emploi (Recruteur)",
     *     description="Récupère les détails d'une offre d'emploi appartenant à l'entreprise du recruteur, quel que soit son statut (draft, pending, published, closed, expired)",
     *     operationId="getRecruiterJob",
     *     tags={"Jobs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'offre",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=403, description="Vous n'êtes pas autorisé à voir cette offre"),
     *     @OA\Response(response=404, description="Offre non trouvée")
     * )
     */
    public function showRecruiterJob(int $id): JsonResponse
    {
        $user = Auth::user();
        $recruiter = $user->recruiter;

        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        $job = Job::with([
                'company',
                'category',
                'location',
                'contractType',
                'postedBy',
                'skillTests' => function ($query) {
                    $query->where('is_active', true)
                          ->select('id', 'job_id', 'title', 'description', 'duration_minutes', 'passing_score');
                }
            ])
            ->withCount('applications')
            ->find($id);

        if (!$job) {
            return response()->json([
                'message' => 'Offre non trouvée',
            ], 404);
        }

        // Vérifier que l'offre appartient à l'entreprise du recruteur
        if ($job->company_id !== $recruiter->company_id) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à voir cette offre',
            ], 403);
        }

        return response()->json([
            'data' => $job,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/recruiter/jobs",
     *     summary="Mes offres d'emploi (Recruteur)",
     *     description="Récupère toutes les offres d'emploi publiées par le recruteur connecté pour son entreprise",
     *     operationId="getMyJobs",
     *     tags={"Jobs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrer par statut",
     *         required=false,
     *         @OA\Schema(type="string", enum={"draft", "pending", "published", "closed", "expired"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des offres du recruteur",
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
    public function myJobs(Request $request): JsonResponse
    {
        $recruiter = Auth::user()->recruiter;

        if (! $recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        $query = Job::where('company_id', $recruiter->company_id)
            ->with(['category', 'location', 'contractType', 'skillTests' => function ($query) {
                $query->where('is_active', true)
                      ->select('id', 'job_id', 'title', 'description', 'duration_minutes', 'passing_score', 'is_active');
            }])
            ->withCount('applications');

        // Filtrer par statut si fourni
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($jobs);
    }

    /**
     * @OA\Get(
     *     path="/api/recruiter/dashboard",
     *     summary="Dashboard recruteur",
     *     description="Récupère les statistiques et données essentielles du dashboard recruteur (stats, offres actives, candidatures récentes)",
     *     operationId="getRecruiterDashboard",
     *     tags={"Jobs"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Données du dashboard",
     *         @OA\JsonContent(
     *             @OA\Property(property="statistics", type="object",
     *                 @OA\Property(property="total_jobs", type="integer", example=12),
     *                 @OA\Property(property="active_jobs", type="integer", example=8),
     *                 @OA\Property(property="total_applications", type="integer", example=45),
     *                 @OA\Property(property="new_applications", type="integer", example=5),
     *                 @OA\Property(property="total_views", type="integer", example=1248)
     *             ),
     *             @OA\Property(property="active_jobs", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="recent_applications", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(
     *         response=403,
     *         description="Vous n'êtes pas recruteur"
     *     )
     * )
     */
    public function dashboard(): JsonResponse
    {
        $user = Auth::user();
        $recruiter = $user->recruiter;

        if (! $recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        // Vérifier l'abonnement actif
        if (!$user->hasActiveSubscription()) {
            return response()->json([
                'message' => 'Vous devez avoir un abonnement actif pour accéder au dashboard',
                'error_code' => 'NO_SUBSCRIPTION',
                'subscription_required' => true,
            ], 403);
        }

        // Statistiques de base (toujours disponibles)
        $stats = [
            'total_jobs' => Job::where('company_id', $recruiter->company_id)->count(),
            'active_jobs' => Job::where('company_id', $recruiter->company_id)
                ->where('status', 'published')
                ->count(),
            'total_applications' => Application::whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->count(),
            'new_applications' => Application::whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->where('status', 'pending')->count(),
            'accepted_applications' => Application::whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->where('status', 'accepted')->count(),
            'rejected_applications' => Application::whereHas('job', function ($q) use ($recruiter) {
                $q->where('company_id', $recruiter->company_id);
            })->where('status', 'rejected')->count(),
        ];

        // Analytics avancées (nécessitent can_see_analytics)
        $canSeeAnalytics = $user->canSeeAnalytics();
        $analytics = null;

        if ($canSeeAnalytics) {
            $totalViews = Job::where('company_id', $recruiter->company_id)->sum('views_count');

            $analytics = [
                'total_views' => $totalViews,
                'views_this_month' => Job::where('company_id', $recruiter->company_id)
                    ->whereMonth('created_at', now()->month)
                    ->sum('views_count'),
                'applications_this_month' => Application::whereHas('job', function ($q) use ($recruiter) {
                    $q->where('company_id', $recruiter->company_id);
                })->whereMonth('created_at', now()->month)->count(),
                'conversion_rate' => $totalViews > 0
                    ? round(($stats['total_applications'] / $totalViews) * 100, 2)
                    : 0,
            ];
        }

        // Top 5 offres actives avec nombre de candidatures
        $activeJobs = Job::where('company_id', $recruiter->company_id)
            ->where('status', 'published')
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 5 dernières candidatures EN ATTENTE uniquement (non traitées)
        $recentApplications = Application::whereHas('job', function ($q) use ($recruiter) {
            $q->where('company_id', $recruiter->company_id);
        })
            ->where('status', 'pending')
            ->with(['user' => function ($q) {
                $q->select('id', 'name', 'profile_photo', 'experience_level', 'created_at');
            }, 'job'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Infos d'abonnement
        $subscriptionInfo = $user->getSubscriptionInfo();

        return response()->json([
            'statistics' => $stats,
            'analytics' => $analytics,
            'can_see_analytics' => $canSeeAnalytics,
            'active_jobs' => $activeJobs,
            'recent_applications' => $recentApplications,
            'subscription' => $subscriptionInfo,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/jobs/{id}",
     *     summary="Mettre à jour une offre d'emploi",
     *     description="Permet au recruteur de modifier une offre d'emploi de son entreprise",
     *     operationId="updateJob",
     *     tags={"Jobs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Développeur Full Stack Senior"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="location_id", type="integer"),
     *             @OA\Property(property="contract_type_id", type="integer"),
     *             @OA\Property(property="salary_min", type="number", nullable=true),
     *             @OA\Property(property="salary_max", type="number", nullable=true),
     *             @OA\Property(property="salary_negotiable", type="boolean"),
     *             @OA\Property(property="experience_level", type="string", enum={"junior", "intermediaire", "senior", "expert"}),
     *             @OA\Property(property="requirements", type="string", nullable=true),
     *             @OA\Property(property="benefits", type="string", nullable=true),
     *             @OA\Property(property="application_deadline", type="string", format="date", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Offre mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offre mise à jour avec succès"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=403, description="Non autorisé à modifier cette offre"),
     *     @OA\Response(response=404, description="Offre non trouvée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = Auth::user();
        $recruiter = $user->recruiter;

        // Vérifier que l'utilisateur est recruteur
        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à modifier des offres',
            ], 403);
        }

        $job = Job::find($id);

        // Vérifier que l'offre existe
        if (!$job) {
            return response()->json([
                'message' => 'Offre non trouvée',
            ], 404);
        }

        // Vérifier que l'offre appartient à l'entreprise du recruteur
        if ($job->company_id !== $recruiter->company_id) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à modifier cette offre',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'location_id' => 'sometimes|required|exists:locations,id',
            'contract_type_id' => 'sometimes|required|exists:contract_types,id',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_negotiable' => 'sometimes|boolean',
            'experience_level' => 'sometimes|required|in:junior,intermediaire,senior,expert',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date|after:today',
        ]);

        $job->update($validated);

        return response()->json([
            'message' => 'Offre mise à jour avec succès',
            'data' => $job->load(['company', 'category', 'location', 'contractType']),
        ]);
    }
    /**
     * @OA\Delete(
     *     path="/api/jobs/{id}",
     *     summary="Supprimer une offre d'emploi",
     *     description="Permet au recruteur de supprimer une offre d'emploi de son entreprise",
     *     operationId="deleteJob",
     *     tags={"Jobs"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Offre supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Offre supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=403, description="Non autorisé à supprimer cette offre"),
     *     @OA\Response(response=404, description="Offre non trouvée")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $user = Auth::user();
        $recruiter = $user->recruiter;

        // Vérifier que l'utilisateur est recruteur
        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à supprimer des offres',
            ], 403);
        }

        $job = Job::with(['company', 'category', 'location', 'contractType', 'postedBy'])
            ->withCount('applications')
            ->find($id);

        // Vérifier que l'offre existe
        if (!$job) {
            return response()->json([
                'message' => 'Offre non trouvée',
            ], 404);
        }

        // Vérifier que l'offre appartient à l'entreprise du recruteur
        if ($job->company_id !== $recruiter->company_id) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à supprimer cette offre',
            ], 403);
        }

        // 🎯 Décrémenter le compteur jobs_used de l'abonnement recruteur
        $subscription = $user->activeSubscription($user->role);
        if ($subscription && $subscription->jobs_used > 0) {
            $subscription->decrement('jobs_used');
        }

        $job->delete();

        return response()->json([
            'message' => 'Offre supprimée avec succès',
            'usage' => $subscription ? [
                'jobs_used' => $subscription->jobs_used,
                'jobs_limit' => $subscription->getEffectiveJobsLimit(),
                'jobs_remaining' => $subscription->jobs_remaining,
                'can_post_job' => $subscription->canPostJob(),
            ] : null,
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/jobs/{id}/has-applied",
     *     summary="Vérifier si l'utilisateur a déjà postulé à cette offre",
     *     tags={"Jobs"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de l'offre",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut de candidature",
     *         @OA\JsonContent(
     *             @OA\Property(property="has_applied", type="boolean")
     *         )
     *     )
     * )
     */
    public function hasApplied($jobId): JsonResponse
    {
        // Récupérer le job par son ID (sans restriction de statut)
        $job = Job::find($jobId);

        if (!$job) {
            return response()->json([
                'has_applied' => false,
            ]);
        }

        // Vérifier uniquement les candidatures actives (pas les soft deleted)
        $hasApplied = Application::where('job_id', $job->id)
            ->where('user_id', Auth::id())
            ->exists();

        return response()->json([
            'has_applied' => $hasApplied,
        ]);
    }
}