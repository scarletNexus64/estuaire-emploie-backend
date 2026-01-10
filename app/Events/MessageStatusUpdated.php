<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageStatusUpdated implements ShouldBroadcastNow
{
    public function __construct(public int $messageId, public string $status)
    {
        \Log::info('✓ [EVENT] MessageStatusUpdated constructor called', [
            'message_id' => $this->messageId,
            'status' => $this->status,
        ]);
    }

    public function broadcastOn()
    {
        $message = Message::find($this->messageId);
        $channel = new PrivateChannel('chat.' . $message->conversation_id);

        \Log::info('✓ [EVENT] MessageStatusUpdated broadcastOn called', [
            'channel' => 'private-chat.' . $message->conversation_id,
            'conversation_id' => $message->conversation_id,
        ]);

        return $channel;
    }

    public function broadcastWith()
    {
        $data = [
            'message_id' => $this->messageId,
            'status' => $this->status
        ];

        \Log::info('✓ [EVENT] MessageStatusUpdated broadcastWith called', [
            'data' => $data,
        ]);

        return $data;
    }
}
