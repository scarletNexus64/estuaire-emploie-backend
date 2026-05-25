<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TypingEvent implements ShouldBroadcastNow
{
    public function __construct(public int $conversationId, public int $userId)
    {
        \Log::info('⌨️  [EVENT] TypingEvent constructor called', [
            'conversation_id' => $this->conversationId,
            'user_id' => $this->userId,
        ]);
    }

    public function broadcastOn()
    {
        $channel = new PrivateChannel('typing.' . $this->conversationId);

        \Log::info('⌨️  [EVENT] TypingEvent broadcastOn called', [
            'channel' => 'private-typing.' . $this->conversationId,
            'conversation_id' => $this->conversationId,
        ]);

        return $channel;
    }

    public function broadcastWith()
    {
        $data = [
            'conversationId' => $this->conversationId,
            'userId' => $this->userId
        ];

        \Log::info('⌨️  [EVENT] TypingEvent broadcastWith called', [
            'data' => $data,
        ]);

        return $data;
    }
}