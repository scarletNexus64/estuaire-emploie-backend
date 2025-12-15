<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PresenceEvent implements ShouldBroadcast
{
    public function __construct(public int $userId, public bool $online) {}
    public function broadcastOn()
    {
        return new Channel('presence');
    }
    public function broadcastWith()
    {
        return ['userId' => $this->userId, 'online' => $this->online];
    }
}
