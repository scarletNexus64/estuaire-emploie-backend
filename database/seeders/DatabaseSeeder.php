<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Configuration de base
            CategorySeeder::class,
            ContractTypeSeeder::class,
            CurrencySeeder::class, // Devises mondiales (XAF, USD, EUR...)
            ServiceCategorySeeder::class, // Catégories des services rapides (Plomberie, Électricité…)
            DomainsAndSectorsSeeder::class, // Domaines + secteurs depuis config/domains_sectors.php
            ProficiencyLevelsSeeder::class, // Niveaux skill / language / training

            // Monétisation - Plans et services
            SubscriptionPlanSeeder::class, // Forfaits recruteurs (ARGENT, OR, PLATINUM)
            JobSeekerSubscriptionPlanSeeder::class, // Forfaits chercheurs d'emploi (SILVER, GOLD, PLATINUM, PACK ÉTUDIANT)
            PremiumServiceConfigSeeder::class, // Services premium individuels pour candidats
            AddonServiceConfigSeeder::class, // Services à la carte pour recruteurs

            // Utilisateurs et données de test
            SuperAdminSeeder::class, // Admin principal (à exécuter en premier)
            UserSeeder::class, // Utilisateurs de test (recruteurs et candidats)
            CompanySeeder::class,
            RecruiterSeeder::class,
            JobSeeder::class,
            ApplicationSeeder::class,

            // Configuration Académique (Spécialités et Catégories)
            SpecialtySeeder::class, // Spécialités pour épreuves et packs d'épreuves
            TrainingCategorySeeder::class, // Catégories pour packs de formation

            // Contenu Étudiant - Packs de Formation et Épreuves
            TrainingVideoSeeder::class, // Vidéos de formation (YouTube)
            TrainingPackSeeder::class, // Packs de formation payants
            ExamPaperBulkSeeder::class, // Épreuves réelles + Packs d'épreuves (1000-2500 XAF)

            // Traductions multilingues FR / EN / ES / AR
            // À LANCER EN DERNIER : peuple la table `translations` pour toutes
            // les données de référence créées par les seeders ci-dessus.
            TranslationsSeeder::class,

            // Traductions spécifiques des Catégories d'entreprise (885 entrées,
            // 1082 libellés distincts). Mapping volumineux externalisé dans
            // database/seeders/data/company_categories_level{1,2,3}.php
            CompanyCategoriesTranslationsSeeder::class,

            // Traductions des programmes professionnels (Program + ProgramStep).
            // Idempotent : à lancer après ProgramSeeder (créé manuellement via
            // `php artisan db:seed --class=ProgramSeeder`).
            ProgramsTranslationsSeeder::class,
        ]);
    }
}
