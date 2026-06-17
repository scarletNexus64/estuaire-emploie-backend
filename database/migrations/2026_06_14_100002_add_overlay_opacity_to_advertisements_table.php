<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Opacité de l'overlay (0–100) appliqué au-dessus de l'image d'une annonce,
 * pour régler la lisibilité du texte. Ignoré quand il n'y a pas d'image
 * (c'est alors background_color qui prime).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            // `background_color` manque sur le schéma de prod (table advertisements
            // divergente). On la crée d'abord car la feature Marketing en dépend.
            if (!Schema::hasColumn('advertisements', 'background_color')) {
                $table->string('background_color')->default('#0277BD')->after('status');
            }
        });

        Schema::table('advertisements', function (Blueprint $table) {
            if (!Schema::hasColumn('advertisements', 'overlay_opacity')) {
                $table->unsignedTinyInteger('overlay_opacity')->default(60)->after('background_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn('overlay_opacity');
        });
    }
};
