<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ConversationController extends Controller
{
    public function store(Request $request)
    {
        \Log::info('💬 Creating conversation', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'user_two' => 'required|exists:users,id|different:' . Auth::id(),
        ]);

        // Récupérer la candidature avec l'offre d'emploi
        $application = \App\Models\Application::with('job')->findOrFail($validated['application_id']);

        \Log::info('💬 Application data', [
            'application_id' => $application->id,
            'applicant_id' => $application->user_id,
            'job_id' => $application->job_id,
            'recruiter_id' => $application->job->posted_by,
            'status' => $application->status,
            'current_user' => Auth::id()
        ]);

        // Vérifier que la candidature est acceptée
        if ($application->status !== 'accepted') {
            \Log::warning('💬 Application not accepted', [
                'application_id' => $application->id,
                'status' => $application->status
            ]);

            return response()->json([
                'message' => 'La conversation ne peut être créée que pour les candidatures acceptées',
            ], 403);
        }

        // Vérifier que l'utilisateur actuel est un recruteur de la même entreprise
        $currentUser = Auth::user();
        $currentRecruiter = $currentUser->recruiter;

        if (!$currentRecruiter) {
            \Log::warning('💬 User is not a recruiter', [
                'current_user_id' => Auth::id(),
                'current_user_role' => $currentUser->role,
            ]);

            return response()->json([
                'message' => 'Seul un recruteur peut initier une conversation',
            ], 403);
        }

        // Vérifier que le recruteur appartient à la même entreprise que le job
        $jobCompanyId = $application->job->company_id;
        $recruiterCompanyId = $currentRecruiter->company_id;

        if ($jobCompanyId !== $recruiterCompanyId) {
            \Log::warning('💬 Recruiter is not from the same company', [
                'current_user_id' => Auth::id(),
                'job_company_id' => $jobCompanyId,
                'recruiter_company_id' => $recruiterCompanyId,
            ]);

            return response()->json([
                'message' => 'Seul un recruteur de l\'entreprise peut initier une conversation',
            ], 403);
        }

        // user_two doit être le candidat
        $applicantId = $application->user_id;
        if ($validated['user_two'] !== $applicantId) {
            \Log::warning('💬 user_two is not the applicant', [
                'user_two' => $validated['user_two'],
                'applicant_id' => $applicantId
            ]);

            return response()->json([
                'message' => 'La conversation doit être avec le candidat',
            ], 400);
        }

        // Vérifier si une conversation existe déjà pour cette application
        $existingConversation = Conversation::where('application_id', $validated['application_id'])
            ->where(function ($q) use ($validated) {
                $q->where(function ($query) use ($validated) {
                    $query->where('user_one', Auth::id())
                        ->where('user_two', $validated['user_two']);
                })->orWhere(function ($query) use ($validated) {
                    $query->where('user_one', $validated['user_two'])
                        ->where('user_two', Auth::id());
                });
            })
            ->first();

        if ($existingConversation) {
            \Log::info('💬 Conversation already exists', [
                'conversation_id' => $existingConversation->id
            ]);

            return response()->json([
                'conversation_id' => $existingConversation->id,
                'message' => 'Conversation already exists',
            ], 200);
        }

        $conversation = Conversation::create([
            'application_id' => $validated['application_id'],
            'user_one' => Auth::id(), // Recruteur
            'user_two' => $validated['user_two'], // Candidat
        ]);

        \Log::info('💬 ✅ Conversation created successfully', [
            'conversation_id' => $conversation->id,
            'recruiter_id' => Auth::id(),
            'applicant_id' => $validated['user_two']
        ]);

        return response()->json([
            'conversation_id' => $conversation->id,
            'message' => 'Conversation created successfully',
        ], 201);
    }
    public function getConversationsList()
    {
        $userId = Auth::id();
        \Log::info('📋 Loading conversations for user', ['user_id' => $userId]);

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
                'userOne.presence:user_id,online,last_seen',
                'userTwo:id,name,profile_photo',
                'userTwo.presence:user_id,online,last_seen',
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

                \Log::info('📋 Processing conversation', [
                    'conversation_id' => $conversation->id,
                    'user_one' => $conversation->user_one,
                    'user_two' => $conversation->user_two,
                    'current_user' => $userId,
                    'other_user_id' => $otherUser?->id,
                    'other_user_name' => $otherUser?->name,
                    'presence_data' => $otherUser?->presence,
                    'online_value' => $otherUser?->presence?->online,
                    'online_type' => gettype($otherUser?->presence?->online),
                ]);

                return [
                    'conversation_id' => $conversation->id,

                    'user' => $otherUser ? [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'profile_photo' => $otherUser->profile_photo,
                        'is_online' => (bool) ($otherUser->presence?->online ?? false),
                        'last_seen' => optional($otherUser->presence)->last_seen,
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

        \Log::info('✅ Conversations loaded', [
            'count' => $conversations->count(),
            'data' => $conversations->toArray()
        ]);

        return response()->json($conversations, 200);
    }

}