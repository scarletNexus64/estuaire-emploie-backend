<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationService;
use App\Services\Social\SocialNotifier;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Push + historique d'une interaction sociale (like, commentaire, réponse,
 * republication, abonnement entreprise/utilisateur, j'aime de commentaire). Textes traduits dans la langue de chaque
 * destinataire (users.locale, fr par défaut). Voir SocialNotifier.
 *
 * Une seule tentative : sendToUser enregistre la notification en base, un
 * retry la dupliquerait.
 */
class SendSocialNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 1;

    public $timeout = 60;

    /**
     * @param  int[]  $recipientIds
     * @param  array<string, string>  $data
     * @param  array<string, string>  $params  name, title, excerpt, company, item (« offre »/« service »)
     */
    public function __construct(
        public string $type,
        public array $recipientIds,
        public array $data,
        public array $params,
    ) {
        $this->onQueue('notifications');
        $this->afterCommit();
    }

    public function handle(NotificationService $notifications): void
    {
        $users = User::whereIn('id', $this->recipientIds)
            ->get(['id', 'name', 'fcm_token', 'locale']);

        foreach ($users as $user) {
            try {
                $locale = $this->localeFor($user);
                [$title, $message] = $this->texts($locale);

                $notifications->sendToUser($user, $title, $message, $this->type, $this->data);
            } catch (\Throwable $e) {
                Log::error('Push social échoué', [
                    'type' => $this->type,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function localeFor(User $user): string
    {
        $locale = is_string($user->locale ?? null) ? strtolower(substr($user->locale, 0, 2)) : '';

        return in_array($locale, ['fr', 'en', 'es', 'ar'], true) ? $locale : 'fr';
    }

    /** @return array{0: string, 1: string} */
    private function texts(string $locale): array
    {
        $params = $this->params;
        $itemKind = ($this->data['item_type'] ?? '') === 'quick_service' ? 'service' : 'job';
        $params['title'] = Str::limit($params['title'] ?? '', 80);
        $params['excerpt'] = Str::limit($params['excerpt'] ?? '', 80);

        $key = match ($this->type) {
            SocialNotifier::LIKE => "like_{$itemKind}",
            SocialNotifier::COMMENT => 'comment',
            SocialNotifier::REPLY => 'reply',
            SocialNotifier::REPOST => 'repost',
            SocialNotifier::FOLLOW => 'follow',
            SocialNotifier::USER_FOLLOW => 'user_follow',
            SocialNotifier::COMMENT_LIKE => 'comment_like',
            default => 'comment',
        };

        return [
            __("social.push.{$key}.title", $params, $locale),
            __("social.push.{$key}.body", $params, $locale),
        ];
    }
}
