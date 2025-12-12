<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Idéal pour les petites entreprises qui débutent leur recrutement',
                'display_order' => 1,
                'price' => 15000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => 3, // 3 offres par mois
                'contacts_limit' => 10, // 10 contacts candidats
                'can_access_cvtheque' => false,
                'can_boost_jobs' => false,
                'can_see_analytics' => false,
                'priority_support' => false,
                'featured_company_badge' => false,
                'custom_company_page' => false,
                'features' => [
                    'Publication de 3 offres d\'emploi par mois',
                    'Accès aux coordonnées de 10 candidats',
                    'Notifications push pour nouvelles candidatures',
                    'Gestion des candidatures (accepter/rejeter/contacter)',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#3b82f6',
                'icon' => '🚀',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Pour les entreprises en croissance avec des besoins de recrutement réguliers',
                'display_order' => 2,
                'price' => 30000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => 10, // 10 offres par mois
                'contacts_limit' => 50, // 50 contacts candidats
                'can_access_cvtheque' => false,
                'can_boost_jobs' => true,
                'can_see_analytics' => true,
                'priority_support' => false,
                'featured_company_badge' => true,
                'custom_company_page' => false,
                'features' => [
                    'Tout le contenu de la formule Starter',
                    'Publication de 10 offres d\'emploi par mois',
                    'Accès aux coordonnées de 50 candidats',
                    'Mise en avant des offres dans les résultats de recherche',
                    'Statistiques de performance des annonces',
                    'Badge entreprise premium',
                ],
                'is_active' => true,
                'is_popular' => true, // Le plus populaire !
                'color' => '#8b5cf6',
                'icon' => '💼',
            ],
            [
                'name' => 'Entreprise',
                'slug' => 'entreprise',
                'description' => 'Solution complète pour les grandes entreprises avec recrutement intensif',
                'display_order' => 3,
                'price' => 45000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => null, // Illimité
                'contacts_limit' => null, // Illimité
                'can_access_cvtheque' => true,
                'can_boost_jobs' => true,
                'can_see_analytics' => true,
                'priority_support' => true,
                'featured_company_badge' => true,
                'custom_company_page' => true,
                'features' => [
                    'Tout le contenu de la formule Business',
                    'Offres d\'emploi illimitées',
                    'Contacts candidats illimités',
                    'Accès complet à la CVthèque',
                    'Support client prioritaire',
                    'Page entreprise personnalisée',
                    'Statistiques avancées et rapports',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#f59e0b',
                'icon' => '⭐',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('✅ 3 plans d\'abonnement créés avec succès !');
    }
}
