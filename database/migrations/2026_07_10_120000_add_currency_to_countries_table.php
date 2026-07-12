<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute la devise (ISO 4217, ex: XAF, EUR, NGN) à la table de référence
 * des pays. Utilisée pour pré-remplir automatiquement users.preferred_currency
 * lorsqu'un utilisateur choisit son pays à l'inscription ou dans ses réglages.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            // ISO 4217 (3 lettres). Nullable : les pays non seedés restent sans devise.
            $table->string('currency', 3)->nullable()->after('flag');
        });
    }

    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
