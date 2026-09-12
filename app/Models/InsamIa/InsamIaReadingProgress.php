<?php

namespace App\Models\InsamIa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Progression de lecture d'une ressource INSAM-IA par un étudiant.
 *
 * La ressource vit chez INSAM-IA : elle est référencée par son type et son
 * identifiant distant, sans clé étrangère possible.
 */
class InsamIaReadingProgress extends Model
{
    protected $table = 'insam_ia_reading_progress';

    protected $fillable = [
        'user_id',
        'resource_type',
        'resource_id',
        'resource_title',
        'category_id',
        'progress_percent',
        'last_read_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_percent' => 'integer',
            'last_read_at' => 'datetime',
            'completed_at' => 'datetime',
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
