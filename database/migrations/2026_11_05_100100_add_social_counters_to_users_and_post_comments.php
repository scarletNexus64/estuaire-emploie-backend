<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Compteurs dénormalisés de la couche sociale v2 (défaut 0, aucun backfill :
 * user_follows et comment_likes sont créées vides). Mis à jour atomiquement
 * par App\Services\Social\SocialCounter, jamais sous 0.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['followers_count', 'following_count'] as $column) {
            if (! Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->unsignedInteger($column)->default(0);
                });
            }
        }

        if (! Schema::hasColumn('post_comments', 'likes_count')) {
            Schema::table('post_comments', function (Blueprint $table) {
                $table->unsignedInteger('likes_count')->default(0)->after('replies_count');
            });
        }
    }

    public function down(): void
    {
        foreach (['followers_count', 'following_count'] as $column) {
            if (Schema::hasColumn('users', $column)) {
                Schema::table('users', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }

        if (Schema::hasColumn('post_comments', 'likes_count')) {
            Schema::table('post_comments', function (Blueprint $table) {
                $table->dropColumn('likes_count');
            });
        }
    }
};
