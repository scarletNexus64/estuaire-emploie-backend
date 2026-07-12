<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Client de exchangerate-api.com.
 *
 * Récupère, en un seul appel, tous les taux relatifs à une devise de base
 * (par défaut XAF, la devise du ledger). L'API renvoie `conversion_rates`
 * sous la forme `{ "USD": 0.0016, "EUR": 0.0015, ... }` où chaque valeur est
 * « combien de <devise> vaut 1 unité de <base> ».
 *
 * Aucun état ; c'est {@see SyncCurrencyRates} qui persiste le résultat dans la
 * table `currency_rates`.
 */
class ExchangeRateProvider
{
    private const ENDPOINT = 'https://v6.exchangerate-api.com/v6';

    /**
     * Récupère les taux pour [$base]. Retourne un tableau
     * ['ratesByCode' => ['USD' => 0.0016, ...], 'lastUpdate' => Carbon|null]
     * ou null en cas d'échec (réseau, quota, réponse invalide).
     *
     * @return array{ratesByCode: array<string,float>, lastUpdate: ?\Illuminate\Support\Carbon}|null
     */
    public function fetchRates(?string $base = null): ?array
    {
        $apiKey = config('services.exchangerate.api_key');
        $base = strtoupper($base ?? config('services.exchangerate.base_currency', 'XAF'));

        if (empty($apiKey)) {
            Log::warning('[ExchangeRate] EXCHANGERATE_API_KEY manquante — sync ignorée');
            return null;
        }

        try {
            $response = Http::timeout(20)
                ->retry(2, 500)
                ->get(self::ENDPOINT . "/{$apiKey}/latest/{$base}");

            if (!$response->successful()) {
                Log::error('[ExchangeRate] Réponse HTTP non OK', [
                    'status' => $response->status(),
                    'base' => $base,
                ]);
                return null;
            }

            $data = $response->json();

            if (($data['result'] ?? null) !== 'success' || empty($data['conversion_rates'])) {
                Log::error('[ExchangeRate] Réponse API invalide', [
                    'result' => $data['result'] ?? 'N/A',
                    'error_type' => $data['error-type'] ?? null,
                ]);
                return null;
            }

            $rates = [];
            foreach ($data['conversion_rates'] as $code => $rate) {
                if (is_numeric($rate) && $rate > 0) {
                    $rates[strtoupper($code)] = (float) $rate;
                }
            }

            // Horodatage de rafraîchissement fourni par l'API (epoch UTC).
            $lastUpdate = isset($data['time_last_update_unix'])
                ? \Illuminate\Support\Carbon::createFromTimestampUTC($data['time_last_update_unix'])
                : now();

            return [
                'ratesByCode' => $rates,
                'lastUpdate' => $lastUpdate,
            ];
        } catch (\Throwable $e) {
            Log::error('[ExchangeRate] Exception lors du fetch', [
                'message' => $e->getMessage(),
                'base' => $base,
            ]);
            return null;
        }
    }
}
