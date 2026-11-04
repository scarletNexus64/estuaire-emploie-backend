<?php

namespace App\Services\Social;

use App\Models\PostLike;
use App\Models\PostRepost;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

/**
 * J'aime et republications. Bascules sûres en concurrence : contrainte
 * unique (target_type, target_id, user_id) + compteurs dénormalisés mis à jour
 * dans la même transaction, selon le nombre de lignes réellement
 * insérées/supprimées.
 */
class SocialInteractionService
{
    public function __construct(
        private readonly SocialNotifier $notifier,
        private readonly SocialContext $context,
    ) {
    }

    /** @return array{liked: bool, likes_count: int} */
    public function toggleLike(User $user, string $type, int $id): array
    {
        $table = SocialTarget::tableOf($type);

        $liked = DB::transaction(function () use ($user, $type, $id, $table) {
            $deleted = PostLike::where('target_type', $type)
                ->where('target_id', $id)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted > 0) {
                SocialCounter::decrement($table, $id, 'likes_count', $deleted);

                return false;
            }

            // Seules les publications en ligne acceptent un nouveau like
            // (retirer un like reste toujours possible).
            $item = SocialTarget::find($type, $id, true);

            try {
                PostLike::create(['user_id' => $user->id, 'target_type' => $type, 'target_id' => $id]);
            } catch (UniqueConstraintViolationException) {
                // Double appui concurrent : déjà aimé par l'autre requête.
                return true;
            }

            SocialCounter::increment($table, $id, 'likes_count');
            $this->notifyItemOwners(SocialNotifier::LIKE, $user, $type, $item);

            return true;
        }, 3); // 3 tentatives : interblocage (verrous de trou InnoDB) sur double appui

        $this->context->remember("liked:{$type}", $id, $liked);

        return [
            'liked' => $liked,
            'likes_count' => SocialCounter::value($table, $id, 'likes_count'),
        ];
    }

    public function likers(string $type, int $id, int $perPage): LengthAwarePaginator
    {
        SocialTarget::find($type, $id);

        return PostLike::query()
            ->where('target_type', $type)
            ->where('target_id', $id)
            ->with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /** @return array{reposted: bool, reposts_count: int} */
    public function repost(User $user, string $type, int $id, ?string $comment): array
    {
        $table = SocialTarget::tableOf($type);
        $comment = $comment !== null && trim($comment) !== '' ? trim($comment) : null;

        DB::transaction(function () use ($user, $type, $id, $table, $comment) {
            $item = SocialTarget::find($type, $id, true);

            if (SocialTarget::isOwner($user, $item)) {
                throw SocialException::unprocessable('social.errors.cannot_repost_own');
            }

            $existing = PostRepost::where('target_type', $type)
                ->where('target_id', $id)
                ->where('user_id', $user->id)
                ->first();

            if ($existing) {
                $existing->update(['comment' => $comment]);

                return;
            }

            try {
                PostRepost::create([
                    'user_id' => $user->id,
                    'target_type' => $type,
                    'target_id' => $id,
                    'comment' => $comment,
                ]);
            } catch (UniqueConstraintViolationException) {
                PostRepost::where('target_type', $type)
                    ->where('target_id', $id)
                    ->where('user_id', $user->id)
                    ->update(['comment' => $comment]);

                return;
            }

            SocialCounter::increment($table, $id, 'reposts_count');
            $this->notifyItemOwners(SocialNotifier::REPOST, $user, $type, $item);
        });

        $this->context->remember("reposted:{$type}", $id, true);

        return [
            'reposted' => true,
            'reposts_count' => SocialCounter::value($table, $id, 'reposts_count'),
        ];
    }

    /** @return array{reposted: bool, reposts_count: int} */
    public function unrepost(User $user, string $type, int $id): array
    {
        $table = SocialTarget::tableOf($type);
        SocialTarget::find($type, $id);

        DB::transaction(function () use ($user, $type, $id, $table) {
            $deleted = PostRepost::where('target_type', $type)
                ->where('target_id', $id)
                ->where('user_id', $user->id)
                ->delete();

            SocialCounter::decrement($table, $id, 'reposts_count', $deleted);
        });

        $this->context->remember("reposted:{$type}", $id, false);

        return [
            'reposted' => false,
            'reposts_count' => SocialCounter::value($table, $id, 'reposts_count'),
        ];
    }

    private function notifyItemOwners(string $notificationType, User $actor, string $type, Model $item): void
    {
        $this->notifier->notify(
            $notificationType,
            (int) $actor->id,
            SocialTarget::ownerUserIds($item),
            "{$type}:{$item->getKey()}",
            ['item_type' => $type, 'item_id' => (string) $item->getKey()],
            ['name' => (string) $actor->name, 'title' => SocialTarget::title($item)],
        );
    }
}
