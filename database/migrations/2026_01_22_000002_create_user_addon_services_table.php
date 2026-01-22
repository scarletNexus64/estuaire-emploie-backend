<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Table pour stocker les services additionnels achetés par les utilisateurs.
     * Remplace le système company_addon_services pour permettre aux users
     * d'acheter des services même sans avoir de company.
     *
     * Exemples de services:
     * - Extra job posting (1 offre supplémentaire)
     * - Boost WhatsApp (boost x3 pendant 7 jours)
     * - Accès coordonnées candidat (1 accès)
     * - Vérification diplômes (1 vérification)
     * - Test de compétences (1 test)
     */
    public function up(): void
    {
        Schema::create('user_addon_services', function (Blueprint $table) {
            $table->id();

            // Utilisateur qui a acheté le service
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Service acheté (référence vers addon_services_configs)
            $table->foreignId('addon_services_config_id')
                ->constrained('addon_services_configs')
                ->onDelete('cascade');

            // Paiement associé
            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->onDelete('set null');

            // Job concerné (pour les boosts de job spécifiques)
            $table->foreignId('related_job_id')
                ->nullable()
                ->constrained('jobs')
                ->onDelete('set null');

            // Candidat concerné (pour accès coordonnées, vérification diplôme, etc.)
            $table->foreignId('related_user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            // Dates et statut
            $table->timestamp('purchased_at'); // Date d'achat
            $table->timestamp('activated_at')->nullable(); // Date d'activation
            $table->timestamp('expires_at')->nullable(); // Date d'expiration
            $table->boolean('is_active')->default(true);

            // Statistiques (pour les boosts)
            $table->integer('views_count')->default(0);
            $table->integer('clicks_count')->default(0);

            // Utilisations restantes (pour services à usage limité)
            // NULL = illimité, 0 = épuisé, >0 = nombre restant
            $table->integer('uses_remaining')->nullable();

            // Métadonnées additionnelles (boost_multiplier, etc.)
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('user_id');
            $table->index('addon_services_config_id');
            $table->index('expires_at');
            $table->index(['user_id', 'is_active']);
            $table->index(['user_id', 'addon_services_config_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addon_services');
    }
};
