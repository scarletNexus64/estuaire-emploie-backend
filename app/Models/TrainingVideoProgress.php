<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Progression de visionnage d'une vidéo de formation par un étudiant.
 *
 * La vidéo vit chez InsamTechs, qui ne conserve aucune progression : Estuaire
 * la tient donc ici, c'est elle qui ouvre droit à l'attestation de formation.
 * Aucune clé étrangère vers la formation ni la vidéo, leurs identifiants
 * appartiennent au référentiel distant.
 */
class TrainingVideoProgress extends Model
{
    protected $table = 'training_video_progress';

    protected $fillable = [
        'user_id',
        'formation_id',
        'video_id',
        'formation_title',
        'video_title',
        'position_seconds',
        'duration_seconds',
        'progress_percent',
        'completed_at',
        'last_watched_at',
    ];

    protected function casts(): array
    {
        return [
            'formation_id' => 'integer',
            'video_id' => 'integer',
            'position_seconds' => 'integer',
            'duration_seconds' => 'integer',
            'progress_percent' => 'integer',
            'completed_at' => 'datetime',
            'last_watched_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }
}
