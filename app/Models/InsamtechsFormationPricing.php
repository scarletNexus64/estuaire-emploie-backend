<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsamtechsFormationPricing extends Model
{
    protected $table = 'insamtechs_formation_pricing';

    protected $fillable = [
        'insamtechs_formation_id',
        'formation_title',
        'price_xaf',
        'price_usd',
        'price_eur',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'insamtechs_formation_id' => 'integer',
        'price_xaf' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'price_eur' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getPrice(string $currency): float
    {
        return match (strtoupper($currency)) {
            'USD' => (float) $this->price_usd,
            'EUR' => (float) $this->price_eur,
            default => (float) $this->price_xaf,
        };
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
