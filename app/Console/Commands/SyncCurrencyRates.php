<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\CurrencyRate;
use App\Services\CurrencyService;
use App\Services\ExchangeRateProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Synchronise les taux de change depuis exchangerate-api.com.
 *
 * Un seul appel API (base XAF) fournit tous les taux. On persiste, pour chaque
 * devise supportée C : `XAF -> C` (taux direct) et `C -> XAF` (inverse). Les
 * conversions croisées (ex: USD -> EUR) transitent par XAF dans
 * {@see CurrencyService::convert}. Idempotent : updateOrCreate sur la paire.
 *
 * Devises supportées = devises des pays (countries.currency) ∪ {XAF, USD, EUR}.
 */
class SyncCurrencyRates extends Command
{
    protected $signature = 'currency:sync-rates {--base=XAF : Devise de base du ledger}';

    protected $description = 'Récupère les taux de change live (exchangerate-api) et met à jour currency_rates';

    public function handle(ExchangeRateProvider $provider, CurrencyService $currencyService): int
    {
        $base = strtoupper($this->option('base') ?: config('services.exchangerate.base_currency', 'XAF'));

        $this->info("Récupération des taux (base {$base})…");
        $result = $provider->fetchRates($base);

        if ($result === null) {
            $this->error('Échec de récupération des taux (voir logs). Taux existants conservés.');
            return self::FAILURE;
        }

        $ratesByCode = $result['ratesByCode'];
        $lastUpdate = $result['lastUpdate'];

        // Devises que l'on veut réellement maintenir (pays activables + trio historique).
        $supported = $this->supportedCurrencies($base);

        $upserted = 0;
        $missing = [];

        foreach ($supported as $code) {
            if ($code === $base) {
                continue;
            }

            $rate = $ratesByCode[$code] ?? null;
            if ($rate === null || $rate <= 0) {
                $missing[] = $code;
                continue;
            }

            // base -> code (ex: 1 XAF = 0.0016 USD)
            $this->upsertPair($base, $code, $rate, $lastUpdate);
            // code -> base (inverse, ex: 1 USD = 625 XAF)
            $this->upsertPair($code, $base, 1 / $rate, $lastUpdate);
            $upserted += 2;
        }

        // Vider le cache pour que les nouvelles valeurs soient prises en compte.
        $currencyService->clearRatesCache();

        $this->info("✅ {$upserted} taux mis à jour ({$lastUpdate}).");
        if (!empty($missing)) {
            $this->warn('Devises sans taux fourni par l\'API : ' . implode(', ', $missing));
            Log::warning('[currency:sync-rates] Devises sans taux', ['missing' => $missing]);
        }

        return self::SUCCESS;
    }

    /**
     * Liste des devises à maintenir : celles des pays (countries.currency)
     * plus XAF/USD/EUR (compat historique + PayPal).
     *
     * @return array<int,string>
     */
    private function supportedCurrencies(string $base): array
    {
        $fromCountries = Country::query()
            ->whereNotNull('currency')
            ->distinct()
            ->pluck('currency')
            ->map(fn ($c) => strtoupper($c))
            ->all();

        return array_values(array_unique(array_merge(
            [$base, 'XAF', 'USD', 'EUR'],
            $fromCountries,
        )));
    }

    private function upsertPair(string $from, string $to, float $rate, $lastUpdate): void
    {
        CurrencyRate::updateOrCreate(
            ['from_currency' => $from, 'to_currency' => $to],
            [
                'rate' => $rate,
                'is_active' => true,
                'last_updated' => $lastUpdate,
            ]
        );
    }
}
