<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCommission extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'transaction_type',
        'transaction_reference',
        'transaction_amount',
        'commission_percentage',
        'commission_amount',
    ];

    protected $casts = [
        'transaction_amount' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    /**
     * Le parrain qui reçoit la commission
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Le filleul qui a fait la transaction
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }
}
