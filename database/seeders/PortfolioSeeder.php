<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find users with GOLD or PLATINUM subscription for job seekers
        $eligibleUsers = \App\Models\User::whereHas('userSubscriptionPlans', function ($query) {
            $query->active()
                ->whereHas('subscriptionPlan', function ($planQuery) {
                    $planQuery->where('plan_type', 'job_seeker')
                        ->where(function ($nameQuery) {
                            $nameQuery->where('name', 'LIKE', '%GOLD%')
                                ->orWhere('name', 'LIKE', '%PLATINUM%');
                        });
                });
        })->whereDoesntHave('portfolio')->take(5)->get();

        // If we have eligible users, create portfolios for them
        if ($eligibleUsers->count() > 0) {
            foreach ($eligibleUsers as $user) {
                \App\Models\Portfolio::factory()->create([
                    'user_id' => $user->id,
                ]);
            }

            $this->command->info("✅ Created {$eligibleUsers->count()} portfolios for GOLD/PLATINUM users");
        } else {
            // If no eligible users, create portfolios for any job seekers without a portfolio
            $regularUsers = \App\Models\User::where('role', 'job_seeker')
                ->whereDoesntHave('portfolio')
                ->take(3)
                ->get();

            if ($regularUsers->count() > 0) {
                foreach ($regularUsers as $user) {
                    \App\Models\Portfolio::factory()->create([
                        'user_id' => $user->id,
                    ]);
                }

                $this->command->info("✅ Created {$regularUsers->count()} portfolios for regular job seekers (for testing)");
            } else {
                $this->command->info("ℹ️ No eligible users found. Please ensure you have job seeker users in the database.");
            }
        }
    }
}
