<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Republication (style LinkedIn) d'une publication, avec un court texte
 * optionnel. Une seule republication par utilisateur et par publication.
 */
class PostRepost extends Model
{
    protected $fillable = ['user_id', 'target_type', 'target_id', 'comment'];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'target_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
