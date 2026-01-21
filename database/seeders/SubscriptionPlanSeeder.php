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
                'name' => 'ARGENT',
                'slug' => 'argent',
                'plan_type' => 'recruiter',
                'description' => 'Idéal pour les petites entreprises qui débutent leur recrutement - 5,000 FCFA/Mois',
                'display_order' => 1,
                'price' => 5000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => 3, // 3 offres par mois
                'contacts_limit' => 10, // 10 contacts candidats
                'can_access_cvtheque' => true,
                'can_boost_jobs' => false,
                'can_see_analytics' => false,
                'priority_support' => false,
                'featured_company_badge' => false,
                'custom_company_page' => false,
                'features' => [
                    'Publication de 3 offres d\'emploi par mois',
                    'Notifications push pour nouvelles candidatures',
                    'Gestion des candidatures (accepter/rejeter/contacter)',
                    'Formation internationale',
                    'Géolocalisation de votre entreprise',
                    'Promotion de tous vos produits et services en ligne (e-commerce)',
                    'Accès à de nombreux CV des demandeurs d\'emploi',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#C0C0C0',
                'icon' => '🥈',
            ],
            [
                'name' => 'OR',
                'slug' => 'or',
                'plan_type' => 'recruiter',
                'description' => 'Pour les entreprises en croissance avec des besoins de recrutement réguliers - 10,000 FCFA/Mois',
                'display_order' => 2,
                'price' => 10000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => 10, // 10 offres par mois
                'contacts_limit' => 50, // 50 contacts candidats
                'can_access_cvtheque' => true,
                'can_boost_jobs' => true,
                'can_see_analytics' => true,
                'priority_support' => false,
                'featured_company_badge' => true,
                'custom_company_page' => false,
                'features' => [
                    'Tout le contenu de la formule Argent',
                    'Publication de 10 offres d\'emploi par mois',
                    'Mise en avant des offres dans les résultats de recherche',
                    'Statistiques de performance des annonces',
                    'Digitalisation de votre entreprise',
                    'Externalisation du service RH',
                ],
                'is_active' => true,
                'is_popular' => true, // Le plus populaire !
                'color' => '#FFD700',
                'icon' => '🥇',
            ],
            [
                'name' => 'PLATINUM',
                'slug' => 'platinum',
                'plan_type' => 'recruiter',
                'description' => 'Solution complète pour les grandes entreprises avec recrutement intensif - 25,000 FCFA/Mois',
                'display_order' => 3,
                'price' => 25000.00, // FCFA
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
                    'Tout le contenu de la formule Or',
                    'Page entreprise personnalisée',
                    'Support client prioritaire',
                    'Assistance en communication/marketing pour la promotion de vos produits et services',
                    'Conception du site web et applications mobile de votre entreprise',
                    'Call center 24h/7',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#E5E4E2',
                'icon' => '💎',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('✅ 3 forfaits recruteurs créés avec succès (ARGENT, OR, PLATINUM) !');
    }
}
