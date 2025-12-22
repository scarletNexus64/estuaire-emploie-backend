<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TypingEvent implements ShouldBroadcast
{
    public function __construct(public int $conversationId, public int $userId) {}

    public function broadcastOn()
    {
        return new PrivateChannel('typing.' . $this->conversationId);
    }

    public function broadcastWith()
    {
        return [
            'conversationId' => $this->conversationId,
            'userId' => $this->userId
        ];
    }
}