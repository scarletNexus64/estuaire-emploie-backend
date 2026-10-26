@extends('admin.layouts.app')
@section('title', 'Communauté et mentors')
@section('page-title', 'Communauté et mentors')
@section('content')
@if($errors->any())<div role="alert" style="padding:16px;color:#b91c1c">{{ $errors->first() }}</div>@endif
<div class="card" style="margin-bottom:24px"><div class="card-body">
<h3>Candidatures de mentors ({{ $applications->total() }})</h3>
<p>Les candidatures en attente apparaissent en premier. Seuls les mentors validés et disponibles sont proposés aux membres.</p>
@forelse($applications as $application)
<div style="border:1px solid #e2e8f0;border-radius:12px;padding:20px;margin:16px 0">
    <h4>{{ $application->user?->name }} · {{ ['pending'=>'En attente','approved'=>'Validée','rejected'=>'Refusée'][$application->status] }}</h4>
    <p><strong>Spécialités :</strong> {{ $application->specialties->pluck('name')->join(', ') }}</p>
    <p style="white-space:pre-wrap">{{ $application->bio }}</p>
    <p style="white-space:pre-wrap"><strong>Expérience :</strong> {{ $application->experience }}</p>
    <p><strong>Disponibilités :</strong> {{ $application->availability }}</p>
    @if($application->proof_url)<p><a href="{{ $application->proof_url }}" target="_blank" rel="noopener noreferrer">Consulter le justificatif / portfolio</a></p>@endif
    @if($application->review_message)<p><strong>Motif communiqué :</strong> {{ $application->review_message }}</p>@endif
    <form method="POST" action="{{ route('admin.community.review', $application) }}" style="display:flex;flex-direction:column;gap:12px;margin-top:16px">
        @csrf @method('PATCH')
        <label>Décision <select class="form-input" name="status" required><option value="approved">Valider</option><option value="rejected">Refuser / retirer la validation</option></select></label>
        <label>Message au candidat (obligatoire en cas de refus)<textarea class="form-input" name="review_message" rows="3" maxlength="2000" style="width:100%"></textarea></label>
        <button class="btn btn-primary" type="submit">Enregistrer la décision et notifier</button>
    </form>
</div>
@empty<p>Aucune candidature reçue.</p>@endforelse
{{ $applications->withQueryString()->links() }}
</div></div>
<div class="card" style="margin-bottom:24px"><div class="card-body">
<h3>Publications de la communauté</h3>
@forelse($discussions as $discussion)
<div style="border-bottom:1px solid #e2e8f0;padding:16px 0">
    <strong>{{ $discussion->title }}</strong> · {{ $discussion->specialty?->name }} · {{ $discussion->user?->name }}
    <p style="white-space:pre-wrap">{{ $discussion->content }}</p>
    @if($discussion->resource_url)<a href="{{ $discussion->resource_url }}" target="_blank" rel="noopener noreferrer">Ressource partagée</a>@endif
    <form method="POST" action="{{ route('admin.community.destroy', $discussion) }}" onsubmit="return confirm('Retirer cette publication et ses réponses ?')" style="margin-top:12px">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Retirer la publication</button></form>
</div>
@empty<p>Aucune publication.</p>@endforelse
{{ $discussions->withQueryString()->links() }}
</div></div>
<div class="card"><div class="card-body">
<h3>Demandes d’assistance</h3>
@forelse($requests as $assistance)
<div style="border-bottom:1px solid #e2e8f0;padding:12px 0"><strong>#{{ $assistance->id }} · {{ $assistance->title }}</strong><p>{{ $assistance->requester?->name }} → {{ $assistance->mentor?->name ?? 'Mentor à attribuer' }} · {{ $assistance->specialty?->name }}</p><p>{{ ['pending'=>'En attente','accepted'=>'Acceptée','in_progress'=>'En cours','completed'=>'Terminée','cancelled'=>'Annulée','rejected'=>'Refusée'][$assistance->status] }}</p></div>
@empty<p>Aucune demande d’assistance.</p>@endforelse
{{ $requests->withQueryString()->links() }}
</div></div>
@endsection
