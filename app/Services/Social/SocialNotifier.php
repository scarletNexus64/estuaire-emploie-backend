<?php

namespace App\Services\Social;

use App\Jobs\SendSocialNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Notifications push de la couche sociale.
 *
 * - Envoi APRÈS commit (DB::afterCommit) via un job de queue
 *   (SendSocialNotification, queue `notifications`) qui appelle
 *   NotificationService::sendToUser (historique en base + FCM) : la requête
 *   ne paie pas la latence FCM, et un échec push ne la fait jamais échouer.
 * - Jamais à l'acteur lui-même.
 * - Anti-spam (post_like, post_repost, company_follow, user_follow,
 *   comment_like) : au plus un push par
 *   (type, acteur, publication) sur 24 h, via un verrou de cache posé par
 *   Cache::add (atomique sur le store `database`/`redis`) — un
 *   « je n'aime plus / j'aime » ne renotifie pas. Les commentaires et
 *   réponses notifient à chaque fois.
 */
class SocialNotifier
{
    public const LIKE = 'post_like';
    public const COMMENT = 'post_comment';
    public const REPLY = 'comment_reply';
    public const REPOST = 'post_repost';
    public const FOLLOW = 'company_follow';
    public const USER_FOLLOW = 'user_follow';
    public const COMMENT_LIKE = 'comment_like';

    private const THROTTLED = [self::LIKE, self::REPOST, self::FOLLOW, self::USER_FOLLOW, self::COMMENT_LIKE];

    private const THROTTLE_SECONDS = 86400;

    /**
     * @param  int[]  $recipientIds
     * @param  array<string, string>  $data  données FCM (valeurs string)
     * @param  array<string, string>  $params  paramètres des traductions
     */
    public function notify(string $type, int $actorId, array $recipientIds, string $subjectKey, array $data, array $params): void
    {
        $recipientIds = array_values(array_unique(array_filter(
            array_map('intval', $recipientIds),
            fn (int $id) => $id > 0 && $id !== $actorId
        )));

        if ($recipientIds === []) {
            return;
        }

        DB::afterCommit(function () use ($type, $actorId, $recipientIds, $subjectKey, $data, $params) {
            try {
                if (in_array($type, self::THROTTLED, true)) {
                    $key = "social:push:{$type}:{$actorId}:{$subjectKey}";
                    if (! Cache::add($key, 1, self::THROTTLE_SECONDS)) {
                        return;
                    }
                }

                SendSocialNotification::dispatch(
                    $type,
                    $recipientIds,
                    array_map(fn ($v) => (string) $v, $data),
                    array_map(fn ($v) => (string) $v, $params),
                );
            } catch (\Throwable $e) {
                Log::error('Push social non mis en file', [
                    'type' => $type,
                    'actor_id' => $actorId,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
}
