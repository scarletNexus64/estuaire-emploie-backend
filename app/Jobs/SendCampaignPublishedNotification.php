<?php

namespace App\Jobs;

use App\Models\Advertisement;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

/**
 * Notifie la publication d'une campagne Marketing Digital.
 *
 * Stratégie : envoi au TOPIC FCM correspondant à l'audience ciblée (un seul
 * appel Firebase, aucune boucle sur les tokens => pas de timeout/500), PLUS une
 * notification directe à l'annonceur lui-même (token + persistance BDD) afin
 * qu'il reçoive « Consultez votre annonce… » même s'il n'est pas dans le topic ciblé.
 */
class SendCampaignPublishedNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 120;

    /** Mapping audience => topic FCM (cohérent avec l'abonnement côté app). */
    public const AUDIENCE_TOPICS = [
        'all' => 'all_users',
        'student' => 'role_student',
        'candidate' => 'role_candidate',
        'recruiter' => 'role_recruiter',
    ];

    public function __construct(public int $advertisementId)
    {
        $this->onQueue('notifications');
    }

    public function handle(
        FirebaseNotificationService $firebaseService,
        NotificationService $notificationService
    ): void {
        $ad = Advertisement::with('company')->find($this->advertisementId);
        if (!$ad) {
            Log::warning('[CampaignNotif] Advertisement introuvable', ['id' => $this->advertisementId]);
            return;
        }

        $companyName = $ad->company->name ?? 'une entreprise';

        $title = $ad->title;
        $body = "Annonce sponsorisée proposée par {$companyName}";

        $data = [
            'type' => 'campaign',
            'advertisement_id' => (string) $ad->id,
            'company_name' => $companyName,
            'content_type' => (string) $ad->content_type,
        ];

        // Ciblage géographique : si l'annonce vise des pays précis, on ne peut
        // pas utiliser un topic FCM (les topics ignorent le pays). On envoie
        // alors aux tokens des users du bon rôle ET du bon pays (multicast par
        // lots de 500). Sinon (tous pays) on garde le topic global, plus léger.
        $targetCountries = $ad->target_countries ?? [];

        if (!empty($targetCountries)) {
            $sentCount = $this->notifyByCountries(
                $firebaseService,
                $ad->target_audience,
                $targetCountries,
                $title,
                $body,
                $data,
                $ad->created_by_user_id
            );
            Log::info('[CampaignNotif] Notif ciblée par pays', [
                'advertisement_id' => $ad->id,
                'audience' => $ad->target_audience,
                'countries' => $targetCountries,
                'tokens_sent' => $sentCount,
            ]);
        } else {
            // 1. Diffusion au topic ciblé (un seul appel FCM).
            $topic = self::AUDIENCE_TOPICS[$ad->target_audience] ?? 'all_users';
            $firebaseService->sendToTopic($topic, $title, $body, $data);
            Log::info('[CampaignNotif] Notif via topic (tous pays)', [
                'advertisement_id' => $ad->id,
                'topic' => $topic,
                'audience' => $ad->target_audience,
            ]);
        }

        // 2. Notification directe à l'annonceur (push token + BDD), pour qu'il
        //    reçoive lui aussi « Consultez votre annonce ».
        if ($ad->created_by_user_id) {
            $advertiser = User::find($ad->created_by_user_id);
            if ($advertiser) {
                $notificationService->sendToUser(
                    $advertiser,
                    'Votre annonce est en ligne 🎉',
                    "Consultez votre annonce « {$ad->title} » proposée par {$companyName}",
                    'campaign',
                    [
                        'advertisement_id' => (string) $ad->id,
                        'company_name' => $companyName,
                        'is_owner' => '1',
                    ]
                );
            }
        }
    }

    /**
     * Envoie la notification aux users ciblés par rôle ET pays, via multicast
     * par tokens (par lots de 500, géré par FirebaseNotificationService).
     * Exclut l'annonceur (notifié séparément avec un message dédié).
     *
     * @param array<int,string> $countries Codes pays ISO alpha-2
     * @return int Nombre de tokens auxquels on a tenté d'envoyer
     */
    protected function notifyByCountries(
        FirebaseNotificationService $firebaseService,
        string $audience,
        array $countries,
        string $title,
        string $body,
        array $data,
        ?int $excludeUserId
    ): int {
        $query = User::query()
            ->whereIn('country', $countries)
            ->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '');

        // 'all' => tous les rôles ; sinon on filtre sur le rôle correspondant.
        if ($audience !== 'all') {
            $query->where('role', $audience);
        }

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        $tokens = $query->pluck('fcm_token')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($tokens)) {
            return 0;
        }

        $firebaseService->sendMulticast($tokens, $title, $body, $data);

        return count($tokens);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('[CampaignNotif] Échec définitif', [
            'advertisement_id' => $this->advertisementId,
            'error' => $exception->getMessage(),
        ]);
    }
}
