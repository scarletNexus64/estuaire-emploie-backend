<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            // KPay Configuration (remplace FreeMoPay)
            $table->string('kpay_base_url')->default('https://admin.kpay.site')->after('paypal_cancel_url');
            $table->text('kpay_api_key')->nullable()->after('kpay_base_url');           // X-API-Key (kpay_test_ / kpay_live_)
            $table->text('kpay_secret_key')->nullable()->after('kpay_api_key');          // X-Secret-Key (sk_test_ / sk_live_)
            $table->text('kpay_webhook_secret')->nullable()->after('kpay_secret_key');   // HMAC-SHA256 webhook signature
            $table->string('kpay_deposit_callback_url')->nullable()->after('kpay_webhook_secret');
            $table->string('kpay_withdrawal_callback_url')->nullable()->after('kpay_deposit_callback_url');
            $table->string('kpay_environment')->default('sandbox')->after('kpay_withdrawal_callback_url'); // sandbox | live
            $table->integer('kpay_init_payment_timeout')->default(30)->after('kpay_environment');
            $table->integer('kpay_status_check_timeout')->default(30)->after('kpay_init_payment_timeout');
            $table->integer('kpay_max_retries')->default(3)->after('kpay_status_check_timeout');
            $table->decimal('kpay_retry_delay', 4, 1)->default(1.0)->after('kpay_max_retries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'kpay_base_url',
                'kpay_api_key',
                'kpay_secret_key',
                'kpay_webhook_secret',
                'kpay_deposit_callback_url',
                'kpay_withdrawal_callback_url',
                'kpay_environment',
                'kpay_init_payment_timeout',
                'kpay_status_check_timeout',
                'kpay_max_retries',
                'kpay_retry_delay',
            ]);
        });
    }
};
