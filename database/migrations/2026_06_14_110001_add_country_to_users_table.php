<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pays de résidence du user (code ISO 3166-1 alpha-2, ex: CM).
 * Sert au ciblage géographique des annonces Marketing Digital : un user
 * ne voit que les annonces destinées à son pays (ou à tous les pays).
 * Default 'CM' : les comptes existants deviennent Cameroun automatiquement.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('country', 2)->default('CM')->after('locale');
            $table->index('country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['country']);
            $table->dropColumn('country');
        });
    }
};
