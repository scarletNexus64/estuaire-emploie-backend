<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{CommunityFiliere, CommunitySpecialty, CommunityDiscussion, CommunityReply, CommunityMentorApplication, CommunityAssistanceRequest, CommunityAssistanceMessage, User};
use App\Services\CommunityNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Storage};
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CommunityController extends Controller
{
    public function __construct(private CommunityNotificationService $notifications) {}

    private function active(CommunitySpecialty $specialty): void
    {
        abort_unless($specialty->is_active && $specialty->filiere?->is_active, 404);
    }

    private function moderator(User $user): bool
    {
        return $user->isSuperAdmin() || ($user->isAdmin() && $user->hasPermission('manage_premium_services')) || (bool) $user->is_forum_admin;
    }

    public function catalog()
    {
        $counts = CommunityDiscussion::where('kind', 'discussion')->selectRaw('community_specialty_id, count(*) as total')->groupBy('community_specialty_id')->pluck('total', 'community_specialty_id');
        $mentors = DB::table('community_mentor_specialties as s')->join('community_mentor_applications as m', 'm.id', '=', 's.application_id')
            ->where('m.status', 'approved')->where('m.is_available', true)->selectRaw('s.specialty_id, count(*) as total')->groupBy('s.specialty_id')->pluck('total', 's.specialty_id');
        $filieres = CommunityFiliere::where('is_active', true)->orderBy('display_order')->with(['specialties' => fn ($q) => $q->where('is_active', true)])->get();
        return response()->json(['data' => $filieres->map(fn ($f) => [
            'id' => $f->id, 'name' => $f->name, 'slug' => $f->slug,
            'specialties' => $f->specialties->map(fn ($s) => [
                'id' => $s->id, 'insam_ia_category_id' => $s->insam_ia_category_id, 'name' => $s->name,
                'slug' => $s->slug, 'description' => $s->description, 'filiere_id' => $f->id, 'filiere_name' => $f->name,
                'discussions_count' => (int) ($counts[$s->id] ?? 0), 'mentors_count' => (int) ($mentors[$s->id] ?? 0),
            ]),
        ])]);
    }

    public function account(Request $request)
    {
        return response()->json(['data' => [
            'user_id' => $request->user()->id,
            'recommended_category_id' => $request->user()->insam_ia_category_id,
            'is_moderator' => $this->moderator($request->user()),
            'mentor_application' => CommunityMentorApplication::where('user_id', $request->user()->id)->with('specialties')->first(),
        ]]);
    }

    public function discussions(Request $request, CommunitySpecialty $specialty)
    {
        $this->active($specialty);
        $data = $request->validate(['kind' => 'sometimes|in:discussion,resource']);
        return response()->json(['data' => CommunityDiscussion::where('community_specialty_id', $specialty->id)
            ->where('kind', $data['kind'] ?? 'discussion')->with('user')->withCount('replies')->latest('id')->paginate(20)]);
    }

    public function storeDiscussion(Request $request, CommunitySpecialty $specialty)
    {
        $this->active($specialty);
        $data = $request->validate([
            'title' => 'required|string|min:5|max:180', 'content' => 'required|string|min:10|max:10000',
            'kind' => 'required|in:discussion,resource', 'resource_url' => 'nullable|required_if:kind,resource|url:http,https|max:2000',
        ]);
        $discussion = CommunityDiscussion::create([
            ...$data, 'resource_url' => $data['kind'] === 'resource' ? $data['resource_url'] : null,
            'community_specialty_id' => $specialty->id, 'user_id' => $request->user()->id,
        ]);
        return response()->json(['data' => $discussion->load('user')], 201);
    }

    public function discussion(Request $request, CommunityDiscussion $discussion)
    {
        $this->active($discussion->specialty);
        return response()->json(['data' => $discussion->load('user', 'specialty', 'acceptedReply')->loadCount('replies')]);
    }

    public function replies(CommunityDiscussion $discussion)
    {
        $this->active($discussion->specialty);
        return response()->json(['data' => $discussion->replies()->with('user')->latest('id')->paginate(20)]);
    }

    public function reply(Request $request, CommunityDiscussion $discussion)
    {
        $this->active($discussion->specialty);
        $data = $request->validate(['content' => 'required|string|min:2|max:10000']);
        $reply = DB::transaction(function () use ($request, $discussion, $data) {
            $reply = $discussion->replies()->create([...$data, 'user_id' => $request->user()->id]);
            if ($discussion->user_id !== $request->user()->id) {
                $this->notifications->send($discussion->user_id, 'community_reply', 'Une réponse à votre discussion', $discussion->title, ['discussion_id' => $discussion->id]);
            }
            return $reply;
        });
        return response()->json(['data' => $reply->load('user')], 201);
    }

    public function resolution(Request $request, CommunityDiscussion $discussion)
    {
        abort_unless($discussion->user_id === $request->user()->id, 403);
        $this->active($discussion->specialty);
        abort_unless($discussion->kind === 'discussion', 422, 'Seule une discussion peut être marquée comme résolue.');
        $data = $request->validate(['is_resolved' => 'required|boolean', 'reply_id' => 'nullable|integer']);
        DB::transaction(function () use ($discussion, $data) {
            $locked = CommunityDiscussion::lockForUpdate()->findOrFail($discussion->id);
            $replyId = $data['reply_id'] ?? null;
            if ($replyId) $locked->replies()->findOrFail($replyId);
            $locked->update(['is_resolved' => $data['is_resolved'], 'accepted_reply_id' => $data['is_resolved'] ? $replyId : null]);
        });
        return response()->json(['data' => $discussion->fresh()]);
    }

    public function deleteDiscussion(Request $request, CommunityDiscussion $discussion)
    {
        abort_unless($discussion->user_id === $request->user()->id || $this->moderator($request->user()), 403);
        $discussion->delete();
        return response()->json(['success' => true]);
    }

    public function deleteReply(Request $request, CommunityReply $reply)
    {
        abort_unless($reply->user_id === $request->user()->id || $this->moderator($request->user()), 403);
        DB::transaction(function () use ($reply) {
            $discussion = CommunityDiscussion::lockForUpdate()->findOrFail($reply->community_discussion_id);
            if ($discussion->accepted_reply_id === $reply->id) $discussion->update(['accepted_reply_id' => null, 'is_resolved' => false]);
            $reply->delete();
        });
        return response()->json(['success' => true]);
    }

    public function mentors(CommunitySpecialty $specialty)
    {
        $this->active($specialty);
        return response()->json(['data' => CommunityMentorApplication::available()->whereHas('specialties', fn ($q) => $q->whereKey($specialty->id))
            ->select(['id', 'user_id', 'bio', 'experience', 'availability', 'is_available'])->with('user')->orderBy('id')->paginate(20)]);
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'specialty_ids' => 'required|array|min:1|max:5', 'specialty_ids.*' => 'required|integer|distinct|exists:community_specialties,id',
            'bio' => 'required|string|min:20|max:3000', 'experience' => 'required|string|min:20|max:3000',
            'availability' => 'required|string|max:500', 'proof_url' => 'nullable|url:http,https|max:2000',
        ]);
        $application = DB::transaction(function () use ($request, $data) {
            User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            $old = CommunityMentorApplication::where('user_id', $request->user()->id)->first();
            if ($old && $old->status !== 'rejected') throw ValidationException::withMessages(['bio' => 'Vous avez déjà une candidature en attente ou validée.']);
            foreach (CommunitySpecialty::whereIn('id', $data['specialty_ids'])->get() as $specialty) $this->active($specialty);
            $application = CommunityMentorApplication::updateOrCreate(['user_id' => $request->user()->id], [
                'bio' => $data['bio'], 'experience' => $data['experience'], 'availability' => $data['availability'],
                'proof_url' => $data['proof_url'] ?? null, 'status' => 'pending', 'is_available' => true,
                'review_message' => null, 'reviewed_at' => null, 'reviewed_by' => null,
            ]);
            $application->specialties()->sync($data['specialty_ids']);
            return $application;
        });
        return response()->json(['data' => $application->load('specialties')], 201);
    }

    public function availability(Request $request)
    {
        $data = $request->validate(['is_available' => 'required|boolean']);
        $application = CommunityMentorApplication::where('user_id', $request->user()->id)->where('status', 'approved')->firstOrFail();
        $application->update($data);
        return response()->json(['data' => $application->load('specialties')]);
    }

    private function eligible(User $user, CommunityAssistanceRequest $assistance): bool
    {
        return $assistance->requester_id !== $user->id && $assistance->status === 'pending'
            && ($assistance->mentor_id === null || $assistance->mentor_id === $user->id)
            && $assistance->specialty?->is_active && $assistance->specialty?->filiere?->is_active
            && CommunityMentorApplication::available()->where('user_id', $user->id)
                ->whereHas('specialties', fn ($q) => $q->whereKey($assistance->community_specialty_id))->exists();
    }

    private function participant(User $user, CommunityAssistanceRequest $assistance): bool
    {
        return $assistance->requester_id === $user->id || $assistance->mentor_id === $user->id;
    }

    public function assistanceList(Request $request)
    {
        $data = $request->validate(['scope' => 'sometimes|in:mine,mentor']);
        $query = CommunityAssistanceRequest::with('specialty', 'requester', 'mentor');
        if (($data['scope'] ?? 'mine') === 'mine') $query->where('requester_id', $request->user()->id);
        else {
            $application = CommunityMentorApplication::available()->where('user_id', $request->user()->id)->first();
            $ids = $application?->specialties()->where('is_active', true)->whereHas('filiere', fn ($q) => $q->where('is_active', true))->pluck('community_specialties.id')->all() ?? [];
            $query->where('requester_id', '!=', $request->user()->id)->where(function ($q) use ($request, $ids) {
                $q->where('mentor_id', $request->user()->id);
                if ($ids) $q->orWhere(fn ($q) => $q->whereNull('mentor_id')->where('status', 'pending')->whereIn('community_specialty_id', $ids));
            });
        }
        return response()->json(['data' => $query->latest('id')->paginate(20)]);
    }

    public function requestAssistance(Request $request)
    {
        $data = $request->validate([
            'specialty_id' => 'required|integer|exists:community_specialties,id', 'mentor_id' => 'nullable|integer|exists:users,id',
            'title' => 'required|string|min:5|max:180', 'description' => 'required|string|min:20|max:10000',
        ]);
        $specialty = CommunitySpecialty::findOrFail($data['specialty_id']);
        $this->active($specialty);
        $assistance = DB::transaction(function () use ($request, $data, $specialty) {
            User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            if (CommunityAssistanceRequest::where('requester_id', $request->user()->id)->whereIn('status', CommunityAssistanceRequest::ACTIVE)->count() >= 3) {
                throw ValidationException::withMessages(['title' => 'Vous avez déjà trois demandes actives. Terminez ou annulez une demande avant de continuer.']);
            }
            $mentors = CommunityMentorApplication::available()->where('user_id', '!=', $request->user()->id)
                ->whereHas('specialties', fn ($q) => $q->whereKey($specialty->id));
            if (! empty($data['mentor_id'])) {
                $mentors->where('user_id', $data['mentor_id']);
                if (! $mentors->exists()) throw ValidationException::withMessages(['mentor_id' => 'Ce mentor n’est pas disponible pour cette spécialité.']);
            }
            $assistance = CommunityAssistanceRequest::create([
                'community_specialty_id' => $specialty->id, 'requester_id' => $request->user()->id,
                'mentor_id' => $data['mentor_id'] ?? null, 'title' => $data['title'], 'description' => $data['description'], 'status' => 'pending',
            ]);
            foreach ($mentors->pluck('user_id') as $mentorId) {
                $this->notifications->send($mentorId, 'community_assistance', 'Nouvelle demande d’assistance', $assistance->title, ['assistance_id' => $assistance->id]);
            }
            return $assistance;
        });
        return response()->json(['data' => $assistance->load('specialty', 'requester', 'mentor')], 201);
    }

    public function assistance(Request $request, CommunityAssistanceRequest $assistance)
    {
        abort_unless($this->participant($request->user(), $assistance) || $this->eligible($request->user(), $assistance), 404);
        $data = $assistance->load('specialty', 'requester', 'mentor')->toArray();
        $data['can_accept'] = $this->eligible($request->user(), $assistance);
        $data['messages'] = $assistance->messages()->with('user')->latest('id')->paginate(30);
        return response()->json(['data' => $data])->header('Cache-Control', 'private, no-store');
    }

    public function transition(Request $request, CommunityAssistanceRequest $assistance)
    {
        $data = $request->validate(['action' => 'required|in:accept,start,complete,cancel,reject']);
        DB::transaction(function () use ($request, $assistance, $data) {
            $a = CommunityAssistanceRequest::lockForUpdate()->findOrFail($assistance->id);
            $owner = $a->requester_id === $request->user()->id;
            $mentor = $a->mentor_id === $request->user()->id;
            $allowed = match ($data['action']) {
                'accept' => $this->eligible($request->user(), $a),
                'reject' => $mentor && $a->status === 'pending',
                'start' => $mentor && $a->status === 'accepted',
                'complete' => ($owner || $mentor) && in_array($a->status, ['accepted', 'in_progress'], true),
                'cancel' => $owner && in_array($a->status, CommunityAssistanceRequest::ACTIVE, true),
            };
            abort_unless($allowed, 409, 'Cette action n’est plus disponible pour cette demande.');
            $a->status = ['accept' => 'accepted', 'reject' => 'rejected', 'start' => 'in_progress', 'complete' => 'completed', 'cancel' => 'cancelled'][$data['action']];
            if ($data['action'] === 'accept') $a->mentor_id = $request->user()->id;
            $a->save();
            $recipient = $owner ? $a->mentor_id : $a->requester_id;
            if ($recipient) {
                $labels = ['accepted' => 'acceptée', 'rejected' => 'refusée', 'in_progress' => 'en cours', 'completed' => 'terminée', 'cancelled' => 'annulée'];
                $this->notifications->send($recipient, 'community_assistance', 'Votre assistance évolue', "La demande « {$a->title} » est {$labels[$a->status]}.", ['assistance_id' => $a->id]);
            }
        });
        return response()->json(['data' => $assistance->fresh()->load('specialty', 'requester', 'mentor')]);
    }

    public function message(Request $request, CommunityAssistanceRequest $assistance)
    {
        abort_unless($this->participant($request->user(), $assistance), 404);
        $data = $request->validate([
            'content' => 'nullable|required_without:attachment|string|max:10000',
            'attachment' => 'nullable|required_without:content|file|mimes:pdf,jpg,jpeg,png,webp,txt|max:10240',
        ]);
        $path = null;
        try {
            $message = DB::transaction(function () use ($request, $assistance, $data, &$path) {
                $a = CommunityAssistanceRequest::lockForUpdate()->findOrFail($assistance->id);
                abort_unless($this->participant($request->user(), $a) && in_array($a->status, ['accepted', 'in_progress'], true), 409, 'La conversation est disponible pendant l’accompagnement.');
                $file = $request->file('attachment');
                if ($file) {
                    $path = $file->store('community-private/'.$a->id, 'local');
                    abort_unless($path, 500, 'La pièce jointe n’a pas pu être enregistrée.');
                }
                $message = $a->messages()->create([
                    'user_id' => $request->user()->id, 'content' => $data['content'] ?? null,
                    'attachment_path' => $path, 'attachment_name' => $file ? mb_substr(basename($file->getClientOriginalName()), 0, 255) : null,
                    'attachment_mime' => $file?->getMimeType(),
                ]);
                $recipient = $a->requester_id === $request->user()->id ? $a->mentor_id : $a->requester_id;
                $this->notifications->send($recipient, 'community_assistance', 'Nouveau message de votre accompagnement', $a->title, ['assistance_id' => $a->id]);
                return $message;
            });
        } catch (\Throwable $e) {
            if ($path) Storage::disk('local')->delete($path);
            throw $e;
        }
        return response()->json(['data' => $message->load('user')], 201);
    }

    public function attachment(Request $request, CommunityAssistanceRequest $assistance, CommunityAssistanceMessage $message)
    {
        abort_unless($this->participant($request->user(), $assistance) && $message->request_id === $assistance->id, 404);
        abort_unless($message->attachment_path && Storage::disk('local')->exists($message->attachment_path), 404);
        return Storage::disk('local')->download($message->attachment_path, $message->attachment_name, ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
