<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    public function __construct(public Message $message) {}
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->conversation_id);
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->user->name,
            'message' => $this->message->message,
            'status' => $this->message->status,
            'created_at' => $this->message->created_at->toDateTimeString(),

        ];
    }
}