<?php

namespace App\Services;

use App\Models\TrainingVideoProgress;
use App\Models\User;

/**
 * Suivi du visionnage des formations vidéo.
 *
 * Les vidéos et le catalogue des formations vivent chez InsamTechs, qui ne
 * garde aucune trace de ce que chaque étudiant a vu. Estuaire tient donc seul
 * cette progression : c'est elle qui décide de la délivrance de l'attestation
 * de formation.
 */
class TrainingProgressService
{
    /**
     * Pourcentage à partir duquel une vidéo est considérée comme vue.
     *
     * Pas 100 % : un étudiant qui saute le générique de fin, ou dont le
     * lecteur s'arrête quelques secondes avant la fin, a bel et bien suivi la
     * vidéo et ne doit pas rester bloqué à la porte de son attestation.
     */
    public const COMPLETION_THRESHOLD = 95;

    /**
     * Enregistre la position de lecture d'une vidéo.
     *
     * Une seule ligne par (étudiant, vidéo) : les appels successifs du lecteur
     * mettent à jour la même ligne. La progression ne redescend jamais, sinon
     * un étudiant qui revient en arrière pour revoir un passage perdrait
     * l'achèvement déjà acquis.
     */
    public function track(
        User $user,
        int $formationId,
        int $videoId,
        int $positionSeconds,
        ?int $durationSeconds = null,
        ?string $formationTitle = null,
        ?string $videoTitle = null,
    ): TrainingVideoProgress {
        $progress = TrainingVideoProgress::firstOrNew([
            'user_id' => $user->id,
            'video_id' => $videoId,
        ]);

        // La formation peut être corrigée en cours de route (vidéo déplacée
        // dans le catalogue distant) : on garde toujours la dernière connue.
        $progress->formation_id = $formationId;

        $durationSeconds = $durationSeconds !== null && $durationSeconds > 0
            ? $durationSeconds
            : $progress->duration_seconds;

        $positionSeconds = max(0, $positionSeconds);

        if ($durationSeconds !== null && $durationSeconds > 0) {
            $positionSeconds = min($positionSeconds, $durationSeconds);
        }

        $progress->position_seconds = max($progress->position_seconds ?? 0, $positionSeconds);
        $progress->duration_seconds = $durationSeconds;

        $progress->progress_percent = max(
            $progress->progress_percent ?? 0,
            $this->percentFor($progress->position_seconds, $durationSeconds),
        );

        // Les intitulés sont recopiés du catalogue distant pour rester
        // lisibles si celui-ci change ; un appel sans titre ne les efface pas.
        $progress->formation_title = $formationTitle ?? $progress->formation_title;
        $progress->video_title = $videoTitle ?? $progress->video_title;

        $progress->last_watched_at = now();

        if ($progress->progress_percent >= self::COMPLETION_THRESHOLD && $progress->completed_at === null) {
            $progress->completed_at = now();
        }

        $progress->save();

        return $progress;
    }

    /**
     * Avancement d'un étudiant sur une formation.
     *
     * `$videosTotal` vient du catalogue InsamTechs : notre base ne connaît que
     * les vidéos déjà ouvertes par l'étudiant et sous-estimerait le total. À
     * défaut, on retombe sur les lignes connues, ce qui reste juste tant que
     * l'étudiant a au moins effleuré chaque vidéo.
     *
     * @return array{videos_total: int, videos_completed: int, progress_percent: int, completed: bool, last_watched_at: ?string}
     */
    public function formationProgress(User $user, int $formationId, ?int $videosTotal = null): array
    {
        $rows = TrainingVideoProgress::where('user_id', $user->id)
            ->where('formation_id', $formationId)
            ->get();

        $completed = $rows->whereNotNull('completed_at')->count();

        // Le catalogue peut annoncer moins de vidéos que celles déjà suivies
        // (vidéo retirée) : le total ne peut pas être inférieur au vu.
        $total = max((int) ($videosTotal ?? $rows->count()), $completed);

        return [
            'videos_total' => $total,
            'videos_completed' => $completed,
            'progress_percent' => $total > 0 ? (int) floor($completed * 100 / $total) : 0,
            'completed' => $total > 0 && $completed >= $total,
            'last_watched_at' => $rows->max('last_watched_at')?->toISOString(),
        ];
    }

    /**
     * Toutes les vidéos de la formation ont-elles été vues ?
     */
    public function isFormationCompleted(User $user, int $formationId, int $videosTotal): bool
    {
        if ($videosTotal <= 0) {
            // Une formation sans vidéo ne peut rien attester.
            return false;
        }

        return $this->formationProgress($user, $formationId, $videosTotal)['completed'];
    }

    /**
     * Pourcentage vu d'une vidéo, borné à 100.
     *
     * Sans durée connue (le lecteur ne l'a pas encore transmise), on ne peut
     * rien déduire de la position : on reste à 0 plutôt que de deviner.
     */
    private function percentFor(int $positionSeconds, ?int $durationSeconds): int
    {
        if ($durationSeconds === null || $durationSeconds <= 0) {
            return 0;
        }

        return min(100, (int) floor($positionSeconds * 100 / $durationSeconds));
    }
}
