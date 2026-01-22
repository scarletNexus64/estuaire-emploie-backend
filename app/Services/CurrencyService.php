<?php

namespace App\Services;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Cache;

/**
 * Service de gestion des devises et conversions
 */
class CurrencyService
{
    /**
     * Convertit un montant d'une devise à une autre
     *
     * @param float $amount Montant à convertir
     * @param string $from Devise source (ex: XAF)
     * @param string $to Devise cible (ex: USD)
     * @return float Montant converti
     */
    public function convert(float $amount, string $from, string $to): float
    {
        // Si même devise, pas de conversion
        if ($from === $to) {
            return $amount;
        }

        // Récupérer le taux de change (avec cache de 1h)
        $cacheKey = "currency_rate_{$from}_to_{$to}";

        $rate = Cache::remember($cacheKey, 3600, function () use ($from, $to) {
            return CurrencyRate::forConversion($from, $to)->first();
        });

        if (!$rate) {
            throw new \Exception("Taux de change non trouvé pour {$from} vers {$to}");
        }

        return round($amount * $rate->rate, 2);
    }

    /**
     * Formate un montant dans une devise spécifique
     *
     * @param float $amount Montant à formater
     * @param string $currency Code de la devise (XAF, USD, EUR)
     * @return string Montant formaté (ex: "3,000 FCFA", "$5.00", "4.50 €")
     */
    public function format(float $amount, string $currency): string
    {
        $symbol = CurrencyRate::getCurrencySymbol($currency);

        return match($currency) {
            'XAF' => number_format($amount, 0, ',', ' ') . ' ' . $symbol,
            'USD' => $symbol . number_format($amount, 2, '.', ','),
            'EUR' => number_format($amount, 2, ',', ' ') . ' ' . $symbol,
            default => number_format($amount, 2) . ' ' . $currency,
        };
    }

    /**
     * Retourne toutes les devises disponibles
     *
     * @return array
     */
    public function getAvailableCurrencies(): array
    {
        return [
            [
                'code' => 'XAF',
                'name' => CurrencyRate::getCurrencyName('XAF'),
                'symbol' => CurrencyRate::getCurrencySymbol('XAF'),
            ],
            [
                'code' => 'USD',
                'name' => CurrencyRate::getCurrencyName('USD'),
                'symbol' => CurrencyRate::getCurrencySymbol('USD'),
            ],
            [
                'code' => 'EUR',
                'name' => CurrencyRate::getCurrencyName('EUR'),
                'symbol' => CurrencyRate::getCurrencySymbol('EUR'),
            ],
        ];
    }

    /**
     * Retourne tous les taux de change actifs
     *
     * @return array
     */
    public function getAllRates(): array
    {
        return Cache::remember('all_currency_rates', 3600, function () {
            $rates = CurrencyRate::active()->get();

            $result = [];
            foreach ($rates as $rate) {
                $key = "{$rate->from_currency}_TO_{$rate->to_currency}";
                $result[$key] = [
                    'from' => $rate->from_currency,
                    'to' => $rate->to_currency,
                    'rate' => (float) $rate->rate,
                    'last_updated' => $rate->last_updated?->toISOString(),
                ];
            }

            return $result;
        });
    }

    /**
     * Efface le cache des taux de change
     */
    public function clearRatesCache(): void
    {
        Cache::forget('all_currency_rates');

        $currencies = CurrencyRate::getAvailableCurrencies();
        foreach ($currencies as $from) {
            foreach ($currencies as $to) {
                if ($from !== $to) {
                    Cache::forget("currency_rate_{$from}_to_{$to}");
                }
            }
        }
    }

    /**
     * Met à jour un taux de change
     *
     * @param string $from Devise source
     * @param string $to Devise cible
     * @param float $rate Nouveau taux
     * @return CurrencyRate
     */
    public function updateRate(string $from, string $to, float $rate): CurrencyRate
    {
        $currencyRate = CurrencyRate::forConversion($from, $to)->firstOrFail();
        $currencyRate->update([
            'rate' => $rate,
            'last_updated' => now(),
        ]);

        // Effacer le cache
        $this->clearRatesCache();

        return $currencyRate;
    }
}
