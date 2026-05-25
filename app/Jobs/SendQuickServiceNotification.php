<?php

namespace App\Jobs;

use App\Models\QuickService;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job pour envoyer des notifications lors de l'approbation d'un service rapide
 * Utilise Firebase Multicast pour envoyer en masse (500 tokens/requête)
 * BULK: FCM uniquement (pas d'email)
 */
class SendQuickServiceNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $quickService;

    public $tries = 3;
    public $timeout = 600; // 10 minutes

    public function __construct(QuickService $quickService)
    {
        $this->quickService = $quickService;
        $this->onQueue('notifications');
    }

    public function handle(NotificationService $notificationService): void
    {
        try {
            $service = $this->quickService->load(['user', 'category']);

            $title = "Nouveau service : {$service->title}";
            $message = "{$service->user->name} propose un service";
            if ($service->location_name) {
                $message .= " à {$service->location_name}";
            }

            $additionalData = [
                'service_id' => $service->id,
                'service_title' => $service->title,
                'user_name' => $service->user->name,
                'category_name' => $service->category->name ?? null,
                'location_name' => $service->location_name,
            ];

            // Récupérer tous les utilisateurs avec token FCM SAUF l'auteur du service
            $users = User::whereIn('role', ['candidate', 'recruiter'])
                ->whereNotNull('fcm_token')
                ->where('id', '!=', $service->user_id)
                ->get();

            Log::info('Quick service notification', [
                'service_id' => $service->id,
                'users' => $users->count(),
            ]);

            // Envoyer via multicast (500 tokens par requête Firebase - FCM uniquement)
            $result = $notificationService->sendToMultipleUsers(
                $users,
                $title,
                $message,
                'quick_service_published',
                $additionalData
            );

            Log::info('Quick service notification sent', [
                'service_id' => $service->id,
                'sent' => $result['sent'],
                'failed' => $result['failed'],
            ]);

        } catch (\Exception $e) {
            Log::error('Quick service notification failed', [
                'service_id' => $this->quickService->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Quick service notification permanently failed', [
            'service_id' => $this->quickService->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
