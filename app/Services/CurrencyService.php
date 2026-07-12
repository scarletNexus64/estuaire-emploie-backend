<?php

namespace App\Services;

use App\Models\Country;
use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Cache;

/**
 * Service de gestion des devises et conversions.
 *
 * Devise de base du ledger = XAF. Les conversions croisées (ex: NGN -> EUR)
 * transitent par XAF quand aucune paire directe n'existe. Le résultat est
 * arrondi selon le nombre de décimales de la devise cible.
 */
class CurrencyService
{
    /** Devise de base du ledger. */
    public const BASE_CURRENCY = 'XAF';

    /** Devises à 0 décimale (pas de sous-unité usuelle). */
    private const ZERO_DECIMAL = ['XAF', 'XOF', 'XPF', 'JPY', 'KRW', 'VND', 'CLP', 'ISK', 'GNF', 'RWF', 'UGX', 'BIF', 'DJF', 'KMF', 'PYG'];

    /** Devises à 3 décimales. */
    private const THREE_DECIMAL = ['BHD', 'KWD', 'OMR', 'TND', 'IQD', 'JOD', 'LYD'];

    /**
     * Convertit un montant d'une devise à une autre.
     *
     * @throws \Exception si aucune route de conversion (directe ou via XAF).
     */
    public function convert(float $amount, string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return $this->roundFor($amount, $to);
        }

        // 1) Paire directe.
        $direct = $this->rateFor($from, $to);
        if ($direct !== null) {
            return $this->roundFor($amount * $direct, $to);
        }

        // 2) Routage via la devise de base (XAF) : from -> XAF -> to.
        if ($from !== self::BASE_CURRENCY && $to !== self::BASE_CURRENCY) {
            $toBase = $this->rateFor($from, self::BASE_CURRENCY);
            $fromBase = $this->rateFor(self::BASE_CURRENCY, $to);
            if ($toBase !== null && $fromBase !== null) {
                return $this->roundFor($amount * $toBase * $fromBase, $to);
            }
        }

