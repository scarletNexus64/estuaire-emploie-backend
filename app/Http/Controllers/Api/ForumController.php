<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ForumMessage;
use App\Events\ForumMessageSent;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ForumController extends Controller
{
    /**
     * Récupérer tous les messages du forum
     * GET /api/forum/messages
     */
    public function index()
    {
        try {
            $messages = ForumMessage::with('user')
                ->orderBy('created_at', 'asc')
                ->limit(500) // Limiter à 500 messages les plus récents
                ->get();

            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des messages',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Créer un nouveau message dans le forum
     * POST /api/forum/messages
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:5000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation échouée',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Créer le message
            $message = ForumMessage::create([
                'user_id' => auth()->id(),
                'content' => $request->content,
            ]);

            // Charger la relation user
            $message->load('user');

            // Broadcast l'événement via Reverb
            broadcast(new ForumMessageSent($message))->toOthers();

            // Envoyer notification FCM au topic 'forum' pour notifier tous les abonnés
            $this->notifyForumTopic($message);

            return response()->json([
                'success' => true,
                'message' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du message',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Notifier tous les utilisateurs abonnés au topic 'forum' via FCM
     */
    private function notifyForumTopic(ForumMessage $message)
    {
        try {
            $firebaseService = app(\App\Services\FirebaseNotificationService::class);

            // Envoyer la notification au topic 'forum'
            $firebaseService->sendToTopic(
                'forum',
                'Nouveau message au forum',
                $message->user->name . ' : ' . mb_substr($message->content, 0, 100),
                [
                    'type' => 'forum_message',
                    'message_id' => $message->id,
                    'user_id' => $message->user_id,
                    'user_name' => $message->user->name,
                    'sent_at' => now()->toISOString(),
                ]
            );

            Log::info('Forum topic notification sent', [
                'topic' => 'forum',
                'message_id' => $message->id,
                'user_id' => $message->user_id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur notification forum topic: ' . $e->getMessage());
        }
    }
}
