<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ajoute des index de performance critiques pour gérer efficacement
     * un grand volume d'offres d'emploi (même avec 1 milliard de lignes).
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Index sur status - utilisé dans TOUTES les requêtes de listing
            $table->index('status', 'idx_jobs_status');

            // Index sur created_at - utilisé pour le tri (ORDER BY created_at DESC)
            $table->index('created_at', 'idx_jobs_created_at');

            // Index sur experience_level - utilisé pour filtrer
            $table->index('experience_level', 'idx_jobs_experience_level');

            // Index sur is_featured - utilisé pour les offres à la une
            $table->index('is_featured', 'idx_jobs_is_featured');

            // Index composé (status, created_at) - CRITIQUE pour la requête principale
            // WHERE status = 'published' ORDER BY created_at DESC
            // Cet index permet à MySQL/PostgreSQL d'utiliser un index scan au lieu d'un table scan
            $table->index(['status', 'created_at'], 'idx_jobs_status_created_at');

            // Index composé pour les offres à la une publiées
            // WHERE status = 'published' AND is_featured = true ORDER BY created_at DESC
            $table->index(['status', 'is_featured', 'created_at'], 'idx_jobs_featured');

            // Index sur application_deadline pour trouver les offres expirées rapidement
            $table->index('application_deadline', 'idx_jobs_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            // Supprimer les index dans l'ordre inverse
            $table->dropIndex('idx_jobs_deadline');
            $table->dropIndex('idx_jobs_featured');
            $table->dropIndex('idx_jobs_status_created_at');
            $table->dropIndex('idx_jobs_is_featured');
            $table->dropIndex('idx_jobs_experience_level');
            $table->dropIndex('idx_jobs_created_at');
            $table->dropIndex('idx_jobs_status');
        });
    }
};
