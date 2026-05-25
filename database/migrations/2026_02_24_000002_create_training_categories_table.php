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
        Schema::create('training_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nom de la catégorie
            $table->string('slug')->unique(); // Slug pour URL
            $table->text('description')->nullable(); // Description
            $table->string('icon')->nullable(); // Nom de l'icône
            $table->string('color')->nullable(); // Couleur hex
            $table->integer('display_order')->default(0); // Ordre d'affichage
            $table->boolean('is_active')->default(true); // Actif ou non
            $table->timestamps();
            $table->softDeletes();
        });

        // Index pour les recherches fréquentes
        Schema::table('training_categories', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_categories');
    }
};
