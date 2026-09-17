<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateInsamIaCourseRevisionCard;
use App\Jobs\GenerateInsamIaRevisionCard;
use App\Models\InsamIa\InsamIaAttempt;
use App\Models\InsamIa\InsamIaAttestation;
use App\Models\InsamIa\InsamIaCourseProgress;
use App\Models\InsamIa\InsamIaExamContribution;
use App\Models\InsamIa\InsamIaGeneratedExam;
use App\Models\InsamIa\InsamIaReadingProgress;
use App\Services\InsamIa\InsamIaAttestationException;
use App\Services\InsamIa\InsamIaAttestationService;
use App\Services\InsamIa\InsamIaException;
use App\Services\InsamIa\InsamIaService;
use App\Services\InsamIa\PdfTextExtractor;
use App\Services\TrainingProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @OA\Tag(
 *     name="INSAM-IA",
 *     description="Ressources pédagogiques de l'espace étudiant : packs d'épreuves, fiches de révision, évaluations et attestations"
 * )
 */
class InsamIaController extends Controller
{
    /**
     * Durée de conservation d'un corrigé d'exercices.
     *
     * Large : l'étudiant compose à son rythme, et un corrigé perdu l'obligerait
     * à régénérer l'exercice — donc à repayer une génération par IA.
     */
    private const EXERCISE_TTL = 7200;

    /**
     * Niveaux proposés au dépôt d'un sujet.
     *
     * INSAM-IA n'expose pas de référentiel de niveaux : on garde celui de
     * l'administration, déjà utilisé par le back-office des épreuves.
     */
    private const CONTRIBUTION_LEVELS = [
        1 => 'Niveau 1 - BTS',
        2 => 'Niveau 2 - BTS',
        3 => 'Niveau 3 - Licence',
        4 => 'Niveau 4 - Master 1',
        5 => 'Niveau 5 - Master 2',
    ];

