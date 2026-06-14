<?php

namespace App\Services;

use App\Jobs\SendCampaignPublishedNotification;
use App\Models\AdPricingConfig;
use App\Models\Advertisement;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Sponsoring self-service (Marketing Digital) façon TikTok Ads :
 * estimation budget => audience, création de campagne avec débit wallet,
 * et auto-publication de l'annonce ciblée.
 */
class MarketingCampaignService
{
    public function __construct(protected WalletService $walletService)
    {
    }

    /**
     * Estime le nombre d'utilisateurs touchables pour un budget et un segment.
     *
     * @return array{target_reach:int, price_per_user:float, min_budget:float, max_budget:float}
     */
    public function estimate(string $audience, float $budget): array
    {
        $config = AdPricingConfig::forSegment($audience);

        if (!$config) {
            throw new \RuntimeException("Aucune grille tarifaire active pour le ciblage demandé.");
        }

        return [
            'target_reach' => $config->reachForBudget($budget),
            'price_per_user' => (float) $config->price_per_user,
            'min_budget' => (float) $config->min_budget,
            'max_budget' => (float) $config->max_budget,
        ];
    }

    /**
     * Crée une campagne sponsorisée : débite le wallet puis publie l'annonce (auto-publication).
     *
     * @param array $data title, description, content_type, target_audience, budget,
     *                    image (chemin déjà stocké, optionnel), duration_days, provider
     */
    public function createCampaign(User $user, Company $company, array $data): Advertisement
    {
        $audience = $data['target_audience'];
        $budget = (float) $data['budget'];
        $provider = $data['provider'] ?? 'freemopay';
        // Ciblage géographique : tableau de codes pays ISO. Vide => null (tous pays).
        $targetCountries = !empty($data['target_countries']) ? array_values($data['target_countries']) : null;

        $config = AdPricingConfig::forSegment($audience);
        if (!$config) {
            throw new \RuntimeException("Aucune grille tarifaire active pour le ciblage demandé.");
        }

        if ($budget < (float) $config->min_budget || $budget > (float) $config->max_budget) {
            throw new \RuntimeException(
                "Le budget doit être compris entre {$config->min_budget} et {$config->max_budget} FCFA."
            );
        }

        $reach = $config->reachForBudget($budget);
        if ($reach <= 0) {
            throw new \RuntimeException("Budget insuffisant pour toucher au moins un utilisateur.");
        }

        $durationDays = (int) ($data['duration_days'] ?? 30);

        $advertisement = DB::transaction(function () use ($user, $company, $data, $audience, $targetCountries, $budget, $reach, $durationDays, $provider) {
            // Débit du wallet (lève une exception si solde insuffisant)
            $transaction = $this->walletService->debit(
                $user,
                $budget,
                "Campagne Marketing Digital: {$data['title']}",
                'marketing_campaign',
                null,
                [
                    'company_id' => $company->id,
                    'target_audience' => $audience,
                    'target_reach' => $reach,
                ],
                $provider
            );

            // Auto-publication immédiate
            $advertisement = Advertisement::create([
                'company_id' => $company->id,
                'created_by_user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'image' => $data['image'] ?? null,
                'background_color' => $data['background_color'] ?? '#0277BD',
                'overlay_opacity' => $data['overlay_opacity'] ?? 60,
                'content_type' => $data['content_type'],
                'target_audience' => $audience,
                'target_countries' => $targetCountries,
                'budget' => $budget,
                'target_reach' => $reach,
                'payment_id' => $transaction->payment_id,
                'source' => 'self_service',
                'ad_type' => 'homepage_banner',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays($durationDays)->toDateString(),
                'is_active' => true,
                'status' => 'active',
                'display_order' => 0,
            ]);

            Log::info('[MarketingCampaignService] Campaign created & published', [
                'advertisement_id' => $advertisement->id,
                'company_id' => $company->id,
                'budget' => $budget,
                'target_reach' => $reach,
                'audience' => $audience,
                'transaction_id' => $transaction->id,
            ]);

            return $advertisement;
        });

        // Notification push (topic + annonceur) hors transaction, une fois la
        // campagne réellement persistée et le wallet débité.
        SendCampaignPublishedNotification::dispatch($advertisement->id);

        return $advertisement;
    }

    /**
     * KPI d'une campagne.
     */
    public function stats(Advertisement $ad): array
    {
        $impressions = (int) $ad->impressions_count;
        $clicks = (int) $ad->clicks_count;

        return [
            'id' => $ad->id,
            'title' => $ad->title,
            'status' => $ad->status,
            'target_audience' => $ad->target_audience,
            'target_countries' => $ad->target_countries ?? [],
            'budget' => (float) $ad->budget,
            'target_reach' => (int) $ad->target_reach,
            'impressions_count' => $impressions,
            'clicks_count' => $clicks,
            'ctr' => $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0,
            'reach_progress' => $ad->target_reach > 0
                ? min(100, round(($impressions / $ad->target_reach) * 100, 2))
                : 0,
            'start_date' => optional($ad->start_date)->format('Y-m-d'),
            'end_date' => optional($ad->end_date)->format('Y-m-d'),
            'created_at' => optional($ad->created_at)->toISOString(),
        ];
    }
}
