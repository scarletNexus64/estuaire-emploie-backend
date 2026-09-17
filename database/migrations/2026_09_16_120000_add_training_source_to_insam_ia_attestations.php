<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ouvre les attestations aux formations vidéo.
 *
 * Une attestation n'était délivrée que sur une tentative de QCM. Elle peut
 * désormais sanctionner l'achèvement d'une formation vidéo : `source`
 * distingue les deux origines, `formation_id` porte la formation concernée.
 *
 * `attempt_id` était déjà nullable ; le couple (source, formation_id) garantit
 * qu'une formation ne donne qu'une attestation par étudiant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insam_ia_attestations', function (Blueprint $table) {
            // 'evaluation' : QCM réussi (comportement historique).
            // 'training'   : formation vidéo achevée.
            $table->string('source', 24)->default('evaluation')->after('attempt_id');

            $table->unsignedBigInteger('formation_id')->nullable()->after('source');
            $table->unsignedSmallInteger('videos_total')->nullable()->after('formation_id');

            $table->unique(['user_id', 'formation_id'], 'insam_ia_attestations_formation_unique');
        });
    }

    public function down(): void
    {
        Schema::table('insam_ia_attestations', function (Blueprint $table) {
            $table->dropUnique('insam_ia_attestations_formation_unique');
            $table->dropColumn(['source', 'formation_id', 'videos_total']);
        });
    }
};
