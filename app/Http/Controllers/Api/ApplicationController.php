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

        // Vérifier si l'utilisateur a déjà postulé (incluant les soft deleted)
        $existingApplication = Application::withTrashed()
            ->where('job_id', $job->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingApplication) {
            // Si l'application est soft deleted, on peut la restaurer
            if ($existingApplication->trashed()) {
                return response()->json([
                    'message' => 'Vous avez déjà postulé à cette offre (candidature archivée)',
                ], 400);
            }

            return response()->json([
                'message' => 'Vous avez déjà postulé à cette offre',
            ], 400);
        }

        $validated = $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'cover_letter' => 'nullable|string',
            'portfolio_url' => 'nullable|url',
        ]);

        // Upload du CV
        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        try {
            $application = Application::create([
                'job_id' => $job->id,
                'user_id' => $request->user()->id,
                'cv_path' => $cvPath,
                'cover_letter' => $validated['cover_letter'] ?? null,
                'portfolio_url' => $validated['portfolio_url'] ?? null,
                'status' => 'pending',
            ]);

            $application->load(['job.company']);

            return response()->json([
                'data' => $application,
                'message' => 'Candidature soumise avec succès',
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            // Gérer l'erreur de contrainte unique au cas où
            if ($e->getCode() === '23000') {
                return response()->json([
                    'message' => 'Vous avez déjà postulé à cette offre',
                ], 400);
            }
            throw $e;
        }
    }

    /**
     * @OA\Get(
     *     path="/api/my-applications/stats",
     *     summary="Statistiques de mes candidatures",
     *     tags={"Applications"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques des candidatures par statut",
     *         @OA\JsonContent(
     *             @OA\Property(property="total", type="integer", example=10),
     *             @OA\Property(property="pending", type="integer", example=5),
     *             @OA\Property(property="accepted", type="integer", example=3),
     *             @OA\Property(property="rejected", type="integer", example=2)
     *         )
     *     )
     * )
     */
    public function myApplicationsStats(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $stats = [
            'total' => Application::where('user_id', $userId)->count(),
            'pending' => Application::where('user_id', $userId)
                ->whereIn('status', ['pending', 'viewed', 'shortlisted', 'interview'])
                ->count(),
            'accepted' => Application::where('user_id', $userId)
                ->where('status', 'accepted')
                ->count(),
            'rejected' => Application::where('user_id', $userId)
                ->where('status', 'rejected')
                ->count(),
        ];

        return response()->json($stats);
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
        $query = Application::with(['job.company', 'job.location', 'job.category', 'job.contractType'])
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
        $recruiter = auth()->user()->recruiter;

        if (!$recruiter) {
            return response()->json([
                'message' => 'Vous n\'êtes pas recruteur',
            ], 403);
        }

        $query = Application::whereHas('job', function ($q) use ($recruiter) {
            $q->where('company_id', $recruiter->company_id);
        })->with(['user', 'job']);

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

        return response()->json($applications);
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

        return response()->json([
            'message' => 'Statut de la candidature mis à jour',
            'data' => $application->fresh(['user', 'job']),
        ]);
    }
}
