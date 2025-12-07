@extends('admin.layouts.app')

@section('title', 'Candidatures')
@section('page-title', 'Gestion des Candidatures')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Candidatures</h3>
            <div>
                <select class="form-control" onchange="window.location.href='?status=' + this.value" style="width: auto; display: inline-block;">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="viewed" {{ request('status') === 'viewed' ? 'selected' : '' }}>Vues</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Retenues</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetées</option>
                    <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Entretien</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Email</th>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Date de soumission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>
                                <strong>{{ $application->user->name }}</strong>
                            </td>
                            <td>{{ $application->user->email }}</td>
                            <td>{{ $application->job->title }}</td>
                            <td>{{ $application->job->company->name }}</td>
                            <td>
                                @if($application->status === 'pending')
                                    <span class="badge badge-warning">En attente</span>
                                @elseif($application->status === 'viewed')
                                    <span class="badge badge-info">Vue</span>
                                @elseif($application->status === 'shortlisted')
                                    <span class="badge badge-success">Retenue</span>
                                @elseif($application->status === 'rejected')
                                    <span class="badge badge-danger">Rejetée</span>
                                @elseif($application->status === 'interview')
                                    <span class="badge badge-success">Entretien</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($application->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary btn-sm">Voir Détails</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                Aucune candidature trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div style="padding: 1.5rem;">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
@endsection
