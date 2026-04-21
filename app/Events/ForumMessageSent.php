<?php

namespace App\Events;

use App\Models\ForumMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForumMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(ForumMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('forum'),
        ];
    }

    /**
     * Le nom de l'événement qui sera diffusé
     */
    public function broadcastAs(): string
    {
        return 'forum.message.new';
    }

    /**
     * Les données à broadcaster
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'user_name' => $this->message->user_name,
            'user_photo' => $this->message->user_photo,
            'content' => $this->message->content,
            'is_admin' => $this->message->is_admin,
            'created_at' => $this->message->created_at->toIso8601String(),
        ];
    }
}
