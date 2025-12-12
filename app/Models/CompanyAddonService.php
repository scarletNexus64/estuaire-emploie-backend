<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAddonService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'addon_service_config_id',
        'payment_id',
        'related_job_id',
        'related_user_id',
        'purchased_at',
        'activated_at',
        'expires_at',
        'is_active',
        'views_count',
        'clicks_count',
        'uses_remaining',
    ];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
            'views_count' => 'integer',
            'clicks_count' => 'integer',
            'uses_remaining' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function config(): BelongsTo
    {
        return $this->belongsTo(AddonServiceConfig::class, 'addon_service_config_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function relatedJob(): BelongsTo
    {
        return $this->belongsTo(Job::class, 'related_job_id');
    }

    public function relatedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'related_user_id');
    }

    public function isValid(): bool
    {
        return $this->is_active &&
               ($this->expires_at === null || $this->expires_at >= now());
    }
}
