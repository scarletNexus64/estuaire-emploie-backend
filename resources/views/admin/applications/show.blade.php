@extends('admin.layouts.app')

@section('title', 'Détails Candidature')
@section('page-title', 'Détails de la Candidature')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Candidature de {{ $application->user?->name ?? 'N/A' }}</h3>
            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations du Candidat</h4>
                    <p><strong>Nom:</strong> {{ $application->user?->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $application->user?->email ?? 'N/A' }}</p>
                    <p><strong>Téléphone:</strong> {{ $application->user?->phone ?? 'N/A' }}</p>
                    <p><strong>Niveau d'expérience:</strong> {{ ucfirst($application->user?->experience_level ?? 'N/A') }}</p>

                    @if($application->user?->skills)
                        <p><strong>Compétences:</strong> {{ $application->user->skills }}</p>
                    @endif

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Offre d'Emploi</h4>
                    <p><strong>Titre:</strong> {{ $application->job?->title ?? 'N/A' }}</p>
                    <p><strong>Entreprise:</strong> {{ $application->job?->company?->name ?? 'N/A' }}</p>

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Lettre de Motivation</h4>
                    <p style="white-space: pre-wrap;">{{ $application->cover_letter ?? 'Aucune lettre de motivation fournie' }}</p>

                    @if($application->portfolio_url)
                        <p><strong>Portfolio:</strong> <a href="{{ $application->portfolio_url }}" target="_blank">{{ $application->portfolio_url }}</a></p>
                    @endif
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Statut de la Candidature</h4>

                    <form action="{{ route('admin.applications.status', $application) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label class="form-label">Changer le statut</label>
                            <select name="status" class="form-control">
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="viewed" {{ $application->status === 'viewed' ? 'selected' : '' }}>Vue</option>
                                <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Retenue</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejetée</option>
                                <option value="interview" {{ $application->status === 'interview' ? 'selected' : '' }}>Entretien</option>
                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Acceptée</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes internes</label>
                            <textarea name="internal_notes" class="form-control" rows="4">{{ $application->internal_notes }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>

                    <div style="margin-top: 2rem;">
                        <p><strong>Date de candidature:</strong> {{ $application->created_at->format('d/m/Y H:i') }}</p>
                        @if($application->viewed_at)
                            <p><strong>Vue le:</strong> {{ $application->viewed_at->format('d/m/Y H:i') }}</p>
                        @endif
                        @if($application->responded_at)
                            <p><strong>Répondu le:</strong> {{ $application->responded_at->format('d/m/Y H:i') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
