<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $query = Job::with(['company', 'category', 'location', 'contractType'])
            ->where('status', 'published');

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

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $jobs = $query->latest()
            ->paginate(20);

        return response()->json($jobs);
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
        if ($job->status !== 'published') {
            return response()->json([
                'message' => 'Offre non disponible',
            ], 404);
        }

        $job->incrementViews();

        $job->load(['company', 'category', 'location', 'contractType', 'postedBy']);

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
        $jobs = Job::with(['company', 'category', 'location'])
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
        $recruiter = auth()->user()->recruiter;

        if (! $recruiter || ! $recruiter->can_publish) {
            return response()->json([
                'message' => 'Vous n\'êtes pas autorisé à publier des offres',
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
            'posted_by' => auth()->id(),
            'status' => 'pending', // Admin doit approuver
        ]));

        return response()->json([
            'message' => 'Offre créée avec succès. En attente de validation.',
            'data' => $job->load(['company', 'category', 'location', 'contractType']),
        ], 201);
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
        $recruiter = auth()->user()->recruiter;

        if (! $recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        $query = Job::where('company_id', $recruiter->company_id)
            ->with(['category', 'location', 'contractType'])
            ->withCount('applications');

        // Filtrer par statut si fourni
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(20);

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
        $recruiter = auth()->user()->recruiter;

        if (! $recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        // Statistiques
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
            'total_views' => Job::where('company_id', $recruiter->company_id)
                ->sum('views_count'),
        ];

        // Top 5 offres actives avec nombre de candidatures
        $activeJobs = Job::where('company_id', $recruiter->company_id)
            ->where('status', 'published')
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 5 dernières candidatures reçues
        $recentApplications = Application::whereHas('job', function ($q) use ($recruiter) {
            $q->where('company_id', $recruiter->company_id);
        })
            ->with(['user', 'job'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'statistics' => $stats,
            'active_jobs' => $activeJobs,
            'recent_applications' => $recentApplications,
        ]);
    }
}
