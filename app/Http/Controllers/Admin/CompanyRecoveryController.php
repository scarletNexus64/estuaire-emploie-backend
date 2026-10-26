<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCompanyRecoveryDelivery;
use App\Models\CompanyRecoveryRequest;
use App\Models\CompanyRecoveryDelivery;
use App\Services\CompanyRecoveryService;
use Illuminate\Http\Request;

class CompanyRecoveryController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->isAdmin() && $request->user()->hasPermission('manage_companies'), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin($request);
        $status = $request->validate(['status' => 'nullable|in:pending,approved,rejected,all'])['status'] ?? 'pending';
        $claims = CompanyRecoveryRequest::with(['company.recruiters.user', 'requester', 'resultUser', 'reviewer', 'deliveries'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))->latest()->paginate(20)->withQueryString();
        $competing = CompanyRecoveryRequest::whereIn('company_id', $claims->pluck('company_id'))->where('status', 'pending')->get()->groupBy('company_id');
        return view('admin.company-recoveries.index', compact('claims', 'status', 'competing'));
    }

    public function review(Request $request, int $claim, CompanyRecoveryService $service)
    {
        $this->authorizeAdmin($request);
        $data = $request->validate([
            'decision' => 'required|in:approved,rejected',
            'admin_reply' => 'required_if:decision,rejected|nullable|string|max:2000',
            'verified' => $request->input('decision') === 'approved' ? 'required|accepted' : 'nullable',
        ]);
        $service->review($claim, $request->user(), $data['decision'] === 'approved', $data['admin_reply'] ?? null);
        return back()->with('success', 'Décision enregistrée. La notification est disponible et les emails sont mis en file d’envoi.');
    }

    public function resend(Request $request, int $claim, CompanyRecoveryService $service)
    {
        $this->authorizeAdmin($request);
        $service->resendCredentials($claim, $request->user());
        return back()->with('success', 'Un nouveau mot de passe temporaire a été généré. Son envoi est en attente.');
    }

    public function retry(Request $request, int $claim)
    {
        $this->authorizeAdmin($request);
        foreach (CompanyRecoveryDelivery::where('request_id', $claim)->whereNull('sent_at')->whereNull('cancelled_at')->get() as $delivery) {
            SendCompanyRecoveryDelivery::dispatch($delivery->id);
        }
        return back()->with('success', 'Les envois en attente ont été relancés.');
    }
}
