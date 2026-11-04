<?php

namespace Database\Seeders;

use App\Models\FeedPartnerAd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Cartes sponsorisées des 3 applications partenaires du fil candidat.
 * Idempotent (updateOrCreate par partenaire). Les logos versionnés dans
 * database/seeders/data/partners/ sont copiés sur le disque `public`.
 * Les liens des stores restent vides tant qu'ils ne sont pas fournis.
 */
class FeedPartnerAdSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'partner' => 'estuaire_achats',
                'name' => 'Estuaire Achats',
                'headline' => 'Vos courses et achats du quotidien, en quelques clics',
                'body' => 'Mode, électronique, maison, alimentation : parcourez des milliers de produits de vendeurs locaux, comparez les prix et faites-vous livrer chez vous.',
                'logo' => 'estuaire_achat.png',
                'cta_label' => 'Télécharger l\'app',
                'accent_color' => '#3684C4',
                'play_store_url' => 'https://play.google.com/store/apps/details?id=com.estuaireachats.estuaire_achats',
                'app_store_url' => 'https://apps.apple.com/us/app/estuaire-achats/id6783661934',
                'display_order' => 1,
            ],
            [
                'partner' => 'estuaire_eat',
                'name' => 'Estuaire Eat',
                'headline' => 'Une petite faim ? Vos restaurants préférés livrés chez vous',
                'body' => 'Plats locaux, fast-food, pâtisseries : commandez auprès des restaurants de votre ville, suivez votre commande en direct et régalez-vous où que vous soyez.',
                'logo' => 'estuaire_eat.png',
                'cta_label' => 'Commander maintenant',
                'accent_color' => '#E31E24',
                'play_store_url' => 'https://play.google.com/store/apps/details?id=com.chrisdev237.myinsamresto1',
                'app_store_url' => 'https://apps.apple.com/us/app/estuaire-eat/id6769510526',
                'display_order' => 2,
            ],
            [
                'partner' => 'gfs',
                'name' => 'Global Financial Solution',
                'headline' => 'Gérez votre argent simplement, depuis votre téléphone',
                'body' => 'Paiements, transferts, épargne et solutions de financement : GFS vous accompagne dans la gestion de vos finances au quotidien. Conditions disponibles dans l\'application.',
                'logo' => 'gfs.png',
                'cta_label' => 'Découvrir GFS',
                'accent_color' => '#BC1823',
                'play_store_url' => 'https://play.google.com/store/apps/details?id=com.microfinance.cameroun.microfinance_mobile',
                'app_store_url' => 'https://apps.apple.com/us/app/gfsolutions/id6775826390',
                'display_order' => 3,
            ],
        ];

        foreach ($ads as $ad) {
            $logo = $ad['logo'];
            unset($ad['logo']);

            $ad['logo_path'] = $this->publishLogo($logo);
            $ad['is_active'] = true;

            FeedPartnerAd::updateOrCreate(['partner' => $ad['partner']], $ad);
        }

        $this->command?->info('FeedPartnerAdSeeder : ' . count($ads) . ' pubs partenaires à jour.');
    }

    private function publishLogo(string $file): ?string
    {
        $source = database_path('seeders/data/partners/' . $file);
        if (! is_file($source)) {
            $this->command?->warn("Logo introuvable : {$source}");
            return null;
        }

        $target = 'feed-ads/' . $file;
        Storage::disk('public')->put($target, file_get_contents($source));

        return $target;
    }
}
