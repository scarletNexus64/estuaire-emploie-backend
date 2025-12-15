<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageStatusUpdated implements ShouldBroadcast
{
    public function __construct(public int $messageId, public string $status) {}
    public function broadcastOn()
    {
        return new PrivateChannel('message-status');
    }
    public function broadcastWith()
    {
        return ['messageId' => $this->messageId, 'status' => $this->status];
    }
}
