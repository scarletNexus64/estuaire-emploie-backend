<?php

namespace App\Services\Chat;

use App\Models\Conversation;

/**
 * Forme JSON d'une conversation, vue par un participant (liste, détail,
 * réponses des demandes de message).
 *
 * `status` est déjà traduit pour le lecteur : « request » (demande reçue, à
 * accepter), « pending » (demande envoyée, en attente) ou « accepted ».
 */
class ConversationPresenter
{
    /** Relations nécessaires à item(). */
    public static function relations(): array
    {
        return [
            'lastMessage',
            'userOne:id,name,profile_photo,role',
            'userOne.presence:user_id,online,last_seen',
            'userTwo:id,name,profile_photo,role',
            'userTwo.presence:user_id,online,last_seen',
            'company:id,name,logo',
        ];
    }

    public static function item(Conversation $conversation, int $userId, ?int $unread = null): array
    {
        $otherUser = (int) $conversation->user_one === $userId
            ? $conversation->userTwo
            : $conversation->userOne;

        $company = $conversation->company;
        $last = $conversation->lastMessage;

        return [
            'conversation_id' => $conversation->id,
            'status' => $conversation->statusFor($userId),
            'is_requester' => $conversation->requested_by !== null
                && (int) $conversation->requested_by === $userId,
            'user' => $otherUser ? [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'profile_photo' => $otherUser->profile_photo,
                'role' => $otherUser->role,
                'is_online' => (bool) ($otherUser->presence?->online ?? false),
                'last_seen' => optional($otherUser->presence)->last_seen,
            ] : null,
            'company' => $company ? [
                'id' => $company->id,
                'name' => $company->name,
                'logo' => $company->logo_url,
            ] : null,
            'last_message' => $last ? [
                'message' => $last->message,
                'status' => $last->status,
                'sender_id' => $last->sender_id,
                'sent_at' => $last->created_at?->toDateTimeString(),
            ] : null,
            'unread_count' => $unread ?? (int) ($conversation->unread_count ?? 0),
            'created_at' => $conversation->created_at?->toDateTimeString(),
        ];
    }

    /** Résumé compact joint aux cartes de l'annuaire. */
    public static function contactLink(?Conversation $conversation, int $userId): ?array
    {
        if (! $conversation) {
            return null;
        }

        return [
            'id' => $conversation->id,
            'status' => $conversation->statusFor($userId),
        ];
    }
}
