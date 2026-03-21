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

        // 🎯 Incrémenter le compteur de contacts utilisés pour le recruteur
        $currentUser = Auth::user();
        $subscription = $currentUser->activeSubscription($currentUser->role);
        if ($subscription) {
            $subscription->incrementContactsUsed();
            \Log::info('💬 📊 Contact counter incremented', [
                'subscription_id' => $subscription->id,
                'contacts_used' => $subscription->contacts_used,
                'contacts_limit' => $subscription->getEffectiveContactsLimit(),
            ]);
        }

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
            // Inclure à la fois les conversations d'application ET les conversations de service
            ->where(function ($query) {
                $query->whereHas('application', function ($q) {
                    $q->where('status', 'accepted');
                })
                // OU les conversations de service (sans application)
                ->orWhereNull('application_id');
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

    /**
     * Créer ou récupérer une conversation avec un prestataire de service
     * Cette méthode permet de démarrer une conversation sans lien avec une application
     */
    public function getOrCreateServiceConversation(Request $request)
    {
        \Log::info('💬 Getting or creating service conversation', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'provider_id' => 'required|exists:users,id|different:' . Auth::id(),
            'service_id' => 'nullable|exists:quick_services,id', // Optionnel pour traçabilité
            'initial_message' => 'nullable|string|max:1000',
        ]);

        $currentUserId = Auth::id();
        $providerId = $validated['provider_id'];

        // Vérifier si une conversation existe déjà entre ces deux utilisateurs
        // (indépendamment de l'application_id qui sera NULL pour les services)
        $existingConversation = Conversation::where('application_id', null)
            ->where(function ($q) use ($currentUserId, $providerId) {
                $q->where(function ($query) use ($currentUserId, $providerId) {
                    $query->where('user_one', $currentUserId)
                        ->where('user_two', $providerId);
                })->orWhere(function ($query) use ($currentUserId, $providerId) {
                    $query->where('user_one', $providerId)
                        ->where('user_two', $currentUserId);
                });
            })
            ->first();

        if ($existingConversation) {
            \Log::info('💬 Service conversation already exists', [
                'conversation_id' => $existingConversation->id
            ]);

            // Si un message initial est fourni, l'envoyer
            if (!empty($validated['initial_message'])) {
                // Vérifier si la conversation est vide (aucun message)
                $hasMessages = Message::where('conversation_id', $existingConversation->id)->exists();

                if (!$hasMessages) {
                    $message = Message::create([
                        'conversation_id' => $existingConversation->id,
                        'sender_id' => $currentUserId,
                        'message' => $validated['initial_message'],
                        'status' => 'sent',
                    ]);

                    \Log::info('💬 Initial message sent to existing conversation', [
                        'message_id' => $message->id,
                    ]);

                    // Broadcaster le message
                    broadcast(new \App\Events\MessageSent($message))->toOthers();
                }
            }

            return response()->json([
                'conversation_id' => $existingConversation->id,
                'message' => 'Service conversation already exists',
            ], 200);
        }

        // Créer une nouvelle conversation
        $conversation = Conversation::create([
            'application_id' => null, // Pas liée à une application
            'user_one' => $currentUserId,
            'user_two' => $providerId,
            'service_id' => $validated['service_id'] ?? null, // Pour traçabilité
        ]);

        \Log::info('💬 ✅ Service conversation created successfully', [
            'conversation_id' => $conversation->id,
            'user_one' => $currentUserId,
            'user_two' => $providerId,
            'service_id' => $validated['service_id'] ?? null,
        ]);

        // Envoyer le message initial si fourni
        if (!empty($validated['initial_message'])) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $currentUserId,
                'message' => $validated['initial_message'],
                'status' => 'sent',
            ]);

            \Log::info('💬 Initial message sent', [
                'message_id' => $message->id,
            ]);

            // Broadcaster le message
            broadcast(new \App\Events\MessageSent($message))->toOthers();
        }

        return response()->json([
            'conversation_id' => $conversation->id,
            'message' => 'Service conversation created successfully',
        ], 201);
    }

}