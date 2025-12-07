<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
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
}
