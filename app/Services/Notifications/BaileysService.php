<?php

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaileysService
{
    protected string $baseUrl;
    protected string $secret;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.baileys.url', 'http://localhost:3001'), '/');
        $this->secret  = config('services.baileys.secret', '');
    }

    /**
     * Envoie un message WhatsApp via le microservice Baileys.
     *
     * @param string $phone  Numero au format international (+237...)
     * @param string $message Contenu du message
     * @return array
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            $response = Http::withHeaders([
                'x-api-secret' => $this->secret,
                'Accept'       => 'application/json',
            ])
            ->timeout(15)
            ->post($this->baseUrl . '/send-message', [
                'phone'   => $phone,
                'message' => $message,
            ]);

            Log::info('[Baileys] Reponse', [
                'phone'  => $phone,
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            if ($response->successful() && ($response->json('success') === true)) {
                return ['success' => true, 'message' => 'Message WhatsApp envoye'];
            }

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Echec envoi WhatsApp',
            ];
        } catch (\Exception $e) {
            Log::error('[Baileys] Erreur', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Verifie si le microservice est connecte a WhatsApp.
     */
    public function isConnected(): bool
    {
        try {
            $response = Http::withHeaders(['x-api-secret' => $this->secret])
                ->timeout(5)
                ->get($this->baseUrl . '/status');

            return $response->successful() && ($response->json('connected') === true);
        } catch (\Exception) {
            return false;
        }
    }
}
