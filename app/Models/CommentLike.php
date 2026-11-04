<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * « J'aime » sur un commentaire (compteur dénormalisé post_comments.likes_count).
 */
class CommentLike extends Model
{
    protected $fillable = ['comment_id', 'user_id'];

    protected function casts(): array
    {
        return [
            'comment_id' => 'integer',
            'user_id' => 'integer',
        ];
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'comment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
