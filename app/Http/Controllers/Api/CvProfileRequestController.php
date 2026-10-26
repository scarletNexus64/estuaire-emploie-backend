<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Jobs\SendCvProfileRequestNotification;
use App\Models\Conversation;
use App\Models\CvProfileRequest;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Demandes de profil de la CVThèque : une entreprise publie le profil qu'elle
 * recherche, tous les abonnés au topic all_users sont notifiés et peuvent y
 * répondre par la messagerie (la demande est épinglée dans la conversation).
 */
class CvProfileRequestController extends Controller
{
    /** Nombre maximum de demandes par recruteur sur 24 h (chaque demande notifie tout le monde). */
    private const DAILY_LIMIT = 3;

    /** Durée pendant laquelle une demande reste visible dans le fil public. */
    private const FEED_DAYS = 30;

    /**
     * POST /api/cv-profile-requests
     * Publication par un recruteur ayant une entreprise active.
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user->isRecruiter()) {
            return response()->json(['message' => 'Seules les entreprises peuvent publier une demande de profil.'], 403);
        }
        if (! $user->current_company_id) {
            return response()->json([
                'message' => __('application.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'specialty' => 'nullable|string|max:150',
            'experience_level' => ['nullable', 'string', Rule::in(array_keys(CvProfileRequest::EXPERIENCE_LEVELS))],
            'contract_type' => ['nullable', 'string', Rule::in(array_keys(CvProfileRequest::CONTRACT_TYPES))],
            'city' => 'nullable|string|max:120',
            'skills' => 'nullable|array|max:15',
            'skills.*' => 'string|max:60',
            'description' => 'required|string|max:1500',
        ]);

        $recentCount = CvProfileRequest::withTrashed()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDay())
            ->count();
        if ($recentCount >= self::DAILY_LIMIT) {
            return response()->json([
                'message' => 'Vous avez atteint la limite de '.self::DAILY_LIMIT.' demandes de profil par jour. Réessayez demain.',
            ], 429);
        }

        $skills = collect($validated['skills'] ?? [])
            ->map(fn ($s) => trim((string) $s))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $profileRequest = CvProfileRequest::create([
            'user_id' => $user->id,
            'company_id' => $user->current_company_id,
            'title' => $validated['title'],
            'specialty' => $validated['specialty'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
            'contract_type' => $validated['contract_type'] ?? null,
            'city' => $validated['city'] ?? null,
            'skills' => $skills ?: null,
            'description' => $validated['description'],
            'status' => 'open',
        ]);

        // Diffusion au topic all_users via la queue : la réponse HTTP ne dépend jamais
        // de FCM. Même si la mise en file échoue, la demande est enregistrée.
        $notificationQueued = true;
        try {
            SendCvProfileRequestNotification::dispatch($profileRequest->id)->afterCommit();
        } catch (\Throwable $e) {
            $notificationQueued = false;
            Log::error('[CvProfileRequest] Mise en file de la notification impossible', [
                'request_id' => $profileRequest->id,
                'error' => $e->getMessage(),
            ]);
        }

        $profileRequest->load('company:id,name,logo');

        return response()->json([
            'data' => $this->present($profileRequest, $user->id),
            'notification_queued' => $notificationQueued,
            'message' => $notificationQueued
                ? 'Votre demande a été publiée. La notification à tous les utilisateurs est en cours de traitement.'
                : 'Votre demande a été publiée, mais l’envoi de la notification n’a pas pu démarrer.',
        ], 201);
    }

    /**
     * GET /api/cv-profile-requests/mine
     * Demandes publiées par le recruteur connecté.
     */
    public function mine(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $page = CvProfileRequest::with('company:id,name,logo')
            ->where('user_id', $userId)
            ->latest()
            ->paginate(20);

        $page->getCollection()->transform(fn (CvProfileRequest $r) => $this->present($r, $userId));

        return response()->json($page);
    }

