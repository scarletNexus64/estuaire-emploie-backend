<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Jobs\SendServiceRequestNotification;
use App\Models\Conversation;
use App\Models\DigitalizationRequest;
use App\Models\Message;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Demandes de prestation de service (ex-« demandes de digitalisation »),
 * ouvertes aux profils étudiant, candidat et entreprise.
 */
class DigitalizationRequestController extends Controller
{
    /** Nombre maximum de demandes par utilisateur sur 24 h (anti-spam : chaque demande notifie tout le monde). */
    private const DAILY_LIMIT = 5;

    /** Durée pendant laquelle une demande reste visible dans le fil public. */
    private const FEED_DAYS = 30;

    /**
     * Soumettre une demande depuis l'app mobile (multipart si pièce jointe).
     * Champs : catégorie, besoin, canaux de contact, ville, quartier,
     * description (≤ 50 mots), pièce jointe optionnelle.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', Rule::in(array_keys(DigitalizationRequest::CATEGORIES))],
            'project_name' => 'required|string|max:255',
            'channels' => 'nullable|array|min:1',
            'channels.*' => ['string', Rule::in(array_keys(DigitalizationRequest::CHANNELS))],
            'contact' => 'nullable|string|max:255',
            'description' => 'required|string',
            'company_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        // Anciennes versions de l'app : pas de canaux => contact par téléphone.
        $channels = array_values(array_unique($validated['channels'] ?? ['call']));

        if (array_intersect($channels, DigitalizationRequest::PHONE_CHANNELS) && empty($validated['contact'])) {
            return response()->json([
                'message' => 'Un numéro de téléphone est requis pour être contacté par WhatsApp, SMS ou appel.',
                'errors' => ['contact' => ['Le numéro de téléphone est obligatoire pour ces canaux.']],
            ], 422);
        }

        // Limite de 50 mots sur la description.
        if (str_word_count($validated['description']) > 50) {
            return response()->json([
                'message' => 'La description ne doit pas dépasser 50 mots.',
                'errors' => ['description' => ['La description ne doit pas dépasser 50 mots.']],
            ], 422);
        }

        $userId = $request->user()->id;

        $recentCount = DigitalizationRequest::withTrashed()
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDay())
            ->count();
        if ($recentCount >= self::DAILY_LIMIT) {
            return response()->json([
                'message' => 'Vous avez atteint la limite de '.self::DAILY_LIMIT.' demandes par jour. Réessayez demain.',
            ], 429);
        }

        $attachmentPath = null;
        $attachmentName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('service_requests', 'public');
            $attachmentName = $file->getClientOriginalName();
        }

        $serviceRequest = DigitalizationRequest::create([
            'user_id' => $userId,
            'category' => $validated['category'] ?? 'company_service',
            'project_name' => $validated['project_name'],
            'company_name' => $validated['company_name'] ?? null,
            'city' => $validated['city'] ?? null,
            'district' => $validated['district'] ?? null,
            'contact' => $validated['contact'] ?? null,
            'channels' => $channels,
            'description' => $validated['description'],
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'status' => 'pending',
        ]);

        // Diffusion immédiate via la queue : la réponse HTTP ne dépend jamais
        // de FCM. Même si la mise en file échoue, la demande est enregistrée.
        $notificationQueued = false;
        try {
            SendServiceRequestNotification::dispatch($serviceRequest->id);
            $notificationQueued = true;
        } catch (\Throwable $e) {
            Log::error('[ServiceRequest] Mise en file de la notification impossible', [
                'request_id' => $serviceRequest->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'data' => $serviceRequest,
            'notification_queued' => $notificationQueued,
            'message' => $notificationQueued
                ? 'Votre demande a été publiée. La notification est en cours de traitement.'
                : 'Votre demande a été publiée. La notification n’a pas pu être mise en file.',
        ], 201);
    }

    /**
     * Fil public des demandes récentes des autres utilisateurs.
     * Filtres optionnels : category, city, q (recherche texte).
     */
    public function feed(Request $request): JsonResponse
    {
        $query = DigitalizationRequest::with('user:id,name,profile_photo')
            ->where('user_id', '!=', $request->user()->id)
            ->where('status', '!=', 'rejected')
            ->where('created_at', '>=', now()->subDays(self::FEED_DAYS));

        if ($category = $request->query('category')) {
            $query->where('category', $category);
        }
        if ($city = $request->query('city')) {
            $query->where('city', 'like', '%'.$city.'%');
        }
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        return response()->json($query->latest()->paginate(20));
    }

