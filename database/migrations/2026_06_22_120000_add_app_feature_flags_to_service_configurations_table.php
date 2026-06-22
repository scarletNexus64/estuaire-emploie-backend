<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Feature flags globaux exposés à l'app mobile via /api/app-config.
     * Stockés sur l'enregistrement service_type = 'notification_preferences'.
     */
    public function up(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->boolean('use_otp')->default(true)->after('default_notification_channel');
            $table->boolean('use_paypal')->default(true)->after('use_otp');
        });
    }

    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn(['use_otp', 'use_paypal']);
        });
    }
};
