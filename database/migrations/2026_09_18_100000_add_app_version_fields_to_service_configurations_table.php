<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Champs de mise à jour forcée exposés à l'app mobile via /api/app-config.
     * Stockés sur l'enregistrement service_type = 'notification_preferences'.
     */
    public function up(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            if (!Schema::hasColumn('service_configurations', 'app_latest_version')) {
                $table->string('app_latest_version', 20)->nullable()->after('use_paypal');
            }
            if (!Schema::hasColumn('service_configurations', 'app_min_supported_version')) {
                $table->string('app_min_supported_version', 20)->nullable()->after('app_latest_version');
            }
            if (!Schema::hasColumn('service_configurations', 'app_store_url_android')) {
                $table->string('app_store_url_android', 500)->nullable()->after('app_min_supported_version');
            }
            if (!Schema::hasColumn('service_configurations', 'app_store_url_ios')) {
                $table->string('app_store_url_ios', 500)->nullable()->after('app_store_url_android');
            }
            if (!Schema::hasColumn('service_configurations', 'app_release_notes')) {
                $table->text('app_release_notes')->nullable()->after('app_store_url_ios');
            }
        });
    }

    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'app_latest_version',
                'app_min_supported_version',
                'app_store_url_android',
                'app_store_url_ios',
                'app_release_notes',
            ]);
        });
    }
};
