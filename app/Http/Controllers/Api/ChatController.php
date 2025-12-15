<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Events\MessageStatusUpdated;
use App\Events\PresenceEvent;
use App\Events\TypingEvent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\UserPresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
 public function getMessages($conversationId)
{
    // $authId = Auth::user()->id;

    // // Récupère la conversation entre l'utilisateur connecté et l'autre utilisateur
    // $conversation = Conversation::where(function($query) use ($conversationId, $authId) {
    //     $query->where('user_one', $authId)
    //           ->where('user_two', ($conversationId + 1));
    // })->orWhere(function($query) use ($conversationId, $authId) {
    //     $query->where('user_one', ($conversationId + 1))
    //           ->where('user_two', $authId);
    // })->first();

    // if (!$conversation) {
    //     return response()->json([]); // pas de conversation, renvoie un tableau vide
    // }

    // Récupère tous les messages de cette conversation, triés par date
    $messages = Message::where('conversation_id', $conversationId)
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($msg) {
            return [
                'id' => $msg->id,
                'conversation_id' => $msg->conversation_id,
                'sender_id' => $msg->sender_id,
                'message' => $msg->message,
                'status' => $msg->status,
                'created_at' => $msg->created_at ? $msg->created_at->toDateTimeString() : null,
                'updated_at' => $msg->updated_at ? $msg->updated_at->toDateTimeString() : null,
            ];
        })->toArray(); // convertit en tableau pur

        Log::info('Messages fetched for conversation', ['conversation_id' => $conversationId, 'messages_count' => count($messages)]);


    // Renvoie directement le tableau, prêt à être consommé côté Flutter
    return response()->json($messages, 200);
}


    public function send(Request $request)
    {
        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender_id' => Auth::user()->id,
            'message' => $request->message,
            'status' => 'sent'
        ]);

        // destinataire en ligne ?
        if ($this->isReceiverOnline($request->conversation_id)) {
            $message->update(['status' => 'delivered']);
            broadcast(new MessageStatusUpdated($message->id, 'delivered'));
        }

        broadcast(new MessageSent($message))->toOthers();
        return response()->json($message);
    }

    public function markRead($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::user()->id)
            ->where('status', '!=', 'read')
            ->get();

        foreach ($messages as $msg) {
            $msg->update(['status' => 'read']);
            broadcast(new MessageStatusUpdated($msg->id, 'read'));
        }
    }

    public function typing(Request $request)
    {
        broadcast(new TypingEvent($request->conversation_id, Auth::user()->id))->toOthers();
    }

    public function online()
    {
        UserPresence::updateOrCreate(
            ['user_id' => Auth::user()->id],
            ['online' => true, 'last_seen' => now()]
        );
        broadcast(new PresenceEvent(Auth::user()->id, true));
    }

    public function offline()
    {
        UserPresence::where('user_id', Auth::user()->id)
            ->update(['online' => false, 'last_seen' => now()]);
        broadcast(new PresenceEvent(Auth::user()->id, false));
    }

    private function isReceiverOnline($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        $receiverId = $conversation->user_one == Auth::user()->id ? $conversation->user_two : $conversation->user_one;
        $presence = UserPresence::find($receiverId);
        return $presence ? $presence->online : false;
    }
}