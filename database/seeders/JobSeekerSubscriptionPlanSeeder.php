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
                'description' => '1 000 FCFA/Mois',
                'display_order' => 1,
                'price' => 1000.00,
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
                    'free_cv_creation' => true,
                    'cv_accessible_recruiters' => true,
                    'free_regional_jobs' => true,
                    'free_certifications' => true,
                    'transformation_program' => false,
                    'portfolio_creation' => false,
                    'premium_cv' => false,
                    'verified_badge' => false,
                    'cv_review' => false,
                    'interview_coaching' => false,
                    'job_alerts' => false,
                    'immersion_program' => false,
                    'entrepreneurship' => false,
                    'international_internship' => false,
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
                'description' => '5 000 FCFA/Mois',
                'display_order' => 2,
                'price' => 5000.00,
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
                    'free_cv_creation' => true,
                    'cv_accessible_recruiters' => true,
                    'free_regional_jobs' => true,
                    'free_certifications' => true,
                    'transformation_program' => true,
                    'portfolio_creation' => true,
                    'premium_cv' => false,
                    'verified_badge' => false,
                    'cv_review' => false,
                    'interview_coaching' => false,
                    'job_alerts' => false,
                    'immersion_program' => false,
                    'entrepreneurship' => false,
                    'international_internship' => false,
                ],
                'is_active' => true,
                'is_popular' => true,
                'color' => '#FFD700',
                'icon' => '🥇',
            ],
            [
                'name' => 'PLATINUM',
                'slug' => 'platinum-job-seeker',
                'plan_type' => 'job_seeker',
                'description' => '10 000 FCFA/Mois',
                'display_order' => 3,
                'price' => 10000.00,
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
                    'free_cv_creation' => true,
                    'cv_accessible_recruiters' => true,
                    'free_regional_jobs' => true,
                    'free_certifications' => true,
                    'transformation_program' => true,
                    'portfolio_creation' => true,
                    'premium_cv' => true,
                    'verified_badge' => true,
                    'cv_review' => true,
                    'interview_coaching' => true,
                    'job_alerts' => true,
                    'immersion_program' => true,
                    'entrepreneurship' => true,
                    'international_internship' => true,
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
                'description' => '2 000 FCFA/Mois',
                'display_order' => 4,
                'price' => 2000.00,
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
                    'free_cv_creation' => true,
                    'cv_accessible_recruiters' => true,
                    'free_regional_jobs' => true,
                    'free_certifications' => true,
                    'transformation_program' => false,
                    'portfolio_creation' => false,
                    'premium_cv' => false,
                    'verified_badge' => false,
                    'cv_review' => false,
                    'interview_coaching' => false,
                    'job_alerts' => false,
                    'immersion_program' => false,
                    'entrepreneurship' => false,
                    'international_internship' => false,
                    // Student Pack specific features
                    'past_exam_subjects' => true,
                    'professional_orientation' => true,
                    'local_internship' => true,
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#6366F1',
                'icon' => '🎓',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug'], 'plan_type' => $plan['plan_type']],
                $plan
            );
        }

        $this->command->info('✅ 4 forfaits chercheurs d\'emploi créés avec succès (SILVER, GOLD, PLATINUM, PACK ÉTUDIANT) !');
    }
}