    public function __construct(
        private readonly InsamIaService $insamIa,
        private readonly InsamIaAttestationService $attestations,
        private readonly TrainingProgressService $trainingProgress,
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
                // Les cours passent par la clé API : sans elle, l'onglet doit
                // s'annoncer indisponible plutôt que rester vide.
                'has_course_library' => $this->insamIa->hasCourseLibrary(),
                // L'application ouvre son écran de configuration tant que le
                // profil d'études n'est pas renseigné : elle doit le savoir
                // avant même de tenter un appel de contenu.
                'study_profile' => $this->presentStudyProfile($user),
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

        // Les épreuves ne sont pas filtrées par le profil d'études : une
        // annale d'une autre spécialité ou d'une année antérieure reste un
        // support de révision valable, et le catalogue s'explore librement.
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
     * Texte du sujet, pour consultation dans l'application.
     *
     * Les épreuves ne se téléchargent plus : elles se lisent en ligne. Servir
     * un fichier revenait à en abandonner le contrôle dès le premier partage.
     * Le texte, lui, reste dans l'application.
     *
     * @OA\Get(
     *     path="/api/insam-ia/exams/{id}/content",
     *     summary="Texte du sujet d'une épreuve (lecture seule)",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Texte du sujet"),
     *     @OA\Response(response=404, description="Sujet non extractible")
     * )
     */
    public function examContent(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->examContent($id)]);
    }

    /**
     * Texte du corrigé officiel d'une épreuve.
     *
     * @OA\Get(
     *     path="/api/insam-ia/exams/{id}/correction",
     *     summary="Texte du corrigé d'une épreuve (lecture seule)",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Texte du corrigé"),
     *     @OA\Response(response=404, description="Corrigé indisponible")
     * )
     */
    public function examCorrection(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->examCorrection($id)]);
    }

    /**
     * Correction par l'IA d'une copie rédigée par l'étudiant.
     *
     * @OA\Post(
     *     path="/api/insam-ia/exams/{id}/evaluate",
     *     summary="Faire corriger sa copie",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"copie"},
     *         @OA\Property(property="copie", type="string")
     *     )),
     *     @OA\Response(response=200, description="Appréciation et note sur 20")
     * )
     */
    public function evaluateExamCopy(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'copie' => 'required|string|max:50000',
        ]);

        return $this->respond(function () use ($id, $validated) {
            $exam = $this->insamIa->examContent($id);

            // Le corrigé officiel améliore nettement la correction, mais son
            // absence ne doit pas empêcher l'évaluation.
            $correction = null;

            if ($exam['has_correction']) {
                try {
                    $correction = $this->insamIa->examCorrection($id)['correction'];
                } catch (InsamIaException) {
                    $correction = null;
                }
            }

            return [
                'data' => $this->insamIa->evaluateExamCopy(
                    $exam['sujet'],
                    $validated['copie'],
                    $correction,
                ),
            ];
        });
    }

    // ------------------------------------------------------------------
    // Profil d'études
    // ------------------------------------------------------------------

    /**
     * Profil d'études de l'étudiant, avec le référentiel pour le choisir.
     *
     * L'espace ne sert à rien tant qu'il n'est pas renseigné : sans spécialité
     * ni niveau, les contenus arrivent toutes filières confondues.
     *
     * @OA\Get(
     *     path="/api/insam-ia/study-profile",
     *     summary="Profil d'études et référentiel des spécialités",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Profil et filières disponibles")
     * )
     */
    public function studyProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->respond(fn () => [
            'data' => [
                'profile' => $this->presentStudyProfile($user),
                'filieres' => $this->insamIa->categoriesByFiliere(),
                'niveaux' => $this->insamIa->availableLevels(),
            ],
        ]);
    }

    /**
     * Enregistre la spécialité et le niveau choisis.
     *
     * La spécialité est conservée par son identifiant *et* par son libellé :
     * les évaluations se filtrent sur l'identifiant, les épreuves sur le nom,
     * qu'elles portent tel quel.
     *
     * @OA\Post(
     *     path="/api/insam-ia/study-profile",
     *     summary="Enregistrer son profil d'études",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"category_id","niveau"},
     *         @OA\Property(property="category_id", type="integer", example=24),
     *         @OA\Property(property="niveau", type="integer", example=1)
     *     )),
     *     @OA\Response(response=200, description="Profil enregistré"),
     *     @OA\Response(response=422, description="Spécialité inconnue")
     * )
     */
    public function saveStudyProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|min:1',
            'niveau' => 'required|integer|min:1|max:3',
        ]);

        return $this->respond(function () use ($request, $validated) {
            $category = $this->insamIa->findCategory((int) $validated['category_id']);

            if ($category === null) {
                throw InsamIaException::notFound(
                    "Spécialité {$validated['category_id']} introuvable."
                );
            }

            $user = $request->user();

            $user->forceFill([
                'insam_ia_category_id' => (int) $validated['category_id'],
                'insam_ia_specialite' => $category['name'] ?? null,
                'insam_ia_filiere' => $category['filiere'] ?? null,
                'insam_ia_niveau' => (int) $validated['niveau'],
                'insam_ia_profile_completed_at' => now(),
            ])->save();

            return [
                'message' => __('insam_ia.study_profile_saved'),
                'data' => ['profile' => $this->presentStudyProfile($user)],
            ];
        });
    }

    // ------------------------------------------------------------------
    // Supports de cours
    // ------------------------------------------------------------------

    /**
     * @OA\Get(
     *     path="/api/insam-ia/courses",
     *     summary="Bibliothèque de cours",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Supports de cours paginés")
     * )
     */
    public function courses(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'search' => 'nullable|string|max:120',
            'page' => 'nullable|integer|min:1',
        ]);

        $user = $request->user();
        $showAll = $request->boolean('all');

        return $this->respond(function () use ($validated, $user, $showAll) {
            // La bibliothèque ne porte qu'un code UE : le filtre passe par les
            // préfixes rattachés à la spécialité de l'étudiant, et par le
            // premier chiffre du code, qui donne l'année.
            $prefixes = $showAll
                ? null
                : $this->insamIa->uePrefixesFor(
                    $user?->insam_ia_category_id,
                    $user?->insam_ia_filiere,
                );

            $library = $this->insamIa->courseLibrary(
                $validated['search'] ?? null,
                (int) ($validated['page'] ?? 1),
                $prefixes,
                $showAll ? null : $user?->insam_ia_niveau,
            );

            return [
                'data' => $library['documents'],
                'meta' => [
                    'current_page' => $library['page'],
                    'last_page' => $library['pages'],
                    'total' => $library['total'],
                ],
            ];
        });
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/courses/{id}",
     *     summary="Contenu d'un support de cours",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Support avec son contenu")
     * )
     */
    public function course(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->courseMaterial($id)]);
    }

    /**
     * @OA\Get(
     *     path="/api/insam-ia/courses/{code}/chapters",
     *     summary="Chapitres d'une UE",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="code", in="path", required=true, @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Chapitres et leur contenu")
     * )
     */
    public function courseChapters(Request $request, string $code): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => ['data' => $this->insamIa->ueChapters($code)]);
    }

    /**
     * Assistant du cours : question libre ou résumé.
     *
     * Les fiches de révision et les exercices ont leurs propres routes : ils
     * se conservent, là où une question reste sans lendemain.
     *
     * @OA\Post(
     *     path="/api/insam-ia/courses/{id}/assistant",
     *     summary="Poser une question sur un cours",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"action"},
     *         @OA\Property(property="action", type="string", enum={"question","resume"}),
     *         @OA\Property(property="message", type="string")
     *     )),
     *     @OA\Response(response=200, description="Réponse de l'assistant")
     * )
     */
    public function courseAssistant(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'action' => 'required|in:question,resume',
            'message' => 'required_if:action,question|nullable|string|max:2000',
            'chapitre' => 'nullable|string|max:200',
            'historique' => 'nullable|array|max:20',
        ]);

        return $this->respond(fn () => [
            'data' => $this->insamIa->courseAssistant(array_filter([
                'document_id' => $id,
                'action' => $validated['action'],
                'message' => $validated['message'] ?? null,
                'chapitre' => $validated['chapitre'] ?? null,
                'historique' => $validated['historique'] ?? null,
            ], fn ($value) => $value !== null)),
        ]);
    }

    /**
     * Exercices tirés d'un cours — c'est ce qui rattache une évaluation au
     * cours révisé.
     *
     * Les bonnes réponses sont retirées de l'énoncé et conservées en session
     * le temps de la correction : elles ne doivent jamais partir avec les
     * questions.
     *
     * @OA\Post(
     *     path="/api/insam-ia/courses/{id}/exercises",
     *     summary="Générer des exercices sur un cours",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Questions à choix multiples")
     * )
     */
    public function courseExercises(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'chapitre' => 'nullable|string|max:200',
        ]);

        return $this->respond(function () use ($request, $id, $validated) {
            $exercises = $this->insamIa->courseExercises($id, $validated['chapitre'] ?? null);

            // Le corrigé reste côté serveur jusqu'à la soumission. L'API est
            // sans session (jeton Sanctum) : il est déposé au cache, le temps
            // de faire l'exercice.
            Cache::put(
                $this->exerciseAnswersKey($request->user()->id, $id),
                $exercises['answers'],
                self::EXERCISE_TTL,
            );

            return [
                'data' => [
                    'document' => $exercises['document'],
                    'questions' => $exercises['questions'],
                ],
            ];
        });
    }

    /**
     * Corrige les réponses à des exercices de cours.
     *
     * @OA\Post(
     *     path="/api/insam-ia/courses/{id}/exercises/submit",
     *     summary="Corriger ses réponses",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Score et corrections")
     * )
     */
    public function submitCourseExercises(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|integer|min:0',
        ]);

        $key = $this->exerciseAnswersKey($request->user()->id, $id);
        $expected = Cache::get($key);

        if (!is_array($expected) || $expected === []) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.exercises_expired'),
            ], 409);
        }

        $corrections = [];
        $score = 0;

        foreach ($expected as $index => $answer) {
            $submitted = $validated['answers'][$index] ?? $validated['answers'][(string) $index] ?? null;
            $correct = $answer['correct_answer'] ?? null;

            // Une question dont l'assistant n'a pas donné la réponse ne peut
            // ni être comptée juste, ni être comptée fausse.
            $isCorrect = $correct !== null && $submitted !== null && (int) $submitted === (int) $correct;

            if ($isCorrect) {
                $score++;
            }

            $corrections[] = [
                'index' => (int) $index,
                'question' => $answer['question'] ?? '',
                'options' => $answer['options'] ?? [],
                'submitted' => $submitted !== null ? (int) $submitted : null,
                'correct_answer' => $correct,
                'is_correct' => $isCorrect,
                'explanation' => $answer['explanation'] ?? null,
            ];
        }

        // Un exercice ne se corrige qu'une fois : le corrigé est consommé.
        Cache::forget($key);

        $total = count($expected);

        return response()->json([
            'success' => true,
            'data' => [
                'score' => $score,
                'total' => $total,
                'percentage' => $total > 0 ? (int) round($score * 100 / $total) : 0,
                'corrections' => $corrections,
            ],
        ]);
    }

    /**
     * Profil d'études tel que servi à l'application.
     */
    private function presentStudyProfile($user): array
    {
        return [
            'completed' => $user !== null && $user->hasInsamIaStudyProfile(),
            'category_id' => $user?->insam_ia_category_id,
            'specialite' => $user?->insam_ia_specialite,
            'filiere' => $user?->insam_ia_filiere,
            'niveau' => $user?->insam_ia_niveau,
            'completed_at' => $user?->insam_ia_profile_completed_at?->toISOString(),
        ];
    }

    /**
     * Emplacement du corrigé, propre à l'étudiant et au cours.
     */
    private function exerciseAnswersKey(int $userId, int $documentId): string
    {
        return "insam_ia:exercises:{$userId}:{$documentId}";
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
            'data' => $this->insamIa->revisionCards($categoryId, $request->user()),
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

        // Une fiche tirée d'un cours n'existe que chez nous : elle se lit par
        // son identifiant local, que `source=course` désigne explicitement.
        if ($request->query('source') === 'course') {
            return $this->respond(fn () => [
                'data' => $this->insamIa->courseRevisionCard($id, $request->user()),
            ]);
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

        // Une fiche se demande désormais sur un cours précis. La génération
        // par filière reste acceptée pour ne pas casser les clients déjà
        // déployés, mais elle ne produit que des fiches génériques.
        $validated = $request->validate([
            'document_id' => 'required_without:category_id|integer|min:1',
            'chapitre' => 'nullable|string|max:200',
            'lacunes' => 'nullable|array|max:20',
            'lacunes.*' => 'string|max:300',
            'category_id' => 'required_without:document_id|integer|min:1',
            'category_name' => 'nullable|string|max:150',
        ]);

        if (isset($validated['document_id'])) {
            GenerateInsamIaCourseRevisionCard::dispatch(
                $request->user()->id,
                (int) $validated['document_id'],
                $validated['chapitre'] ?? null,
                $validated['lacunes'] ?? [],
            );

            return response()->json([
                'success' => true,
                'message' => __('insam_ia.generation_queued'),
            ], 202);
        }

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

    // ------------------------------------------------------------------
    // Formations vidéo — progression et attestation
    // ------------------------------------------------------------------

    /**
     * Enregistre l'avancement de visionnage d'une vidéo de formation.
     *
     * Les vidéos vivent chez InsamTechs, qui ne garde aucune progression par
     * étudiant : c'est Estuaire qui la tient, et c'est elle qui ouvre droit à
     * l'attestation de formation.
     *
     * @OA\Post(
     *     path="/api/insam-ia/trainings/{formation}/progress",
     *     summary="Suivre le visionnage d'une vidéo de formation",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="formation", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"video_id","position_seconds"},
     *         @OA\Property(property="video_id", type="integer"),
     *         @OA\Property(property="position_seconds", type="integer"),
     *         @OA\Property(property="duration_seconds", type="integer")
     *     )),
     *     @OA\Response(response=200, description="Progression enregistrée")
     * )
     */
    public function trackTrainingVideo(Request $request, int $formationId): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'video_id' => 'required|integer|min:1',
            'position_seconds' => 'required|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:1',
            'formation_title' => 'nullable|string|max:200',
            'video_title' => 'nullable|string|max:200',
            'videos_total' => 'nullable|integer|min:1',
        ]);

        $user = $request->user();

        $this->trainingProgress->track(
            $user,
            $formationId,
            (int) $validated['video_id'],
            (int) $validated['position_seconds'],
            isset($validated['duration_seconds']) ? (int) $validated['duration_seconds'] : null,
            $validated['formation_title'] ?? null,
            $validated['video_title'] ?? null,
        );

        return response()->json([
            'success' => true,
            'message' => __('insam_ia.progress_saved'),
            'data' => $this->trainingProgress->formationProgress(
                $user,
                $formationId,
                isset($validated['videos_total']) ? (int) $validated['videos_total'] : null,
            ),
        ]);
    }

    /**
     * Progression de l'étudiant sur une formation.
     *
     * Le nombre total de vidéos vient du catalogue InsamTechs : l'application
     * le transmet, faute de quoi seule la part déjà connue d'Estuaire compte.
     *
     * @OA\Get(
     *     path="/api/insam-ia/trainings/{formation}/progress",
     *     summary="Progression sur une formation",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="formation", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="videos_total", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Progression de la formation")
     * )
     */
    public function showTrainingProgress(Request $request, int $formationId): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'videos_total' => 'nullable|integer|min:1',
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->trainingProgress->formationProgress(
                $request->user(),
                $formationId,
                isset($validated['videos_total']) ? (int) $validated['videos_total'] : null,
            ),
        ]);
    }

    /**
     * Délivre l'attestation d'une formation suivie de bout en bout.
     *
     * @OA\Post(
     *     path="/api/insam-ia/trainings/{formation}/attestation",
     *     summary="Obtenir l'attestation d'une formation",
     *     tags={"INSAM-IA"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="formation", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         required={"formation_title","videos_total"},
     *         @OA\Property(property="formation_title", type="string"),
     *         @OA\Property(property="videos_total", type="integer")
     *     )),
     *     @OA\Response(response=201, description="Attestation délivrée"),
     *     @OA\Response(response=422, description="Formation non achevée")
     * )
     */
    public function issueTrainingAttestation(Request $request, int $formationId): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'formation_title' => 'required|string|max:200',
            'videos_total' => 'required|integer|min:1',
        ]);

        try {
            $attestation = $this->attestations->issueForTraining(
                $request->user(),
                $formationId,
                $validated['formation_title'],
                (int) $validated['videos_total'],
            );
        } catch (InsamIaAttestationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => __('insam_ia.training_attestation_issued'),
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

        if (!$user || !$this->hasAccess($user)) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.student_mode_required'),
                'requires_student_mode' => true,
            ], 403);
        }

        // Le profil d'études commande le filtrage de tous les contenus : sans
        // lui, l'espace servirait 604 épreuves et 6 800 cours toutes filières
        // confondues. L'application ouvre alors son écran de configuration.
        if (!$user->hasInsamIaStudyProfile()) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.study_profile_required'),
                'requires_study_profile' => true,
            ], 428);
        }

        return null;
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
            // Une attestation sanctionne soit une évaluation, soit une
            // formation vidéo suivie de bout en bout : l'application n'affiche
            // pas « score » de la même façon dans les deux cas.
            'source' => $attestation->source,
            'formation_id' => $attestation->formation_id,
            'videos_total' => $attestation->videos_total,
            'issued_at' => $attestation->issued_at?->toISOString(),
            'download_url' => route('insam-ia.attestations.download', $attestation->id),
        ];
    }

    // ------------------------------------------------------------------
    // Espace de travail « épreuves »
    // ------------------------------------------------------------------

    /**
     * Épreuves d'entraînement générées par l'étudiant.
     *
     * Le miroir local fait office de listing : INSAM-IA ne sait pas lister ses
     * propres générations, `generated` étant capturé par `/exams/{id}`.
     */
    public function generatedExams(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $exams = InsamIaGeneratedExam::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn (InsamIaGeneratedExam $exam) => $this->presentGeneratedExam($exam));

        return response()->json(['success' => true, 'data' => $exams]);
    }

    /**
     * Sujet complet d'une épreuve générée.
     */
    public function generatedExam(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $exam = InsamIaGeneratedExam::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $this->presentGeneratedExam($exam, withContent: true),
        ]);
    }

    /**
     * Génère une épreuve d'entraînement par IA.
     *
     * La génération prend une vingtaine de secondes : le sujet est conservé
     * chez nous pour que l'étudiant le retrouve sans repayer un appel.
     */
    public function generateExam(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'matiere' => 'required|string|max:150',
            'filiere' => 'nullable|string|max:150',
            'niveau' => 'nullable|string|max:100',
            'nombre' => 'nullable|integer|min:1|max:10',
            'difficulte' => 'nullable|string|in:facile,moyen,difficile',
        ]);

        $user = $request->user();

        return $this->respond(function () use ($validated, $user) {
            // À défaut de précision, le profil d'études de l'étudiant : c'est
            // lui qui cadre déjà tous les autres contenus de l'espace.
            $filiere = $validated['filiere'] ?? $user->insam_ia_specialite;
            $niveau = $validated['niveau'] ?? $user->insam_ia_niveau;

            $generated = $this->insamIa->generateExercises(
                $validated['matiere'],
                $filiere,
                $niveau,
                $validated['nombre'] ?? 3,
                $validated['difficulte'] ?? null,
            );

            $exam = InsamIaGeneratedExam::create([
                'user_id' => $user->id,
                'remote_id' => $generated['remote_id'],
                'matiere' => $validated['matiere'],
                'filiere' => $filiere,
                'niveau' => $niveau,
                'difficulte' => $validated['difficulte'] ?? null,
                'nombre' => $validated['nombre'] ?? 3,
                'content' => $generated['content'],
            ]);

            return ['data' => $this->presentGeneratedExam($exam, withContent: true)];
        });
    }

    /**
     * Supprime une épreuve générée, ici et chez INSAM-IA.
     */
    public function deleteGeneratedExam(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $exam = InsamIaGeneratedExam::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        // La suppression distante ne doit pas retenir la locale : l'étudiant a
        // demandé à ne plus voir ce sujet, et une panne du service tiers ne
        // peut pas l'en empêcher.
        if ($exam->remote_id !== null) {
            try {
                $this->insamIa->deleteGeneratedExam($exam->remote_id);
            } catch (InsamIaException $e) {
                Log::warning('Suppression distante impossible', [
                    'remote_id' => $exam->remote_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $exam->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Corrige une copie rédigée par l'étudiant.
     *
     * Deux entrées : le texte collé, ou un PDF dont on extrait le texte. Le
     * sujet est facultatif — la correction gagne à l'avoir, mais une copie
     * seule reste évaluable.
     */
    public function correctCopy(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'copie' => 'required_without:file|nullable|string|max:50000',
            'file' => 'required_without:copie|nullable|file|mimes:pdf|max:10240',
            'sujet' => 'nullable|string|max:50000',
            'matiere' => 'nullable|string|max:150',
            'title' => 'nullable|string|max:200',
        ]);

        $copie = $validated['copie'] ?? null;

        if ($request->hasFile('file')) {
            $extracted = app(PdfTextExtractor::class)->extract($request->file('file'));

            // PDF scanné : aucune couche texte. On le dit plutôt que de faire
            // noter une copie vide.
            if ($extracted === null) {
                return response()->json([
                    'success' => false,
                    'message' => __('insam_ia.pdf_not_readable'),
                    'pdf_not_readable' => true,
                ], 422);
            }

            $copie = $extracted;
        }

        $user = $request->user();

        return $this->respond(function () use ($validated, $copie, $user) {
            $result = $this->insamIa->evaluateExamCopy(
                $validated['sujet'] ?? __('insam_ia.copy_without_subject'),
                $copie,
            );

            $note = $result['note_sur_20'] ?? null;

            // La progression n'a de sens qu'avec une note : une appréciation
            // sans barème ne se compare à rien.
            if ($note !== null) {
                InsamIaCourseProgress::create([
                    'user_id' => $user->id,
                    'type' => InsamIaCourseProgress::TYPE_CORRECTION,
                    'subject' => $validated['matiere'] ?? $user->insam_ia_specialite ?? '—',
                    'title' => $validated['title'] ?? __('insam_ia.copy_correction'),
                    'score' => $note,
                    'max_score' => 20,
                ]);
            }

            return ['data' => $result];
        });
    }

    // ------------------------------------------------------------------
    // Enrichissement de la banque
    // ------------------------------------------------------------------

    /**
     * Référentiel de saisie d'un dépôt : spécialités, matières et niveaux.
     *
     * Tout vient d'INSAM-IA : ce sont ses spécialités (47, groupées en
     * filières) et ses UE que l'étudiant retrouve partout ailleurs dans
     * l'espace. Une liste tenue de notre côté finirait par diverger de la
     * sienne, et un dépôt classé « Informatique » ne se retrouverait plus
     * depuis une bibliothèque qui parle de « GÉNIE LOGICIEL ».
     *
     * Les matières arrivent vides tant que l'application n'a pas choisi de
     * spécialité : elles se chargent ensuite via [contributionSubjects],
     * les parcourir toutes coûterait des centaines d'appels distants.
     */
    public function contributionOptions(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(function () {
            $specialties = collect($this->insamIa->categories())
                ->map(fn (array $category) => [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'filiere' => $category['filiere'],
                ])
                ->filter(fn (array $category) => $category['id'] !== null)
                ->values()
                ->all();

            $levels = [];

            foreach (self::CONTRIBUTION_LEVELS as $value => $label) {
                $levels[] = ['value' => (string) $value, 'label' => $label];
            }

            return [
                'data' => [
                    'specialties' => $specialties,
                    'levels' => $levels,
                ],
            ];
        });
    }

    /**
     * Matières (UE) d'une spécialité.
     *
     * Appel séparé : la liste se charge au moment où l'étudiant choisit sa
     * spécialité, pas avant.
     */
    public function contributionSubjects(Request $request, int $categoryId): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        return $this->respond(fn () => [
            'data' => $this->insamIa->subjectsForCategory($categoryId),
        ]);
    }

    /**
     * Sujets déposés par l'étudiant.
     */
    public function contributions(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $contributions = InsamIaExamContribution::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn (InsamIaExamContribution $c) => $this->presentContribution($c));

        return response()->json(['success' => true, 'data' => $contributions]);
    }

    /**
     * Dépose un sujet pour enrichir la banque commune.
     *
     * Le fichier reste sur un disque privé : un sujet servi par URL publique
     * échapperait à tout contrôle dès le premier partage. Le dépôt attend une
     * validation avant d'être visible des autres étudiants.
     */
    public function storeContribution(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        // La spécialité doit exister chez INSAM-IA : c'est ce qui rattache le
        // dépôt au reste de l'espace. La matière est vérifiée juste après,
        // une fois la spécialité connue.
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => ['required', 'integer'],
            'matiere' => ['required', 'string', 'max:200'],
            'niveau' => ['required', Rule::in(array_map('strval', array_keys(self::CONTRIBUTION_LEVELS)))],
            'annee' => 'nullable|string|max:16',
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $specialty = collect($this->insamIa->categories())
            ->firstWhere('id', (int) $validated['category_id']);

        if ($specialty === null) {
            throw ValidationException::withMessages([
                'category_id' => __('validation.exists', ['attribute' => 'category_id']),
            ]);
        }

        // La matière doit appartenir à cette spécialité : c'est ce qui évite
        // qu'un sujet de comptabilité atterrisse dans le rayon informatique.
        $subjects = collect($this->insamIa->subjectsForCategory((int) $validated['category_id']));

        if ($subjects->isNotEmpty()
            && !$subjects->contains(fn (array $subject) => $subject['label'] === $validated['matiere'])) {
            throw ValidationException::withMessages([
                'matiere' => __('validation.in', ['attribute' => 'matiere']),
            ]);
        }

        $file = $request->file('file');

        // Le texte sert la recherche et la modération ; son absence (sujet
        // scanné) ne justifie pas de refuser le dépôt, contrairement à une
        // correction où la copie doit être lisible.
        $extracted = app(PdfTextExtractor::class)->extract($file);

        $path = $file->store('insam-ia/contributions', 'local');

        $contribution = InsamIaExamContribution::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'matiere' => $validated['matiere'],
            'filiere' => $specialty['name'],
            'niveau' => $validated['niveau'],
            'annee' => $validated['annee'] ?? null,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'extracted_text' => $extracted,
            'status' => InsamIaExamContribution::STATUS_PENDING,
        ]);

        return response()->json([
            'success' => true,
            'data' => $this->presentContribution($contribution),
        ], 201);
    }

    /**
     * Retire un dépôt, tant qu'il n'a pas été validé.
     */
    public function deleteContribution(Request $request, int $id): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $contribution = InsamIaExamContribution::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        // Une fois le sujet entré dans la banque commune, il ne s'agit plus
        // seulement du dépôt de son auteur : les autres étudiants s'en servent.
        if ($contribution->status === InsamIaExamContribution::STATUS_APPROVED) {
            return response()->json([
                'success' => false,
                'message' => __('insam_ia.contribution_locked'),
            ], 409);
        }

        Storage::disk('local')->delete($contribution->file_path);
        $contribution->delete();

        return response()->json(['success' => true]);
    }

    // ------------------------------------------------------------------
    // Progression
    // ------------------------------------------------------------------

    /**
     * Relevé des activités notées, avec moyennes par matière.
     */
    public function courseProgress(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $entries = InsamIaCourseProgress::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(100)
            ->get();

        // Moyenne ramenée sur 20 : les activités n'ont pas toutes le même
        // barème, seul le rapport est comparable.
        $average = $entries->isEmpty() ? null : round(
            $entries->avg(fn (InsamIaCourseProgress $e) => $e->max_score > 0
                ? $e->score / $e->max_score * 20
                : 0),
            2,
        );

        $bySubject = $entries
            ->groupBy('subject')
            ->map(fn ($group, $subject) => [
                'subject' => $subject,
                'count' => $group->count(),
                'average' => round($group->avg(fn (InsamIaCourseProgress $e) => $e->max_score > 0
                    ? $e->score / $e->max_score * 20
                    : 0), 2),
            ])
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'average' => $average,
                'total' => $entries->count(),
                'by_subject' => $bySubject,
                'entries' => $entries->map(fn (InsamIaCourseProgress $e) => [
                    'id' => $e->id,
                    'type' => $e->type,
                    'subject' => $e->subject,
                    'title' => $e->title,
                    'score' => (float) $e->score,
                    'max_score' => (float) $e->max_score,
                    'created_at' => $e->created_at?->toIso8601String(),
                ]),
            ],
        ]);
    }

    /**
     * Enregistre une activité notée.
     */
    public function trackCourseProgress(Request $request): JsonResponse
    {
        if ($denied = $this->denyWithoutAccess($request)) {
            return $denied;
        }

        $validated = $request->validate([
            'type' => 'required|string|in:' . implode(',', InsamIaCourseProgress::TYPES),
            'subject' => 'required|string|max:150',
            'title' => 'required|string|max:200',
            'score' => 'required|numeric|min:0',
            'max_score' => 'nullable|numeric|min:1',
            'meta' => 'nullable|array',
        ]);

        $entry = InsamIaCourseProgress::create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'subject' => $validated['subject'],
            'title' => $validated['title'],
            'score' => $validated['score'],
            'max_score' => $validated['max_score'] ?? 20,
            'meta' => $validated['meta'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $entry->id,
                'type' => $entry->type,
                'subject' => $entry->subject,
                'title' => $entry->title,
                'score' => (float) $entry->score,
                'max_score' => (float) $entry->max_score,
                'created_at' => $entry->created_at?->toIso8601String(),
            ],
        ], 201);
    }

    private function presentGeneratedExam(
        InsamIaGeneratedExam $exam,
        bool $withContent = false,
    ): array {
        $payload = [
            'id' => $exam->id,
            'remote_id' => $exam->remote_id,
            'matiere' => $exam->matiere,
            'filiere' => $exam->filiere,
            'niveau' => $exam->niveau,
            'difficulte' => $exam->difficulte,
            'nombre' => $exam->nombre,
            'created_at' => $exam->created_at?->toIso8601String(),
        ];

        if ($withContent) {
            $payload['content'] = $exam->content;
        }

        return $payload;
    }

    private function presentContribution(InsamIaExamContribution $contribution): array
    {
        return [
            'id' => $contribution->id,
            'title' => $contribution->title,
            'matiere' => $contribution->matiere,
            'filiere' => $contribution->filiere,
            'niveau' => $contribution->niveau,
            'annee' => $contribution->annee,
            'file_name' => $contribution->file_name,
            'file_size' => $contribution->file_size,
            'status' => $contribution->status,
            'rejection_reason' => $contribution->rejection_reason,
            'created_at' => $contribution->created_at?->toIso8601String(),
        ];
    }
}
