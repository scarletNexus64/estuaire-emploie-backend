<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsamtechsFormationPurchase extends Model
{
    protected $table = 'insamtechs_formation_purchases';

    protected $fillable = [
        'user_id',
        'insamtechs_formation_id',
        'formation_title',
        'amount_paid',
        'currency',
        'payment_method',
        'payment_provider',
        'status',
        'purchased_at',
    ];

    protected $casts = [
        'insamtechs_formation_id' => 'integer',
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
