<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rattache les fiches de révision au cours dont elles sont tirées.
 *
 * Jusqu'ici une fiche n'était rattachée qu'à une filière (`category_id`), ce
 * qui produisait des fiches génériques sans rapport avec ce que l'étudiant
 * lit réellement. Les fiches sont désormais générées depuis un support de
 * cours précis, via l'assistant de cours d'INSAM-IA.
 *
 * Ces fiches-là n'existent pas dans le référentiel distant : elles sont
 * produites à la demande et n'ont donc pas de `remote_id`. La colonne devient
 * nullable, et l'unicité est reportée sur le couple (cours, étudiant) pour
 * qu'un même étudiant ne stocke qu'une fiche par cours.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insam_ia_revision_cards', function (Blueprint $table) {
            // Support de cours d'origine, côté INSAM-IA. Pas de FK : le
            // référentiel des cours vit chez le tiers.
            $table->unsignedBigInteger('document_id')->nullable()->after('category_filiere');
            $table->string('document_title')->nullable()->after('document_id');
            $table->string('ue_code', 32)->nullable()->after('document_title');
            $table->string('ue_nom')->nullable()->after('ue_code');

            // Une fiche tirée d'un cours appartient à l'étudiant qui l'a
            // demandée : deux étudiants peuvent avoir leur propre fiche sur
            // le même cours, chacune tenant compte de leurs lacunes.
            $table->index(['document_id', 'generated_by'], 'insam_ia_cards_document_owner');
            $table->index('ue_code', 'insam_ia_cards_ue_code');
        });

        // `remote_id` n'a plus de sens pour une fiche produite à la demande.
        // On le rend nullable en conservant l'unicité pour celles qui en ont
        // un (MySQL autorise plusieurs NULL sous une contrainte unique).
        Schema::table('insam_ia_revision_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('remote_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('insam_ia_revision_cards', function (Blueprint $table) {
            $table->dropIndex('insam_ia_cards_document_owner');
            $table->dropIndex('insam_ia_cards_ue_code');
            $table->dropColumn(['document_id', 'document_title', 'ue_code', 'ue_nom']);
        });

        // Les fiches sans `remote_id` sont propres au nouveau mode de
        // génération : elles disparaissent avec lui.
        \Illuminate\Support\Facades\DB::table('insam_ia_revision_cards')->whereNull('remote_id')->delete();

        Schema::table('insam_ia_revision_cards', function (Blueprint $table) {
            $table->unsignedBigInteger('remote_id')->nullable(false)->change();
        });
    }
};
