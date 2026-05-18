<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Supprime le rattachement d'une offre à une "Localisation" admin.
     * Désormais la position d'une offre est dérivée de son entreprise
     * (company.city / latitude / longitude) et sa portée géographique
     * est gérée par la colonne `visibility` (national | local).
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère puis la colonne.
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('location_id')
                  ->nullable()
                  ->after('category_id')
                  ->constrained()
                  ->nullOnDelete();
        });
    }
};
