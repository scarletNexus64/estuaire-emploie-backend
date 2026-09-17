<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Espace de travail « épreuves » de l'étudiant, conservé chez Estuaire.
 *
 * Trois besoins qu'INSAM-IA ne couvre pas :
 *
 *  - les épreuves générées par l'IA n'y sont pas listables (la route de
 *    listing n'existe pas, `generated` étant capturé par `/exams/{id}`) : sans
 *    miroir local, l'étudiant perd son sujet en quittant l'écran ;
 *  - les sujets déposés par les étudiants (« enrichir la banque ») restent
 *    chez nous, l'upload distant étant hors service ;
 *  - la progression est suivie ici, le `type` attendu par l'API distante
 *    n'étant pas documenté.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Épreuves produites par l'IA. `remote_id` reste la clé d'échange avec
        // INSAM-IA : c'est lui qu'attend la suppression distante.
        Schema::create('insam_ia_generated_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Null si la génération distante n'a pas renvoyé d'identifiant :
            // le sujet reste consultable, seule la suppression chez eux est
            // alors impossible.
            $table->unsignedBigInteger('remote_id')->nullable()->index();

            $table->string('matiere');
            $table->string('filiere')->nullable();
            $table->string('niveau')->nullable();
            $table->string('difficulte', 32)->nullable();
            $table->unsignedSmallInteger('nombre')->nullable();

            // Markdown complet du sujet : ~20 s de calcul, on ne le redemande pas.
            $table->longText('content');

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Sujets déposés par les étudiants pour enrichir la banque commune.
        Schema::create('insam_ia_exam_contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('matiere');
            $table->string('filiere')->nullable();
            $table->string('niveau')->nullable();
            $table->string('annee', 16)->nullable();

            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedInteger('file_size')->nullable();

            // Texte extrait à l'envoi : il sert la recherche et évite de
            // relire le PDF à chaque consultation.
            $table->longText('extracted_text')->nullable();

            // Un dépôt n'est visible des autres étudiants qu'une fois validé :
            // la banque commune ne peut pas accepter n'importe quel fichier.
            $table->string('status', 32)->default('pending')->index();
            $table->string('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });

        // Progression : une ligne par activité terminée.
        Schema::create('insam_ia_course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Activité à l'origine du score : correction de copie, épreuve
            // générée, évaluation. Contrôlé applicativement pour rester
            // extensible sans migration.
            $table->string('type', 32)->index();
            $table->string('subject');
            $table->string('title');

            // Note ramenée sur 20, comparable d'une activité à l'autre.
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2)->default(20);

            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'created_at']);
            $table->index(['user_id', 'subject']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insam_ia_course_progress');
        Schema::dropIfExists('insam_ia_exam_contributions');
        Schema::dropIfExists('insam_ia_generated_exams');
    }
};
