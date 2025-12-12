<?php

namespace Database\Seeders;

use App\Models\AddonServiceConfig;
use Illuminate\Database\Seeder;

class AddonServiceConfigSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Offre Supplémentaire',
                'slug' => 'extra_job_posting',
                'description' => 'Publiez une offre d\'emploi supplémentaire au-delà de votre quota mensuel',
                'display_order' => 1,
                'price' => 3000.00, // FCFA
                'duration_days' => 30, // Valide 30 jours
                'service_type' => 'extra_job_posting',
                'boost_multiplier' => null,
                'features' => [
                    'Publication d\'une offre supplémentaire',
                    'Valable 30 jours',
                    'Toutes les fonctionnalités standard incluses',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#3b82f6',
                'icon' => '📄',
            ],
            [
                'name' => 'Boost Annonce (7 jours)',
                'slug' => 'job_boost_7days',
                'description' => 'Augmentez la visibilité de votre offre x3 pendant 7 jours',
                'display_order' => 2,
                'price' => 5000.00, // FCFA
                'duration_days' => 7, // 7 jours
                'service_type' => 'job_boost',
                'boost_multiplier' => 3, // Visibilité x3
                'features' => [
                    'Votre offre apparaît en priorité',
                    'Visibilité multipliée par 3',
                    'Position en haut des résultats de recherche',
                    'Badge "Offre Boostée"',
                    'Durée : 7 jours',
                ],
                'is_active' => true,
                'is_popular' => true, // Le plus populaire !
                'color' => '#f59e0b',
                'icon' => '🚀',
            ],
            [
                'name' => 'Accès Coordonnées Candidat',
                'slug' => 'candidate_contact',
                'description' => 'Accédez aux coordonnées complètes d\'un candidat spécifique',
                'display_order' => 3,
                'price' => 500.00, // FCFA
                'duration_days' => null, // Permanent
                'service_type' => 'candidate_contact',
                'boost_multiplier' => null,
                'features' => [
                    'Téléphone du candidat',
                    'Email du candidat',
                    'Accès au CV complet',
                    'Lien portfolio si disponible',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#10b981',
                'icon' => '📞',
            ],
            [
                'name' => 'Vérification de Diplômes',
                'slug' => 'diploma_verification',
                'description' => 'Vérification officielle des diplômes et références du candidat',
                'display_order' => 4,
                'price' => 5000.00, // FCFA
                'duration_days' => null, // Service unique
                'service_type' => 'diploma_verification',
                'boost_multiplier' => null,
                'features' => [
                    'Vérification auprès des établissements',
                    'Contrôle des références professionnelles',
                    'Rapport détaillé sous 5 jours ouvrés',
                    'Certificat de vérification',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#8b5cf6',
                'icon' => '🎓',
            ],
            [
                'name' => 'Test de Compétences',
                'slug' => 'skills_test',
                'description' => 'Évaluez les compétences techniques du candidat avec un test personnalisé',
                'display_order' => 5,
                'price' => 2000.00, // FCFA
                'duration_days' => null, // Service unique
                'service_type' => 'skills_test',
                'boost_multiplier' => null,
                'features' => [
                    'Test personnalisé selon le poste',
                    'Évaluation technique ou soft skills',
                    'Résultats détaillés et notation',
                    'Rapport de compétences',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#ef4444',
                'icon' => '📊',
            ],
        ];

        foreach ($services as $service) {
            AddonServiceConfig::create($service);
        }

        $this->command->info('✅ 5 services additionnels recruteurs créés avec succès !');
    }
}
