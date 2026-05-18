<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute la visibilité d'une offre d'emploi :
     * - national : visible par tout le pays
     * - local    : visible uniquement dans la ville de l'entreprise
     *
     * La colonne est ajoutée avec une valeur temporaire 'national' pour
     * pouvoir remplir les lignes existantes, puis le défaut est retiré
     * afin que le choix soit OBLIGATOIRE pour les nouvelles offres
     * (la validation API impose le champ).
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->enum('visibility', ['national', 'local'])
                  ->default('national')
                  ->after('status');
            $table->index('visibility');
        });

        // Les offres déjà existantes restent visibles partout.
        DB::table('jobs')->update(['visibility' => 'national']);

        // Retirer le défaut : le choix devient obligatoire côté applicatif.
        DB::statement("ALTER TABLE `jobs` MODIFY `visibility` ENUM('national','local') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['visibility']);
            $table->dropColumn('visibility');
        });
    }
};
