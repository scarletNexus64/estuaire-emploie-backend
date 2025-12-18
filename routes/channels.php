<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {

    Log::Info('Vérification canal chat pour utilisateur ID: ', [$user->id, $conversationId]);
    return Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })->exists();
});

Broadcast::channel('typing.{conversationId}', function ($user, $conversationId) {
    Log::Info('Vérification canal typing pour utilisateur ID: ', [$user->id, $conversationId]);
    return Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })->exists();
});
Broadcast::channel('presence', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});