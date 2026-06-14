<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Roadmap;
use App\Models\RoadmapLevel;
use App\Models\UserRoadmapProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * API mobile des Roadmaps gamifiées.
 *
 * Logique de déblocage : un niveau d'order N est débloqué si N <=
 * current_level de la progression utilisateur. On valide un niveau en
 * réussissant son QCM (score >= pass_threshold de la roadmap), ce qui
 * incrémente current_level. Le contrôle d'accès packs (C1/C2/C3) est
 * identique à celui des programmes.
 */
class RoadmapController extends Controller
{
    /**
     * Liste des roadmaps actives, avec accès + progression de l'utilisateur.
     */
    public function index(Request $request): JsonResponse
    {
        // Auth optionnelle : route publique (mode vitrine). Le token, s'il est
        // présent, est résolu pour personnaliser accès + progression ; sinon
        // $user est null et on renvoie la version "invité" (1er niveau jouable).
        $user = auth('sanctum')->user();

        $roadmaps = Roadmap::withCount('levels')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $userPack = $this->getUserPack($user);
        $progressByRoadmap = $user
            ? UserRoadmapProgress::where('user_id', $user->id)
                ->whereIn('roadmap_id', $roadmaps->pluck('id'))
                ->get()
                ->keyBy('roadmap_id')
            : collect();

        $data = $roadmaps->map(function (Roadmap $roadmap) use ($userPack, $progressByRoadmap) {
            $progress = $progressByRoadmap->get($roadmap->id);
            $completed = $progress ? count($progress->completed_levels ?? []) : 0;

            return [
                'id' => $roadmap->id,
                'title' => $roadmap->title,
                'slug' => $roadmap->slug,
                'domain' => $roadmap->domain,
                'domain_display' => $roadmap->domain_display,
                'description' => $roadmap->description,
                'objectives' => $roadmap->objectives,
                'icon' => $roadmap->icon,
                'color' => $roadmap->color,
                'difficulty' => $roadmap->difficulty,
                'difficulty_display' => $roadmap->difficulty_display,
                'pass_threshold' => $roadmap->pass_threshold,
                'levels_count' => $roadmap->levels_count,
                'has_access' => $this->checkAccess($roadmap, $userPack),
                'required_packs' => $roadmap->required_packs ?? [],
                'progress' => [
                    'current_level' => $progress->current_level ?? 1,
                    'completed_levels' => $completed,
                    'total_xp' => $progress->total_xp ?? 0,
                    'started' => $progress !== null,
                    'completed' => $progress?->completed_at !== null,
                ],
            ];
        });

        return response()->json([
            'success' => true,
            'locale' => App::getLocale(),
            'roadmaps' => $data,
            'user_pack' => $userPack,
            // Freemium : false = seul le 1er niveau de chaque roadmap est jouable.
            'has_full_access' => $this->hasFullAccess($user),
        ]);
    }

