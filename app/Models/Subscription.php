<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'subscription_plan_id',
        'start_date',
        'end_date',
        'next_billing_date',
        'status',
        'auto_renew',
        'jobs_posted_this_period',
        'contacts_used_this_period',
        'last_reset_at',
        'cancellation_reason',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'next_billing_date' => 'date',
            'auto_renew' => 'boolean',
            'jobs_posted_this_period' => 'integer',
            'contacts_used_this_period' => 'integer',
            'last_reset_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now();
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->end_date < now();
    }
}
