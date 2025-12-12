<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            LocationSeeder::class,
            ContractTypeSeeder::class,

            // Monétisation - Config des plans et services
            SubscriptionPlanSeeder::class,
            PremiumServiceConfigSeeder::class,
            AddonServiceConfigSeeder::class,

            UserSeeder::class,
            CompanySeeder::class,
            RecruiterSeeder::class,
            JobSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}
