<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Compteurs sociaux dénormalisés (évitent tout COUNT par carte dans les
 * listes). Défaut 0 : les lignes existantes sont valides sans backfill (les
 * tables sociales sont créées vides par la migration précédente). Mis à jour
 * atomiquement par App\Services\Social\SocialCounter, jamais sous 0.
 */
return new class extends Migration
{
    private const POST_COUNTERS = ['likes_count', 'comments_count', 'reposts_count'];

    public function up(): void
    {
        foreach (['jobs', 'quick_services'] as $tableName) {
            foreach (self::POST_COUNTERS as $column) {
                if (! Schema::hasColumn($tableName, $column)) {
                    Schema::table($tableName, function (Blueprint $table) use ($column) {
                        $table->unsignedInteger($column)->default(0);
                    });
                }
            }
        }

        if (! Schema::hasColumn('companies', 'followers_count')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->unsignedInteger('followers_count')->default(0);
            });
        }
    }

    public function down(): void
    {
        foreach (['jobs', 'quick_services'] as $tableName) {
            foreach (self::POST_COUNTERS as $column) {
                if (Schema::hasColumn($tableName, $column)) {
                    Schema::table($tableName, function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }

        if (Schema::hasColumn('companies', 'followers_count')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('followers_count');
            });
        }
    }
};
