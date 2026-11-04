<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * « J'aime » sur une publication (offre ou service rapide). Distinct des
 * favoris. `target_type` = alias court (voir App\Services\Social\SocialTarget).
 */
class PostLike extends Model
{
    protected $fillable = ['user_id', 'target_type', 'target_id'];

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
