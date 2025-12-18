<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\UserPresence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'user_two' => 'required|exists:users,id|different:' . Auth::id(),
        ]);

        $conversation = Conversation::create([
            'application_id' => $validated['application_id'],
            'user_one' => Auth::id(),
            'user_two' => $validated['user_two'],
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
        ], 201);
    }
    public function getConversationsList()
    {
        $userId = Auth::id();

        $conversations = Conversation::query()
            ->whereHas('application', function ($q) {
                $q->where('status', 'accepted');
            })
            ->where(function ($q) use ($userId) {
                $q->where('user_one', $userId)
                ->orWhere('user_two', $userId);
            })
            ->with([
                'lastMessage',
                'userOne:id,name,profile_photo',
                'userOne.presence:user_id,online',
                'userTwo:id,name,profile_photo',
                'userTwo.presence:user_id,online',
            ])
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('messages.conversation_id', 'conversations.id')
                    ->latest()
                    ->take(1)
            )
            ->get()
            ->map(function ($conversation) use ($userId) {

                $otherUser = $conversation->user_one == $userId
                    ? $conversation->userTwo
                    : $conversation->userOne;

                return [
                    'conversation_id' => $conversation->id,

                    'user' => $otherUser ? [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'profile_photo' => $otherUser->profile_photo,
                        'is_online' => (bool) optional($otherUser->presence)->online,
                    ] : null,

                    'last_message' => $conversation->lastMessage ? [
                        'message' => $conversation->lastMessage->message,
                        'status' => $conversation->lastMessage->status,
                        'sent_at' => $conversation->lastMessage->created_at?->toDateTimeString(),
                    ] : null,

                    'unread_count' => Message::where('conversation_id', $conversation->id)
                        ->where('sender_id', '!=', $userId)
                        ->where('status', '!=', 'read')
                        ->count(),
                ];
            })
            ->values();

        return response()->json($conversations, 200);
    }

}