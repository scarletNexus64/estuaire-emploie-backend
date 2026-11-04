<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Couche sociale (style LinkedIn) : j'aime, commentaires, republications et
 * abonnements aux entreprises.
 *
 * Les publications ciblées sont polymorphes (`target_type` + `target_id`).
 * `target_type` contient un alias court et stable (`job`, `quick_service`),
 * PAS un nom de classe : la table `favorites` stocke des noms de classe et le
 * projet n'a pas de morph map globale (en ajouter une changerait ce que
 * `favorites` écrit). La correspondance alias ⇄ modèle vit à un seul endroit :
 * App\Services\Social\SocialTarget.
 *
 * Les contraintes uniques rendent les bascules (like, repost, follow) sûres
 * face aux doubles appuis concurrents.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('post_likes')) {
            Schema::create('post_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('target_type', 32);
                $table->unsignedBigInteger('target_id');
                $table->timestamps();

                $table->unique(['target_type', 'target_id', 'user_id'], 'post_likes_target_user_unique');
                $table->index(['user_id', 'target_type'], 'post_likes_user_type_index');
                $table->index(['target_type', 'created_at'], 'post_likes_type_created_index');
            });
        }

        if (! Schema::hasTable('post_comments')) {
            Schema::create('post_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('target_type', 32);
                $table->unsignedBigInteger('target_id');
                // Un seul niveau de réponses : parent_id pointe toujours vers
                // un commentaire racine.
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->text('body');
                $table->timestamp('edited_at')->nullable();
                $table->unsignedInteger('replies_count')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('parent_id')->references('id')->on('post_comments')->cascadeOnDelete();
                $table->index(['target_type', 'target_id', 'parent_id', 'created_at'], 'post_comments_target_parent_index');
                $table->index(['target_type', 'created_at'], 'post_comments_type_created_index');
            });
        }

        if (! Schema::hasTable('post_reposts')) {
            Schema::create('post_reposts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('target_type', 32);
                $table->unsignedBigInteger('target_id');
                $table->string('comment', 500)->nullable();
                $table->timestamps();

                $table->unique(['target_type', 'target_id', 'user_id'], 'post_reposts_target_user_unique');
                $table->index(['user_id', 'target_type'], 'post_reposts_user_type_index');
                $table->index(['created_at'], 'post_reposts_created_index');
            });
        }

        if (! Schema::hasTable('company_follows')) {
            Schema::create('company_follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['company_id', 'user_id'], 'company_follows_company_user_unique');
                $table->index(['user_id', 'created_at'], 'company_follows_user_created_index');
                $table->index(['company_id', 'created_at'], 'company_follows_company_created_index');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_follows');
        Schema::dropIfExists('post_reposts');
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('post_likes');
    }
};