    /**
     * Détail d'une demande (ouverte depuis une notification ou le fil).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $serviceRequest = DigitalizationRequest::with('user:id,name,profile_photo')->find($id);

        $isOwner = $serviceRequest && $serviceRequest->user_id === $request->user()->id;
        if (! $serviceRequest || (! $isOwner && $serviceRequest->status === 'rejected')) {
            return response()->json(['message' => 'Demande introuvable.'], 404);
        }

        return response()->json(['data' => $serviceRequest]);
    }

    /**
     * Répondre à une demande par la messagerie Estuaire Emploi.
     *
     * Ouvre (ou réutilise) la conversation avec l'auteur et y poste le message,
     * avec la demande épinglée dans metadata.service_request : le chat l'affiche
     * en bandeau et sur la bulle. L'auteur reçoit un push dédié.
     */
    public function respond(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $responder = $request->user();
        $serviceRequest = DigitalizationRequest::find($id);

        if (! $serviceRequest || $serviceRequest->status === 'rejected' || ! $serviceRequest->user_id) {
            return response()->json(['message' => 'Demande introuvable.'], 404);
        }
        if ($serviceRequest->user_id === $responder->id) {
            return response()->json(['message' => 'Vous ne pouvez pas répondre à votre propre demande.'], 422);
        }
        if (! in_array('estuaire', $serviceRequest->channels ?? ['estuaire'], true)) {
            return response()->json(['message' => "L'auteur souhaite être contacté par téléphone uniquement."], 422);
        }

        $ownerId = $serviceRequest->user_id;

        [$conversation, $message, $isFirstResponse] = DB::transaction(function () use ($responder, $ownerId, $serviceRequest, $validated) {
            // Même règle que les conversations de service : une seule
            // conversation hors candidature entre deux utilisateurs.
            $conversation = Conversation::whereNull('application_id')
                ->where(function ($q) use ($responder, $ownerId) {
                    $q->where(fn ($q) => $q->where('user_one', $responder->id)->where('user_two', $ownerId))
                        ->orWhere(fn ($q) => $q->where('user_one', $ownerId)->where('user_two', $responder->id));
                })
                ->first()
                ?? Conversation::create([
                    'application_id' => null,
                    'user_one' => $responder->id,
                    'user_two' => $ownerId,
                ]);

            $isFirstResponse = ! Message::where('conversation_id', $conversation->id)
                ->where('sender_id', $responder->id)
                ->where('metadata->service_request->id', $serviceRequest->id)
                ->exists();

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $responder->id,
                'message' => $validated['message'],
                'status' => 'sent',
                'metadata' => ['service_request' => $serviceRequest->toChatSnapshot()],
            ]);

            if ($isFirstResponse) {
                $serviceRequest->increment('responses_count');
            }

            return [$conversation, $message, $isFirstResponse];
        });

        // Temps réel et push : best effort, jamais bloquants pour la réponse.
        try {
            $message->load('user:id,name,profile_photo');
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            Log::warning('[ServiceRequest] Broadcast de la réponse impossible', ['error' => $e->getMessage()]);
        }

        try {
            if ($owner = $serviceRequest->user) {
                app(NotificationService::class)->sendToUser(
                    $owner,
                    $isFirstResponse
                        ? "{$responder->name} répond à votre demande"
                        : "Nouveau message de {$responder->name}",
                    '« '.Str::limit($serviceRequest->project_name, 40).' » · '.Str::limit($validated['message'], 80),
                    'chat_message',
                    [
                        'conversation_id' => $conversation->id,
                        'message_id' => $message->id,
                        'sender_id' => $responder->id,
                        'sender_name' => $responder->name,
                        'service_request_id' => $serviceRequest->id,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('[ServiceRequest] Push de réponse impossible', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'message' => 'Votre réponse a été envoyée.',
            'data' => ['conversation_id' => $conversation->id],
        ], 201);
    }

    /**
     * Liste des demandes de l'utilisateur connecté.
     */
    public function myRequests(Request $request): JsonResponse
    {
        $requests = DigitalizationRequest::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($requests);
    }

    /**
     * Supprime une demande de l'utilisateur connecté.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $deleted = DigitalizationRequest::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'Demande introuvable.',
            ], 404);
        }

        return response()->json([
            'message' => 'Demande supprimée avec succès.',
        ]);
    }

    /**
     * Supprime plusieurs demandes de l'utilisateur connecté.
     */
    public function bulkDestroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $count = DigitalizationRequest::where('user_id', $request->user()->id)
            ->whereIn('id', $validated['ids'])
            ->delete();

        return response()->json([
            'message' => "{$count} demande(s) supprimée(s).",
            'deleted_count' => $count,
        ]);
    }
}
