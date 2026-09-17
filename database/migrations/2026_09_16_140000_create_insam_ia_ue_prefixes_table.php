<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Correspondance entre les préfixes de codes UE et les filières d'INSAM-IA.
 *
 * La bibliothèque de cours n'expose qu'un `ue_code` (« IGL111 ») : ni
 * spécialité, ni filière. Impossible donc de filtrer les cours d'un étudiant
 * sans savoir à quoi « IGL » se rattache.
 *
 * Seul `POST /api/external/course-materials` porte cette information, code par
 * code. Cette table en conserve le résultat pour les 237 préfixes du
 * catalogue, alimentée par `insam-ia:sync-ue-prefixes`.
 *
 * Deux libellés y coexistent volontairement :
 *  - `filiere_label` : la valeur brute d'INSAM-IA, en français ou en anglais,
 *    à des granularités variables (« GENIE INFORMATIQUE », « MANAGEMENT »…) ;
 *  - `category_id` / `category_name` : la spécialité du référentiel
 *    `/api/public/categories`, quand le rapprochement a pu être fait.
 *
 * Le rapprochement n'aboutit que pour une partie des préfixes — les deux
 * référentiels ne se recouvrent pas. C'est assumé : à défaut de spécialité, le
 * filtrage retombe sur `filiere_label`, ce qui vaut mieux qu'un écran vide.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insam_ia_ue_prefixes', function (Blueprint $table) {
            $table->id();

            // Préfixe alphabétique du code UE : « IGL », « BAT », « SFM »…
            $table->string('prefix', 8)->unique();

            // Libellé brut renvoyé par INSAM-IA pour ce préfixe.
            $table->string('filiere_label')->nullable();

            // Spécialité du référentiel, si le rapprochement a abouti.
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();

            // Nombre de supports observés : sert à départager un préfixe vu
            // sous plusieurs libellés, le plus fréquent l'emportant.
            $table->unsignedInteger('documents_count')->default(0);

            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index('category_id');
            $table->index('filiere_label');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insam_ia_ue_prefixes');
    }
};
