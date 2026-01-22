<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle pour les taux de change entre devises
 *
 * @property int $id
 * @property string $from_currency
 * @property string $to_currency
 * @property float $rate
 * @property bool $is_active
 * @property \Carbon\Carbon $last_updated
 */
class CurrencyRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_currency',
        'to_currency',
        'rate',
        'is_active',
        'last_updated',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:8',
            'is_active' => 'boolean',
            'last_updated' => 'datetime',
        ];
    }

    /**
     * Scope: Taux actifs uniquement
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Trouver un taux de conversion spécifique
     */
    public function scopeForConversion($query, string $from, string $to)
    {
        return $query->where('from_currency', $from)
                     ->where('to_currency', $to)
                     ->where('is_active', true);
    }

    /**
     * Convertit un montant d'une devise à une autre
     */
    public function convert(float $amount): float
    {
        return $amount * $this->rate;
    }

    /**
     * Retourne les devises disponibles
     */
    public static function getAvailableCurrencies(): array
    {
        return ['XAF', 'USD', 'EUR'];
    }

    /**
     * Retourne le symbole de la devise
     */
    public static function getCurrencySymbol(string $currency): string
    {
        return match($currency) {
            'XAF' => 'FCFA',
            'USD' => '$',
            'EUR' => '€',
            default => $currency,
        };
    }

    /**
     * Retourne le nom complet de la devise
     */
    public static function getCurrencyName(string $currency): string
    {
        return match($currency) {
            'XAF' => 'Franc CFA',
            'USD' => 'Dollar américain',
            'EUR' => 'Euro',
            default => $currency,
        };
    }
}
