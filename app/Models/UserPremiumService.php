<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPremiumService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'premium_service_config_id',
        'payment_id',
        'purchased_at',
        'activated_at',
        'expires_at',
        'is_active',
        'auto_renew',
        'uses_remaining',
    ];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'auto_renew' => 'boolean',
            'uses_remaining' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function config(): BelongsTo
    {
        return $this->belongsTo(PremiumServiceConfig::class, 'premium_service_config_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function isValid(): bool
    {
        return $this->is_active &&
               ($this->expires_at === null || $this->expires_at >= now());
    }
}
