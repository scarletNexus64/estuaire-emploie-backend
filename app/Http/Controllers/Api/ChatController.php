<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Events\MessageStatusUpdated;
use App\Events\MessagesStatusUpdated;
use App\Events\PresenceEvent;
use App\Events\TypingEvent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\UserPresence;
use App\Services\NotificationService;
use App\Services\Chat\MessageRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    /**
     * Messages d'une conversation.
     *
     * Sans paramètre : liste complète (compatibilité des anciennes versions de
     * l'app). Avec `limit`, `before_id` ou `after_id` : page par curseur sur
     * l'id (ordre réel d'envoi), renvoyée en ordre croissant :
     *  - `limit` seul : les N derniers messages ;
     *  - `before_id` : les N messages précédents (historique) ;
     *  - `after_id` : les messages arrivés depuis (rattrapage après coupure).
     * Réponse paginée : `{data: [...], has_more: bool}`.
     */
    public function getMessages(Request $request, $conversationId)
    {
        // Vérifier que l'utilisateur fait partie de cette conversation
        Conversation::where('id', $conversationId)
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        $base = Message::where('conversation_id', $conversationId)
            ->with('user:id,name,profile_photo');

        $paginated = $request->hasAny(['limit', 'before_id', 'after_id']);

        if (!$paginated) {
            $messages = $base->orderBy('id', 'asc')->get()
                ->map(fn ($msg) => $this->formatMessage($msg));

            return response()->json($messages, 200);
        }

        $limit = max(1, min((int) $request->query('limit', 30), 100));

        if ($request->filled('after_id')) {
            $rows = $base->where('id', '>', (int) $request->query('after_id'))
                ->orderBy('id', 'asc')
                ->limit($limit + 1)
                ->get();
            $hasMore = $rows->count() > $limit;
            $rows = $rows->take($limit);
        } else {
            if ($request->filled('before_id')) {
                $base->where('id', '<', (int) $request->query('before_id'));
            }
            $rows = $base->orderBy('id', 'desc')->limit($limit + 1)->get();
            $hasMore = $rows->count() > $limit;
            $rows = $rows->take($limit)->reverse()->values();
        }

        return response()->json([
            'data' => $rows->map(fn ($msg) => $this->formatMessage($msg))->values(),
            'has_more' => $hasMore,
        ], 200);
    }

    /**
     * Représentation d'un message pour l'API. `created_at` reste au format
     * historique (anciennes versions) ; `created_at_iso` porte le fuseau.
     */
    private function formatMessage(Message $msg, ?string $clientId = null): array
    {
        return [
            'id' => $msg->id,
            'conversation_id' => $msg->conversation_id,
            'sender_id' => $msg->sender_id,
            'sender_name' => ($msg->sender_id == Auth::id()) ? 'Vous' : ($msg->user->name ?? ''),
            'sender_photo' => $msg->user->profile_photo ?? null,
            'message' => $msg->message,
            'status' => $msg->status,
            'company_product_id' => $msg->company_product_id,
            'metadata' => $msg->metadata,
            'client_id' => $clientId,
            'created_at' => $msg->created_at?->toDateTimeString(),
            'created_at_iso' => $msg->created_at?->toIso8601String(),
            'updated_at' => $msg->updated_at?->toDateTimeString(),
        ];
    }

    /**
     * Diffuse un événement sans jamais faire échouer la requête : si le
     * serveur temps réel est arrêté, le message reste enregistré et les
     * clients le récupèrent par rattrapage.
     */
    private function safeBroadcast(object $event, bool $toOthers = false): void
    {
        try {
            $pending = broadcast($event);
            if ($toOthers) {
                $pending->toOthers();
            }
            // Le PendingBroadcast diffuse à sa destruction : on la force ici
            // pour capturer l'erreur dans ce try.
            unset($pending);
        } catch (\Throwable $e) {
            \Log::warning('[Chat] Diffusion temps réel impossible', [
                'event' => class_basename($event),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function send(Request $request)
    {
        \Log::info('💬 📤 [SEND] Starting message send', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'broadcast_connection' => config('broadcasting.default'),
        ]);

        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string|max:5000',
            'company_product_id' => 'nullable|exists:company_products,id',
            // Identifiant généré par l'app : un renvoi après coupure réseau
            // renvoie le message déjà créé au lieu d'un doublon.
            'client_id' => 'nullable|string|max:64',
        ]);

        $clientId = $validated['client_id'] ?? null;
        $idempotencyKey = $clientId ? 'chat_client_msg:' . Auth::id() . ':' . $clientId : null;
        if ($idempotencyKey && ($existingId = Cache::get($idempotencyKey))) {
            $existing = Message::with('user:id,name,profile_photo')->find($existingId);
            if ($existing && (int) $existing->sender_id === (int) Auth::id()) {
                return response()->json($this->formatMessage($existing, $clientId), 200);
            }
        }
        // Deux envois simultanés du même client_id (renvoi pendant que le
        // premier est encore en cours) : un seul crée le message.
        if ($idempotencyKey && !Cache::add($idempotencyKey . ':pending', 1, 30)) {
            for ($i = 0; $i < 20 && !Cache::get($idempotencyKey); $i++) {
                usleep(250000);
            }
            $existing = ($id = Cache::get($idempotencyKey))
                ? Message::with('user:id,name,profile_photo')->find($id)
                : null;
            if ($existing) {
                return response()->json($this->formatMessage($existing, $clientId), 200);
            }
            return response()->json(['success' => false, 'code' => 'send_in_progress'], 409);
        }

        // Vérifier que l'utilisateur fait partie de cette conversation
        $conversation = Conversation::where('id', $validated['conversation_id'])
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        // Demande de message : le demandeur attend l'acceptation ; le
        // destinataire qui répond accepte la demande.
        $me = (int) Auth::id();
        if ($conversation->isAwaitingReplyFor($me)) {
            return response()->json([
                'success' => false,
                'code' => 'request_pending',
                'message' => __('conversation.request_pending'),
            ], 403);
        }
        if ($conversation->status !== Conversation::STATUS_ACCEPTED) {
            app(MessageRequestService::class)->markAccepted($conversation, Auth::user());
        }

        \Log::info('💬 📤 [SEND] Conversation verified', [
            'conversation_id' => $conversation->id,
            'user_one' => $conversation->user_one,
            'user_two' => $conversation->user_two,
        ]);

        $productMeta = null;
        if (!empty($validated['company_product_id'])) {
            $taggedProduct = \App\Models\CompanyProduct::find($validated['company_product_id']);
            if ($taggedProduct) {
                $productMeta = [
                    'product' => [
                        'id' => $taggedProduct->id,
                        'name' => $taggedProduct->name,
                        'price' => $taggedProduct->price,
                        'currency' => $taggedProduct->currency,
                        'billing_type' => $taggedProduct->billing_type,
                        'type' => $taggedProduct->type,
                        'image' => $taggedProduct->first_image_url,
                    ],
                ];
            }
        }

        $message = Message::create([
            'conversation_id' => $validated['conversation_id'],
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
            'status' => 'sent',
            'company_product_id' => $validated['company_product_id'] ?? null,
            'metadata' => $productMeta,
        ]);

        if ($idempotencyKey) {
            Cache::put($idempotencyKey, $message->id, now()->addDay());
        }

        \Log::info('💬 📤 [SEND] Message created in DB', [
            'message_id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'status' => $message->status,
        ]);

        // Charger la relation user pour le broadcast
        $message->load('user:id,name,profile_photo');

        // Vérifier si le destinataire est en ligne AVANT de broadcaster
        $isReceiverOnline = $this->isReceiverOnline($validated['conversation_id']);

        \Log::info('💬 📤 [SEND] Receiver online status', [
            'conversation_id' => $validated['conversation_id'],
            'is_receiver_online' => $isReceiverOnline,
        ]);

        if ($isReceiverOnline) {
            // Mettre à jour le statut AVANT de broadcaster
            $message->status = 'delivered';
            $message->save();
            \Log::info('💬 📤 [SEND] Message status updated to delivered');
        }

        // Broadcaster le message avec le bon statut (sent ou delivered)
        \Log::info('💬 📡 [BROADCAST] Broadcasting message', [
            'message_id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'channel' => 'private-chat.' . $message->conversation_id,
            'event' => 'MessageSent',
            'broadcast_driver' => config('broadcasting.default'),
        ]);

        $this->safeBroadcast(new MessageSent($message, $clientId), true);

        // Notification push au destinataire, envoyée APRÈS la réponse : l'appel
        // FCM ne ralentit plus l'envoi du message.
        $receiverId = (int) $conversation->user_one === (int) Auth::id()
            ? $conversation->user_two
            : $conversation->user_one;
        $sender = Auth::user();
        $conversationId = $conversation->id;
        $messageId = $message->id;
        $preview = mb_substr($validated['message'], 0, 100);

        dispatch(function () use ($receiverId, $sender, $conversationId, $messageId, $preview) {
            $receiver = User::find($receiverId);
            if (!$receiver) {
                return;
            }
            try {
                app(NotificationService::class)->sendToUser(
                    $receiver,
                    "Nouveau message de {$sender->name}",
                    $preview,
                    'chat_message',
                    [
                        'conversation_id' => $conversationId,
                        'message_id' => $messageId,
                        'sender_id' => $sender->id,
                        'sender_name' => $sender->name,
                    ]
                );
            } catch (\Throwable $e) {
                \Log::warning('[Chat] Push message impossible', ['error' => $e->getMessage()]);
            }
        })->afterResponse();

        return response()->json($this->formatMessage($message, $clientId), 201);
    }

    /**
     * Marque comme lus les messages reçus (jusqu'à `up_to_id` si fourni).
     * Mise à jour en une requête et un seul événement groupé ; les 20 plus
     * récents sont aussi diffusés un par un pour les anciennes versions.
     */
    public function markRead(Request $request, $conversationId)
    {
        return $this->updateReceivedStatus($request, (int) $conversationId, 'read', ['sent', 'delivered']);
    }

    /**
     * Accusé de réception : l'app signale les messages reçus (hors de la
     * conversation ouverte), ils passent de « envoyé » à « distribué ».
     */
    public function markDelivered(Request $request, $conversationId)
    {
        return $this->updateReceivedStatus($request, (int) $conversationId, 'delivered', ['sent']);
    }

    /**
     * @param  array<int, string>  $fromStatuses
     */
    private function updateReceivedStatus(Request $request, int $conversationId, string $status, array $fromStatuses)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();
        $otherUserId = (int) $conversation->user_one === (int) Auth::id()
            ? (int) $conversation->user_two
            : (int) $conversation->user_one;

        $query = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->whereIn('status', $fromStatuses);

        if ($request->filled('up_to_id')) {
            $query->where('id', '<=', (int) $request->input('up_to_id'));
        }

        $ids = $query->orderBy('id')->pluck('id')->all();

        if ($ids) {
            Message::whereIn('id', $ids)->update(['status' => $status, 'updated_at' => now()]);

            $this->safeBroadcast(new MessagesStatusUpdated($conversationId, $ids, $status, (int) Auth::id(), $otherUserId));
            foreach (array_slice($ids, -20) as $id) {
                $this->safeBroadcast(new MessageStatusUpdated($id, $status));
            }
        }

        return response()->json([
            'success' => true,
            'marked_as_read' => $status === 'read' ? count($ids) : 0,
            'updated' => count($ids),
            'status' => $status,
        ], 200);
    }

    /**
     * Paramètres de connexion temps réel pour l'app (aucun secret : la clé
     * publique Reverb est déjà visible de tout client WebSocket).
     *
     * L'hôte public peut différer de l'hôte utilisé par le serveur pour
     * diffuser (ex. 127.0.0.1 en local) : REVERB_CLIENT_* prime, sinon un hôte
     * privé / local est remplacé par celui de la requête.
     */
    public function realtimeConfig(Request $request)
    {
        $reverb = config('broadcasting.connections.reverb', []);
        $options = $reverb['options'] ?? [];

        $host = env('REVERB_CLIENT_HOST') ?: ($options['host'] ?? null);
        $isPrivate = !$host
            || in_array($host, ['127.0.0.1', 'localhost', '0.0.0.0'], true)
            || (filter_var($host, FILTER_VALIDATE_IP)
                && !filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE));
        if ($isPrivate && !env('REVERB_CLIENT_HOST')) {
            $host = $request->getHost();
        }

        $scheme = env('REVERB_CLIENT_SCHEME') ?: ($options['scheme'] ?? 'https');
        $port = (int) (env('REVERB_CLIENT_PORT') ?: ($options['port'] ?? ($scheme === 'https' ? 443 : 8080)));

        return response()->json([
            'enabled' => config('broadcasting.default') === 'reverb' && !empty($reverb['key']),
            'key' => $reverb['key'] ?? null,
            'host' => $host,
            'port' => $port,
            'scheme' => $scheme,
        ]);
    }

    public function typing(Request $request)
    {
        \Log::info('⌨️  [TYPING] Typing event received', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        // Vérifier que l'utilisateur fait partie de cette conversation
        Conversation::where('id', $validated['conversation_id'])
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        \Log::info('⌨️  📡 [TYPING] Broadcasting typing event', [
            'conversation_id' => $validated['conversation_id'],
            'user_id' => Auth::id(),
            'channel' => 'private-chat.' . $validated['conversation_id'],
        ]);

        $this->safeBroadcast(new TypingEvent($validated['conversation_id'], Auth::id()), true);

        \Log::info('⌨️  ✅ [TYPING] Typing event broadcasted');

        return response()->json(['success' => true], 200);
    }

    public function online()
    {
        UserPresence::updateOrCreate(
            ['user_id' => Auth::id()],
            ['online' => true, 'last_seen' => now()]
        );
        $this->safeBroadcast(new PresenceEvent(Auth::id(), true));

        return response()->json(['success' => true, 'status' => 'online'], 200);
    }

    public function offline()
    {
        UserPresence::where('user_id', Auth::id())
            ->update(['online' => false, 'last_seen' => now()]);
        $this->safeBroadcast(new PresenceEvent(Auth::id(), false));

        return response()->json(['success' => true, 'status' => 'offline'], 200);
    }

    private function isReceiverOnline($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        $receiverId = $conversation->user_one == Auth::id() ? $conversation->user_two : $conversation->user_one;
        $presence = UserPresence::where('user_id', $receiverId)->first();
        return $presence ? $presence->online : false;
    }
}