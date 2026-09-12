<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Activation du paiement par carte bancaire (Visa/Mastercard).
 *
 * Le moyen `CARD` doit d'abord être autorisé sur l'application côté KPay ;
 * tant qu'il ne l'est pas, l'API répond « moyen de paiement non activé ».
 * Ce drapeau permet de ne proposer la carte dans l'app qu'une fois cette
 * activation faite, plutôt que d'exposer un bouton qui échoue.
 *
 * Désactivé par défaut : on n'active qu'après vérification côté KPay.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->boolean('kpay_card_enabled')->default(false)->after('kpay_gateway_secret');
        });
    }

    public function down(): void
    {
        Schema::table('service_configurations', function (Blueprint $table) {
            $table->dropColumn('kpay_card_enabled');
        });
    }
};
