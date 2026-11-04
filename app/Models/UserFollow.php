<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Abonnement d'un utilisateur (follower) à un autre (followed) — onglet
 * « Réseau ». Compteurs dénormalisés : users.followers_count /
 * users.following_count.
 */
class UserFollow extends Model
{
    protected $fillable = ['follower_id', 'followed_id'];

    protected function casts(): array
    {
        return [
            'follower_id' => 'integer',
            'followed_id' => 'integer',
        ];
    }

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function followed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
