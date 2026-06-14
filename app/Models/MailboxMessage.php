<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MailboxMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_id',
        'direction',
        'from_email',
        'from_name',
        'to_email',
        'recipients_to',
        'cc',
        'subject',
        'body_html',
        'body_text',
        'message_id',
        'in_reply_to',
        'references',
        'imap_folder',
        'imap_uid',
        'sent_by_admin_id',
        'is_read',
        'has_attachments',
        'send_status',
        'error',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'has_attachments' => 'boolean',
            'sent_at' => 'datetime',
        ];
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(MailboxThread::class, 'thread_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MailboxAttachment::class, 'message_id');
    }

    public function sentByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_admin_id');
    }

    public function isInbound(): bool
    {
        return $this->direction === 'inbound';
    }
}
