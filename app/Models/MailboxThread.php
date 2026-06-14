<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailboxThread extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subject',
        'contact_email',
        'contact_name',
        'status',
        'assigned_admin_id',
        'unread_count',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'unread_count' => 'integer',
        ];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MailboxMessage::class, 'thread_id');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(MailboxMessage::class, 'thread_id')->latest();
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }
}
