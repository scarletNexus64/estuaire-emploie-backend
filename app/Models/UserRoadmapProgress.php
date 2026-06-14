<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Progression d'un utilisateur sur une roadmap (niveau courant, XP, scores).
 */
class UserRoadmapProgress extends Model
{
    use HasFactory;

    protected $table = 'user_roadmap_progress';

    protected $fillable = [
        'user_id',
        'roadmap_id',
        'current_level',
        'completed_levels',
        'quiz_scores',
        'total_xp',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'current_level' => 'integer',
        'completed_levels' => 'array',
        'quiz_scores' => 'array',
        'total_xp' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }
}