    /**
     * Détail d'une roadmap : tous les niveaux, avec leur état (locked / unlocked
     * / completed) et le QCM des niveaux débloqués.
     */
    public function show(Request $request, Roadmap $roadmap): JsonResponse
    {
        // Auth optionnelle : route publique (mode vitrine).
        $user = auth('sanctum')->user();
        $userPack = $this->getUserPack($user);

        if (!$this->checkAccess($roadmap, $userPack)) {
            return response()->json([
                'success' => false,
                'message' => __('program.no_access'),
                'required_packs' => $roadmap->required_packs ?? [],
                'current_pack' => $userPack,
            ], 403);
        }

        $roadmap->load('levels.questions');
        $progress = $this->resolveProgress($user, $roadmap);
        $currentLevel = $progress->current_level;
        $completedIds = $progress->completed_levels ?? [];

        // Accès "complet" = l'utilisateur possède un pack étudiant valide.
        // Sans pack (mode freemium) : seul le 1er niveau (order 1) est jouable,
        // les suivants sont verrouillés par pack (requires_pack).
        $hasFullAccess = $this->hasFullAccess($user);

        $levels = $roadmap->levels->map(function (RoadmapLevel $level) use ($currentLevel, $completedIds, $progress, $hasFullAccess) {
            $isCompleted = in_array($level->id, $completedIds);
            // Verrou "pack" : niveau 2+ sans accès complet.
            $requiresPack = !$hasFullAccess && $level->order > 1;
            // Débloqué par la progression (quiz réussis) ET non bloqué par pack.
            $isUnlocked = !$requiresPack && $level->order <= $currentLevel;
            $isAccessible = $isUnlocked || $isCompleted;

            return [
                'id' => $level->id,
                'title' => $level->title,
                'subtitle' => $level->subtitle,
                // Contenu masqué tant que le niveau n'est pas accessible.
                'content' => $isAccessible ? $level->content : null,
                'order' => $level->order,
                'has_quiz' => $level->has_quiz,
                'xp_reward' => $level->xp_reward,
                'status' => $isCompleted ? 'completed' : ($isUnlocked ? 'unlocked' : 'locked'),
                // Distingue "verrouillé par progression" vs "verrouillé par pack".
                'requires_pack' => $requiresPack,
                'quiz_score' => $progress->quiz_scores[$level->id] ?? null,
                // Le QCM (sans les bonnes réponses) seulement si accessible.
                'questions' => $isAccessible ? $level->questions->map(fn ($q) => [
                    'id' => $q->id,
                    'question' => $q->question,
                    'options' => $q->options,
                    // correct_answers volontairement omis (corrigé côté serveur).
                ]) : [],
            ];
        });

        return response()->json([
            'success' => true,
            'locale' => App::getLocale(),
            'has_full_access' => $hasFullAccess,
            'roadmap' => [
                'id' => $roadmap->id,
                'title' => $roadmap->title,
                'slug' => $roadmap->slug,
                'domain' => $roadmap->domain,
                'description' => $roadmap->description,
                'icon' => $roadmap->icon,
                'color' => $roadmap->color,
                'pass_threshold' => $roadmap->pass_threshold,
                'levels' => $levels,
            ],
            'progress' => [
                'current_level' => $currentLevel,
                'total_xp' => $progress->total_xp,
                'completed_levels' => count($completedIds),
            ],
        ]);
    }

