<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Tentative d'évaluation (QCM) passée sur INSAM-IA.
 *
 * Le résultat est conservé côté Estuaire : il conditionne l'attestation et
 * doit rester consultable même si le service tiers est indisponible.
 */
class InsamIaAttempt extends Model
{
    protected $table = 'insam_ia_attempts';

    protected $fillable = [
        'user_id',
        'session_id',
        'remote_attempt_id',
        'session_title',
        'specialite',
        'matiere',
        'duration_minutes',
        'status',
        'score',
        'total',
        'percentage',
        'questions',
        'corrections',
        'started_at',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'corrections' => 'array',
            'score' => 'integer',
            'total' => 'integer',
            'percentage' => 'integer',
            'duration_minutes' => 'integer',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attestation(): HasOne
    {
        return $this->hasOne(InsamIaAttestation::class, 'attempt_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Les questions telles qu'envoyées à l'étudiant : sans les réponses
     * correctes, qui ne sont révélées qu'à la soumission.
     */
    public function questionsForCandidate(): array
    {
        return collect($this->questions ?? [])
            ->map(fn (array $question) => [
                'index' => $question['index'] ?? null,
                'question' => $question['question'] ?? null,
                'options' => $question['options'] ?? [],
            ])
            ->all();
    }
}
