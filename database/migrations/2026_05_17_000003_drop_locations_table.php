<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Supprime définitivement la table `locations` (système de
     * "Localisations" configurables côté admin), remplacé par la
     * position de l'entreprise + la visibilité de l'offre.
     *
     * À exécuter APRÈS la suppression de jobs.location_id.
     */
    public function up(): void
    {
        Schema::dropIfExists('locations');
    }

    /**
     * Reverse the migrations.
     *
     * Recrée la table à l'identique de sa définition d'origine
     * (2024_01_01_000004_create_categories_table) — les données
     * historiques ne sont pas restaurées.
     */
    public function down(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country')->default('Cameroun');
            $table->timestamps();
        });
    }
};
