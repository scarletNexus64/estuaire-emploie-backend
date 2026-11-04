<?php

namespace App\Services\Chat;

use App\Events\MessageSent;
use App\Models\Company;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Demandes de message de l'annuaire.
 *
 * - Le demandeur écrit un premier message : la conversation naît
 *   « pending » et le destinataire reçoit un push « X souhaite vous écrire ».
 * - Tant qu'elle n'est pas acceptée, le demandeur ne peut plus écrire.
 * - Le destinataire accepte (ou répond, ce qui vaut acceptation) : le
 *   demandeur reçoit un push « X a accepté votre demande ».
 * - Un refus masque la demande chez le destinataire ; le demandeur la voit
 *   toujours « en attente » (le refus n'est pas exposé).
 */
class MessageRequestService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    /**
     * Interlocuteur d'une entreprise : le recruteur qui gère la fiche, sinon
     * le premier recruteur actif.
     */
    public function companyContact(Company $company): ?User
    {
        $recruiter = $company->recruiters()
            ->whereHas('user', fn ($q) => $q->where('is_active', true))
            ->orderByDesc('can_modify_company')
            ->orderBy('id')
            ->with('user')
            ->first();

        return $recruiter?->user;
    }

    /**
     * Envoie une demande (ou un message si la conversation est déjà ouverte).
     *
     * @return array{0: Conversation, 1: string} conversation + issue
     *               (« requested », « sent », « already_pending »)
     */
    public function request(User $sender, User $target, string $text, ?Company $company = null): array
    {
        $senderId = (int) $sender->id;
        $targetId = (int) $target->id;

        $existing = Conversation::between($senderId, $targetId)
            ->orderByDesc('id')
            ->first();

        if ($existing) {
            if ($existing->isAwaitingReplyFor($senderId)) {
                return [$existing, 'already_pending'];
            }

            // Demande reçue (ou refusée) puis on écrit soi-même : c'est accepter.
            if ($existing->isPending() || $existing->isDeclined()) {
                $this->markAccepted($existing, $sender);
            }
            if ($company && ! $existing->company_id) {
                $existing->company_id = $company->id;
                $existing->save();
            }

            $this->post($existing, $sender, $text);

            return [$existing, 'sent'];
        }

        $conversation = DB::transaction(function () use ($senderId, $targetId, $company, $text) {
            $conversation = Conversation::create([
                'user_one' => $senderId,
                'user_two' => $targetId,
                'application_id' => null,
                'status' => Conversation::STATUS_PENDING,
                'requested_by' => $senderId,
                'company_id' => $company?->id,
            ]);

            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $senderId,
                'message' => $text,
                'status' => 'sent',
            ]);

            return $conversation;
        });

        $this->push(
            $target,
            'conversation.push.request_title',
            ['name' => $sender->name],
            mb_substr($text, 0, 100),
            'message_request',
            $conversation,
            $sender,
        );

        Log::info('💬 Demande de message envoyée', [
            'conversation_id' => $conversation->id,
            'from' => $senderId,
            'to' => $targetId,
            'company_id' => $company?->id,
        ]);

        return [$conversation, 'requested'];
    }

    /** Le destinataire accepte : statut ouvert + push au demandeur. */
    public function accept(Conversation $conversation, User $recipient): void
    {
        $this->markAccepted($conversation, $recipient);
    }

    public function decline(Conversation $conversation): void
    {
        $conversation->status = Conversation::STATUS_DECLINED;
        $conversation->save();
    }

    /**
     * Passe la conversation en « accepted » et prévient le demandeur.
     * Sans effet si elle l'est déjà.
     */
    public function markAccepted(Conversation $conversation, User $recipient): void
    {
        if ($conversation->status === Conversation::STATUS_ACCEPTED) {
            return;
        }

        $conversation->status = Conversation::STATUS_ACCEPTED;
        $conversation->accepted_at = now();
        $conversation->save();

        $requester = $conversation->requested_by ? User::find($conversation->requested_by) : null;
        if ($requester && (int) $requester->id !== (int) $recipient->id) {
            $this->push(
                $requester,
                'conversation.push.accepted_title',
                ['name' => $recipient->name],
                null,
                'message_request_accepted',
                $conversation,
                $recipient,
                'conversation.push.accepted_body',
            );
        }
    }

    /** Message dans une conversation ouverte : diffusion temps réel + push. */
    private function post(Conversation $conversation, User $sender, string $text): Message
    {
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'message' => $text,
            'status' => 'sent',
        ]);
        $message->load('user:id,name,profile_photo');

        broadcast(new MessageSent($message))->toOthers();

        $receiver = User::find($conversation->otherUserId((int) $sender->id));
        if ($receiver) {
            $this->push(
                $receiver,
                'conversation.push.message_title',
                ['name' => $sender->name],
                mb_substr($text, 0, 100),
                'chat_message',
                $conversation,
                $sender,
                null,
                ['message_id' => $message->id],
            );
        }

        return $message;
    }

    /** Push dans la langue du destinataire. */
    private function push(
        User $to,
        string $titleKey,
        array $replace,
        ?string $body,
        string $type,
        Conversation $conversation,
        User $from,
        ?string $bodyKey = null,
        array $extra = [],
    ): void {
        try {
            $locale = $to->locale ?: config('app.locale');
            $title = __($titleKey, $replace, $locale);
            $text = $body ?? ($bodyKey ? __($bodyKey, $replace, $locale) : '');

            $this->notifications->sendToUser($to, $title, $text, $type, [
                'conversation_id' => $conversation->id,
                'sender_id' => $from->id,
                'sender_name' => $from->name,
            ] + $extra);
        } catch (\Throwable $e) {
            Log::warning('💬 Push de messagerie non envoyé', [
                'type' => $type,
                'to' => $to->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
