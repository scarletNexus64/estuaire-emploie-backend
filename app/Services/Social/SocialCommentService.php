<?php

namespace App\Services\Social;

use App\Models\CommentLike;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Commentaires (un seul niveau de réponses). `comments_count` de la
 * publication compte TOUS les commentaires visibles (racines + réponses) ;
 * `replies_count` d'un commentaire racine compte ses réponses visibles.
 */
class SocialCommentService
{
    private const PREVIEW_REPLIES = 2;

    public function __construct(
        private readonly SocialNotifier $notifier,
        private readonly SocialContext $context,
    ) {
    }

    /**
     * J'aime sur un commentaire (bascule). Retirer un j'aime reste possible
     * même si la publication n'est plus en ligne ; en ajouter un exige
     * qu'elle le soit.
     *
     * @return array{liked: bool, likes_count: int}
     */
    public function toggleLike(User $user, int $commentId): array
    {
        $comment = PostComment::find($commentId);
        if (! $comment) {
            throw SocialException::notFound('social.errors.comment_not_found');
        }

        $liked = DB::transaction(function () use ($user, $comment) {
            $deleted = CommentLike::where('comment_id', $comment->id)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted > 0) {
                SocialCounter::decrement('post_comments', (int) $comment->id, 'likes_count', $deleted);

                return false;
            }

            $item = SocialTarget::find($comment->target_type, (int) $comment->target_id, true);

            try {
                CommentLike::create(['comment_id' => $comment->id, 'user_id' => $user->id]);
            } catch (UniqueConstraintViolationException) {
                return true;
            }

            SocialCounter::increment('post_comments', (int) $comment->id, 'likes_count');

            $this->notifier->notify(
                SocialNotifier::COMMENT_LIKE,
                (int) $user->id,
                [(int) $comment->user_id],
                "comment:{$comment->id}",
                [
                    'item_type' => (string) $comment->target_type,
                    'item_id' => (string) $item->getKey(),
                    'comment_id' => (string) $comment->id,
                ],
                ['name' => (string) $user->name, 'title' => SocialTarget::title($item)],
            );

            return true;
        }, 3); // 3 tentatives : interblocage (verrous de trou InnoDB) sur double appui

        $this->context->remember('comment_liked', (int) $comment->id, $liked);

