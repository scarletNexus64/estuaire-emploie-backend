<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1 : Ajouter les colonnes polymorphiques si elles n'existent pas
        if (!Schema::hasColumn('favorites', 'favoriteable_type')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->string('favoriteable_type')->nullable()->after('user_id');
            });
        }

        if (!Schema::hasColumn('favorites', 'favoriteable_id')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->unsignedBigInteger('favoriteable_id')->nullable()->after('favoriteable_type');
            });
        }

        // Ajouter l'index si absent
        $indexes = DB::select("SHOW INDEX FROM favorites WHERE Key_name = 'favorites_favoriteable_type_favoriteable_id_index'");
        if (empty($indexes)) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->index(['favoriteable_type', 'favoriteable_id']);
            });
        }

        // Étape 2 : Migrer les données existantes
        if (Schema::hasColumn('favorites', 'job_id')) {
            DB::table('favorites')
                ->whereNull('favoriteable_type')
                ->orWhereNull('favoriteable_id')
                ->update([
                    'favoriteable_type' => 'App\\Models\\Job',
                    'favoriteable_id' => DB::raw('job_id')
                ]);
        }

        // Étape 3 : Rendre les colonnes NON nullable
        Schema::table('favorites', function (Blueprint $table) {
            $table->string('favoriteable_type')->nullable(false)->change();
            $table->unsignedBigInteger('favoriteable_id')->nullable(false)->change();
        });

        // Étape 4 : Supprimer l'ancienne structure SI job_id existe encore
        if (Schema::hasColumn('favorites', 'job_id')) {
            // Vérifier et supprimer la contrainte unique user_id + job_id
            $uniqueConstraints = DB::select("SHOW INDEX FROM favorites WHERE Key_name = 'favorites_user_id_job_id_unique'");
            if (!empty($uniqueConstraints)) {
                Schema::table('favorites', function (Blueprint $table) {
                    $table->dropUnique(['user_id', 'job_id']);
                });
            }

            // Vérifier et supprimer la clé étrangère
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'favorites'
                AND COLUMN_NAME = 'job_id'
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            if (!empty($foreignKeys)) {
                $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                DB::statement("ALTER TABLE favorites DROP FOREIGN KEY `{$constraintName}`");
            }

            // Supprimer la colonne job_id
            Schema::table('favorites', function (Blueprint $table) {
                $table->dropColumn('job_id');
            });
        }

        // Étape 5 : Ajouter la nouvelle contrainte unique polymorphique
        $uniquePolyConstraint = DB::select("SHOW INDEX FROM favorites WHERE Key_name = 'favorites_unique'");
        if (empty($uniquePolyConstraint)) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->unique(['user_id', 'favoriteable_type', 'favoriteable_id'], 'favorites_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recréer la structure originale
        Schema::table('favorites', function (Blueprint $table) {
            $table->foreignId('job_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
        });

        // Migrer les données : favoriteable → job_id (seulement pour les Job)
        DB::table('favorites')
            ->where('favoriteable_type', 'App\\Models\\Job')
            ->update(['job_id' => DB::raw('favoriteable_id')]);

        // Supprimer les favoris qui ne sont pas des jobs
        DB::table('favorites')
            ->where('favoriteable_type', '!=', 'App\\Models\\Job')
            ->delete();

        Schema::table('favorites', function (Blueprint $table) {
            // Rendre job_id NON nullable
            $table->unsignedBigInteger('job_id')->nullable(false)->change();

            // Supprimer la contrainte unique polymorphique
            $table->dropUnique('favorites_unique');

            // Supprimer l'index polymorphique
            $table->dropIndex(['favoriteable_type', 'favoriteable_id']);

            // Supprimer les colonnes polymorphiques
            $table->dropColumn(['favoriteable_type', 'favoriteable_id']);

            // Recréer l'ancienne contrainte unique
            $table->unique(['user_id', 'job_id']);
        });
    }
};
