<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Database\Seeders\SupportAccountSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Support in-app : conversations ouvertes depuis l'application vers le compte
 * officiel « Estuaire Emploi ».
 *
 * Distinct de `MessagerieController`, qui traite les e-mails (`MailboxThread`).
 * Ici il s'agit des `Conversation`/`Message` de l'application : la réponse
 * écrite depuis cet écran arrive directement dans le chat de l'utilisateur,
 * diffusée par le même événement websocket que les messages entre membres.
 */
class SupportConversationController extends Controller
{
    /** Le compte qui incarne le support, ou `null` s'il n'a pas été semé. */
    protected function supportUser(): ?User
    {
        return User::where('email', SupportAccountSeeder::EMAIL)->first();
    }

    /**
     * Boîte de réception : toutes les conversations adressées au support.
     */
    public function index(Request $request): View
    {
        $support = $this->supportUser();

        if (! $support) {
            return view('admin.support.index', [
                'conversations' => collect(),
                'support' => null,
                'search' => null,
                'filter' => 'all',
            ]);
        }

        $filter = $request->get('filter', 'all'); // all | unanswered
        $search = trim((string) $request->get('search', ''));

        $conversations = Conversation::query()
            ->where(fn ($q) => $q->where('user_one', $support->id)
                ->orWhere('user_two', $support->id))
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->withCount('messages')
            ->when($search !== '', function ($q) use ($search, $support) {
                // On cherche sur l'interlocuteur, jamais sur le compte support.
                $q->where(function ($qq) use ($search, $support) {
                    $qq->whereHas('userOne', fn ($u) => $u->where('id', '!=', $support->id)
                        ->where(fn ($w) => $w->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")))
                        ->orWhereHas('userTwo', fn ($u) => $u->where('id', '!=', $support->id)
                            ->where(fn ($w) => $w->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")));
                });
            })
            ->get()
            ->sortByDesc(fn ($c) => optional($c->lastMessage)->created_at ?? $c->created_at);

        if ($filter === 'unanswered') {
            // Sans réponse : le dernier message ne vient pas du support.
            $conversations = $conversations->filter(
                fn ($c) => $c->lastMessage && (int) $c->lastMessage->sender_id !== (int) $support->id
            );
        }

        return view('admin.support.index', [
            'conversations' => $conversations->values(),
            'support' => $support,
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    /**
     * Fil d'une conversation, et formulaire de réponse.
     */
    public function show(Conversation $conversation): View|RedirectResponse
    {
        $support = $this->supportUser();

        if (! $support || ! $this->belongsToSupport($conversation, $support)) {
            return redirect()
                ->route('admin.support.index')
                ->with('error', "Cette conversation ne concerne pas le support.");
        }

        // Les messages de l'utilisateur sont considérés lus dès l'ouverture du
        // fil : c'est le moment où un opérateur en prend connaissance.
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', $support->id)
            ->where('status', '!=', 'read')
            ->update(['status' => 'read']);

        return view('admin.support.show', [
            'conversation' => $conversation->load(['userOne', 'userTwo']),
            'messages' => $conversation->messages()->with('user')->orderBy('created_at')->get(),
            'support' => $support,
            'contact' => $this->contactOf($conversation, $support),
        ]);
    }

    /**
     * Publie une réponse au nom du compte support.
     */
    public function reply(Request $request, Conversation $conversation): RedirectResponse
    {
        $support = $this->supportUser();

        if (! $support || ! $this->belongsToSupport($conversation, $support)) {
            return redirect()
                ->route('admin.support.index')
                ->with('error', "Cette conversation ne concerne pas le support.");
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $support->id,
            'message' => $validated['message'],
            'status' => 'sent',
        ]);

        // Même événement que les messages entre membres : la réponse apparaît
        // en direct dans l'application, sans traitement particulier côté client.
        broadcast(new MessageSent($message))->toOthers();

        return redirect()
            ->route('admin.support.show', $conversation)
            ->with('success', 'Réponse envoyée.');
    }

    /** `true` si le compte support est l'un des deux participants. */
    protected function belongsToSupport(Conversation $conversation, User $support): bool
    {
        return (int) $conversation->user_one === (int) $support->id
            || (int) $conversation->user_two === (int) $support->id;
    }

    /** L'utilisateur qui a écrit au support. */
    protected function contactOf(Conversation $conversation, User $support): ?User
    {
        return (int) $conversation->user_one === (int) $support->id
            ? $conversation->userTwo
            : $conversation->userOne;
    }
}
