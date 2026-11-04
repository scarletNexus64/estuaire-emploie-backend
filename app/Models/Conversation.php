<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    /** Conversation ouverte : les deux participants écrivent librement. */
    public const STATUS_ACCEPTED = 'accepted';

    /** Demande de message en attente de réponse du destinataire. */
    public const STATUS_PENDING = 'pending';

    /** Demande refusée : masquée chez le destinataire, « en attente » chez le demandeur. */
    public const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'user_one',
        'user_two',
        'application_id',
        'service_id',
        'status',
        'requested_by',
        'company_id',
        'accepted_at',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACCEPTED,
    ];

    protected function casts(): array
    {
        return [
            'user_one' => 'integer',
            'user_two' => 'integer',
            'requested_by' => 'integer',
            'company_id' => 'integer',
            'accepted_at' => 'datetime',
        ];
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function service()
    {
        return $this->belongsTo(QuickService::class, 'service_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function userOne()
    {
        return $this->belongsTo(User::class, 'user_one');
    }

    public function userTwo()
    {
        return $this->belongsTo(User::class, 'user_two');
    }

    public function otherUserId(int $userId): int
    {
        return (int) $this->user_one === $userId ? (int) $this->user_two : (int) $this->user_one;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isDeclined(): bool
    {
        return $this->status === self::STATUS_DECLINED;
    }

    /** Demande (en attente ou refusée) envoyée par $userId : il ne peut pas encore écrire. */
    public function isAwaitingReplyFor(int $userId): bool
    {
        return ($this->isPending() || $this->isDeclined()) && (int) $this->requested_by === $userId;
    }

    /** Demande reçue par $userId et pas encore acceptée. */
    public function isRequestFor(int $userId): bool
    {
        return $this->isPending() && $this->requested_by !== null && (int) $this->requested_by !== $userId;
    }

    /**
     * Statut vu par $userId : « request » (demande reçue), « pending »
     * (demande envoyée ; un refus reste affiché « en attente » pour ne pas
     * l'exposer), sinon « accepted ».
     */
    public function statusFor(int $userId): string
    {
        if ($this->isRequestFor($userId)) {
            return 'request';
        }
        if ($this->isAwaitingReplyFor($userId)) {
            return 'pending';
        }

        return self::STATUS_ACCEPTED;
    }

    /** Conversations « libres » (hors candidature) entre deux utilisateurs. */
    public function scopeBetween($query, int $a, int $b)
    {
        return $query->whereNull('application_id')
            ->where(function ($q) use ($a, $b) {
                $q->where(fn ($w) => $w->where('user_one', $a)->where('user_two', $b))
                    ->orWhere(fn ($w) => $w->where('user_one', $b)->where('user_two', $a));
            });
    }
}
