<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_id',
        'payable_type',
        'payable_id',
        'amount',
        'fees',
        'total',
        'payment_method',
        'payment_type',
        'currency',
        'provider',
        'transaction_reference',
        'external_id',
        'provider_reference',
        'phone_number',
        'payment_provider_response',
        'status',
        'paid_at',
        'refunded_at',
        'cancelled_at',
        'notes',
        'description',
        'failure_reason',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'fees' => 'decimal:2',
            'total' => 'decimal:2',
            'payment_provider_response' => 'array',
            'metadata' => 'array',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Relation vers la souscription utilisateur (relation ternaire)
     */
    public function userSubscriptionPlan(): HasOne
    {
        return $this->hasOne(UserSubscriptionPlan::class);
    }

    /**
     * Marque le paiement comme complété
     */
    public function markAsCompleted(): self
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        return $this;
    }

    /**
     * Marque le paiement comme échoué
     */
    public function markAsFailed(string $reason = null): self
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);

        return $this;
    }
}
