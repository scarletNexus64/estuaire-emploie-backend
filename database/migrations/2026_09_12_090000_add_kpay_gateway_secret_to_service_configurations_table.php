<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Secret de la passerelle hébergée KPay (paiement par carte bancaire).
 *
 * Distinct du secret webhook : KPay signe le retour de redirection
 * (`status|reference|externalId|ts`) avec ce secret-là, tandis que le secret
 * webhook signe le corps brut des notifications serveur à serveur.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->string('kpay_gateway_secret')->nullable()->after('kpay_webhook_secret');
        });
    }

    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn('kpay_gateway_secret');
        });
    }
};
