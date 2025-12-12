@extends('admin.layouts.app')

@section('title', 'Services Premium')
@section('page-title', 'Services Premium Candidats')

@section('breadcrumbs')
    <span>/ Monétisation / Services Premium</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.premium-services.create') }}" class="header-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Créer un Service
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Services Premium pour Candidats</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Gérez les services additionnels proposés aux chercheurs d'emploi
        </p>
    </div>

    @if($services->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">✨</div>
            <h3 style="color: #64748b; margin-bottom: 1rem;">Aucun service premium</h3>
            <a href="{{ route('admin.premium-services.create') }}" class="btn btn-primary">
                Créer le premier service
            </a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Type</th>
                        <th>Prix</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Achats</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="font-size: 2rem;">{{ $service->icon ?? '✨' }}</div>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b;">{{ $service->name }}</div>
                                        <div style="font-size: 0.875rem; color: #64748b;">{{ Str::limit($service->description, 60) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: {{ $service->color ?? '#667eea' }}">
                                    {{ strtoupper(str_replace('_', ' ', $service->service_type)) }}
                                </span>
                            </td>
                            <td>
                                <strong style="color: #1e293b;">{{ number_format($service->price, 0, ',', ' ') }} FCFA</strong>
                            </td>
                            <td>
                                @if($service->duration_days)
                                    {{ $service->duration_days }} jours
                                @else
                                    <span class="badge badge-success">Permanent</span>
                                @endif
                            </td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Inactif</span>
                                @endif
                                @if($service->is_popular)
                                    <span class="badge" style="background: #f59e0b;">⭐ Populaire</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $service->userServices->count() }}</strong>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.premium-services.edit', $service->id) }}" class="btn btn-sm btn-primary">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.premium-services.toggle', $service->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $service->is_active ? 'btn-warning' : 'btn-success' }}">
                                            @if($service->is_active)
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @else
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.premium-services.destroy', $service->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce service ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
