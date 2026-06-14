<?php

namespace Database\Seeders;

use Database\Seeders\Roadmaps\AdminSystemesReseauxRoadmapSeeder;
use Database\Seeders\Roadmaps\AgricultureRoadmapSeeder;
use Database\Seeders\Roadmaps\AideSoignantRoadmapSeeder;
use Database\Seeders\Roadmaps\AnatomieRoadmapSeeder;
use Database\Seeders\Roadmaps\BackendRoadmapSeeder;
use Database\Seeders\Roadmaps\BlockchainRoadmapSeeder;
use Database\Seeders\Roadmaps\CommercialB2BRoadmapSeeder;
use Database\Seeders\Roadmaps\CommunityManagerRoadmapSeeder;
use Database\Seeders\Roadmaps\ComptabiliteGeneraleRoadmapSeeder;
use Database\Seeders\Roadmaps\ComptabiliteOhadaRoadmapSeeder;
use Database\Seeders\Roadmaps\CuisineRoadmapSeeder;
use Database\Seeders\Roadmaps\CybersecuriteBlueTeamRoadmapSeeder;
use Database\Seeders\Roadmaps\DataAnalystRoadmapSeeder;
use Database\Seeders\Roadmaps\DesignGraphiqueRoadmapSeeder;
use Database\Seeders\Roadmaps\DeveloppeurFlutterRoadmapSeeder;
use Database\Seeders\Roadmaps\DevopsRoadmapSeeder;
use Database\Seeders\Roadmaps\DroitDesAffairesRoadmapSeeder;
use Database\Seeders\Roadmaps\DroitDuTravailRoadmapSeeder;
use Database\Seeders\Roadmaps\DroitOhadaRoadmapSeeder;
use Database\Seeders\Roadmaps\EcommerceRoadmapSeeder;
use Database\Seeders\Roadmaps\ElectriciteBatimentRoadmapSeeder;
use Database\Seeders\Roadmaps\EnergiesRenouvelablesRoadmapSeeder;
use Database\Seeders\Roadmaps\EnglishRoadmapSeeder;
use Database\Seeders\Roadmaps\EntrepreneuriatRoadmapSeeder;
use Database\Seeders\Roadmaps\FrontendRoadmapSeeder;
use Database\Seeders\Roadmaps\FullstackRoadmapSeeder;
use Database\Seeders\Roadmaps\GenieCivilRoadmapSeeder;
use Database\Seeders\Roadmaps\GestionDeProjetRoadmapSeeder;
use Database\Seeders\Roadmaps\GestionRhRoadmapSeeder;
use Database\Seeders\Roadmaps\GestionSiRoadmapSeeder;
use Database\Seeders\Roadmaps\HackingEthiqueRoadmapSeeder;
use Database\Seeders\Roadmaps\IaGenerativeRoadmapSeeder;
use Database\Seeders\Roadmaps\InformatiqueEmbarqueeRoadmapSeeder;
use Database\Seeders\Roadmaps\IngenieurQaRoadmapSeeder;
use Database\Seeders\Roadmaps\MachineLearningRoadmapSeeder;
use Database\Seeders\Roadmaps\ManagementEquipeRoadmapSeeder;
use Database\Seeders\Roadmaps\MarketingRoadmapSeeder;
use Database\Seeders\Roadmaps\MecaniqueAutomobileRoadmapSeeder;
use Database\Seeders\Roadmaps\MedecineGeneraleRoadmapSeeder;
use Database\Seeders\Roadmaps\MobileMoneyRoadmapSeeder;
use Database\Seeders\Roadmaps\MontageVideoRoadmapSeeder;
use Database\Seeders\Roadmaps\NotariatRoadmapSeeder;
use Database\Seeders\Roadmaps\NutritionRoadmapSeeder;
use Database\Seeders\Roadmaps\PharmacieOfficineRoadmapSeeder;
use Database\Seeders\Roadmaps\PubliciteAdsRoadmapSeeder;
use Database\Seeders\Roadmaps\SantePubliqueRoadmapSeeder;
use Database\Seeders\Roadmaps\SeoRoadmapSeeder;
use Database\Seeders\Roadmaps\SoinsInfirmiersRoadmapSeeder;
use Database\Seeders\Roadmaps\SqlRoadmapSeeder;
use Database\Seeders\Roadmaps\TechniqueVenteRoadmapSeeder;
use Database\Seeders\Roadmaps\TechnicienSupportRoadmapSeeder;
use Database\Seeders\Roadmaps\UxUiDesignRoadmapSeeder;
use Illuminate\Database\Seeder;

