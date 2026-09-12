<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Miroir local des fiches de révision INSAM-IA.
 *
 * La génération d'une fiche coûte ~45 s de calcul chez INSAM-IA : une fois le
 * contenu reçu, on le conserve chez nous pour le resservir instantanément et
 * garder l'espace étudiant consultable quand le service tiers est indisponible.
 *
 * `remote_id` est l'identifiant de la fiche chez INSAM-IA : il reste la clé
 * d'échange avec l'API distante et avec l'application, qui continue de
 * référencer les fiches par cet id (progression de lecture incluse).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insam_ia_revision_cards', function (Blueprint $table) {
            $table->id();

            // Identifiant de la fiche chez INSAM-IA. Unique : une même fiche
            // distante n'est stockée qu'une fois, les régénérations mettant à
            // jour la ligne existante.
            $table->unsignedBigInteger('remote_id')->unique();

            $table->string('title');
            $table->text('summary')->nullable();
            $table->json('key_points')->nullable();
            // Markdown complet de la fiche : c'est lui qui coûte cher à produire.
            $table->longText('content')->nullable();

            $table->string('status', 32)->nullable();
            $table->string('source')->nullable();

            // Catégorie INSAM-IA (filière) d'origine. Pas de clé étrangère :
            // le référentiel des catégories vit chez le service tiers.
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->string('category_filiere')->nullable();

            // Étudiant à l'origine de la demande de génération, quand la fiche
            // vient de chez nous. Null pour une fiche seulement synchronisée.
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();

            // `updated_at` distant, pour ne réécrire que sur du contenu plus frais.
            $table->timestamp('remote_updated_at')->nullable();
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            $table->index(['category_id', 'remote_updated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insam_ia_revision_cards');
    }
};
