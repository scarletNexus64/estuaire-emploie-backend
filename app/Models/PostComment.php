<?php

namespace App\Models;

use App\Services\Social\SocialContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Commentaire sur une publication. Un seul niveau de réponses : `parent_id`
 * pointe toujours vers un commentaire racine.
 */
class PostComment extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'target_type', 'target_id', 'parent_id', 'body', 'edited_at'];

    protected static function booted(): void
    {
        // Chargement groupé de `is_liked` (commentaire) et `is_following`
        // (auteur) pour toute la page : voir SocialContext.
        static::retrieved(function (self $comment) {
            $context = app(SocialContext::class);
            $context->track('comment', $comment->getKey());
            $context->track('user', $comment->user_id);
        });
    }

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'target_id' => 'integer',
            'parent_id' => 'integer',
            'replies_count' => 'integer',
            'likes_count' => 'integer',
            'edited_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }
}
