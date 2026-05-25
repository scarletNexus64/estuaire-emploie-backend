<?php
// MessageSent.php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageSent implements ShouldBroadcastNow
{
    public function __construct(public Message $message)
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

        return $channel;
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
            'created_at' => $this->message->created_at->toDateTimeString(),
            'updated_at' => $this->message->updated_at->toDateTimeString(),
        ];

        \Log::info('📡 [EVENT] MessageSent broadcastWith called', [
            'data' => $data,
        ]);

        return $data;
    }
}