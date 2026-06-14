@extends('admin.layouts.app')

@section('title', 'Demande de Digitalisation')
@section('page-title', $digitalizationRequest->project_name)

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.digitalization-requests.index') }}">Digitalisation</a>
    <span> / </span>
    <span>{{ $digitalizationRequest->project_name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.digitalization-requests.index') }}" class="btn btn-secondary">
        ← Retour à la liste
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Détails de la demande -->
    <div class="card">
        <div style="padding: 1.5rem;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.125rem; font-weight: 700;">Informations de la demande</h3>

            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Nom du projet</span>
                    <strong>{{ $digitalizationRequest->project_name }}</strong>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Entreprise</span>
                    <span>{{ $digitalizationRequest->company_name ?? '—' }}</span>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Ville</span>
                    <span>{{ $digitalizationRequest->city ?? '—' }}</span>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Quartier</span>
                    <span>{{ $digitalizationRequest->district ?? '—' }}</span>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Contact</span>
                    <strong>{{ $digitalizationRequest->contact }}</strong>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Demandeur (compte)</span>
                    <span>{{ $digitalizationRequest->user->name ?? 'Utilisateur supprimé' }}</span>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Reçue le</span>
                    <span>{{ $digitalizationRequest->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1rem; align-items: start;">
                    <span style="color: var(--secondary); font-weight: 600;">Description</span>
                    <p style="white-space: pre-line; margin: 0;">{{ $digitalizationRequest->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Traitement -->
    <div class="card" style="height: fit-content;">
        <div style="padding: 1.5rem;">
            <h3 style="margin-bottom: 1rem; font-size: 1.125rem; font-weight: 700;">Traitement</h3>

            <div style="margin-bottom: 1rem;">
                <span style="color: var(--secondary); font-weight: 600;">Statut actuel : </span>
                @if($digitalizationRequest->status === 'pending')
                    <span class="badge badge-warning">En attente</span>
                @elseif($digitalizationRequest->status === 'processed')
                    <span class="badge badge-success">Traitée</span>
                @else
                    <span class="badge badge-danger">Rejetée</span>
                @endif
            </div>

            @if($digitalizationRequest->processed_at)
                <p style="color: var(--secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                    Mise à jour le {{ $digitalizationRequest->processed_at->format('d/m/Y à H:i') }}
                </p>
            @endif

            <form method="POST" action="{{ route('admin.digitalization-requests.process', $digitalizationRequest) }}">
                @csrf
                @method('PATCH')

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ $digitalizationRequest->status === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processed" {{ $digitalizationRequest->status === 'processed' ? 'selected' : '' }}>Traitée</option>
                        <option value="rejected" {{ $digitalizationRequest->status === 'rejected' ? 'selected' : '' }}>Rejetée</option>
                    </select>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label class="form-label">Notes internes</label>
                    <textarea name="admin_notes" class="form-control" rows="4" placeholder="Notes (optionnel)">{{ $digitalizationRequest->admin_notes }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection
