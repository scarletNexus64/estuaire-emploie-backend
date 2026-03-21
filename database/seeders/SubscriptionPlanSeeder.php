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
                'description' => '5 000 FCFA/Mois',
                'display_order' => 1,
                'price' => 5000.00,
                'duration_days' => 30,
                'jobs_limit' => 3,
                'contacts_limit' => 10,
                'can_access_cvtheque' => true,
                'can_boost_jobs' => false,
                'can_see_analytics' => false,
                'priority_support' => false,
                'featured_company_badge' => false,
                'custom_company_page' => false,
                'features' => [
                    'post_jobs' => true,
                    'push_notifications' => true,
                    'application_management' => true,
                    'international_training' => true,
                    'company_geolocation' => true,
                    'ecommerce_promotion' => true,
                    'cv_database_access' => true,
                    'featured_listings' => false,
                    'performance_stats' => false,
                    'company_digitalization' => false,
                    'hr_outsourcing' => false,
                    'custom_company_page' => false,
                    'priority_support' => false,
                    'marketing_assistance' => false,
                    'website_app_design' => false,
                    'call_center_24_7' => false,
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
                'description' => '10 000 FCFA/Mois',
                'display_order' => 2,
                'price' => 10000.00,
                'duration_days' => 30,
                'jobs_limit' => 10,
                'contacts_limit' => 50,
                'can_access_cvtheque' => true,
                'can_boost_jobs' => true,
                'can_see_analytics' => true,
                'priority_support' => false,
                'featured_company_badge' => true,
                'custom_company_page' => false,
                'features' => [
                    'post_jobs' => true,
                    'push_notifications' => true,
                    'application_management' => true,
                    'international_training' => true,
                    'company_geolocation' => true,
                    'ecommerce_promotion' => true,
                    'cv_database_access' => true,
                    'featured_listings' => true,
                    'performance_stats' => true,
                    'company_digitalization' => true,
                    'hr_outsourcing' => true,
                    'custom_company_page' => false,
                    'priority_support' => false,
                    'marketing_assistance' => false,
                    'website_app_design' => false,
                    'call_center_24_7' => false,
                ],
                'is_active' => true,
                'is_popular' => true,
                'color' => '#FFD700',
                'icon' => '🥇',
            ],
            [
                'name' => 'PLATINUM',
                'slug' => 'platinum',
                'plan_type' => 'recruiter',
                'description' => '25 000 FCFA/Mois',
                'display_order' => 3,
                'price' => 25000.00,
                'duration_days' => 30,
                'jobs_limit' => null,
                'contacts_limit' => null,
                'can_access_cvtheque' => true,
                'can_boost_jobs' => true,
                'can_see_analytics' => true,
                'priority_support' => true,
                'featured_company_badge' => true,
                'custom_company_page' => true,
                'features' => [
                    'post_jobs' => true,
                    'push_notifications' => true,
                    'application_management' => true,
                    'international_training' => true,
                    'company_geolocation' => true,
                    'ecommerce_promotion' => true,
                    'cv_database_access' => true,
                    'featured_listings' => true,
                    'performance_stats' => true,
                    'company_digitalization' => true,
                    'hr_outsourcing' => true,
                    'custom_company_page' => true,
                    'priority_support' => true,
                    'marketing_assistance' => true,
                    'website_app_design' => true,
                    'call_center_24_7' => true,
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#E5E4E2',
                'icon' => '💎',
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug'], 'plan_type' => $plan['plan_type']],
                $plan
            );
        }

        $this->command->info('✅ 3 forfaits recruteurs créés avec succès (ARGENT, OR, PLATINUM) !');
    }
}
