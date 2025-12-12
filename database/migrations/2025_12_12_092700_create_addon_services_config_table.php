<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addon_services_config', function (Blueprint $table) {
            $table->id();

            // Informations de base
            $table->string('name'); // Ex: "Boost Annonce", "Contact Candidat"
            $table->string('slug')->unique(); // Ex: "job_boost", "candidate_contact"
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);

            // Tarification
            $table->decimal('price', 10, 2); // Prix en FCFA
            $table->integer('duration_days')->nullable(); // Durée (ex: 7 jours pour boost)

            // Type de service
            $table->enum('service_type', [
                'extra_job_posting',    // Offre supplémentaire
                'job_boost',            // Boost d'annonce
                'candidate_contact',     // Accès coordonnées candidat
                'diploma_verification',  // Vérification diplômes
                'skills_test',          // Test de compétences
                'custom'                // Service personnalisé
            ]);

            // Configuration spécifique
            $table->integer('boost_multiplier')->nullable(); // Ex: 3 = visibilité x3
            $table->json('features')->nullable(); // Features custom

            // Visibilité
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);

            // Style (pour frontend)
            $table->string('color')->nullable();
            $table->string('icon')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addon_services_config');
    }
};
