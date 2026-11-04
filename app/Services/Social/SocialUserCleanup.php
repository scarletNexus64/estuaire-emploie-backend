<?php

namespace App\Services\Social;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Suppression DÉFINITIVE d'un utilisateur (forceDelete, ex. suppression de
 * compte) : les clés étrangères `cascadeOnDelete` effacent ses lignes
 * sociales (likes, commentaires + leurs réponses, republications,
 * abonnements, j'aime de commentaires) sans toucher aux compteurs
 * dénormalisés. On les corrige ici, AVANT la suppression (appelé depuis
 * l'événement `forceDeleting` de User, dans la même transaction).
 */
class SocialUserCleanup
{
    public static function forUser(int $userId): void
    {
        if (Schema::hasTable('post_likes')) {
            self::posts($userId);
        }
        if (Schema::hasTable('user_follows')) {
            self::network($userId);
        }
    }

    private static function posts(int $userId): void
    {
        foreach (['post_likes' => 'likes_count', 'post_reposts' => 'reposts_count'] as $table => $column) {
            $rows = DB::table($table)
                ->where('user_id', $userId)
                ->groupBy('target_type', 'target_id')
                ->selectRaw('target_type, target_id, COUNT(*) as aggregate')
                ->get();
            foreach ($rows as $row) {
                if (SocialTarget::isValidType($row->target_type)) {
                    SocialCounter::decrement(SocialTarget::tableOf($row->target_type), (int) $row->target_id, $column, (int) $row->aggregate);
                }
            }
        }

        // Commentaires visibles qui disparaissent : ceux de l'utilisateur +
        // les réponses (de quiconque) à ses commentaires racines (cascade
        // parent_id).
        $ownRoots = DB::table('post_comments')->select('id')->where('user_id', $userId)->whereNull('parent_id');
        $rows = DB::table('post_comments')
            ->whereNull('deleted_at')
            ->where(fn ($q) => $q->where('user_id', $userId)->orWhereIn('parent_id', $ownRoots))
            ->groupBy('target_type', 'target_id')
            ->selectRaw('target_type, target_id, COUNT(*) as aggregate')
            ->get();
        foreach ($rows as $row) {
            if (SocialTarget::isValidType($row->target_type)) {
                SocialCounter::decrement(SocialTarget::tableOf($row->target_type), (int) $row->target_id, 'comments_count', (int) $row->aggregate);
            }
        }

        // Ses réponses visibles sous les commentaires des autres.
        $rows = DB::table('post_comments as reply')
            ->join('post_comments as root', 'root.id', '=', 'reply.parent_id')
            ->where('reply.user_id', $userId)
            ->whereNull('reply.deleted_at')
            ->where('root.user_id', '!=', $userId)
            ->groupBy('reply.parent_id')
            ->selectRaw('reply.parent_id as parent_id, COUNT(*) as aggregate')
            ->get();
        foreach ($rows as $row) {
            SocialCounter::decrement('post_comments', (int) $row->parent_id, 'replies_count', (int) $row->aggregate);
        }

        $rows = DB::table('company_follows')->where('user_id', $userId)->pluck('company_id');
        foreach ($rows as $companyId) {
            SocialCounter::decrement('companies', (int) $companyId, 'followers_count');
        }
    }

    private static function network(int $userId): void
    {
        foreach (DB::table('user_follows')->where('follower_id', $userId)->pluck('followed_id') as $id) {
            SocialCounter::decrement('users', (int) $id, 'followers_count');
        }
        foreach (DB::table('user_follows')->where('followed_id', $userId)->pluck('follower_id') as $id) {
            SocialCounter::decrement('users', (int) $id, 'following_count');
        }

        if (Schema::hasTable('comment_likes')) {
            $rows = DB::table('comment_likes')->where('user_id', $userId)->pluck('comment_id');
            foreach ($rows as $commentId) {
                SocialCounter::decrement('post_comments', (int) $commentId, 'likes_count');
            }
        }
    }
}
