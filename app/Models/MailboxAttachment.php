<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailboxAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'filename',
        'mime_type',
        'size',
        'path',
        'content_id',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(MailboxMessage::class, 'message_id');
    }
}
