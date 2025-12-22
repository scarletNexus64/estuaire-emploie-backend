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

class ChatController extends Controller
{
    public function getMessages($conversationId)
    {
        // Vérifier que l'utilisateur fait partie de cette conversation
        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        $messages = Message::where('conversation_id', $conversationId)
            ->with('user:id,name,profile_photo')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'conversation_id' => $msg->conversation_id,
                    'sender_id' => $msg->sender_id,
                    'sender_name' => ($msg->sender_id == Auth::id()) ? 'Vous' : $msg->user->name,
                    'sender_photo' => $msg->user->profile_photo,
                    'message' => $msg->message,
                    'status' => $msg->status,
                    'created_at' => $msg->created_at?->toDateTimeString(),
                    'updated_at' => $msg->updated_at?->toDateTimeString(),
                ];
            });

        return response()->json($messages, 200);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string|max:5000',
        ]);

        // Vérifier que l'utilisateur fait partie de cette conversation
        $conversation = Conversation::where('id', $validated['conversation_id'])
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        $message = Message::create([
            'conversation_id' => $validated['conversation_id'],
            'sender_id' => Auth::id(),
            'message' => $validated['message'],
            'status' => 'sent',
        ]);

        // Charger la relation user pour le broadcast
        $message->load('user:id,name,profile_photo');

        // Vérifier si le destinataire est en ligne AVANT de broadcaster
        $isReceiverOnline = $this->isReceiverOnline($validated['conversation_id']);

        if ($isReceiverOnline) {
            // Mettre à jour le statut AVANT de broadcaster
            $message->status = 'delivered';
            $message->save();
        }

        // Broadcaster le message avec le bon statut (sent ou delivered)
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->user->name,
            'sender_photo' => $message->user->profile_photo,
            'message' => $message->message,
            'status' => $message->status,
            'created_at' => $message->created_at?->toDateTimeString(),
            'updated_at' => $message->updated_at?->toDateTimeString(),
        ], 201);
    }

    public function markRead($conversationId)
    {
        // Vérifier que l'utilisateur fait partie de cette conversation
        Conversation::where('id', $conversationId)
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        $messages = Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('status', '!=', 'read')
            ->get();

        foreach ($messages as $msg) {
            $msg->update(['status' => 'read']);
            broadcast(new MessageStatusUpdated($msg->id, 'read'));
        }

        return response()->json([
            'success' => true,
            'marked_as_read' => $messages->count(),
        ], 200);
    }

    public function typing(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        // Vérifier que l'utilisateur fait partie de cette conversation
        Conversation::where('id', $validated['conversation_id'])
            ->where(function ($q) {
                $q->where('user_one', Auth::id())
                    ->orWhere('user_two', Auth::id());
            })
            ->firstOrFail();

        broadcast(new TypingEvent($validated['conversation_id'], Auth::id()));

        return response()->json(['success' => true], 200);
    }

    public function online()
    {
        UserPresence::updateOrCreate(
            ['user_id' => Auth::id()],
            ['online' => true, 'last_seen' => now()]
        );
        broadcast(new PresenceEvent(Auth::id(), true));

        return response()->json(['success' => true, 'status' => 'online'], 200);
    }

    public function offline()
    {
        UserPresence::where('user_id', Auth::id())
            ->update(['online' => false, 'last_seen' => now()]);
        broadcast(new PresenceEvent(Auth::id(), false));

        return response()->json(['success' => true, 'status' => 'offline'], 200);
    }

    private function isReceiverOnline($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        $receiverId = $conversation->user_one == Auth::id() ? $conversation->user_two : $conversation->user_one;
        $presence = UserPresence::where('user_id', $receiverId)->first();
        return $presence ? $presence->online : false;
    }
}