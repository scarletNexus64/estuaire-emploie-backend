<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CompanyRecoveryMail;
use App\Models\Company;
use App\Models\CompanyRecoveryRequest;
use App\Models\User;
use App\Services\CompanyRecoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class CompanyRecoveryController extends Controller
{
    public function __construct(private CompanyRecoveryService $service) {}

    public function companies(Request $request)
    {
        $data = $request->validate([
            'q' => 'nullable|string|max:100', 'city' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255', 'page' => 'nullable|integer|min:1',
        ]);
        $viewer = auth('sanctum')->user();
        $query = $this->catalogQuery($viewer)->with('categories');
        if (!empty($data['q'])) {
            $term = '%'.addcslashes(trim($data['q']), '%_\\').'%';
            $query->where(fn ($q) => $q->where('name', 'like', $term)->orWhere('address', 'like', $term));
        }
        if (!empty($data['city'])) $query->where('city', $data['city']);
        if (!empty($data['domain'])) $query->whereHas('categories', fn ($q) => $q->where('level_1', $data['domain']));
        $page = $query->orderBy('city')->orderBy('name')->paginate(30);
        $ids = $page->getCollection()->pluck('id');
        $available = $this->service->eligibleQuery()->whereIn('id', $ids)->pluck('id')->all();
        $claims = $this->latestClaims($viewer, $ids->all());
        $page->through(fn ($company) => $this->companyData($company, in_array($company->id, $available, true), $claims->get($company->id)));
        return response()->json(['data' => $page])->header('Cache-Control', 'private, no-store');
    }

    private function ownedRequests(User $viewer)
    {
        return CompanyRecoveryRequest::where(fn ($q) => $q->where('requester_user_id', $viewer->id)->orWhere('result_user_id', $viewer->id));
    }

    private function catalogQuery(?User $viewer)
    {
        return Company::where('status', 'verified')->where(function ($q) use ($viewer) {
            $q->whereIn('id', $this->service->eligibleQuery()->select('companies.id'));
            if ($viewer) $q->orWhereIn('id', $this->ownedRequests($viewer)->select('company_id'));
        });
    }

    private function latestClaims(?User $viewer, array $companyIds)
    {
        if (!$viewer) return collect();
        return CompanyRecoveryRequest::whereIn('id', $this->ownedRequests($viewer)
            ->whereIn('company_id', $companyIds)->selectRaw('MAX(id)')->groupBy('company_id'))
            ->get()->keyBy('company_id');
    }

    private function companyData(Company $company, bool $available, ?CompanyRecoveryRequest $claim, bool $detail = false): array
    {
        $data = [
            'id' => $company->id, 'name' => $company->name, 'city' => $company->city,
            'address' => $company->address, 'sector' => $company->sector, 'logo_url' => $company->logo_url,
            'description' => $detail ? $company->description : Str::limit($company->description ?? '', 200),
            'cover_url' => $company->photos_urls[0] ?? null,
            'latitude' => $company->latitude, 'longitude' => $company->longitude,
            'domains' => $company->categories->pluck('level_1')->filter()->unique()->values(),
            'recovery_available' => $available,
            'can_request' => $available && !in_array($claim?->status, ['pending', 'approved'], true),
            'my_request' => $claim ? [
                'id' => $claim->id, 'status' => $claim->status, 'admin_reply' => $claim->admin_reply,
                'created_at' => $claim->created_at?->toIso8601String(), 'reviewed_at' => $claim->reviewed_at?->toIso8601String(),
            ] : null,
        ];
        if ($detail) {
            // Imported placeholder emails are not verified business contact addresses.
            $placeholderEmail = $company->recovery_source && (!$company->recovery_source_key
                || $company->recovery_source_key === hash('sha256', 'entreprises_cameroun:'.$company->email));
            $data += ['photos_urls' => $company->photos_urls, 'country' => $company->country,
                'phone' => $company->phone, 'email' => $placeholderEmail ? null : $company->email,
                'website' => $company->website, 'facebook' => $company->facebook, 'tiktok' => $company->tiktok];
        }
        return $data;
    }

    public function show(Company $company)
    {
        abort_unless($company->status === 'verified', 404);
        $company->load('categories');
        $available = $this->service->eligibleQuery()->whereKey($company->id)->exists();
        $claim = $this->latestClaims(auth('sanctum')->user(), [$company->id])->get($company->id);
        return response()->json(['data' => $this->companyData($company, $available, $claim, true)])
            ->header('Cache-Control', 'private, no-store');
    }

    public function filters()
    {
        $companies = $this->catalogQuery(auth('sanctum')->user())->with('categories:id,level_1')->get(['id', 'city']);
        return response()->json(['cities' => $companies->pluck('city')->filter()->unique()->sort()->values(),
            'domains' => $companies->flatMap(fn ($c) => $c->categories->map(fn ($category) => [
                'value' => $category->getRawOriginal('level_1'), 'label' => $category->level_1,
            ]))->filter(fn ($option) => !empty($option['value']))->unique('value')->sortBy('label')->values()])
            ->header('Cache-Control', 'private, no-store');
    }

    public function sendCode(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255', 'company_id' => 'required|integer']);
        $email = Str::lower(trim($data['email']));
        abort_unless($this->service->eligibleQuery()->whereKey($data['company_id'])->exists(), 422, 'Cette entreprise n’est plus disponible.');
        $key = 'company-recovery-email:'.hash('sha256', $email);
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json(['message' => 'Trop de codes demandés. Réessayez dans une heure.'], 429);
        }
        RateLimiter::hit($key, 3600);
        $id = (string) Str::uuid();
        $code = (string) random_int(100000, 999999);
        DB::table('company_recovery_email_challenges')->insert([
            'id' => $id, 'company_id' => $data['company_id'], 'email' => $email,
            'code_hash' => hash_hmac('sha256', $id.':'.$code, config('app.key')),
            'expires_at' => now()->addMinutes(10), 'created_at' => now(), 'updated_at' => now(),
        ]);
        try {
            Mail::mailer(config('company_recovery.mailer'))->to($email)->send(new CompanyRecoveryMail([
                'title' => 'Vérifiez votre email de réponse', 'code' => $code,
                'message' => 'Ce code confirme votre adresse email pour une demande de récupération d’entreprise. La demande sera ensuite examinée par l’administration.',
            ]));
        } catch (\Throwable $e) {
            DB::table('company_recovery_email_challenges')->where('id', $id)->delete();
            return response()->json(['message' => 'Le code n’a pas pu être envoyé. Réessayez plus tard.'], 503);
        }
        return response()->json(['challenge_id' => $id, 'message' => 'Code envoyé. Vérifiez également vos courriers indésirables.']);
    }

    public function attach(Request $request) { return $this->submit($request, 'attach'); }
    public function account(Request $request) { return $this->submit($request, 'account'); }

    private function submit(Request $request, string $mode)
    {
        $data = $request->validate([
            'company_id' => 'required|integer', 'name' => 'required|string|min:2|max:255',
            'phone' => 'required|string|min:6|max:30', 'position' => 'required|string|min:2|max:100',
            'explanation' => 'required|string|min:20|max:3000',
        ] + ($mode === 'account' ? [
            'reply_email' => 'required|email|max:255', 'challenge_id' => 'required|uuid', 'code' => 'required|digits:6',
        ] : []));
        if ($mode === 'attach') abort_unless($request->user()->is_active && !$request->user()->isAdmin(), 403);
        return DB::transaction(function () use ($data, $mode, $request) {
            $company = Company::lockForUpdate()->findOrFail($data['company_id']);
            $this->service->assertAvailable($company);
            $email = $mode === 'account' ? Str::lower(trim($data['reply_email'])) : null;
            if ($mode === 'account') {
                $challenge = DB::table('company_recovery_email_challenges')->where('id', $data['challenge_id'])->lockForUpdate()->first();
                if (!$challenge || $challenge->email !== $email || (int) $challenge->company_id !== $company->id
                    || $challenge->consumed_at || now()->gte($challenge->expires_at) || $challenge->attempts >= 5) {
                    return response()->json(['message' => 'Code invalide ou expiré. Demandez un nouveau code.'], 422);
                }
                // Return, do not throw: failed attempts must be committed.
                DB::table('company_recovery_email_challenges')->where('id', $challenge->id)->increment('attempts');
                if (!hash_equals($challenge->code_hash, hash_hmac('sha256', $challenge->id.':'.$data['code'], config('app.key')))) {
                    return response()->json(['message' => 'Code invalide ou expiré.'], 422);
                }
                if (User::withTrashed()->where('email', $email)->exists()) {
                    return response()->json(['message' => 'Cet email est déjà associé à un compte. Connectez-vous pour demander le rattachement de l’entreprise.'], 422);
                }
            }
            $duplicates = CompanyRecoveryRequest::where('company_id', $company->id)->where('status', 'pending');
            $mode === 'attach' ? $duplicates->where('requester_user_id', $request->user()->id) : $duplicates->where('reply_email', $email);
            if ($duplicates->exists()) {
                return response()->json(['message' => 'Vous avez déjà une demande en attente pour cette entreprise.'], 422);
            }
            if ($mode === 'account') DB::table('company_recovery_email_challenges')->where('id', $data['challenge_id'])->update(['consumed_at' => now()]);
            $claim = CompanyRecoveryRequest::create([
                'company_id' => $company->id, 'mode' => $mode,
                'requester_user_id' => $mode === 'attach' ? $request->user()->id : null,
                'name' => trim($data['name']), 'phone' => trim($data['phone']), 'position' => trim($data['position']),
                'explanation' => trim($data['explanation']), 'reply_email' => $email,
            ]);
            if ($mode === 'account') {
                $this->service->enqueue($claim, 'mail', ['email' => $email,
                    'title' => 'Votre demande a été reçue',
                    'message' => 'Votre demande n°'.$claim->id.' pour '.$company->name.' est en attente de vérification. La réponse sera envoyée à cette adresse. Aucun accès n’est accordé avant approbation.',
                ]);
            }
            return response()->json(['message' => 'Votre demande a été transmise à l’administration.', 'data' => $claim->customerData()], 201);
        });
    }

    public function mine(Request $request)
    {
        $page = CompanyRecoveryRequest::with('company')->where(function ($q) use ($request) {
            $q->where('requester_user_id', $request->user()->id)->orWhere('result_user_id', $request->user()->id);
        })->latest()->paginate(20);
        $page->through(fn ($claim) => $claim->customerData());
        return response()->json(['data' => $page]);
    }
}
