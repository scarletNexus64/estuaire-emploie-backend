<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('premium_services_configs', function (Blueprint $table) {
            $table->id();

            // Informations de base
            $table->string('name'); // Ex: "CV Premium", "Badge Profil Vérifié"
            $table->string('slug')->unique(); // Ex: "cv_premium", "verified_badge"
            $table->text('description')->nullable();
            $table->integer('display_order')->default(0);

            // Tarification
            $table->decimal('price', 10, 2); // Prix en FCFA
            $table->integer('duration_days')->nullable(); // Durée (null = permanent)

            // Type de service
            $table->enum('service_type', [
                'cv_premium',           // Mise en avant du CV
                'verified_badge',       // Badge vérifié
                'sms_alerts',          // Alertes SMS/WhatsApp
                'cv_review',           // Révision CV par expert
                'interview_coaching',   // Coaching entretien
                'custom'               // Service personnalisé
            ]);

            // Fonctionnalités JSON (configurable)
            $table->json('features')->nullable(); // Features spécifiques

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
        Schema::dropIfExists('premium_services_configs');
    }
};
