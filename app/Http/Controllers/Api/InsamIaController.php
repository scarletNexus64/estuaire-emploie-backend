<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateInsamIaRevisionCard;
use App\Models\InsamIa\InsamIaAttempt;
use App\Models\InsamIa\InsamIaAttestation;
use App\Models\InsamIa\InsamIaReadingProgress;
use App\Services\InsamIa\InsamIaAttestationException;
use App\Services\InsamIa\InsamIaAttestationService;
use App\Services\InsamIa\InsamIaException;
use App\Services\InsamIa\InsamIaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\Tag(
 *     name="INSAM-IA",
 *     description="Ressources pédagogiques de l'espace étudiant : packs d'épreuves, fiches de révision, évaluations et attestations"
 * )
 */
class InsamIaController extends Controller
{
    public function __construct(
        private readonly InsamIaService $insamIa,
        private readonly InsamIaAttestationService $attestations,
    ) {
    }

    // ------------------------------------------------------------------
    // Catalogue
    // ------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/api/insam-ia/status",
     *     summary="Disponibilité de l'intégration INSAM-IA",
     *     tags={"INSAM-IA"},
     *     @OA\Response(response=200, description="État du service")
     * )
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'configured' => $this->insamIa->isConfigured(),
                'has_access' => $user !== null && $this->hasAccess($user),
                'pass_threshold' => InsamIaAttestation::PASS_THRESHOLD,
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/categories",
     *     summary="Catégories INSAM-IA groupées par filière",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Liste des filières et de leurs catégories"),
     *     @OA\Response(response=503, description="Service INSAM-IA indisponible")
     * )
     */
    public function categories(): JsonResponse
    {
        return $this->respond(fn () => [
            'data' => $this->insamIa->categoriesByFiliere(),
        ]);
    }

    // ------------------------------------------------------------------
    // Ressource 1 — Packs d'épreuves
    // ------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/api/insam-ia/exams",
     *     summary="Épreuves disponibles",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="category_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Épreuves paginées"),
     *     @OA\Response(response=403, description="Mode Étudiant requis"),
     *     @OA\Response(response=503, description="Service INSAM-IA indisponible")
     * )
     */
    public function exams(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'category_id' => 'nullable|integer|min:1',
            'search' => 'nullable|string|max:120',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        return $this->respond(fn () => $this->insamIa->exams(
            $validated['category_id'] ?? null,
            $validated['search'] ?? null,
            (int) ($validated['page'] ?? 1),
            (int) ($validated['per_page'] ?? 20),
        ));
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/exams/{id}",
     *     summary="Détail d'une épreuve",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Détail de l'épreuve")
     * )
     */
    public function exam(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->exam($id)]);
    }

    /**
     * Télécharge le sujet d'une épreuve.
     *
     * Le fichier transite par Estuaire : l'application n'a jamais besoin des
     * identifiants INSAM-IA.
     *
     * @OA\Get(
     *     path="/api/insam-ia/exams/{id}/download",
     *     summary="Télécharger le sujet d'une épreuve",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Fichier binaire du sujet")
     * )
     */
    public function downloadExam(Request $request, int $id): StreamedResponse|JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        try {
            $file = $this->insamIa->downloadExam($id);
        } catch (InsamIaException $e) {
            return $this->failure($e);
        }

        $filename = $file['filename'] ?: "epreuve-{$id}";

        return response()->streamDownload(
            fn () => print($file['body']),
            $filename,
            ['Content-Type' => $file['content_type']]
        );
    }

    // ------------------------------------------------------------------
    // Ressource 5 — Fiches de révision
    // ------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/api/insam-ia/revision-cards",
     *     summary="Fiches de révision",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="category_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Liste des fiches")
     * )
     */
    public function revisionCards(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $categoryId = $request->integer('category_id') ?: null;

        return $this->respond(fn () => [
            'data' => $this->insamIa->revisionCards($categoryId),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/revision-cards/{id}",
     *     summary="Contenu d'une fiche de révision",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Fiche avec son contenu Markdown")
     * )
     */
    public function revisionCard(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->revisionCard($id)]);
    }

    /**
     * Demande la génération d'une fiche de révision.
     *
     * La génération dure plusieurs dizaines de secondes : elle est confiée à
     * la file d'attente, et l'étudiant est notifié quand la fiche est prête.
     *
     * @OA\Post(
     *     path="/api/insam-ia/revision-cards/generate",
     *     summary="Générer une fiche de révision",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"category_id"},
     *         @OA\Property(property="category_id", type="integer", example=9)
     *     )),
     *     @OA\Response(response=202, description="Génération planifiée")
     * )
     */
    public function generateRevisionCard(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        if (!$this->insamIa->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.not_configured'),
                'service_unavailable' => true,
            ], 503);
        }

        $validated = $request->validate([
            'category_id' => 'required|integer|min:1',
            'category_name' => 'nullable|string|max:150',
        ]);

        GenerateInsamIaRevisionCard::dispatch(
            $request->user()->id,
            (int) $validated['category_id'],
            $validated['category_name'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => __('insam_ia.generation_queued'),
        ], 202);
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Progression de lecture
    // ------------------------------------------------------------------

    /**
     * @OA\Post(
     *     path="/api/insam-ia/reading-progress",
     *     summary="Enregistrer la progression de lecture",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"resource_type","resource_id","progress_percent"},
     *         @OA\Property(property="resource_type", type="string", enum={"revision_card","exam","category"}),
     *         @OA\Property(property="resource_id", type="integer"),
     *         @OA\Property(property="progress_percent", type="integer", example=60)
     *     )),
     *     @OA\Response(response=200, description="Progression enregistrée")
     * )
     */
    public function trackReading(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'resource_type' => 'required|in:revision_card,exam,category',
            'resource_id' => 'required|integer|min:1',
            'progress_percent' => 'required|integer|min:0|max:100',
            'resource_title' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|min:1',
        ]);

        $progress = $this->insamIa->trackReading(
            $request->user(),
            $validated['resource_type'],
            (int) $validated['resource_id'],
            (int) $validated['progress_percent'],
            $validated['resource_title'] ?? null,
            isset($validated['category_id']) ? (int) $validated['category_id'] : null,
        );

        return response()->json([
            'success' => true,
            'message' => __('insam_ia.progress_saved'),
            'data' => [
                'resource_type' => $progress->resource_type,
                'resource_id' => $progress->resource_id,
                'progress_percent' => $progress->progress_percent,
                'completed' => $progress->isCompleted(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/progress",
     *     summary="Tableau de bord du parcours de l'étudiant",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Lecture, évaluations et attestations")
     * )
     */
    public function progress(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $this->insamIa->learningSummary($user),
                'resources' => InsamIaReadingProgress::where('user_id', $user->id)
                    ->orderByDesc('last_read_at')
                    ->limit(50)
                    ->get()
                    ->map(fn (InsamIaReadingProgress $item) => [
                        'resource_type' => $item->resource_type,
                        'resource_id' => $item->resource_id,
                        'resource_title' => $item->resource_title,
                        'category_id' => $item->category_id,
                        'progress_percent' => $item->progress_percent,
                        'completed' => $item->isCompleted(),
                        'last_read_at' => $item->last_read_at?->toISOString(),
                    ]),
            ],
        ]);
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Évaluations
    // ------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/api/insam-ia/evaluations/active",
     *     summary="Sessions d'évaluation ouvertes",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Sessions actives")
     * )
     */
    public function activeSessions(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->activeSessions()]);
    }

    /**
     * Démarre une tentative : la génération des questions par INSAM-IA peut
     * durer près d'une minute, la requête est donc volontairement longue.
     *
     * @OA\Post(
     *     path="/api/insam-ia/evaluations/start",
     *     summary="Démarrer une évaluation",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"session_id","nom","prenom","specialite"},
     *         @OA\Property(property="session_id", type="integer"),
     *         @OA\Property(property="nom", type="string"),
     *         @OA\Property(property="prenom", type="string"),
     *         @OA\Property(property="matricule", type="string", nullable=true),
     *         @OA\Property(property="specialite", type="string")
     *     )),
     *     @OA\Response(response=201, description="Questions servies")
     * )
     */
    public function startEvaluation(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'session_id' => 'required|integer|min:1',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'matricule' => 'nullable|string|max:50',
            'specialite' => 'required|string|max:100',
        ]);

        try {
            $attempt = $this->insamIa->startAttempt(
                $request->user(),
                (int) $validated['session_id'],
                $validated,
            );
        } catch (InsamIaException $e) {
            return $this->failure($e);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'attempt_id' => $attempt->id,
                'session_title' => $attempt->session_title,
                'duration_minutes' => $attempt->duration_minutes,
                // Les bonnes réponses ne sont jamais exposées avant la
                // soumission, même si INSAM-IA venait à les inclure.
                'questions' => $attempt->questionsForCandidate(),
                'started_at' => $attempt->started_at?->toISOString(),
            ],
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/insam-ia/evaluations/{attempt}/submit",
     *     summary="Soumettre les réponses",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="attempt", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="answers", type="object", example={"0": 2, "1": 0})
     *     )),
     *     @OA\Response(response=200, description="Score et corrections")
     * )
     */
    public function submitEvaluation(Request $request, int $attemptId): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $attempt = InsamIaAttempt::where('user_id', $request->user()->id)->find($attemptId);

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.attempt_not_found'),
            ], 404);
        }

        if ($attempt->isSubmitted()) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.attempt_already_submitted'),
            ], 409);
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:0',
        ]);

        try {
            $attempt = $this->insamIa->submitAttempt($attempt, $validated['answers']);
        } catch (InsamIaException $e) {
            return $this->failure($e);
        }

        return response()->json([
            'success' => true,
            'data' => $this->presentAttempt($attempt, withCorrections: true),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/evaluations/my-attempts",
     *     summary="Historique des évaluations",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Tentatives de l'étudiant")
     * )
     */
    public function myAttempts(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $attempts = InsamIaAttempt::where('user_id', $request->user()->id)
            ->with('attestation')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attempts->map(fn (InsamIaAttempt $attempt) => $this->presentAttempt($attempt)),
        ]);
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Attestations
    // ------------------------------------------------------------------

    /**
     * @OA\Post(
     *     path="/api/insam-ia/evaluations/{attempt}/attestation",
     *     summary="Délivrer l'attestation d'une évaluation réussie",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="attempt", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=201, description="Attestation délivrée"),
     *     @OA\Response(response=422, description="Note insuffisante")
     * )
     */
    public function issueAttestation(Request $request, int $attemptId): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $attempt = InsamIaAttempt::where('user_id', $request->user()->id)->find($attemptId);

        if (!$attempt) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.attempt_not_found'),
            ], 404);
        }

        try {
            $attestation = $this->attestations->issueFor($attempt);
        } catch (InsamIaAttestationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'pass_threshold' => InsamIaAttestation::PASS_THRESHOLD,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => __('insam_ia.attestation_issued'),
            'data' => $this->presentAttestation($attestation),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/attestations",
     *     summary="Attestations de l'étudiant",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Liste des attestations")
     * )
     */
    public function attestations(Request $request): JsonResponse
    {
        $attestations = InsamIaAttestation::where('user_id', $request->user()->id)
            ->orderByDesc('issued_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $attestations->map(fn (InsamIaAttestation $a) => $this->presentAttestation($a)),
        ]);
    }

    /**
     * Télécharge le PDF de l'attestation, régénéré à la volée si le fichier
     * a disparu du stockage.
     *
     * @OA\Get(
     *     path="/api/insam-ia/attestations/{attestation}/download",
     *     summary="Télécharger une attestation en PDF",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="attestation", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Document PDF")
     * )
     */
    public function downloadAttestation(Request $request, int $attestationId): StreamedResponse|JsonResponse
    {
        $attestation = InsamIaAttestation::where('user_id', $request->user()->id)->find($attestationId);

        if (!$attestation) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.attestation_errors.not_available'),
            ], 404);
        }

        try {
            $pdf = $this->attestations->render($attestation);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.attestation_errors.pdf_unavailable'),
            ], 503);
        }

        return response()->streamDownload(
            fn () => print($pdf),
            "attestation-{$attestation->reference}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Exécute une lecture INSAM-IA et convertit toute défaillance du service
     * tiers en réponse « indisponible » compréhensible par l'application.
     */
    private function respond(callable $resolver): JsonResponse
    {
        try {
            $payload = $resolver();
        } catch (InsamIaException $e) {
            return $this->failure($e);
        }

        return response()->json(array_merge(['success' => true], $payload));
    }

    private function failure(InsamIaException $e): JsonResponse
    {
        $message = match ($e->reason) {
            InsamIaException::REASON_NOT_CONFIGURED => __('insam_ia.not_configured'),
            InsamIaException::REASON_NOT_FOUND => __('insam_ia.not_found'),
            default => __('insam_ia.unavailable'),
        };

        return response()->json([
            'success' => false,
            'message' => $message,
            // Permet à l'application de distinguer « rien à afficher » d'une
            // panne, et d'afficher un écran de repli explicite.
            'service_unavailable' => !$e->isNotFound(),
        ], $e->httpStatus());
    }

    /**
     * Les ressources INSAM-IA suivent le même gating que la bibliothèque
     * étudiante : Mode Étudiant ou abonnement candidat C2/C3.
     */
    private function hasAccess($user): bool
    {
        return $user->hasLibraryAccess();
    }

    private function denyWithoutAccess(Request $request): ?JsonResponse
    {
        $user = $request->user();

        if ($user && $this->hasAccess($user)) {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => __('insam_ia.student_mode_required'),
            'requires_student_mode' => true,
        ], 403);
    }

    private function presentAttempt(InsamIaAttempt $attempt, bool $withCorrections = false): array
    {
        $payload = [
            'id' => $attempt->id,
            'session_id' => $attempt->session_id,
            'session_title' => $attempt->session_title,
            'specialite' => $attempt->specialite,
            'matiere' => $attempt->matiere,
            'status' => $attempt->status,
            'score' => $attempt->score,
            'total' => $attempt->total,
            'percentage' => $attempt->percentage,
            'passed' => InsamIaAttestation::isEligible($attempt->percentage),
            'pass_threshold' => InsamIaAttestation::PASS_THRESHOLD,
            'started_at' => $attempt->started_at?->toISOString(),
            'submitted_at' => $attempt->submitted_at?->toISOString(),
            'attestation_id' => $attempt->attestation?->id,
        ];

        if ($withCorrections) {
            $payload['corrections'] = $attempt->corrections ?? [];
        }

        return $payload;
    }

    private function presentAttestation(InsamIaAttestation $attestation): array
    {
        return [
            'id' => $attestation->id,
            'reference' => $attestation->reference,
            'title' => $attestation->title,
            'specialite' => $attestation->specialite,
            'score' => $attestation->score,
            'total' => $attestation->total,
            'percentage' => $attestation->percentage,
            'mention' => $attestation->mention,
            'mention_label' => $attestation->mentionLabel(),
            'issued_at' => $attestation->issued_at?->toISOString(),
            'download_url' => route('insam-ia.attestations.download', $attestation->id),
        ];
    }
}
