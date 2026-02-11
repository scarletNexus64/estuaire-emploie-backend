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
        Schema::create('exam_papers', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Titre de l'épreuve
            $table->string('specialty'); // Spécialité (Informatique, Gestion, etc.)
            $table->string('subject'); // Matière (Mathématiques, Français, etc.)
            $table->integer('level')->default(1); // Niveau 1 à 5
            $table->integer('year')->nullable(); // Année de l'épreuve (ex: 2024)
            $table->boolean('is_correction')->default(false); // Est-ce un corrigé ?
            $table->text('description')->nullable(); // Description optionnelle

            // Fichier PDF
            $table->string('file_path'); // Chemin du fichier PDF
            $table->string('file_name'); // Nom original du fichier
            $table->bigInteger('file_size')->nullable(); // Taille en bytes

            // Statistiques
            $table->integer('downloads_count')->default(0); // Nombre de téléchargements
            $table->integer('views_count')->default(0); // Nombre de vues

            // Gestion
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index(['specialty', 'subject', 'level']);
            $table->index(['is_active', 'display_order']);
            $table->index('is_correction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_papers');
    }
};
