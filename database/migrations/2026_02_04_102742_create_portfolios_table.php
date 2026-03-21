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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('slug')->unique()->index();
            $table->string('title'); // Ex: "Développeur Full Stack"
            $table->text('bio')->nullable(); // Résumé/Bio
            $table->string('photo_url')->nullable();
            $table->string('cv_url')->nullable();

            // Données structurées en JSON
            $table->json('skills')->nullable(); // [{name, level}]
            $table->json('experiences')->nullable(); // [{title, company, duration, description}]
            $table->json('education')->nullable(); // [{degree, school, year, description}]
            $table->json('projects')->nullable(); // [{name, description, url, image, technologies}]
            $table->json('certifications')->nullable(); // [{name, issuer, date, credential_url}]
            $table->json('languages')->nullable(); // [{language, level}]
            $table->json('social_links')->nullable(); // {linkedin, github, twitter, website, etc}

            // Configuration
            $table->string('template_id')->default('professional'); // professional, creative, tech
            $table->boolean('is_public')->default(true);
            $table->string('theme_color')->default('#667eea'); // Couleur principale du portfolio

            // Stats
            $table->unsignedInteger('view_count')->default(0);

            $table->timestamps();

            $table->index(['user_id', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
