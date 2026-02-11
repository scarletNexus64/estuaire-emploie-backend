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
            // PayPal Configuration
            $table->string('paypal_mode')->nullable()->after('freemopay_retry_delay'); // sandbox or live
            $table->text('paypal_client_id')->nullable()->after('paypal_mode');
            $table->text('paypal_client_secret')->nullable()->after('paypal_client_id');
            $table->string('paypal_currency')->default('USD')->after('paypal_client_secret');
            $table->string('paypal_return_url')->nullable()->after('paypal_currency');
            $table->string('paypal_cancel_url')->nullable()->after('paypal_return_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_mode',
                'paypal_client_id',
                'paypal_client_secret',
                'paypal_currency',
                'paypal_return_url',
                'paypal_cancel_url',
            ]);
        });
    }
};
