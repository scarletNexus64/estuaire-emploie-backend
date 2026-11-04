<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Conversation;
use App\Models\User;
use App\Services\Chat\ConversationPresenter;
use App\Services\Chat\MessageRequestService;
use App\Services\Social\SocialPresenter;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Annuaire de la messagerie (personnes et entreprises, classés A→Z) et
 * demandes de message : on écrit un premier message, le destinataire
 * l'accepte avant que la conversation ne s'ouvre.
 */
class MessagingController extends Controller
{
    public function __construct(private readonly MessageRequestService $requests)
    {
    }

    // ───────────────────────────── Annuaire ─────────────────────────────

    /**
     * GET /contacts/people?q=&scope=all|network&page=&per_page=
     *
     * SocialUserCard + `conversation` ({id, status} ou null), classés par nom.
     * `scope=network` : abonnements, abonnés et personnes déjà contactées.
     */
    public function people(Request $request): JsonResponse
    {
        $me = (int) $request->user()->id;
        $q = $this->query($request);

        $users = User::query()
            ->select(SocialPresenter::CARD_COLUMNS)
            ->with(SocialPresenter::cardRelations())
            ->where('users.is_active', true)
            ->where('users.role', '!=', 'admin')
            ->where('users.id', '!=', $me)
            ->whereRaw("TRIM(COALESCE(users.name, '')) != ''");

        if ($request->query('scope') === 'network') {
            $users->where(function (Builder $w) use ($me) {
                $w->whereIn('users.id', fn ($s) => $s->select('followed_id')->from('user_follows')->where('follower_id', $me))
                    ->orWhereIn('users.id', fn ($s) => $s->select('follower_id')->from('user_follows')->where('followed_id', $me))
                    ->orWhereIn('users.id', fn ($s) => $s->select('user_two')->from('conversations')->where('user_one', $me))
                    ->orWhereIn('users.id', fn ($s) => $s->select('user_one')->from('conversations')->where('user_two', $me));
            });
        }

        if ($q !== null) {
            $like = $this->like($q);
            $users->where(function (Builder $w) use ($like) {
                $w->where('users.name', 'like', $like)
                    ->orWhere('users.specialty', 'like', $like)
                    ->orWhereHas('currentCompany', fn ($c) => $c->where('companies.name', 'like', $like));
            });
        }

        // simplePaginate : pas de COUNT(*) à chaque page (scroll infini).
        $page = $users->orderBy('users.name')->orderBy('users.id')
            ->simplePaginate(SocialPresenter::perPage($request->query('per_page', 30)));

        $links = $this->conversationsWith($me, $page->getCollection()->pluck('id'));
        $cards = SocialPresenter::userCards($page->getCollection());
        foreach ($cards as &$card) {
            $card['conversation'] = ConversationPresenter::contactLink($links->get((int) $card['id']), $me);
        }

        return response()->json([
            'success' => true,
            'data' => $cards,
            'meta' => $this->meta($page),
        ]);
    }

    /**
     * GET /contacts/companies?q=&scope=all|followed&page=&per_page=
     *
     * Entreprises joignables (au moins un recruteur actif), hors celles dont
     * on est membre, classées par nom.
     */
    public function companies(Request $request): JsonResponse
    {
        $me = (int) $request->user()->id;
        $q = $this->query($request);

        $companies = Company::query()
            ->where('companies.status', '!=', 'suspended')
            ->whereRaw("TRIM(COALESCE(companies.name, '')) != ''")
            ->whereHas('recruiters.user', fn ($u) => $u->where('is_active', true)->where('users.id', '!=', $me))
            ->whereDoesntHave('recruiters', fn ($r) => $r->where('user_id', $me))
            ->withExists(['follows as is_followed' => fn ($f) => $f->where('user_id', $me)])
            ->with(['recruiters' => fn ($r) => $r
                ->select(['id', 'company_id', 'user_id', 'can_modify_company'])
                ->whereHas('user', fn ($u) => $u->where('is_active', true))
                ->orderByDesc('can_modify_company')
                ->orderBy('id')]);

        if ($request->query('scope') === 'followed') {
            $companies->whereHas('follows', fn ($f) => $f->where('user_id', $me));
        }

        if ($q !== null) {
            $like = $this->like($q);
            $companies->where(function (Builder $w) use ($like) {
                $w->where('companies.name', 'like', $like)
                    ->orWhere('companies.sector', 'like', $like)
                    ->orWhere('companies.city', 'like', $like);
            });
        }

        $page = $companies->orderBy('companies.name')->orderBy('companies.id')
            ->simplePaginate(SocialPresenter::perPage($request->query('per_page', 30)));

        $contactIds = $page->getCollection()
            ->mapWithKeys(fn (Company $c) => [$c->id => (int) ($c->recruiters->first()?->user_id ?? 0)]);
        $links = $this->conversationsWith($me, $contactIds->values()->filter());

        $data = $page->getCollection()->map(function (Company $company) use ($me, $contactIds, $links) {
            $contactId = $contactIds->get($company->id);

            return [
                'id' => $company->id,
                'name' => $company->name,
                'logo' => $company->logo_url,
                'sector' => $company->sector,
                'city' => $company->city,
                'is_verified' => (bool) $company->is_verified,
                'is_followed' => (bool) $company->is_followed,
                'followers_count' => (int) ($company->getAttributes()['followers_count'] ?? 0),
                'conversation' => ConversationPresenter::contactLink($contactId ? $links->get($contactId) : null, $me),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => $this->meta($page),
        ]);
    }

    // ─────────────────────────── Demandes ───────────────────────────

    /**
     * POST /conversations/request {user_id | company_id, message}
     *
     * 201 « requested » (demande créée), 200 « sent » (conversation déjà
     * ouverte : le message est simplement envoyé), 409 « request_pending »
     * (une demande attend déjà une réponse).
     */
    public function request(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'nullable|required_without:company_id|integer|exists:users,id',
            'company_id' => 'nullable|required_without:user_id|integer|exists:companies,id',
            'message' => 'required|string|min:1|max:1000',
        ]);

