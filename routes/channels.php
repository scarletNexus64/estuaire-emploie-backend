<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })->exists();
});

Broadcast::channel('typing.{conversationId}', function ($user, $conversationId) {
    return Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })->exists();
});
