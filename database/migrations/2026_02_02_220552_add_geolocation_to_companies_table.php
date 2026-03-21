<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute les coordonnées géographiques pour la localisation des entreprises
     * - latitude: Coordonnée latitude (ex: 4.0511 pour Douala)
     * - longitude: Coordonnée longitude (ex: 9.7679 pour Douala)
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Coordonnées géographiques
            $table->decimal('latitude', 10, 8)->nullable()->after('country');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');

            // Index pour les requêtes de proximité géographique
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
