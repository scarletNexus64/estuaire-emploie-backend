<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Couche sociale v2 : abonnements entre utilisateurs (onglet « Réseau ») et
 * j'aime sur les commentaires.
 *
 * Contraintes uniques = bascules sûres face aux doubles appuis concurrents.
 * L'auto-abonnement (follower_id = followed_id) est refusé côté application
 * (MySQL interdit un CHECK sur des colonnes portant une FK en cascade).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user_follows')) {
            Schema::create('user_follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('followed_id')->constrained('users')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['follower_id', 'followed_id'], 'user_follows_pair_unique');
                // Abonnés d'un utilisateur (liste, nouveaux abonnés 7 j,
                // abonnés en commun).
                $table->index(['followed_id', 'follower_id'], 'user_follows_followed_follower_index');
                $table->index(['followed_id', 'created_at'], 'user_follows_followed_created_index');
            });
        }

        if (! Schema::hasTable('comment_likes')) {
            Schema::create('comment_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('comment_id')->constrained('post_comments')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['comment_id', 'user_id'], 'comment_likes_comment_user_unique');
                $table->index(['user_id', 'comment_id'], 'comment_likes_user_comment_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_likes');
        Schema::dropIfExists('user_follows');
    }
};
