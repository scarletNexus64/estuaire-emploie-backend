<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     *         @OA\JsonContent(
     *             @OA\Property(property="cover_letter", type="string", description="Lettre de motivation"),
     *             @OA\Property(property="portfolio_url", type="string", description="URL du portfolio")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Candidature soumise avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Vous avez déjà postulé à cette offre"),
     *     @OA\Response(response=404, description="Offre non trouvée")
     * )
     */
    public function apply(Request $request, Job $job): JsonResponse
    {
        if ($job->status !== 'published') {
            return response()->json([
                'message' => 'Cette offre n\'est plus disponible',
            ], 404);
        }

        // Vérifier si l'utilisateur a déjà postulé
        $existingApplication = Application::where('job_id', $job->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'message' => 'Vous avez déjà postulé à cette offre',
            ], 400);
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
        ]);

        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $request->user()->id,
            'cover_letter' => $validated['cover_letter'] ?? null,
            'portfolio_url' => $validated['portfolio_url'] ?? null,
            'status' => 'pending',
        ]);

        $application->load(['job.company']);

        return response()->json([
            'data' => $application,
            'message' => 'Candidature soumise avec succès',
        ], 201);
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
     *         @OA\Schema(type="string", enum={"pending", "viewed", "shortlisted", "rejected", "interview", "accepted"})
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
        $query = Application::with(['job.company', 'job.location'])
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

        $application->load(['job.company', 'job.category', 'job.location']);

        return response()->json([
            'data' => $application,
        ]);
    }
}
