<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la redirection et la notion de « bannière par défaut » aux publicités.
 *
 * Jusqu'ici une bannière n'avait aucune destination : POST /advertisements/{id}/click
 * ne faisait qu'incrémenter un compteur, et l'application affichait un simple
 * message au clic. Les colonnes `redirect_*` décrivent où envoyer l'utilisateur.
 *
 * Les bannières par défaut (`is_default`) sont le carrousel de repli diffusé
 * lorsqu'aucune campagne payante n'est disponible pour l'audience. Elles étaient
 * codées en dur côté application ; elles deviennent des données pilotables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisements', 'redirect_type')) {
                // none          : bannière purement informative (aucun clic utile)
                // internal_route: route interne de l'app mobile (ex. /search)
                // external_url  : URL http(s) ouverte hors de l'app
                // deeplink      : deeplink applicatif (estuaireemploi://...)
                // whatsapp      : ouvre une conversation WhatsApp pré-remplie
                $table->enum('redirect_type', [
                    'none',
                    'internal_route',
                    'external_url',
                    'deeplink',
                    'whatsapp',
                ])->default('none')->after('ad_type');
            }

            if (!Schema::hasColumn('advertisements', 'redirect_target')) {
                // Route, URL, deeplink ou numéro WhatsApp selon `redirect_type`.
                // 2048 caractères pour absorber les URL longues (tracking, UTM).
                $table->string('redirect_target', 2048)->nullable()->after('redirect_type');
            }

            if (!Schema::hasColumn('advertisements', 'redirect_params')) {
                // Arguments passés à la destination, ex. {"contract_type_slug":"stage"}
                // pour pré-filtrer un écran de liste.
                $table->json('redirect_params')->nullable()->after('redirect_target');
            }

            if (!Schema::hasColumn('advertisements', 'slug')) {
                // Identifiant stable des bannières par défaut, utilisé par le
                // seeder pour rester idempotent. Nul pour les campagnes payantes.
                $table->string('slug')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('advertisements', 'is_default')) {
                $table->boolean('is_default')->default(false)->after('is_active');
            }

            if (!Schema::hasColumn('advertisements', 'default_order')) {
                // Ordre de défilement propre au carrousel de repli.
                $table->unsignedInteger('default_order')->default(0)->after('is_default');
            }
        });

        // Index de sélection du carrousel de repli (cf. Advertisement::scopeDefaults).
        // Créé à part : Schema::table ne permet pas de tester l'existence d'un index.
        $indexExists = collect(Schema::getIndexes('advertisements'))
            ->contains(fn ($index) => $index['name'] === 'advertisements_default_idx');

        if (!$indexExists) {
            Schema::table('advertisements', function (Blueprint $table) {
                $table->index(['is_default', 'is_active', 'default_order'], 'advertisements_default_idx');
            });
        }
    }

    public function down(): void
    {
        $indexExists = collect(Schema::getIndexes('advertisements'))
            ->contains(fn ($index) => $index['name'] === 'advertisements_default_idx');

        if ($indexExists) {
            Schema::table('advertisements', function (Blueprint $table) {
                $table->dropIndex('advertisements_default_idx');
            });
        }

        Schema::table('advertisements', function (Blueprint $table) {
            foreach (['default_order', 'is_default', 'redirect_params', 'redirect_target', 'redirect_type'] as $column) {
                if (Schema::hasColumn('advertisements', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('advertisements', 'slug')) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }
        });
    }
};
