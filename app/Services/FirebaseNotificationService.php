<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $credentials = config('firebase.credentials');

        // Le compte de service Firebase est un secret non versionné : il est
        // absent en CI et sur une installation fraîche. Plutôt que de faire
        // échouer le boot de l'application, on désactive l'envoi de push et on
        // le signale dans les logs.
        if (! is_string($credentials) || ! is_file($credentials)) {
            Log::warning('FCM désactivé : fichier de compte de service Firebase introuvable', [
                'credentials' => $credentials,
            ]);

            return;
        }

        try {
            $this->messaging = (new Factory)
                ->withServiceAccount($credentials)
                ->createMessaging();
        } catch (\Throwable $e) {
            Log::warning('FCM désactivé : initialisation Firebase impossible', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Indique si l'envoi de notifications push est disponible.
     */
    public function isEnabled(): bool
    {
        return $this->messaging !== null;
    }

    /**
     * Envoyer une notification push à un seul token FCM
     */
    public function sendToToken(string $fcmToken, string $title, string $body, array $data = [])
    {
        if (! $this->isEnabled()) {
            Log::warning('FCM non configuré : notification ignorée', ['title' => $title]);

            return null;
        }

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
                'token' => substr($fcmToken, 0, 20).'...',
                'title' => $title,
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::warning('FCM send failed', [
                'token' => substr($fcmToken, 0, 20).'...',
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Envoyer une notification push à plusieurs tokens FCM en une seule requête
     * Firebase supporte jusqu'à 500 tokens par appel multicast
     *
     * @param  array  $fcmTokens  Liste des tokens FCM (max 500)
     * @param  string  $title  Titre de la notification
     * @param  string  $body  Corps de la notification
     * @param  array  $data  Données supplémentaires
     * @return array ['success' => int, 'failure' => int, 'invalid_tokens' => array]
     */
    public function sendMulticast(array $fcmTokens, string $title, string $body, array $data = []): array
    {
        if (empty($fcmTokens) || ! $this->isEnabled()) {
            if (! $this->isEnabled()) {
                Log::warning('FCM non configuré : multicast ignoré', ['tokens_count' => count($fcmTokens)]);
            }

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
     * @param  string  $topic  Nom du topic (ex: 'forum', 'news', etc.)
     * @param  string  $title  Titre de la notification
     * @param  string  $body  Corps de la notification
     * @param  array  $data  Données supplémentaires
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): bool
    {
        if (! $this->isEnabled()) {
            Log::warning('FCM non configuré : notification topic ignorée', ['topic' => $topic]);

            return false;
        }

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
