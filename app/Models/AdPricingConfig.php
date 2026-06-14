<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPricingConfig extends Model
{
    protected $fillable = [
        'audience_segment',
        'price_per_user',
        'min_budget',
        'max_budget',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_per_user' => 'decimal:2',
            'min_budget' => 'decimal:2',
            'max_budget' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Récupère la config tarifaire d'un segment, avec repli sur 'all'.
     */
    public static function forSegment(string $segment): ?self
    {
        return static::where('is_active', true)
            ->where('audience_segment', $segment)
            ->first()
            ?? static::where('is_active', true)
                ->where('audience_segment', 'all')
                ->first();
    }

    /**
     * Nombre d'utilisateurs touchables pour un budget donné.
     */
    public function reachForBudget(float $budget): int
    {
        $price = (float) $this->price_per_user;
        if ($price <= 0) {
            return 0;
        }
        return (int) floor($budget / $price);
    }
}
