<?php
// MessageSent.php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    /**
     * @param  string|null  $clientId  identifiant généré par l'app émettrice,
     *         renvoyé pour qu'elle rapproche l'écho de son message en attente.
     */
    public function __construct(public Message $message, public ?string $clientId = null)
    {
        \Log::info('📡 [EVENT] MessageSent constructor called', [
            'message_id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
        ]);
    }

    public function broadcastOn()
    {
        $channel = new PrivateChannel('chat.' . $this->message->conversation_id);

        \Log::info('📡 [EVENT] MessageSent broadcastOn called', [
            'channel' => 'private-chat.' . $this->message->conversation_id,
            'channel_object' => get_class($channel),
            'conversation_id' => $this->message->conversation_id,
        ]);

        // Canal personnel de chaque participant : la liste des conversations
        // et les badges se mettent à jour sans ouvrir la conversation.
        $channels = [$channel];
        $conversation = $this->message->conversation;
        if ($conversation) {
            foreach ([$conversation->user_one, $conversation->user_two] as $userId) {
                if ($userId) {
                    $channels[] = new PrivateChannel('inbox.' . $userId);
                }
            }
        }

        return $channels;
    }

    public function broadcastWith()
    {
        $data = [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->user->name,
            'message' => $this->message->message,
            'status' => $this->message->status,
            // Contexte épinglé (produit taggé, demande de prestation…).
            'metadata' => $this->message->metadata,
            'client_id' => $this->clientId,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'created_at_iso' => $this->message->created_at->toIso8601String(),
            'updated_at' => $this->message->updated_at->toDateTimeString(),
        ];

        \Log::info('📡 [EVENT] MessageSent broadcastWith called', [
            'data' => $data,
        ]);

        return $data;
    }
}