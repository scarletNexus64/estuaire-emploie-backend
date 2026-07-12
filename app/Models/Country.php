<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasTranslations;

    protected array $translatable = ['name'];

    protected $fillable = [
        'code',
        'iso3',
        'dial_code',
        'flag',
        'currency',
        'supports_kpay',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'supports_kpay' => 'boolean',
    ];

    /**
     * Scope to get only active countries.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
