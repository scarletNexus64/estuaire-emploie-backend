<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Repointe jobs.category_id vers la table company_categories.
 *
 * Auparavant la FK pointait vers `categories`. On réutilise la même
 * colonne pour référencer désormais une company_category de niveau 3
 * (celle choisie par le recruteur à la création de l'offre).
 *
 * Les anciennes valeurs (qui référençaient `categories`) sont remises à
 * null car elles ne sont plus valides pour la nouvelle contrainte.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Supprimer l'ancienne contrainte FK (vers categories) tout en
        //    conservant la colonne.
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        // 2. Recréer la colonne, nullable, sans contrainte pour l'instant.
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('company_id');
        });

        // 3. Repointer la contrainte vers company_categories.
        Schema::table('jobs', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('company_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('company_id')
                ->constrained()
                ->nullOnDelete();
        });
    }
};
