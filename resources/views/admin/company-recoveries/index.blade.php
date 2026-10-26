@extends('admin.layouts.app')
@section('title', 'Récupérations d’entreprise')
@section('page-title', 'Récupérations d’entreprise')
@push('styles')
<style>
.recovery-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,290px),1fr));gap:24px}
.recovery-card{margin-bottom:24px;padding:24px}.recovery-note{white-space:pre-wrap;overflow-wrap:anywhere;background:#f1f5f9;padding:16px;border-radius:12px;color:#182235}
.recovery-field{display:block;margin:12px 0}.recovery-field textarea{width:100%;padding:12px;border:1px solid #cbd5e1;border-radius:8px}.recovery-muted{color:#64748b;font-size:14px}
.recovery-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}
</style>
@endpush
@section('content')
<p style="margin-bottom:20px">Vérifiez le lien du demandeur avec l’entreprise, même lorsqu’il est le seul à la réclamer. Aucun document d’immatriculation n’est obligatoire.</p>
<div class="recovery-actions" style="margin-bottom:24px">
@foreach(['pending'=>'En attente','approved'=>'Approuvées','rejected'=>'Refusées','all'=>'Toutes'] as $key=>$label)
<a class="btn {{ $status === $key ? 'btn-primary' : 'btn-secondary' }}" href="{{ route('admin.company-recoveries.index', ['status'=>$key]) }}">{{ $label }}</a>
@endforeach
</div>
@if($errors->any())<div role="alert" class="recovery-note" style="color:#b91c1c;margin-bottom:20px">{{ $errors->first() }}</div>@endif
@forelse($claims as $claim)
<article class="card recovery-card" id="request-{{ $claim->id }}">
    <h3>#{{ $claim->id }} — {{ $claim->company?->name ?? 'Entreprise indisponible' }}</h3>
    <p class="recovery-muted">{{ $claim->company?->city }} · {{ $claim->created_at->format('d/m/Y H:i') }} · {{ \App\Models\CompanyRecoveryRequest::LABELS[$claim->status] }}</p>
    <p><strong>{{ $claim->mode === 'attach' ? 'Rattachement au compte existant' : 'Récupération du compte complet' }}</strong></p>
    <div class="recovery-grid" style="margin-top:20px">
        <div>
            <h4>Demandeur</h4>
            <p>{{ $claim->name }} — {{ $claim->position }}</p><p>Téléphone : {{ $claim->phone }}</p>
            @if($claim->mode === 'account')<p>Email vérifié : {{ $claim->reply_email }}</p>@else
            <p>Compte #{{ $claim->requester_user_id }} : {{ $claim->requester?->email ?? 'Compte supprimé' }}</p>@endif
            <p class="recovery-note" style="margin-top:12px">{{ $claim->explanation }}</p>
        </div>
        <div>
            <h4>Vérification de l’entreprise</h4>
            <p>{{ $claim->company?->address }}</p><p>{{ $claim->company?->sector }}</p>
            @if($claim->company?->latitude !== null && $claim->company?->longitude !== null)
            <a href="https://www.google.com/maps/search/?api=1&amp;query={{ $claim->company->latitude }},{{ $claim->company->longitude }}" target="_blank" rel="noopener noreferrer">Voir la position sur la carte</a>@endif
            <p>Propriété : {{ $claim->company?->recovery_claimed_at ? 'Déjà récupérée' : 'Non réclamée' }}</p>
            <p>Gestionnaires actuels :</p>
            @foreach($claim->company?->recruiters ?? [] as $recruiter)<p class="recovery-muted">#{{ $recruiter->user_id }} {{ $recruiter->user?->name }} — {{ $recruiter->user?->email }}</p>@endforeach
            @php $others = ($competing[$claim->company_id] ?? collect())->where('id', '!=', $claim->id); @endphp
            @if($others->isNotEmpty())
                <p style="color:#b45309"><strong>{{ $others->count() }} autre(s) demande(s) en attente pour cette entreprise.</strong></p>
                @foreach($others as $other)<p>#{{ $other->id }} — {{ $other->name }} · {{ $other->phone }} · {{ $other->position }}</p><p class="recovery-note">{{ $other->explanation }}</p>@endforeach
            @else<p class="recovery-muted">Aucune autre demande en attente. Cela ne constitue pas une preuve de propriété.</p>@endif
        </div>
        <div>
            @if($claim->status === 'pending')
            <form method="POST" action="{{ route('admin.company-recoveries.review', $claim->id) }}">
                @csrf
                <label class="recovery-field">Réponse au demandeur (obligatoire pour refuser)
                    <textarea name="admin_reply" rows="4" maxlength="2000"></textarea>
                </label>
                <label><input type="checkbox" name="verified" value="1"> J’ai vérifié le lien du demandeur avec cette entreprise et examiné les autres demandes.</label>
                <div class="recovery-actions">
                    @if($claim->mode === 'attach' || auth()->user()->hasPermission('manage_users'))
                    <button type="submit" name="decision" value="approved" class="btn btn-primary">{{ $claim->mode === 'account' ? 'Approuver et générer les identifiants' : 'Approuver le rattachement' }}</button>
                    @else<p>La permission de gérer les utilisateurs est nécessaire pour récupérer le compte complet.</p>@endif
                    <button type="submit" name="decision" value="rejected" class="btn btn-secondary">Refuser</button>
                </div>
            </form>
            @else
                <p>Décision de {{ $claim->reviewer?->name ?? 'l’administration' }} le {{ $claim->reviewed_at?->format('d/m/Y H:i') }}</p>
                @if($claim->admin_reply)<p class="recovery-note">{{ $claim->admin_reply }}</p>@endif
            @endif
            @foreach($claim->deliveries->where('channel', 'mail') as $delivery)
                <p class="recovery-muted">Email #{{ $delivery->id }} : {{ $delivery->sent_at ? 'Envoyé le '.$delivery->sent_at->format('d/m/Y H:i') : ($delivery->cancelled_at ? 'Expiré / remplacé' : 'En attente d’envoi') }}</p>
                @if($delivery->last_error)<p style="color:#b45309">{{ $delivery->last_error }}</p>@endif
            @endforeach
            @if($claim->deliveries->whereNull('sent_at')->whereNull('cancelled_at')->isNotEmpty())
                <form method="POST" action="{{ route('admin.company-recoveries.retry', $claim->id) }}">@csrf<button class="btn btn-secondary">Relancer les envois en attente</button></form>
            @endif
            @if($claim->mode === 'account' && $claim->status === 'approved' && $claim->resultUser?->must_change_password && auth()->user()->hasPermission('manage_users'))
                <form method="POST" style="margin-top:12px" action="{{ route('admin.company-recoveries.resend', $claim->id) }}">@csrf<button class="btn btn-secondary">Régénérer et renvoyer le mot de passe temporaire</button></form>
            @endif
        </div>
    </div>
</article>
@empty<div class="card recovery-card">Aucune demande pour ce filtre.</div>@endforelse
{{ $claims->links() }}
@endsection
