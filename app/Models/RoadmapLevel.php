<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Niveau (palier) d'une roadmap : contenu pédagogique + QCM optionnel.
 */
class RoadmapLevel extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'roadmap_id',
        'title',
        'subtitle',
        'content',
        'order',
        'has_quiz',
        'xp_reward',
    ];

    protected $casts = [
        'order' => 'integer',
        'has_quiz' => 'boolean',
        'xp_reward' => 'integer',
    ];

    protected array $translatable = ['title', 'subtitle', 'content'];

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Questions du QCM de ce niveau, ordonnées.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(RoadmapQuestion::class)->orderBy('order');
    }
}
