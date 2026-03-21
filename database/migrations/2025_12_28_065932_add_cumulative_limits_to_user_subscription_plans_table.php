<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute les champs pour stocker les limites cumulées lors des renouvellements.
     * - jobs_limit_total : Limite totale d'offres cumulée (somme des limites de tous les plans)
     * - contacts_limit_total : Limite totale de contacts cumulée
     *
     * Exemple: Un utilisateur avec plan Starter (5 offres) qui renouvelle aura:
     * - jobs_limit_total = 10 (5 du premier plan + 5 du renouvellement)
     */
    public function up(): void
    {
        Schema::table('user_subscription_plans', function (Blueprint $table) {
            // Limite totale d'offres cumulée (null = utiliser la limite du plan actuel)
            $table->unsignedInteger('jobs_limit_total')->nullable()->after('contacts_used');
            // Limite totale de contacts cumulée (null = utiliser la limite du plan actuel)
            $table->unsignedInteger('contacts_limit_total')->nullable()->after('jobs_limit_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_subscription_plans', function (Blueprint $table) {
            $table->dropColumn(['jobs_limit_total', 'contacts_limit_total']);
        });
    }
};
