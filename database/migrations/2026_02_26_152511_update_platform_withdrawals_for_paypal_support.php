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
        Schema::table('platform_withdrawals', function (Blueprint $table) {
            // Add provider column (freemopay or paypal)
            $table->string('provider', 50)->default('freemopay')->after('currency');

            // Add PayPal specific columns
            $table->string('paypal_batch_id')->nullable()->after('freemopay_response');
            $table->string('paypal_payout_item_id')->nullable()->after('paypal_batch_id');
            $table->json('paypal_response')->nullable()->after('paypal_payout_item_id');

            // Modify payment_method to accept paypal
            // Note: payment_method for PayPal will be 'paypal'
            // payment_account will store PayPal email instead of phone

            // Add index for provider
            $table->index('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platform_withdrawals', function (Blueprint $table) {
            $table->dropIndex(['provider']);
            $table->dropColumn(['provider', 'paypal_batch_id', 'paypal_payout_item_id', 'paypal_response']);
        });
    }
};