    /**
     * Soumet les réponses au QCM d'un niveau. Si le score >= seuil, le niveau
     * est validé et le niveau suivant débloqué.
     *
     * Payload attendu :
     *   { "answers": { "<question_id>": [0, 2], "<question_id>": [1] } }
     */
    public function submitQuiz(Request $request, Roadmap $roadmap, RoadmapLevel $level): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => __('common.not_authenticated')], 401);
        }

        abort_if($level->roadmap_id !== $roadmap->id, 404);

        if (!$this->checkAccess($roadmap, $this->getUserPack($user))) {
            return response()->json(['success' => false, 'message' => __('program.no_access')], 403);
        }

        // Freemium : sans pack étudiant, seul le 1er niveau est validable.
        // Les niveaux 2+ exigent un pack actif (déblocage instantané dès l'achat).
        if (!$this->hasFullAccess($user) && $level->order > 1) {
            return response()->json([
                'success' => false,
                'requires_pack' => true,
                'message' => __('program.no_access'),
            ], 403);
        }

        $progress = $this->resolveProgress($user, $roadmap);

        // Le niveau doit être débloqué pour être validé.
        if ($level->order > $progress->current_level) {
            return response()->json([
                'success' => false,
                'message' => 'Ce niveau est encore verrouillé.',
            ], 422);
        }

        $level->load('questions');
        $questions = $level->questions;

        if ($questions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce niveau ne possède pas de QCM.',
            ], 422);
        }

        $answers = $request->input('answers', []);
        $correctCount = 0;
        $results = [];

        foreach ($questions as $question) {
            $given = array_map('intval', (array) ($answers[$question->id] ?? []));
            $isCorrect = $question->isCorrect($given);
            if ($isCorrect) {
                $correctCount++;
            }
            $results[] = [
                'question_id' => $question->id,
                'correct' => $isCorrect,
                'correct_answers' => array_map('intval', $question->correct_answers ?? []),
                'explanation' => $question->explanation,
            ];
        }

        $score = (int) round(($correctCount / $questions->count()) * 100);
        $passed = $score >= $roadmap->pass_threshold;

        // Enregistre le score (meilleur score conservé).
        $scores = $progress->quiz_scores ?? [];
        $scores[$level->id] = max($scores[$level->id] ?? 0, $score);
        $progress->quiz_scores = $scores;

        $leveledUp = false;
        if ($passed) {
            $completed = $progress->completed_levels ?? [];
            if (!in_array($level->id, $completed)) {
                $completed[] = $level->id;
                $progress->completed_levels = $completed;
                $progress->total_xp += $level->xp_reward;
            }

            // Débloque le niveau suivant si on validait le niveau courant.
            $maxOrder = $roadmap->levels()->max('order');
            if ($level->order >= $progress->current_level && $level->order < $maxOrder) {
                $progress->current_level = $level->order + 1;
                $leveledUp = true;
            }

            // Roadmap terminée ?
            if (count($progress->completed_levels) >= $roadmap->levels()->count() && !$progress->completed_at) {
                $progress->completed_at = now();
            }
        }

        $progress->save();

        return response()->json([
            'success' => true,
            'passed' => $passed,
            'score' => $score,
            'pass_threshold' => $roadmap->pass_threshold,
            'correct_count' => $correctCount,
            'total_questions' => $questions->count(),
            'leveled_up' => $leveledUp,
            'current_level' => $progress->current_level,
            'total_xp' => $progress->total_xp,
            'results' => $results,
        ]);
    }

    // ==========================================================
    //  Helpers
    // ==========================================================

    /**
     * Récupère (ou crée) la progression de l'utilisateur sur la roadmap.
     */
    private function resolveProgress($user, Roadmap $roadmap): UserRoadmapProgress
    {
        // Mode vitrine (invité sans compte) : pas de persistance. On renvoie une
        // progression vide en mémoire (niveau 1, aucun niveau complété) pour que
        // le détail de la roadmap soit consultable sans token.
        if (!$user) {
            return new UserRoadmapProgress([
                'roadmap_id' => $roadmap->id,
                'current_level' => 1,
                'completed_levels' => [],
                'quiz_scores' => [],
                'total_xp' => 0,
            ]);
        }

        return UserRoadmapProgress::firstOrCreate(
            ['user_id' => $user->id, 'roadmap_id' => $roadmap->id],
            ['current_level' => 1, 'started_at' => now()]
        );
    }

    /**
     * Accès complet aux roadmaps = l'utilisateur possède le Pack Étudiant
     * (service premium "student_mode") actif. Sans ce pack (freemium), seul le
     * 1er niveau de chaque roadmap est jouable. Dès l'achat du Pack Étudiant,
     * le reste se débloque.
     *
     * NB : volontairement indépendant des abonnements C1/C2/C3 — seul le Pack
     * Étudiant donne l'accès complet à la RoadMap / Projet Pro.
     */
    private function hasFullAccess($user): bool
    {
        if (!$user) {
            return false;
        }
        return $user->hasStudentMode();
    }

    /**
     * Détermine le pack (C1/C2/C3) de l'utilisateur via son abonnement actif.
     * Même logique que Api\ProgramController.
     */
    private function getUserPack($user): ?string
    {
        if (!$user) {
            return null;
        }

        $activeSubscription = $user->activeSubscription();
        $slug = strtoupper($activeSubscription->subscriptionPlan->slug ?? '');

        if (str_contains($slug, 'C3') || str_contains($slug, 'DIAMANT') || str_contains($slug, 'PLATINUM')) {
            return 'C3';
        }
        if (str_contains($slug, 'C2') || str_contains($slug, 'OR') || str_contains($slug, 'GOLD')) {
            return 'C2';
        }
        if (str_contains($slug, 'C1') || str_contains($slug, 'ARGENT') || str_contains($slug, 'SILVER')) {
            return 'C1';
        }

        return null;
    }

    private function checkAccess(Roadmap $roadmap, ?string $userPack): bool
    {
        if (empty($roadmap->required_packs)) {
            return true;
        }
        if (empty($userPack)) {
            return false;
        }

        $hierarchy = ['C1' => 1, 'C2' => 2, 'C3' => 3];
        $userLevel = $hierarchy[$userPack] ?? 0;

        foreach ($roadmap->required_packs as $required) {
            if ($userLevel >= ($hierarchy[$required] ?? 0)) {
                return true;
            }
        }

        return false;
    }
}