        $sender = $request->user();
        $company = null;

        if (! empty($validated['company_id'])) {
            $company = Company::find($validated['company_id']);
            if (! $company || $company->status === 'suspended') {
                return $this->fail('contact_unavailable', 404);
            }
            if ($company->recruiters()->where('user_id', $sender->id)->exists()) {
                return $this->fail('cannot_message_self', 422);
            }
            $target = $this->requests->companyContact($company);
        } else {
            $target = User::find($validated['user_id']);
        }

        if (! $target || ! $target->is_active || $target->role === 'admin') {
            return $this->fail('contact_unavailable', 404);
        }
        if ((int) $target->id === (int) $sender->id) {
            return $this->fail('cannot_message_self', 422);
        }

        [$conversation, $outcome] = $this->requests->request(
            $sender,
            $target,
            trim($validated['message']),
            $company,
        );

        if ($outcome === 'already_pending') {
            return response()->json([
                'success' => false,
                'code' => 'request_pending',
                'message' => __('conversation.request_pending'),
                'conversation' => $this->present($conversation, (int) $sender->id),
            ], 409);
        }

        return response()->json([
            'success' => true,
            'outcome' => $outcome,
            'message' => __($outcome === 'requested' ? 'conversation.request_sent' : 'conversation.message_sent'),
            'conversation' => $this->present($conversation, (int) $sender->id),
        ], $outcome === 'requested' ? 201 : 200);
    }

    /** GET /conversations/{conversation} — en-tête du chat (statut, interlocuteur). */
    public function show(Request $request, int $conversation): JsonResponse
    {
        $me = (int) $request->user()->id;
        $item = $this->participantConversation($conversation, $me);

        return response()->json([
            'success' => true,
            'data' => $this->present($item, $me),
        ]);
    }

    /** POST /conversations/{conversation}/accept — par le destinataire. */
    public function accept(Request $request, int $conversation): JsonResponse
    {
        $me = (int) $request->user()->id;
        $item = $this->participantConversation($conversation, $me);

        if ($item->isAwaitingReplyFor($me)) {
            return $this->fail('request_not_yours', 403);
        }

        $this->requests->accept($item, $request->user());

        return response()->json([
            'success' => true,
            'message' => __('conversation.request_accepted'),
            'data' => $this->present($item->refresh(), $me),
        ]);
    }

    /** POST /conversations/{conversation}/decline — par le destinataire. */
    public function decline(Request $request, int $conversation): JsonResponse
    {
        $me = (int) $request->user()->id;
        $item = $this->participantConversation($conversation, $me);

        if (! $item->isRequestFor($me)) {
            return $this->fail('request_not_yours', 403);
        }

        $this->requests->decline($item);

        return response()->json([
            'success' => true,
            'message' => __('conversation.request_declined'),
        ]);
    }

    // ─────────────────────────── Outils ───────────────────────────

    private function participantConversation(int $id, int $me): Conversation
    {
        return Conversation::query()
            ->whereKey($id)
            ->where(fn ($q) => $q->where('user_one', $me)->orWhere('user_two', $me))
            ->firstOrFail();
    }

    private function present(Conversation $conversation, int $me): array
    {
        $conversation->load(ConversationPresenter::relations());
        $unread = $conversation->messages()
            ->where('sender_id', '!=', $me)
            ->where('status', '!=', 'read')
            ->count();

        return ConversationPresenter::item($conversation, $me, $unread);
    }

    /**
     * Dernière conversation « libre » (hors candidature) entre moi et chacun
     * des utilisateurs donnés, indexée par l'id de l'autre.
     *
     * @return Collection<int, Conversation>
     */
    private function conversationsWith(int $me, Collection $userIds): Collection
    {
        $ids = $userIds->map(fn ($id) => (int) $id)->filter()->unique()->values();
        if ($ids->isEmpty()) {
            return collect();
        }

        return Conversation::query()
            ->whereNull('application_id')
            ->where(function ($q) use ($me, $ids) {
                $q->where(fn ($w) => $w->where('user_one', $me)->whereIn('user_two', $ids))
                    ->orWhere(fn ($w) => $w->where('user_two', $me)->whereIn('user_one', $ids));
            })
            ->orderBy('id')
            ->get()
            ->keyBy(fn (Conversation $c) => $c->otherUserId($me));
    }

    /** Méta de pagination sans total : la page suivante existe-t-elle ? */
    private function meta(Paginator $page): array
    {
        $current = $page->currentPage();

        return [
            'current_page' => $current,
            'last_page' => $page->hasMorePages() ? $current + 1 : $current,
            'per_page' => $page->perPage(),
            'has_more' => $page->hasMorePages(),
        ];
    }

    private function query(Request $request): ?string
    {
        $q = trim(preg_replace('/\s+/u', ' ', (string) $request->query('q', '')));

        return mb_strlen($q) >= 1 ? mb_substr($q, 0, 100) : null;
    }

    private function like(string $q): string
    {
        return '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q).'%';
    }

    private function fail(string $code, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code' => $code,
            'message' => __('conversation.'.$code),
        ], $status);
    }
}
