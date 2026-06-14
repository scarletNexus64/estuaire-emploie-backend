<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ciblage géographique des annonces : liste de codes pays ISO alpha-2.
 * NULL ou tableau vide = annonce visible dans TOUS les pays (option "Tous").
 * Sinon l'annonce n'est visible que pour les users dont users.country est
 * dans la liste. Symétrique au ciblage par rôle (target_audience).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->json('target_countries')->nullable()->after('target_audience');
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('target_countries');
        });
    }
};
