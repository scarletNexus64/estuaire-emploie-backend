<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            // Montants minimum configurables (XAF) pour dépôt et retrait KPay.
            $table->unsignedInteger('kpay_min_deposit')->default(100)->after('kpay_retry_delay');
            $table->unsignedInteger('kpay_min_withdrawal')->default(100)->after('kpay_min_deposit');
        });
    }

    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn(['kpay_min_deposit', 'kpay_min_withdrawal']);
        });
    }
};
