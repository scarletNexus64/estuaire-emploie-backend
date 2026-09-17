<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Profil d'études de l'étudiant dans l'espace Estuaire AI.
 *
 * Sans spécialité ni niveau, l'espace sert les 604 épreuves et les 6 837
 * supports de cours toutes filières confondues : l'étudiant n'y trouve pas ce
 * qui le concerne. Ces deux informations sont donc demandées une fois, à
 * l'entrée de l'espace, et servent ensuite de filtre à tous les contenus.
 *
 * La spécialité est stockée par son identifiant INSAM-IA *et* par son nom :
 * l'identifiant sert au filtrage des évaluations, le nom au filtrage des
 * épreuves, dont le champ `filiere` porte le libellé exact de la spécialité.
 * Conserver les deux évite un aller-retour au référentiel à chaque requête.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('insam_ia_category_id')->nullable()->after('id');
            $table->string('insam_ia_specialite')->nullable()->after('insam_ia_category_id');
            $table->string('insam_ia_filiere')->nullable()->after('insam_ia_specialite');

            // Année d'études : 1, 2 ou 3 — c'est le premier chiffre des codes
            // UE d'INSAM-IA, et l'`annee` que renvoient les supports de cours.
            $table->unsignedTinyInteger('insam_ia_niveau')->nullable()->after('insam_ia_filiere');

            $table->timestamp('insam_ia_profile_completed_at')->nullable()->after('insam_ia_niveau');

            $table->index('insam_ia_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['insam_ia_category_id']);
            $table->dropColumn([
                'insam_ia_category_id',
                'insam_ia_specialite',
                'insam_ia_filiere',
                'insam_ia_niveau',
                'insam_ia_profile_completed_at',
            ]);
        });
    }
};
