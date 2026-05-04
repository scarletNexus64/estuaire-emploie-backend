<?php

namespace Database\Seeders;

use App\Models\PremiumServiceConfig;
use App\Models\UserPremiumService;
use Illuminate\Database\Seeder;

class FixStudentModeMonthlySeeder extends Seeder
{
    public function run(): void
    {
        $studentMode = PremiumServiceConfig::where('slug', 'student_mode')->first();

        if (!$studentMode) {
            $this->command?->warn('student_mode config not found. Nothing to fix.');
            return;
        }

        // 1) Ensure config is monthly going forward
        if ((int) $studentMode->duration_days !== 30) {
            $studentMode->update(['duration_days' => 30]);
            $this->command?->info('Updated student_mode duration_days to 30.');
        }

        // 2) Fix existing yearly user records to monthly when pattern matches old logic
        $checked = 0;
        $updated = 0;

        UserPremiumService::where('premium_services_config_id', $studentMode->id)
            ->whereNotNull('expires_at')
            ->orderBy('id')
            ->chunkById(200, function ($services) use (&$checked, &$updated): void {
                foreach ($services as $service) {
                    $checked++;

                    $baseDate = $service->activated_at ?? $service->purchased_at;
                    if (!$baseDate) {
                        continue;
                    }

                    $expectedYearly = $baseDate->copy()->addDays(365);

                    // Match legacy yearly records (with small tolerance for timezone/seconds drift)
                    if ($service->expires_at->diffInDays($expectedYearly) <= 1) {
                        $service->expires_at = $baseDate->copy()->addDays(30);
                        $service->save();
                        $updated++;
                    }
                }
            });

        $this->command?->info("Checked {$checked} student_mode records.");
        $this->command?->info("Updated {$updated} records from yearly to monthly expiration.");
    }
}
