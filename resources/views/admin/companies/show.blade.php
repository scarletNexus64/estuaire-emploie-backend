@extends('admin.layouts.app')

@section('title', 'Détails Entreprise')
@section('page-title', 'Détails de l\'Entreprise')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $company->name }}</h3>
            <div>
                @if($company->status === 'pending')
                    <form action="{{ route('admin.companies.verify', $company) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">Vérifier</button>
                    </form>
                @endif
                @if($company->status !== 'suspended')
                    <form action="{{ route('admin.companies.suspend', $company) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Suspendre</button>
                    </form>
                @endif
                <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-primary">Éditer</a>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <!-- Logo Section -->
            @if($company->logo)
            <div style="text-align: center; margin-bottom: 2rem;">
                <img src="{{ $company->logo_url }}"
                     alt="Logo {{ $company->name }}"
                     style="max-width: 200px; max-height: 200px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                     onerror="this.style.display='none'">
            </div>
            @endif

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Générales</h4>
                    <p><strong>Email:</strong> {{ $company->email }}</p>
                    <p><strong>Téléphone:</strong> {{ $company->phone ?? 'N/A' }}</p>
                    <p><strong>Secteur:</strong> {{ $company->sector }}</p>
                    <p><strong>Site web:</strong>
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                        @else
                            N/A
                        @endif
                    </p>
                    <p><strong>Ville:</strong> {{ $company->city ?? 'N/A' }}</p>
                    <p><strong>Pays:</strong> {{ $company->country }}</p>
                    <p><strong>Statut:</strong>
                        @if($company->status === 'verified')
                            <span class="badge badge-success">Vérifiée</span>
                        @elseif($company->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @else
                            <span class="badge badge-danger">Suspendue</span>
                        @endif
                    </p>
                    <p><strong>Plan:</strong>
                        @if($company->subscription_plan === 'premium')
                            <span class="badge badge-success">Premium</span>
                        @else
                            <span class="badge badge-secondary">Gratuit</span>
                        @endif
                    </p>
                    <p><strong>Date d'inscription:</strong> {{ $company->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Description</h4>
                    <p>{{ $company->description ?? 'Aucune description disponible' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recruteurs ({{ $company->recruiters->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($company->recruiters as $recruiter)
                        <tr>
                            <td>{{ $recruiter->user?->name ?? 'N/A' }}</td>
                            <td>{{ $recruiter->user?->email ?? 'N/A' }}</td>
                            <td>{{ $recruiter->position ?? 'N/A' }}</td>
                            <td>
                                @if($recruiter->can_publish) <span class="badge badge-success">Publier</span> @endif
                                @if($recruiter->can_view_applications) <span class="badge badge-info">Voir candidatures</span> @endif
                                @if($recruiter->can_modify_company) <span class="badge badge-warning">Modifier entreprise</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem;">Aucun recruteur</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Offres d'Emploi ({{ $company->jobs->count() }})</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Candidatures</th>
                        <th>Vues</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($company->jobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>
                                @if($job->status === 'published')
                                    <span class="badge badge-success">Publié</span>
                                @elseif($job->status === 'pending')
                                    <span class="badge badge-warning">En attente</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($job->status) }}</span>
                                @endif
                            </td>
                            <td><span class="badge badge-info">{{ $job->applications->count() }}</span></td>
                            <td>{{ $job->views_count }}</td>
                            <td>{{ $job->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary btn-sm">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem;">Aucune offre</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
