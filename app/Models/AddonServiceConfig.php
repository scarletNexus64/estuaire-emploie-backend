<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddonServiceConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addon_services_config';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'display_order',
        'price',
        'duration_days',
        'service_type',
        'boost_multiplier',
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
            'boost_multiplier' => 'integer',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ];
    }

    public function companyServices(): HasMany
    {
        return $this->hasMany(CompanyAddonService::class);
    }
}
