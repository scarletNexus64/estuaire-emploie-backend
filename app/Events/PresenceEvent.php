<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PresenceEvent implements ShouldBroadcastNow
{
    public function __construct(public int $userId, public bool $online)
    {
        \Log::info('👤 [EVENT] PresenceEvent constructor called', [
            'user_id' => $this->userId,
            'online' => $this->online,
        ]);
    }

    public function broadcastOn()
    {
        $channel = new Channel('presence');

        \Log::info('👤 [EVENT] PresenceEvent broadcastOn called', [
            'channel' => 'presence',
        ]);

        return $channel;
    }

    public function broadcastWith()
    {
        $data = ['userId' => $this->userId, 'online' => $this->online];

        \Log::info('👤 [EVENT] PresenceEvent broadcastWith called', [
            'data' => $data,
        ]);

        return $data;
    }
}