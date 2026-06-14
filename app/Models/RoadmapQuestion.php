<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Question d'un QCM de niveau de roadmap.
 *
 * `options` = liste de choix ; `correct_answers` = index 0-based des bonnes
 * réponses (supporte le multi-réponses).
 */
class RoadmapQuestion extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'roadmap_level_id',
        'question',
        'options',
        'correct_answers',
        'explanation',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'order' => 'integer',
    ];

    protected array $translatable = ['question', 'explanation'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(RoadmapLevel::class, 'roadmap_level_id');
    }

    /**
     * Vérifie si un tableau d'index de réponses correspond exactement aux
     * bonnes réponses attendues.
     *
     * @param  array<int, int>  $given  Index choisis par l'utilisateur
     */
    public function isCorrect(array $given): bool
    {
        $expected = array_map('intval', $this->correct_answers ?? []);
        $given = array_map('intval', $given);
        sort($expected);
        sort($given);

        return $expected === $given;
    }
}
