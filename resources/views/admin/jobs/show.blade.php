@extends('admin.layouts.app')

@section('title', 'Détails Offre')
@section('page-title', 'Détails de l\'Offre d\'Emploi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $job->title }}</h3>
            <div>
                @if($job->status === 'pending')
                    <form action="{{ route('admin.jobs.publish', $job) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Publier</button>
                    </form>
                @endif
                <form action="{{ route('admin.jobs.feature', $job) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">
                        {{ $job->is_featured ? 'Retirer ⭐' : 'Mettre en avant ⭐' }}
                    </button>
                </form>
                <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-primary">Éditer</a>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Description du poste</h4>
                    <p style="white-space: pre-wrap;">{{ $job->description }}</p>

                    @if($job->requirements)
                        <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Exigences</h4>
                        <p style="white-space: pre-wrap;">{{ $job->requirements }}</p>
                    @endif

                    @if($job->benefits)
                        <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Avantages</h4>
                        <p style="white-space: pre-wrap;">{{ $job->benefits }}</p>
                    @endif
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations</h4>
                    <p><strong>Entreprise:</strong> {{ $job->company?->name ?? 'N/A' }}</p>
                    <p><strong>Catégorie:</strong> {{ $job->category?->name ?? 'N/A' }}</p>
                    <p><strong>Localisation:</strong> {{ $job->location?->name ?? 'N/A' }}</p>
                    <p><strong>Type de contrat:</strong> {{ $job->contractType?->name ?? 'N/A' }}</p>
                    <p><strong>Niveau d'expérience:</strong> {{ ucfirst($job->experience_level ?? 'N/A') }}</p>

                    @if($job->salary_min || $job->salary_max)
                        <p><strong>Salaire:</strong>
                            {{ $job->salary_min ? number_format($job->salary_min) : '' }}
                            {{ $job->salary_min && $job->salary_max ? '-' : '' }}
                            {{ $job->salary_max ? number_format($job->salary_max) : '' }}
                            FCFA
                            @if($job->salary_negotiable) (Négociable) @endif
                        </p>
                    @endif

                    <p><strong>Statut:</strong>
                        @if($job->status === 'published')
                            <span class="badge badge-success">Publié</span>
                        @elseif($job->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($job->status) }}</span>
                        @endif
                    </p>

                    @if($job->is_featured)
                        <p><strong>Mise en avant:</strong> <span class="badge badge-warning">⭐ Oui</span></p>
                    @endif

                    <p><strong>Vues:</strong> {{ $job->views_count }}</p>
                    <p><strong>Candidatures:</strong> {{ $job->applications->count() }}</p>
                    <p><strong>Date limite:</strong> {{ $job->application_deadline ? $job->application_deadline->format('d/m/Y') : 'N/A' }}</p>
                    <p><strong>Publié le:</strong> {{ $job->published_at ? $job->published_at->format('d/m/Y H:i') : 'N/A' }}</p>
                    <p><strong>Publié par:</strong> {{ $job->postedBy?->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Candidatures ({{ $job->applications->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($job->applications as $application)
                        <tr>
                            <td>{{ $application->user?->name ?? 'N/A' }}</td>
                            <td>{{ $application->user?->email ?? 'N/A' }}</td>
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
