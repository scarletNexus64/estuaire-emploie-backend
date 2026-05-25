<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier l'ENUM pour ajouter 'student_mode'
        DB::statement("ALTER TABLE `premium_services_configs`
            MODIFY COLUMN `service_type` ENUM(
                'cv_premium',
                'verified_badge',
                'sms_alerts',
                'cv_review',
                'interview_coaching',
                'student_mode',
                'custom'
            ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer 'student_mode' de l'ENUM
        DB::statement("ALTER TABLE `premium_services_configs`
            MODIFY COLUMN `service_type` ENUM(
                'cv_premium',
                'verified_badge',
                'sms_alerts',
                'cv_review',
                'interview_coaching',
                'custom'
            ) NOT NULL");
    }
};
