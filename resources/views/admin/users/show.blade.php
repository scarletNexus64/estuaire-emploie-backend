@extends('admin.layouts.app')

@section('title', 'Détails Candidat')
@section('page-title', 'Profil du Candidat')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $user->name }}</h3>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Personnelles</h4>
                    <p><strong>Nom:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Téléphone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>Niveau d'expérience:</strong> {{ ucfirst($user->experience_level ?? 'N/A') }}</p>
                    <p><strong>Score de visibilité:</strong> {{ $user->visibility_score }}/100</p>
                    <p><strong>Date d'inscription:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>

                    @if($user->bio)
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Biographie</h4>
                        <p>{{ $user->bio }}</p>
                    @endif

                    @if($user->skills)
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Compétences</h4>
                        <p>{{ $user->skills }}</p>
                    @endif

                    @if($user->portfolio_url)
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Portfolio</h4>
                        <p><a href="{{ $user->portfolio_url }}" target="_blank">{{ $user->portfolio_url }}</a></p>
                    @endif
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Statistiques</h4>
                    <div class="stat-card">
                        <div class="stat-label">Total Candidatures</div>
                        <div class="stat-value">{{ $user->applications->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historique des Candidatures</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->applications as $application)
                        <tr>
                            <td>{{ $application->job?->title ?? 'N/A' }}</td>
                            <td>{{ $application->job?->company?->name ?? 'N/A' }}</td>
                            <td>
                                @if($application->status === 'pending')
                                    <span class="badge badge-warning">En attente</span>
                                @elseif($application->status === 'shortlisted')
                                    <span class="badge badge-success">Retenue</span>
                                @elseif($application->status === 'rejected')
                                    <span class="badge badge-danger">Rejetée</span>
                                @else
                                    <span class="badge badge-info">{{ ucfirst($application->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary btn-sm">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">Aucune candidature</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
