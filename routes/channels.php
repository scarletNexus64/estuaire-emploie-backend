<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// Laravel ajoute automatiquement "private-" devant pour les PrivateChannel
// Donc on définit juste "chat" et cela deviendra "private-chat"
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {

    Log::Info('🔐 Vérification canal chat pour utilisateur ID: ', [$user->id, $conversationId]);
    $exists = Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })->exists();

    Log::Info('🔐 Auth result for chat.'.$conversationId.': '.($exists ? 'GRANTED' : 'DENIED'));
    return $exists;
});

Broadcast::channel('typing.{conversationId}', function ($user, $conversationId) {
    Log::Info('🔐 Vérification canal typing pour utilisateur ID: ', [$user->id, $conversationId]);
    $exists = Conversation::where('id', $conversationId)
        ->where(function ($q) use ($user) {
            $q->where('user_one', $user->id)
                ->orWhere('user_two', $user->id);
        })->exists();

    Log::Info('🔐 Auth result for typing.'.$conversationId.': '.($exists ? 'GRANTED' : 'DENIED'));
    return $exists;
});
Broadcast::channel('presence', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});