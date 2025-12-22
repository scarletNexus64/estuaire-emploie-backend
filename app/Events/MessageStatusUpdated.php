<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageStatusUpdated implements ShouldBroadcast
{
    public function __construct(public int $messageId, public string $status) {}

    public function broadcastOn()
    {
        $message = Message::find($this->messageId);
        return new PrivateChannel('chat.' . $message->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'message_id' => $this->messageId,
            'status' => $this->status
        ];
    }
}
