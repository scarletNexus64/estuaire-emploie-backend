<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Parcours d'apprentissage INSAM-IA de l'espace étudiant.
 *
 * Le contenu (épreuves, fiches de révision, QCM) reste hébergé chez INSAM-IA :
 * on ne duplique ici que ce qui appartient à Estuaire — la progression de
 * lecture de l'étudiant, ses tentatives d'évaluation et l'attestation qui en
 * découle, générée en PDF par nos soins.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Progression de lecture d'une ressource INSAM-IA (fiche de révision,
        // sujet d'épreuve). La ressource est identifiée par son type et son id
        // distant : aucune clé étrangère possible vers un contenu externe.
        Schema::create('insam_ia_reading_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('resource_type', ['revision_card', 'exam', 'category']);
            $table->unsignedBigInteger('resource_id');
            $table->string('resource_title')->nullable();

            // Catégorie INSAM-IA (filière) à laquelle la ressource se rattache,
            // pour calculer la progression d'un parcours complet.
            $table->unsignedBigInteger('category_id')->nullable();

            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'resource_type', 'resource_id'], 'insam_ia_progress_unique');
            $table->index(['user_id', 'category_id']);
        });

        // Tentative d'évaluation (QCM généré par INSAM-IA). On conserve le
        // résultat côté Estuaire : il conditionne la délivrance de l'attestation
        // et doit rester consultable même si INSAM-IA est indisponible.
        Schema::create('insam_ia_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Identifiants distants de la session et de la tentative.
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('remote_attempt_id')->nullable();

            $table->string('session_title')->nullable();
            $table->string('specialite')->nullable();
            $table->string('matiere')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->nullable();

            $table->enum('status', ['started', 'submitted', 'failed'])->default('started');

            $table->unsignedSmallInteger('score')->nullable();
            $table->unsignedSmallInteger('total')->nullable();
            $table->unsignedTinyInteger('percentage')->nullable();

            // Questions servies puis corrections reçues, conservées pour
            // réafficher la tentative sans dépendre du service tiers.
            $table->json('questions')->nullable();
            $table->json('corrections')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'session_id']);
        });

        // Attestation de fin de parcours, générée en PDF par Estuaire à partir
        // de la note obtenue. La référence est publique et vérifiable.
        Schema::create('insam_ia_attestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attempt_id')
                ->nullable()
                ->constrained('insam_ia_attempts')
                ->nullOnDelete();

            $table->string('reference')->unique();
            $table->string('title');
            $table->string('specialite')->nullable();

            $table->unsignedSmallInteger('score');
            $table->unsignedSmallInteger('total');
            $table->unsignedTinyInteger('percentage');
            // Mention dérivée du pourcentage au moment de la délivrance :
            // figée, pour qu'un changement de barème ne réécrive pas le passé.
            $table->string('mention', 40);

            $table->string('pdf_path')->nullable();
            $table->timestamp('issued_at');

            $table->timestamps();

            $table->index(['user_id', 'issued_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insam_ia_attestations');
        Schema::dropIfExists('insam_ia_attempts');
        Schema::dropIfExists('insam_ia_reading_progress');
    }
};
