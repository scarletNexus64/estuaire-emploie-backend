<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\AndroidConfig;
use Illuminate\Support\Facades\Log;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials'));

        $this->messaging = $factory->createMessaging();
    }

    /**
     * Envoyer une notification push à un seul token FCM
     */
    public function sendToToken(string $fcmToken, string $title, string $body, array $data = [])
    {
        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification(Notification::create($title, $body))
            ->withData($data)
            ->withApnsConfig(
                ApnsConfig::fromArray([
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => $title,
                                'body' => $body,
                            ],
                            'sound' => 'default',
                            'badge' => 1,
                            'mutable-content' => 1,
                        ],
                    ],
                ])
            )
            ->withAndroidConfig(
                AndroidConfig::fromArray([
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'channel_id',
                    ],
                ])
            );

        try {
            $result = $this->messaging->send($message);
            Log::info('FCM notification sent successfully', [
                'token' => substr($fcmToken, 0, 20) . '...',
                'title' => $title,
            ]);
            return $result;
        } catch (\Throwable $e) {
            Log::warning('FCM send failed', [
                'token' => substr($fcmToken, 0, 20) . '...',
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Envoyer une notification push à plusieurs tokens FCM en une seule requête
     * Firebase supporte jusqu'à 500 tokens par appel multicast
     *
     * @param array $fcmTokens Liste des tokens FCM (max 500)
     * @param string $title Titre de la notification
     * @param string $body Corps de la notification
     * @param array $data Données supplémentaires
     * @return array ['success' => int, 'failure' => int, 'invalid_tokens' => array]
     */
    public function sendMulticast(array $fcmTokens, string $title, string $body, array $data = []): array
    {
        if (empty($fcmTokens)) {
            return ['success' => 0, 'failure' => 0, 'invalid_tokens' => []];
        }

        // Firebase limite à 500 tokens par requête multicast
        $chunks = array_chunk($fcmTokens, 500);
        $totalSuccess = 0;
        $totalFailure = 0;
        $invalidTokens = [];

        foreach ($chunks as $tokenChunk) {
            try {
                $message = CloudMessage::new()
                    ->withNotification(Notification::create($title, $body))
                    ->withData($data)
                    ->withApnsConfig(
                        ApnsConfig::fromArray([
                            'headers' => [
                                'apns-priority' => '10',
                            ],
                            'payload' => [
                                'aps' => [
                                    'alert' => [
                                        'title' => $title,
                                        'body' => $body,
                                    ],
                                    'sound' => 'default',
                                    'badge' => 1,
                                    'mutable-content' => 1,
                                ],
                            ],
                        ])
                    )
                    ->withAndroidConfig(
                        AndroidConfig::fromArray([
                            'priority' => 'high',
                            'notification' => [
                                'sound' => 'default',
                                'channel_id' => 'channel_id',
                            ],
                        ])
                    );

                $report = $this->messaging->sendMulticast($message, $tokenChunk);

                $totalSuccess += $report->successes()->count();
                $totalFailure += $report->failures()->count();

                // Collecter les tokens invalides pour nettoyage
                foreach ($report->failures()->getItems() as $failure) {
                    $token = $failure->target()->value();
                    $error = $failure->error();

                    if ($error && (
                        str_contains($error->getMessage(), 'not found') ||
                        str_contains($error->getMessage(), 'not valid') ||
                        str_contains($error->getMessage(), 'Invalid registration') ||
                        str_contains($error->getMessage(), 'NotRegistered')
                    )) {
                        $invalidTokens[] = $token;
                    }
                }
            } catch (\Throwable $e) {
                Log::error('FCM multicast failed', [
                    'tokens_count' => count($tokenChunk),
                    'error' => $e->getMessage(),
                ]);
                $totalFailure += count($tokenChunk);
            }
        }

        Log::info('FCM multicast result', [
            'success' => $totalSuccess,
            'failure' => $totalFailure,
            'invalid_tokens' => count($invalidTokens),
        ]);

        return [
            'success' => $totalSuccess,
            'failure' => $totalFailure,
            'invalid_tokens' => $invalidTokens,
        ];
    }

    /**
     * Envoyer une notification push à un topic FCM
     * Permet de notifier tous les utilisateurs abonnés à un topic spécifique
     *
     * @param string $topic Nom du topic (ex: 'forum', 'news', etc.)
     * @param string $title Titre de la notification
     * @param string $body Corps de la notification
     * @param array $data Données supplémentaires
     * @return bool
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): bool
    {
        try {
            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification(Notification::create($title, $body))
                ->withData($data)
                ->withApnsConfig(
                    ApnsConfig::fromArray([
                        'headers' => [
                            'apns-priority' => '10',
                        ],
                        'payload' => [
                            'aps' => [
                                'alert' => [
                                    'title' => $title,
                                    'body' => $body,
                                ],
                                'sound' => 'default',
                                'badge' => 1,
                                'mutable-content' => 1,
                            ],
                        ],
                    ])
                )
                ->withAndroidConfig(
                    AndroidConfig::fromArray([
                        'priority' => 'high',
                        'notification' => [
                            'sound' => 'default',
                            'channel_id' => 'channel_id',
                        ],
                    ])
                );

            $result = $this->messaging->send($message);

            Log::info('FCM topic notification sent successfully', [
                'topic' => $topic,
                'title' => $title,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('FCM topic notification failed', [
                'topic' => $topic,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
