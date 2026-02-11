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
            LocationSeeder::class,
            ContractTypeSeeder::class,

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
        ]);
    }
}
