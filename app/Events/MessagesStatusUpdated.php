<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Changement de statut groupé (distribué / lu) de plusieurs messages d'une
 * conversation, en un seul événement au lieu d'un par message.
 */
class MessagesStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @param  array<int, int>  $messageIds
     */
    public function __construct(
        public int $conversationId,
        public array $messageIds,
        public string $status,
        public int $byUserId,
        public ?int $notifyUserId = null,
    ) {
    }

    public function broadcastOn()
    {
        $channels = [new PrivateChannel('chat.' . $this->conversationId)];
        // L'expéditeur des messages voit ses coches changer même depuis la liste.
        if ($this->notifyUserId) {
            $channels[] = new PrivateChannel('inbox.' . $this->notifyUserId);
        }

        return $channels;
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'message_ids' => array_values($this->messageIds),
            // Plus grand id concerné : tous les messages précédents de
            // l'expéditeur ont au moins ce statut.
            'up_to_id' => $this->messageIds ? max($this->messageIds) : null,
            'status' => $this->status,
            'by_user_id' => $this->byUserId,
        ];
    }
}
