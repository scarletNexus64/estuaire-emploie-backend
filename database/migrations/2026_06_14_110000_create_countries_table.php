<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Table de référence des pays du monde.
 * Utilisée pour le pays de résidence du user (ciblage géographique des
 * annonces Marketing Digital) et le ciblage par pays des campagnes.
 * Le code ISO 3166-1 alpha-2 (`code`) est la clé métier référencée par
 * users.country et advertisements.target_countries.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique();   // ISO 3166-1 alpha-2 (CM, FR, US...)
            $table->string('iso3', 3)->nullable();  // ISO 3166-1 alpha-3 (CMR, FRA...)
            $table->string('dial_code', 8)->nullable(); // Indicatif téléphonique (237, 33...)
            $table->string('flag', 16)->nullable(); // Emoji drapeau 🇨🇲
            $table->string('name');                 // Nom (traduit via la table translations)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
