<?php

namespace Database\Seeders;

use App\Models\AdPricingConfig;
use Illuminate\Database\Seeder;

class AdPricingConfigSeeder extends Seeder
{
    public function run(): void
    {
        // Prix par utilisateur ciblé (FCFA). Ex: 2 FCFA => 500 FCFA = 250 personnes.
        $configs = [
            ['audience_segment' => 'all',        'price_per_user' => 2.00],
            ['audience_segment' => 'student',    'price_per_user' => 1.50],
            ['audience_segment' => 'candidate',  'price_per_user' => 2.00],
            ['audience_segment' => 'recruiter',  'price_per_user' => 3.00],
        ];

        foreach ($configs as $config) {
            AdPricingConfig::updateOrCreate(
                ['audience_segment' => $config['audience_segment']],
                [
                    'price_per_user' => $config['price_per_user'],
                    'min_budget' => 500,
                    'max_budget' => 500000,
                    'is_active' => true,
                ]
            );
        }
    }
}
