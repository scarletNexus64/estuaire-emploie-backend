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
        Schema::create('quick_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description');

            // Prix
            $table->enum('price_type', ['fixed', 'range', 'negotiable'])->default('negotiable');
            $table->decimal('price_min', 10, 2)->nullable();
            $table->decimal('price_max', 10, 2)->nullable();

            // Localisation
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('location_name')->nullable(); // Nom du lieu pour affichage

            // Détails du service
            $table->enum('urgency', ['urgent', 'this_week', 'this_month', 'flexible'])->default('flexible');
            $table->date('desired_date')->nullable();
            $table->string('estimated_duration')->nullable(); // "1h", "demi-journée", "journée", etc.

            // Statut et métadonnées
            $table->enum('status', ['open', 'in_progress', 'completed', 'cancelled'])->default('open');
            $table->timestamp('expires_at')->nullable();
            $table->json('images')->nullable();
            $table->integer('views_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Index pour la recherche géographique
            $table->index(['latitude', 'longitude']);
            $table->index('status');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quick_services');
    }
};