    /**
     * GET /api/cv-profile-requests/feed
     * Demandes ouvertes récentes des entreprises. Filtres : city, q.
     */
    public function feed(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        $query = CvProfileRequest::with('company:id,name,logo')
            ->where('user_id', '!=', $userId)
            ->where('status', 'open')
            ->where('created_at', '>=', now()->subDays(self::FEED_DAYS));

        if ($city = trim((string) $request->query('city'))) {
            $query->where('city', 'like', '%'.$city.'%');
        }
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('specialty', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('skills', 'like', '%'.$search.'%');
            });
        }

        $page = $query->latest()->paginate(20);
        $page->getCollection()->transform(fn (CvProfileRequest $r) => $this->present($r, $userId));

        return response()->json($page);
    }

    /**
     * GET /api/cv-profile-requests/{id}
     * Détail (ouvert depuis une notification, le fil ou le chat).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $userId = $request->user()->id;
        $profileRequest = CvProfileRequest::with('company:id,name,logo')->find($id);

        if (! $profileRequest) {
            return response()->json(['message' => 'Demande introuvable.'], 404);
        }

        return response()->json(['data' => $this->present($profileRequest, $userId, withResponse: true)]);
    }

    /**
     * POST /api/cv-profile-requests/{id}/respond
     *
     * Ouvre (ou réutilise) la conversation avec l'entreprise et y poste le
     * message, avec la demande épinglée dans metadata.cv_profile_request.
     */
    public function respond(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $responder = $request->user();
        $profileRequest = CvProfileRequest::with('company:id,name,logo')->find($id);

        if (! $profileRequest) {
            return response()->json(['message' => 'Demande introuvable.'], 404);
        }
        if (! $profileRequest->isOpen()) {
            return response()->json(['message' => "Cette demande est clôturée, l'entreprise ne reçoit plus de réponses."], 422);
        }
        if ($profileRequest->user_id === $responder->id) {
            return response()->json(['message' => 'Vous ne pouvez pas répondre à votre propre demande.'], 422);
        }

        $ownerId = $profileRequest->user_id;

        [$conversation, $message, $isFirstResponse] = DB::transaction(function () use ($responder, $ownerId, $profileRequest, $validated) {
            // Même règle que les conversations de service : une seule
            // conversation hors candidature entre deux utilisateurs.
            $conversation = Conversation::whereNull('application_id')
                ->where(function ($q) use ($responder, $ownerId) {
                    $q->where(fn ($q) => $q->where('user_one', $responder->id)->where('user_two', $ownerId))
                        ->orWhere(fn ($q) => $q->where('user_one', $ownerId)->where('user_two', $responder->id));
                })
                ->lockForUpdate()
                ->first()
                ?? Conversation::create([
                    'application_id' => null,
                    'user_one' => $responder->id,
                    'user_two' => $ownerId,
                ]);

            $isFirstResponse = ! Message::where('conversation_id', $conversation->id)
                ->where('sender_id', $responder->id)
                ->where('metadata->cv_profile_request->id', $profileRequest->id)
                ->exists();

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $responder->id,
                'message' => $validated['message'],
                'status' => 'sent',
                'metadata' => ['cv_profile_request' => $profileRequest->toChatSnapshot()],
            ]);

            if ($isFirstResponse) {
                $profileRequest->increment('responses_count');
            }

            return [$conversation, $message, $isFirstResponse];
        });

        // Temps réel et push : best effort, jamais bloquants pour la réponse.
        try {
            $message->load('user:id,name,profile_photo');
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            Log::warning('[CvProfileRequest] Broadcast de la réponse impossible', ['error' => $e->getMessage()]);
        }

        try {
            if ($owner = $profileRequest->user) {
                app(NotificationService::class)->sendToUser(
                    $owner,
                    $isFirstResponse
                        ? "{$responder->name} répond à votre demande de profil"
                        : "Nouveau message de {$responder->name}",
                    '« '.Str::limit($profileRequest->title, 40).' » · '.Str::limit($validated['message'], 80),
                    'chat_message',
                    [
                        'conversation_id' => $conversation->id,
                        'message_id' => $message->id,
                        'sender_id' => $responder->id,
                        'sender_name' => $responder->name,
                        'cv_profile_request_id' => $profileRequest->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('[CvProfileRequest] Push de réponse impossible', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Votre réponse a été envoyée.',
            'data' => ['conversation_id' => $conversation->id],
        ], 201);
    }

    /**
     * POST /api/cv-profile-requests/{id}/close
     * Clôture par l'auteur : plus de réponses, la diffusion en cours s'arrête.
     */
    public function close(Request $request, int $id): JsonResponse
    {
        $profileRequest = CvProfileRequest::where('user_id', $request->user()->id)->find($id);
        if (! $profileRequest) {
            return response()->json(['message' => 'Demande introuvable.'], 404);
        }

        $profileRequest->update(['status' => 'closed']);

        return response()->json(['message' => 'Demande clôturée.']);
    }

    /**
     * DELETE /api/cv-profile-requests/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $profileRequest = CvProfileRequest::where('user_id', $request->user()->id)->find($id);
        if (! $profileRequest) {
            return response()->json(['message' => 'Demande introuvable.'], 404);
        }

        // Le statut stoppe aussi une diffusion encore en file.
        $profileRequest->update(['status' => 'closed']);
        $profileRequest->delete();

        return response()->json(['message' => 'Demande supprimée.']);
    }

    /**
     * Forme JSON commune. Avec $withResponse, indique si l'utilisateur a déjà
     * répondu et la conversation à rouvrir.
     */
    private function present(CvProfileRequest $r, int $viewerId, bool $withResponse = false): array
    {
        $data = [
            'id' => $r->id,
            'title' => $r->title,
            'specialty' => $r->specialty,
            'experience_level' => $r->experience_level,
            'experience_level_label' => $r->experience_level_label,
            'contract_type' => $r->contract_type,
            'contract_type_label' => $r->contract_type_label,
            'city' => $r->city,
            'skills' => $r->skills ?? [],
            'description' => $r->description,
            'status' => $r->status,
            'responses_count' => $r->responses_count,
            'company_name' => $r->publisherName(),
            'company_logo' => $r->company?->logo_url,
            'is_owner' => $r->user_id === $viewerId,
            'created_at' => $r->created_at?->toIso8601String(),
        ];

        if ($withResponse && $r->user_id !== $viewerId) {
            $conversationId = Message::where('sender_id', $viewerId)
                ->where('metadata->cv_profile_request->id', $r->id)
                ->latest('id')
                ->value('conversation_id');
            $data['has_responded'] = $conversationId !== null;
            $data['conversation_id'] = $conversationId;
        }

        return $data;
    }
}
