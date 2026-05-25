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
                    <p><strong>Adresse:</strong> {{ $company->address ?? 'N/A' }}</p>
                    <p><strong>Ville:</strong> {{ $company->city ?? 'N/A' }}</p>
                    <p><strong>Pays:</strong> {{ $company->country }}</p>
                    <p><strong>Coordonnées GPS:</strong>
                        @if($company->latitude && $company->longitude)
                            <span style="color: #28a745;">
                                📍 {{ number_format($company->latitude, 6) }}, {{ number_format($company->longitude, 6) }}
                            </span>
                            <a href="https://www.google.com/maps?q={{ $company->latitude }},{{ $company->longitude }}"
                               target="_blank"
                               class="btn btn-sm btn-info"
                               style="margin-left: 10px;">
                                🗺️ Voir sur Google Maps
                            </a>
                        @else
                            <span style="color: #dc3545;">Non renseignées</span>
                        @endif
                    </p>
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

            <!-- Google Maps Preview Section -->
            @if($company->latitude && $company->longitude)
            <div style="margin-top: 2rem;">
                <h4 style="margin-bottom: 1rem; font-weight: 600;">🗺️ Localisation sur la Carte</h4>
                <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <iframe
                        width="100%"
                        height="400"
                        frameborder="0"
                        style="border:0"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAffUHSFli6kMnjkfJOKBGO6AN828ixJPo&q={{ $company->latitude }},{{ $company->longitude }}&zoom=15"
                        allowfullscreen>
                    </iframe>
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6c757d;">
                    📍 Coordonnées: {{ number_format($company->latitude, 6) }}, {{ number_format($company->longitude, 6) }}
                </p>
            </div>
            @elseif($company->address || $company->city)
            <div style="margin-top: 2rem; padding: 1.5rem; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 8px;">
                <h4 style="margin-bottom: 0.5rem; color: #856404;">⚠️ Coordonnées GPS manquantes</h4>
                <p style="margin: 0; color: #856404;">
                    Cette entreprise a une adresse mais pas de coordonnées GPS.
                    <a href="{{ route('admin.companies.edit', $company) }}" style="color: #004085; text-decoration: underline;">
                        Cliquez ici pour les ajouter
                    </a>
                </p>
            </div>
            @endif
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
