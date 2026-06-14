<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

/**
 * Active l'avantage « compte GFSolutions offert » (clé features.gfs_free_account)
 * sur les packs de souscription concernés.
 *
 * Idempotent : fusionne la clé dans le tableau `features` existant sans écraser
 * les autres avantages, et peut être rejoué sans effet de bord.
 *
 * Note métier : le compte GFS n'est créé qu'une seule fois par utilisateur
 * (à sa première souscription d'un de ces packs). Les souscriptions/
 * renouvellements suivants réutilisent le compte existant — cf. GfsService.
 */
class GfsFreeAccountSeeder extends Seeder
{
    /**
     * Slugs des packs offrant le compte GFS.
     * Tous les packs (recruteurs R1-R3 et candidats C1-C3).
     */
    protected array $slugs = [
        'pack-r1-argent',
        'pack-r2-or',
        'pack-r3-diamant',
        'pack-c1-argent',
        'pack-c2-or',
        'pack-c3-diamant',
    ];

    public function run(): void
    {
        foreach ($this->slugs as $slug) {
            $plan = SubscriptionPlan::where('slug', $slug)->first();

            if (!$plan) {
                $this->command?->warn("  ⚠️  Plan introuvable : {$slug}");
                continue;
            }

            $features = is_array($plan->features) ? $plan->features : [];
            $features['gfs_free_account'] = true;

            $plan->features = $features;
            $plan->save();

            $this->command?->info("  ✅ GFS activé sur : {$slug}");
        }
    }
}
