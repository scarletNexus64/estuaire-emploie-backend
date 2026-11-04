<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Index pour la recherche d'offres et l'annuaire des entreprises.
 *
 * - jobs(status, visibility, created_at) : filtre de base de `/jobs`
 *   (publiées + visibilité) servi déjà trié par date.
 * - FULLTEXT jobs(title, description, requirements) : la recherche texte
 *   faisait `LIKE '%…%'` sur des colonnes TEXT, ce qui relit toute la table.
 *   `JobController` l'utilise quand l'index existe, sinon il garde le LIKE.
 * - companies(status) et companies(city) : annuaire (`status = verified`) et
 *   visibilité locale des offres (ville de l'entreprise).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (! Schema::hasIndex('jobs', 'idx_jobs_status_visibility_created_at')) {
                $table->index(['status', 'visibility', 'created_at'], 'idx_jobs_status_visibility_created_at');
            }
        });

        if (DB::getDriverName() === 'mysql' && ! Schema::hasIndex('jobs', 'jobs_search_fulltext')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->fullText(['title', 'description', 'requirements'], 'jobs_search_fulltext');
            });
        }

        Schema::table('companies', function (Blueprint $table) {
            if (! Schema::hasIndex('companies', 'idx_companies_status')) {
                $table->index('status', 'idx_companies_status');
            }
            if (! Schema::hasIndex('companies', 'idx_companies_city')) {
                $table->index('city', 'idx_companies_city');
            }
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasIndex('companies', 'idx_companies_city')) {
                $table->dropIndex('idx_companies_city');
            }
            if (Schema::hasIndex('companies', 'idx_companies_status')) {
                $table->dropIndex('idx_companies_status');
            }
        });

        Schema::table('jobs', function (Blueprint $table) {
            if (Schema::hasIndex('jobs', 'jobs_search_fulltext')) {
                $table->dropFullText('jobs_search_fulltext');
            }
            if (Schema::hasIndex('jobs', 'idx_jobs_status_visibility_created_at')) {
                $table->dropIndex('idx_jobs_status_visibility_created_at');
            }
        });
    }
};