/**
 * Orchestrateur des roadmaps d'apprentissage gamifiées (50 parcours métiers).
 *
 * À lancer manuellement :  php artisan db:seed --class=RoadmapSeeder --force
 * Idempotent : chaque sous-seeder recrée sa roadmap par slug.
 *
 * Les administrateurs peuvent ensuite créer d'autres roadmaps depuis le
 * panel admin (section "Programmes de Formation" → "Roadmaps").
 */
class RoadmapSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Création des roadmaps d\'apprentissage gamifiées...');

        $this->call([
            // --- Développement & Tech ---
            FrontendRoadmapSeeder::class,
            BackendRoadmapSeeder::class,
            FullstackRoadmapSeeder::class,
            DevopsRoadmapSeeder::class,
            DeveloppeurFlutterRoadmapSeeder::class,
            AdminSystemesReseauxRoadmapSeeder::class,
            TechnicienSupportRoadmapSeeder::class,
            IngenieurQaRoadmapSeeder::class,
            InformatiqueEmbarqueeRoadmapSeeder::class,

            // --- Data & IA ---
            MachineLearningRoadmapSeeder::class,
            DataAnalystRoadmapSeeder::class,
            IaGenerativeRoadmapSeeder::class,

            // --- Bases de données ---
            SqlRoadmapSeeder::class,

            // --- Cybersécurité ---
            CybersecuriteBlueTeamRoadmapSeeder::class,
            HackingEthiqueRoadmapSeeder::class,

            // --- Web3 ---
            BlockchainRoadmapSeeder::class,

            // --- Santé & Médical ---
            SoinsInfirmiersRoadmapSeeder::class,
            MedecineGeneraleRoadmapSeeder::class,
            PharmacieOfficineRoadmapSeeder::class,
            AideSoignantRoadmapSeeder::class,
            SantePubliqueRoadmapSeeder::class,
            NutritionRoadmapSeeder::class,
            AnatomieRoadmapSeeder::class,

            // --- Droit & Administratif ---
            DroitDesAffairesRoadmapSeeder::class,
            DroitDuTravailRoadmapSeeder::class,
            NotariatRoadmapSeeder::class,
            DroitOhadaRoadmapSeeder::class,
            GestionRhRoadmapSeeder::class,

            // --- Marketing, Vente & Communication ---
            MarketingRoadmapSeeder::class,
            CommunityManagerRoadmapSeeder::class,
            SeoRoadmapSeeder::class,
            PubliciteAdsRoadmapSeeder::class,
            TechniqueVenteRoadmapSeeder::class,
            CommercialB2BRoadmapSeeder::class,

            // --- E-commerce ---
            EcommerceRoadmapSeeder::class,

            // --- Finance, Gestion & Business ---
            ComptabiliteGeneraleRoadmapSeeder::class,
            ComptabiliteOhadaRoadmapSeeder::class,
            GestionDeProjetRoadmapSeeder::class,
            EntrepreneuriatRoadmapSeeder::class,
            MobileMoneyRoadmapSeeder::class,
            ManagementEquipeRoadmapSeeder::class,
            GestionSiRoadmapSeeder::class,

            // --- Design & Création ---
            UxUiDesignRoadmapSeeder::class,
            DesignGraphiqueRoadmapSeeder::class,
            MontageVideoRoadmapSeeder::class,

            // --- Ingénierie, BTP & Métiers manuels ---
            GenieCivilRoadmapSeeder::class,
            ElectriciteBatimentRoadmapSeeder::class,
            MecaniqueAutomobileRoadmapSeeder::class,
            EnergiesRenouvelablesRoadmapSeeder::class,

            // --- Hôtellerie & Agriculture ---
            CuisineRoadmapSeeder::class,
            AgricultureRoadmapSeeder::class,

            // --- Langues ---
            EnglishRoadmapSeeder::class,
        ]);

        $this->command->info('Roadmaps créées avec succès !');
    }
}