        throw new \Exception("Taux de change non trouvé pour {$from} vers {$to}");
    }

    /**
     * Retourne le taux (float) d'une paire, ou null si absente/inactive.
     * Mis en cache 1h par paire.
     */
    private function rateFor(string $from, string $to): ?float
    {
        $cacheKey = "currency_rate_{$from}_to_{$to}";
        $rate = Cache::remember($cacheKey, 3600, function () use ($from, $to) {
            return CurrencyRate::forConversion($from, $to)->first();
        });

        return $rate ? (float) $rate->rate : null;
    }

    /**
     * Arrondit un montant selon les décimales usuelles de la devise.
     */
    public function roundFor(float $amount, string $currency): float
    {
        return round($amount, $this->decimalsFor(strtoupper($currency)));
    }

    /** Nombre de décimales pour une devise (défaut 2). */
    public function decimalsFor(string $currency): int
    {
        $currency = strtoupper($currency);
        if (in_array($currency, self::ZERO_DECIMAL, true)) {
            return 0;
        }
        if (in_array($currency, self::THREE_DECIMAL, true)) {
            return 3;
        }
        return 2;
    }

    /**
     * Résout la devise d'affichage d'un utilisateur (repli sur la devise de
     * base). Accepte un User, un code string, ou null (invité → base).
     */
    public function resolveCurrency($userOrCode = null): string
    {
        if (is_string($userOrCode) && $userOrCode !== '') {
            return strtoupper($userOrCode);
        }
        if ($userOrCode instanceof \App\Models\User) {
            $cur = $userOrCode->preferred_currency;
            return !empty($cur) ? strtoupper($cur) : self::BASE_CURRENCY;
        }
        return self::BASE_CURRENCY;
    }

    /**
     * Décore un montant (exprimé en devise de base XAF) avec les champs
     * d'affichage dans la devise cible. Le montant de base reste la source de
     * vérité ; `display_*` n'est qu'informatif (jamais utilisé pour débiter).
     *
     * @return array{
     *   display_currency:string, display_price:float,
     *   display_price_formatted:string, base_currency:string
     * }
     */
    public function displayFor(float $baseAmount, $userOrCode = null): array
    {
        $target = $this->resolveCurrency($userOrCode);

        try {
            $converted = $this->convert($baseAmount, self::BASE_CURRENCY, $target);
        } catch (\Throwable $e) {
            // Pas de taux : on retombe sur la devise de base pour ne jamais
            // casser l'affichage d'un prix.
            $target = self::BASE_CURRENCY;
            $converted = $this->roundFor($baseAmount, $target);
        }

        return [
            'base_currency' => self::BASE_CURRENCY,
            'display_currency' => $target,
            'display_price' => $converted,
            'display_price_formatted' => $this->format($converted, $target),
        ];
    }

    /**
     * Indique si les taux sont "périmés" (dernière MAJ trop ancienne).
     * Sert de garde avant de convertir un paiement.
     */
    public function ratesAreStale(): bool
    {
        $maxAgeHours = (int) config('services.exchangerate.max_age_hours', 48);
        $latest = CurrencyRate::active()->max('last_updated');
        if (!$latest) {
            return true;
        }
        return \Illuminate\Support\Carbon::parse($latest)->diffInHours(now()) > $maxAgeHours;
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
        $currency = strtoupper($currency);
        $decimals = $this->decimalsFor($currency);
        $symbol = $this->symbolFor($currency);

        return match ($currency) {
            // Le symbole précède le montant pour les devises "dollar".
            'USD', 'CAD', 'AUD', 'NZD', 'HKD', 'SGD', 'MXN', 'BRL' =>
                $symbol . number_format($amount, $decimals, '.', ','),
            // Espace fine + symbole suffixe pour le reste.
            default => number_format($amount, $decimals, ',', ' ') . ' ' . $symbol,
        };
    }

    /**
     * Symbole d'affichage d'une devise. Priorité à la table `currencies`
     * (référentiel), repli sur les symboles historiques, puis le code.
     */
    public function symbolFor(string $currency): string
    {
        $currency = strtoupper($currency);

        $symbol = Cache::remember("currency_symbol_{$currency}", 86400, function () use ($currency) {
            return \App\Models\Currency::where('code', $currency)->value('symbol');
        });

        if (!empty($symbol)) {
            return $symbol;
        }

        // Repli historique (XAF/USD/EUR) puis code brut.
        return CurrencyRate::getCurrencySymbol($currency);
    }

    /**
     * Retourne toutes les devises disponibles
     *
     * @return array
     */
    public function getAvailableCurrencies(): array
    {
        return Cache::remember('available_currencies', 3600, function () {
            // Devises réellement utilisées par les pays (celles qu'un user peut choisir).
            $codes = Country::query()
                ->whereNotNull('currency')
                ->distinct()
                ->pluck('currency')
                ->map(fn ($c) => strtoupper($c))
                ->all();

            $codes = array_values(array_unique(array_merge(['XAF', 'USD', 'EUR'], $codes)));
            sort($codes);

            return array_map(fn ($code) => [
                'code' => $code,
                'name' => $this->nameFor($code),
                'symbol' => $this->symbolFor($code),
                'decimals' => $this->decimalsFor($code),
            ], $codes);
        });
    }

    /**
     * Nom d'affichage d'une devise (table `currencies` puis repli historique).
     */
    public function nameFor(string $currency): string
    {
        $currency = strtoupper($currency);
        $name = Cache::remember("currency_name_{$currency}", 86400, function () use ($currency) {
            return \App\Models\Currency::where('code', $currency)->value('name');
        });

        return !empty($name) ? $name : CurrencyRate::getCurrencyName($currency);
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
        Cache::forget('available_currencies');

        // Toutes les paires impliquant XAF (la seule route active en pratique),
        // dans les deux sens, pour chaque devise pays connue.
        $codes = Country::query()
            ->whereNotNull('currency')
            ->distinct()
            ->pluck('currency')
            ->map(fn ($c) => strtoupper($c))
            ->all();

        $codes = array_values(array_unique(array_merge(['XAF', 'USD', 'EUR'], $codes)));

        foreach ($codes as $code) {
            if ($code === self::BASE_CURRENCY) {
                continue;
            }
            Cache::forget("currency_rate_" . self::BASE_CURRENCY . "_to_{$code}");
            Cache::forget("currency_rate_{$code}_to_" . self::BASE_CURRENCY);
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
