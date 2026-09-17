<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trace d'une activité notée de l'espace étudiant.
 *
 * Le suivi vit chez Estuaire : l'API distante refuse tous les `type` essayés
 * et son énumération n'est pas documentée. Les valeurs sont donc les nôtres.
 */
class InsamIaCourseProgress extends Model
{
    /** Correction d'une copie (texte collé ou PDF). */
    public const TYPE_CORRECTION = 'correction';

    /** Épreuve d'entraînement produite par l'IA. */
    public const TYPE_GENERATED_EXAM = 'generated_exam';

    /** Réponses soumises sur une épreuve de la banque. */
    public const TYPE_EXAM_ANSWER = 'exam_answer';

    /** Évaluation (QCM) passée dans l'espace. */
    public const TYPE_EVALUATION = 'evaluation';

    public const TYPES = [
        self::TYPE_CORRECTION,
        self::TYPE_GENERATED_EXAM,
        self::TYPE_EXAM_ANSWER,
        self::TYPE_EVALUATION,
    ];

    protected $table = 'insam_ia_course_progress';

    protected $fillable = [
        'user_id',
        'type',
        'subject',
        'title',
        'score',
        'max_score',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'float',
            'max_score' => 'float',
            'meta' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
