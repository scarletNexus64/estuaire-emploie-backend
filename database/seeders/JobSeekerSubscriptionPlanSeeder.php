<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class JobSeekerSubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'SILVER',
                'slug' => 'silver',
                'plan_type' => 'job_seeker',
                'description' => 'Forfait de base pour les chercheurs d\'emploi - 1,000 FCFA/Mois',
                'display_order' => 1,
                'price' => 1000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => null,
                'contacts_limit' => null,
                'can_access_cvtheque' => null,
                'can_boost_jobs' => null,
                'can_see_analytics' => null,
                'priority_support' => null,
                'featured_company_badge' => null,
                'custom_company_page' => null,
                'features' => [
                    'Conception gratuite de votre CV',
                    'Votre CV accessible par les recruteurs à l\'échelle nationale et internationale',
                    'De multiples offres d\'emploi de votre région accessibles gratuitement',
                    'Des formations certifiantes internationales gratuites (une attestation ou une certification)',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#C0C0C0',
                'icon' => '🥈',
            ],
            [
                'name' => 'GOLD',
                'slug' => 'gold',
                'plan_type' => 'job_seeker',
                'description' => 'Forfait intermédiaire pour accélérer votre carrière - 5,000 FCFA/Mois',
                'display_order' => 2,
                'price' => 5000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => null,
                'contacts_limit' => null,
                'can_access_cvtheque' => null,
                'can_boost_jobs' => null,
                'can_see_analytics' => null,
                'priority_support' => null,
                'featured_company_badge' => null,
                'custom_company_page' => null,
                'features' => [
                    'Tout le contenu de la formule SILVER',
                    'L\'établissement de votre programme de transformation professionnelle et personnel',
                    'Création de votre portfolio',
                ],
                'is_active' => true,
                'is_popular' => true, // Le plus populaire !
                'color' => '#FFD700',
                'icon' => '🥇',
            ],
            [
                'name' => 'PLATINUM',
                'slug' => 'platinum-job-seeker',
                'plan_type' => 'job_seeker',
                'description' => 'Forfait premium complet avec accompagnement personnalisé - 10,000 FCFA/Mois',
                'display_order' => 3,
                'price' => 10000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => null,
                'contacts_limit' => null,
                'can_access_cvtheque' => null,
                'can_boost_jobs' => null,
                'can_see_analytics' => null,
                'priority_support' => null,
                'featured_company_badge' => null,
                'custom_company_page' => null,
                'features' => [
                    'Tout le contenu de la formule GOLD',
                    'CV Premium (mise en avant)',
                    'Badge "Profil Vérifié"',
                    'Révision CV par expert',
                    'Coaching entretien',
                    'Alertes emploi SMS/WhatsApp',
                    'Programme d\'immersion professionnelle',
                    'Programme en entreprenariat',
                    'Stage à l\'international',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#E5E4E2',
                'icon' => '💎',
            ],
            [
                'name' => 'PACK ÉTUDIANT',
                'slug' => 'pack-etudiant',
                'plan_type' => 'job_seeker',
                'description' => 'Forfait spécial pour étudiants et jeunes diplômés - 2,000 FCFA/Mois',
                'display_order' => 4,
                'price' => 2000.00, // FCFA
                'duration_days' => 30,
                'jobs_limit' => null,
                'contacts_limit' => null,
                'can_access_cvtheque' => null,
                'can_boost_jobs' => null,
                'can_see_analytics' => null,
                'priority_support' => null,
                'featured_company_badge' => null,
                'custom_company_page' => null,
                'features' => [
                    'Anciens sujets d\'examen',
                    'Orientation professionnelle (spécialité/métier)',
                    'Stage local',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#6366F1',
                'icon' => '🎓',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }

        $this->command->info('✅ 4 forfaits chercheurs d\'emploi créés avec succès (SILVER, GOLD, PLATINUM, PACK ÉTUDIANT) !');
    }
}
