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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title'); // Titre du CV (ex: "CV Développeur", "CV Marketing")
            $table->string('template_type'); // Type de modèle: modern, classic, creative, professional, minimalist

            // Informations personnelles
            $table->json('personal_info'); // {name, email, phone, address, linkedin, github, website, photo}

            // Profil professionnel
            $table->text('professional_summary')->nullable(); // Résumé/Objectif professionnel

            // Expériences professionnelles
            $table->json('experiences')->nullable(); // [{company, position, location, start_date, end_date, description, achievements}]

            // Formation
            $table->json('education')->nullable(); // [{institution, degree, field, location, start_date, end_date, description}]

            // Compétences
            $table->json('skills')->nullable(); // {technical: [], soft: [], languages: [{language, level}]}

            // Certifications
            $table->json('certifications')->nullable(); // [{name, issuer, date, credential_id, url}]

            // Projets
            $table->json('projects')->nullable(); // [{name, description, technologies, url, date}]

            // Références
            $table->json('references')->nullable(); // [{name, position, company, email, phone}]

            // Hobbies/Intérêts
            $table->json('hobbies')->nullable();

            // Couleurs et personnalisation
            $table->json('customization')->nullable(); // {primary_color, secondary_color, font_family, layout_options}

            // PDF généré
            $table->string('pdf_path')->nullable(); // Chemin vers le PDF généré
            $table->timestamp('pdf_generated_at')->nullable(); // Date de génération du PDF

            // Visibilité
            $table->boolean('is_public')->default(false); // Visible pour les recruteurs
            $table->boolean('is_default')->default(false); // CV par défaut pour les candidatures

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('user_id');
            $table->index('template_type');
            $table->index('is_public');
            $table->index('is_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
