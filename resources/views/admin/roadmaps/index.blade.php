@extends('admin.layouts.app')

@section('title', 'Roadmaps')
@section('page-title', 'Gestion des Roadmaps')

@section('breadcrumbs')
    <span> / </span>
    <span>Roadmaps</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.roadmaps.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Roadmap
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Roadmaps</div>
                <div class="stat-value">{{ $roadmaps->count() }}</div>
            </div>
            <div class="stat-icon">🗺️</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Actives</div>
                <div class="stat-value">{{ $roadmaps->where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Inactives</div>
                <div class="stat-value">{{ $roadmaps->where('is_active', false)->count() }}</div>
            </div>
            <div class="stat-icon">⏸</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Niveaux</div>
                <div class="stat-value">{{ $roadmaps->sum('levels_count') }}</div>
            </div>
            <div class="stat-icon">🎯</div>
        </div>
    </div>
</div>

<!-- Roadmaps Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Roadmap</th>
                    <th>Domaine</th>
                    <th>Difficulté</th>
                    <th>Packs Requis</th>
                    <th>Niveaux</th>
                    <th>Seuil QCM</th>
                    <th>Ordre</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roadmaps as $roadmap)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 2rem;">{{ $roadmap->icon }}</div>
                            <div>
                                <strong>{{ $roadmap->title }}</strong>
                                <div class="small text-muted">{{ Str::limit($roadmap->description, 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $domains[$roadmap->domain] ?? $roadmap->domain }}</span>
                    </td>
                    <td>
                        @php
                            $diffColors = [
                                'beginner' => 'success',
                                'intermediate' => 'info',
                                'advanced' => 'warning',
                                'expert' => 'danger',
                            ];
                        @endphp
                        <span class="badge badge-{{ $diffColors[$roadmap->difficulty] ?? 'secondary' }}">
                            {{ $roadmap->difficulty_display }}
                        </span>
                    </td>
                    <td>
                        @if($roadmap->required_packs && count($roadmap->required_packs) > 0)
                            <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                @foreach($roadmap->required_packs as $pack)
                                    @php
                                        $packInfo = [
                                            'C1' => ['icon' => '🥈', 'color' => 'secondary'],
                                            'C2' => ['icon' => '🥇', 'color' => 'warning'],
                                            'C3' => ['icon' => '💎', 'color' => 'primary']
                                        ][$pack] ?? ['icon' => '📦', 'color' => 'secondary'];
                                    @endphp
                                    <span class="badge badge-{{ $packInfo['color'] }}">{{ $packInfo['icon'] }} {{ $pack }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="badge badge-success">Gratuit</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $roadmap->levels_count }} niveau(x)</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $roadmap->pass_threshold }}%</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $roadmap->order }}</span>
                    </td>
                    <td>
                        @if($roadmap->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.roadmaps.show', $roadmap) }}" class="btn btn-sm btn-info" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.roadmaps.manage-levels', $roadmap) }}" class="btn btn-sm btn-primary" title="Gérer les niveaux">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.roadmaps.edit', $roadmap) }}" class="btn btn-sm btn-secondary" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('admin.roadmaps.destroy', $roadmap) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette roadmap ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🗺️</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucune roadmap trouvée</p>
                        <p style="margin-bottom: 1.5rem;">Créez une roadmap d'apprentissage gamifiée pour les étudiants</p>
                        <a href="{{ route('admin.roadmaps.create') }}" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle Roadmap
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
