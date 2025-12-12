<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();

            // Informations de base
            $table->string('name'); // Ex: "Starter", "Business", "Entreprise"
            $table->string('slug')->unique(); // Ex: "starter", "business", "entreprise"
            $table->text('description')->nullable(); // Description du plan
            $table->integer('display_order')->default(0); // Ordre d'affichage (1, 2, 3...)

            // Tarification
            $table->decimal('price', 10, 2); // Prix en FCFA (ex: 15000.00)
            $table->integer('duration_days')->default(30); // Durée en jours (30 = 1 mois)

            // Quotas configurables (null = illimité)
            $table->integer('jobs_limit')->nullable(); // Nombre d'offres par période (3, 10, null)
            $table->integer('contacts_limit')->nullable(); // Nombre de contacts candidats (10, 50, null)

            // Fonctionnalités booléennes
            $table->boolean('can_access_cvtheque')->default(false); // Accès CVthèque
            $table->boolean('can_boost_jobs')->default(false); // Peut booster des annonces
            $table->boolean('can_see_analytics')->default(false); // Statistiques avancées
            $table->boolean('priority_support')->default(false); // Support prioritaire
            $table->boolean('featured_company_badge')->default(false); // Badge entreprise premium
            $table->boolean('custom_company_page')->default(false); // Page entreprise personnalisée

            // Fonctionnalités avancées (JSON pour extensibilité)
            $table->json('features')->nullable(); // Features custom en JSON

            // Statut
            $table->boolean('is_active')->default(true); // Plan actif/inactif
            $table->boolean('is_popular')->default(false); // Badge "Populaire"

            // Couleur et style (pour affichage frontend)
            $table->string('color')->nullable(); // Ex: "#667eea"
            $table->string('icon')->nullable(); // Icône ou emoji

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