        return [
            'liked' => $liked,
            'likes_count' => SocialCounter::value('post_comments', (int) $comment->id, 'likes_count'),
        ];
    }

    /**
     * Commentaires racines, du plus récent au plus ancien, + aperçu des 2
     * premières réponses.
     *
     * @return array{0: LengthAwarePaginator, 1: Model} page + publication
     */
    public function topLevel(string $type, int $id, int $perPage): array
    {
        $item = SocialTarget::find($type, $id);

        $paginator = PostComment::query()
            ->where('target_type', $type)
            ->where('target_id', $id)
            ->whereNull('parent_id')
            ->with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])
            ->orderByDesc('id')
            ->paginate($perPage);

        $this->attachPreviewReplies($paginator->getCollection());

        return [$paginator, $item];
    }

    /**
     * Réponses d'un commentaire racine, de la plus ancienne à la plus récente.
     *
     * @return array{0: LengthAwarePaginator, 1: Model} page + publication
     */
    public function replies(int $commentId, int $perPage): array
    {
        $parent = PostComment::find($commentId);
        if (! $parent) {
            throw SocialException::notFound('social.errors.comment_not_found');
        }
        $item = SocialTarget::find($parent->target_type, (int) $parent->target_id);

        $paginator = PostComment::query()
            ->where('parent_id', $parent->id)
            ->with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])
            ->orderBy('id')
            ->paginate($perPage);

        return [$paginator, $item];
    }

    /** @return array{0: PostComment, 1: int, 2: Model} commentaire créé, comments_count, publication */
    public function create(User $user, string $type, int $id, string $body, ?int $parentId): array
    {
        $table = SocialTarget::tableOf($type);

        [$comment, $item] = DB::transaction(function () use ($user, $type, $id, $body, $parentId, $table) {
            $item = SocialTarget::find($type, $id, true);

            $parent = null;
            if ($parentId !== null) {
                $parent = PostComment::where('id', $parentId)
                    ->where('target_type', $type)
                    ->where('target_id', $id)
                    ->lockForUpdate()
                    ->first();
                if (! $parent) {
                    throw SocialException::unprocessable('social.errors.parent_not_found');
                }
                // Un seul niveau : répondre à une réponse = répondre à sa racine.
                if ($parent->parent_id !== null) {
                    $parent = PostComment::where('id', $parent->parent_id)->lockForUpdate()->first();
                    if (! $parent) {
                        throw SocialException::unprocessable('social.errors.parent_not_found');
                    }
                }
            }

            $comment = PostComment::create([
                'user_id' => $user->id,
                'target_type' => $type,
                'target_id' => $id,
                'parent_id' => $parent?->id,
                'body' => $body,
            ]);

            SocialCounter::increment($table, $id, 'comments_count');
            if ($parent) {
                SocialCounter::increment('post_comments', (int) $parent->id, 'replies_count');
            }

            $this->notifyComment($user, $type, $item, $comment, $parent);

            return [$comment, $item];
        });

        $comment->setRelation('user', $user);
        $comment->replies_count = 0;
        $comment->likes_count = 0;
        $this->context->remember('comment_liked', (int) $comment->id, false);

        return [$comment, SocialCounter::value($table, $id, 'comments_count'), $item];
    }

    public function update(User $user, int $commentId, string $body): array
    {
        $comment = PostComment::with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])->find($commentId);
        if (! $comment) {
            throw SocialException::notFound('social.errors.comment_not_found');
        }
        if ((int) $comment->user_id !== (int) $user->id) {
            throw SocialException::forbidden('social.errors.not_comment_author');
        }

        // Publication vérifiée AVANT d'écrire : une 404 ne doit pas laisser
        // une modification enregistrée.
        $item = SocialTarget::find($comment->target_type, (int) $comment->target_id);

        if ($comment->body !== $body) {
            $comment->forceFill(['body' => $body, 'edited_at' => now()])->save();
        }

        return [$comment, $item];
    }

    /**
     * Suppression (douce) par l'auteur ou un modérateur (membre de
     * l'entreprise de l'offre / auteur du service). Supprimer un commentaire
     * racine supprime ses réponses ; les compteurs suivent.
     *
     * @return int comments_count de la publication
     */
    public function delete(User $user, int $commentId): int
    {
        $comment = PostComment::find($commentId);
        if (! $comment) {
            throw SocialException::notFound('social.errors.comment_not_found');
        }

        $type = $comment->target_type;
        $targetId = (int) $comment->target_id;
        $table = SocialTarget::tableOf($type);
        $item = SocialTarget::TYPES[$type]::withTrashed()->find($targetId);

        $isAuthor = (int) $comment->user_id === (int) $user->id;
        if (! $isAuthor && ! ($item && SocialTarget::isOwner($user, $item))) {
            throw SocialException::forbidden('social.errors.forbidden');
        }

        DB::transaction(function () use ($commentId, $table, $targetId) {
            // Relu sous verrou : deux suppressions concurrentes ne
            // décrémentent qu'une fois.
            $comment = PostComment::whereKey($commentId)->lockForUpdate()->first();
            if (! $comment) {
                return;
            }

            $removed = 1;
            if ($comment->parent_id === null) {
                $removed += PostComment::where('parent_id', $comment->id)->delete();
            } else {
                SocialCounter::decrement('post_comments', (int) $comment->parent_id, 'replies_count');
            }

            $comment->delete();
            SocialCounter::decrement($table, $targetId, 'comments_count', $removed);
        });

        return SocialCounter::value($table, $targetId, 'comments_count');
    }

    /**
     * Utilisateurs affichés comme « Auteur » sous la publication.
     *
     * @return int[]
     */
    public function memberIds(Model $item): array
    {
        return SocialTarget::ownerUserIds($item);
    }

    /** Le visiteur peut-il modérer (supprimer) les commentaires de la publication ? */
    public function canModerate(?int $viewerId, array $memberIds): bool
    {
        return $viewerId !== null && in_array($viewerId, $memberIds, true);
    }

    /**
     * Charge, en UNE requête, les 2 plus anciennes réponses de chaque
     * commentaire racine de la page (relation `previewReplies`).
     */
    private function attachPreviewReplies(Collection $comments): void
    {
        $parentIds = $comments->filter(fn (PostComment $c) => (int) $c->replies_count > 0)
            ->pluck('id')
            ->all();

        $byParent = collect();

        if ($parentIds !== []) {
            $union = null;
            foreach ($parentIds as $parentId) {
                $part = DB::table('post_comments')
                    ->select('id')
                    ->where('parent_id', $parentId)
                    ->whereNull('deleted_at')
                    ->orderBy('id')
                    ->limit(self::PREVIEW_REPLIES);
                $union = $union ? $union->unionAll($part) : $part;
            }

            $ids = $union->pluck('id')->all();

            $byParent = PostComment::query()
                ->whereIn('id', $ids)
                ->with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])
                ->orderBy('id')
                ->get()
                ->groupBy('parent_id');
        }

        foreach ($comments as $comment) {
            $comment->setRelation('previewReplies', $byParent->get($comment->id, new Collection())->values());
        }
    }

    private function notifyComment(User $actor, string $type, Model $item, PostComment $comment, ?PostComment $parent): void
    {
        $data = [
            'item_type' => $type,
            'item_id' => (string) $item->getKey(),
            'comment_id' => (string) $comment->id,
        ];
        $params = [
            'name' => (string) $actor->name,
            'title' => SocialTarget::title($item),
            'excerpt' => Str::limit(preg_replace('/\s+/', ' ', $comment->body), 80),
        ];

        $replyRecipient = null;
        if ($parent && (int) $parent->user_id !== (int) $actor->id) {
            $replyRecipient = (int) $parent->user_id;
            $this->notifier->notify(SocialNotifier::REPLY, (int) $actor->id, [$replyRecipient], "comment:{$comment->id}", $data, $params);
        }

        $owners = array_values(array_filter(
            SocialTarget::ownerUserIds($item),
            fn (int $id) => $id !== $replyRecipient
        ));
        $this->notifier->notify(SocialNotifier::COMMENT, (int) $actor->id, $owners, "comment:{$comment->id}", $data, $params);
    }
}
