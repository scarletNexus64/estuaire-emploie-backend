<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

/**
 * Seeder de test : 20 entreprises fictives autour de San Francisco.
 *
 * Sert à tester l'annuaire des entreprises / la carte avec la position par
 * défaut de l'émulateur (San Francisco, ~37.7749, -122.4194).
 *
 * Les coordonnées correspondent à de vrais quartiers de SF afin que les
 * marqueurs soient bien répartis sur la carte et proches de la position
 * de l'émulateur. Toutes les entreprises sont `verified` pour apparaître.
 *
 * Lancer uniquement ce seeder :
 *   php artisan db:seed --class=SanFranciscoCompaniesSeeder
 */
class SanFranciscoCompaniesSeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Golden Gate Tech',
                'domain' => 'Technologie & Digital',
                'sector' => 'Développement Web',
                'address' => '1 Market St',
                'latitude' => 37.7936,
                'longitude' => -122.3965,
            ],
            [
                'name' => 'Bay Area Finance Group',
                'domain' => 'Finance & Comptabilité',
                'sector' => 'Gestion Financière',
                'address' => '555 California St',
                'latitude' => 37.7925,
                'longitude' => -122.4039,
            ],
            [
                'name' => 'Mission Marketing Lab',
                'domain' => 'Marketing & Communication',
                'sector' => 'Marketing Digital',
                'address' => '2000 Mission St',
                'latitude' => 37.7626,
                'longitude' => -122.4194,
            ],
            [
                'name' => 'SOMA Commerce Hub',
                'domain' => 'Commerce & Vente',
                'sector' => 'E-commerce',
                'address' => '845 Market St',
                'latitude' => 37.7841,
                'longitude' => -122.4076,
            ],
            [
                'name' => 'Pacific Health Clinic',
                'domain' => 'Santé & Sciences',
                'sector' => 'Soins Infirmiers',
                'address' => '2333 Buchanan St',
                'latitude' => 37.7903,
                'longitude' => -122.4318,
            ],
            [
                'name' => 'Sunset EduTech',
                'domain' => 'Éducation & Formation',
                'sector' => 'Formation Professionnelle',
                'address' => '1234 Irving St',
                'latitude' => 37.7639,
                'longitude' => -122.4730,
            ],
            [
                'name' => 'Embarcadero Logistics',
                'domain' => 'Transport & Logistique',
                'sector' => 'Livraison',
                'address' => 'Pier 1, The Embarcadero',
                'latitude' => 37.7955,
                'longitude' => -122.3937,
            ],
            [
                'name' => 'Nob Hill Hospitality',
                'domain' => 'Hôtellerie & Restauration',
                'sector' => 'Hôtellerie',
                'address' => '999 California St',
                'latitude' => 37.7919,
                'longitude' => -122.4127,
            ],
            [
                'name' => 'Richmond BTP Works',
                'domain' => 'Construction & BTP',
                'sector' => 'Génie Civil',
                'address' => '4000 Geary Blvd',
                'latitude' => 37.7813,
                'longitude' => -122.4621,
            ],
            [
                'name' => 'Presidio Green Farms',
                'domain' => 'Agriculture & Environnement',
                'sector' => 'Agriculture',
                'address' => '103 Montgomery St, Presidio',
                'latitude' => 37.7989,
                'longitude' => -122.4662,
            ],
            [
                'name' => 'Financial District Legal',
                'domain' => 'Services Juridiques',
                'sector' => 'Droit des Affaires',
                'address' => '101 California St',
                'latitude' => 37.7932,
                'longitude' => -122.3984,
            ],
            [
                'name' => 'Castro Arts Studio',
                'domain' => 'Arts & Culture',
                'sector' => 'Arts Visuels',
                'address' => '429 Castro St',
                'latitude' => 37.7609,
                'longitude' => -122.4350,
            ],
            [
                'name' => 'Marina Care Services',
                'domain' => 'Services à la Personne',
                'sector' => 'Aide à Domicile',
                'address' => '2200 Chestnut St',
                'latitude' => 37.8003,
                'longitude' => -122.4400,
            ],
            [
                'name' => 'North Beach Media',
                'domain' => 'Médias & Presse',
                'sector' => 'Journalisme',
                'address' => '500 Columbus Ave',
                'latitude' => 37.8003,
                'longitude' => -122.4090,
            ],
            [
                'name' => 'Hayes Valley AI Labs',
                'domain' => 'Technologie & Digital',
                'sector' => 'IA & Data Science',
                'address' => '300 Hayes St',
                'latitude' => 37.7765,
                'longitude' => -122.4240,
            ],
            [
                'name' => 'Dogpatch Manufacturing',
                'domain' => 'Industrie & Production',
                'sector' => 'Manufacture',
                'address' => '900 Third St',
                'latitude' => 37.7570,
                'longitude' => -122.3880,
            ],
            [
                'name' => 'Union Square Retail Co',
                'domain' => 'Commerce & Vente',
                'sector' => 'Vente au Détail',
                'address' => '170 O\'Farrell St',
                'latitude' => 37.7858,
                'longitude' => -122.4064,
            ],
            [
                'name' => 'Tenderloin Consulting',
                'domain' => 'Autre',
                'sector' => 'Conseil & Stratégie',
                'address' => '350 Ellis St',
                'latitude' => 37.7847,
                'longitude' => -122.4145,
            ],
            [
                'name' => 'Potrero Cyber Security',
                'domain' => 'Technologie & Digital',
                'sector' => 'Cybersécurité',
                'address' => '1459 18th St',
                'latitude' => 37.7625,
                'longitude' => -122.3978,
            ],
            [
                'name' => 'Fisherman\'s Wharf Tourism',
                'domain' => 'Hôtellerie & Restauration',
                'sector' => 'Tourisme',
                'address' => 'Pier 39',
                'latitude' => 37.8087,
                'longitude' => -122.4098,
            ],
        ];

        foreach ($companies as $i => $data) {
            $slug = \Illuminate\Support\Str::slug($data['name']);

            Company::updateOrCreate(
                ['email' => "{$slug}@sf-demo.test"],
                array_merge($data, [
                    'phone' => '+1 415 ' . str_pad((string) (1000000 + $i), 7, '0', STR_PAD_LEFT),
                    'website' => "https://{$slug}.example.com",
                    'city' => 'San Francisco',
                    'country' => 'United States',
                    'status' => 'verified',
                    'subscription_plan' => $i % 2 === 0 ? 'premium' : 'free',
                    'verified_at' => now(),
                    'description' => "Entreprise de démonstration ({$data['sector']}) basée à San Francisco pour les tests de l'annuaire.",
                ])
            );
        }

        $this->command?->info('[SanFranciscoCompaniesSeeder] 20 entreprises fictives créées autour de San Francisco.');
    }
}
