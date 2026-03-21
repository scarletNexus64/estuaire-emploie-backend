<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'TechCam Solutions',
                'email' => 'contact@techcam.cm',
                'phone' => '+237 690 123 456',
                'sector' => 'Technologies de l\'information',
                'website' => 'https://techcam.cm',
                'city' => 'Douala',
                'status' => 'verified',
                'subscription_plan' => 'premium',
                'verified_at' => now(),
                'description' => 'Leader en solutions technologiques au Cameroun',
            ],
            [
                'name' => 'FinTech Global Cameroun',
                'email' => 'rh@fintechglobal.cm',
                'phone' => '+237 690 234 567',
                'sector' => 'Finance et Banque',
                'website' => 'https://fintechglobal.cm',
                'city' => 'Yaoundé',
                'status' => 'verified',
                'subscription_plan' => 'premium',
                'verified_at' => now(),
                'description' => 'Solutions financières innovantes',
            ],
            [
                'name' => 'CamHR Solutions',
                'email' => 'info@camhr.cm',
                'phone' => '+237 690 345 678',
                'sector' => 'Ressources Humaines',
                'website' => 'https://camhr.cm',
                'city' => 'Douala',
                'status' => 'verified',
                'subscription_plan' => 'free',
                'verified_at' => now(),
                'description' => 'Cabinet de recrutement et conseil RH',
            ],
            [
                'name' => 'AgriCam Innovation',
                'email' => 'contact@agricam.cm',
                'phone' => '+237 690 456 789',
                'sector' => 'Agriculture',
                'website' => 'https://agricam.cm',
                'city' => 'Bafoussam',
                'status' => 'verified',
                'subscription_plan' => 'free',
                'verified_at' => now(),
                'description' => 'Innovation agricole et agro-industrie',
            ],
            [
                'name' => 'HealthCare Plus',
                'email' => 'rh@healthcareplus.cm',
                'phone' => '+237 690 567 890',
                'sector' => 'Santé et Médical',
                'website' => 'https://healthcareplus.cm',
                'city' => 'Yaoundé',
                'status' => 'pending',
                'subscription_plan' => 'free',
                'description' => 'Réseau de cliniques et centres de santé',
            ],
            [
                'name' => 'EduTech Cameroun',
                'email' => 'contact@edutech.cm',
                'phone' => '+237 690 678 901',
                'sector' => 'Éducation',
                'website' => 'https://edutech.cm',
                'city' => 'Douala',
                'status' => 'pending',
                'subscription_plan' => 'free',
                'description' => 'Plateforme d\'enseignement en ligne',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
