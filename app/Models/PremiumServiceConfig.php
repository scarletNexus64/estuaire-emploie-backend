<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PremiumServiceConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'premium_services_configs';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'display_order',
        'price',
        'duration_days',
        'service_type',
        'features',
        'is_active',
        'is_popular',
        'color',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ];
    }

    public function userServices(): HasMany
    {
        return $this->hasMany(UserPremiumService::class, 'premium_services_config_id');
    }

    public function isPermanent(): bool
    {
        return $this->duration_days === null;
    }
}
