@extends('admin.layouts.app')

@section('title', 'Programmes')
@section('page-title', 'Gestion des Programmes')

@section('breadcrumbs')
    <span> / </span>
    <span>Programmes</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.programs.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau Programme
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Programmes</div>
                <div class="stat-value">{{ $programs->count() }}</div>
            </div>
            <div class="stat-icon">📚</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Actifs</div>
                <div class="stat-value">{{ $programs->where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Inactifs</div>
                <div class="stat-value">{{ $programs->where('is_active', false)->count() }}</div>
            </div>
            <div class="stat-icon">⏸</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Étapes</div>
                <div class="stat-value">{{ $programs->sum('steps_count') }}</div>
            </div>
            <div class="stat-icon">📝</div>
        </div>
    </div>
</div>

<!-- Programs by Type -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">🌟 Immersion Professionnelle</h5>
            </div>
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary);">
                    {{ $programs->where('type', 'immersion_professionnelle')->count() }}
                </div>
                <p class="small text-muted mb-0">programmes disponibles</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">💼 Entreprenariat</h5>
            </div>
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--success);">
                    {{ $programs->where('type', 'entreprenariat')->count() }}
                </div>
                <p class="small text-muted mb-0">programmes disponibles</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">🚀 Transformation Pro & Perso</h5>
            </div>
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: bold; color: var(--info);">
                    {{ $programs->where('type', 'transformation_professionnelle')->count() }}
                </div>
                <p class="small text-muted mb-0">programmes disponibles</p>
            </div>
        </div>
    </div>
</div>

<!-- Programs Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Programme</th>
                    <th>Type</th>
                    <th>Packs Requis</th>
                    <th>Durée</th>
                    <th>Étapes</th>
                    <th>Ordre</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($programs as $program)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="font-size: 2rem;">{{ $program->icon }}</div>
                            <div>
                                <strong>{{ $program->title }}</strong>
                                <div class="small text-muted">{{ Str::limit($program->description, 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $typeColors = [
                                'immersion_professionnelle' => 'info',
                                'entreprenariat' => 'success',
                                'transformation_professionnelle' => 'warning'
                            ];
                            $typeIcons = [
                                'immersion_professionnelle' => '🌟',
                                'entreprenariat' => '💼',
                                'transformation_professionnelle' => '🚀'
                            ];
                        @endphp
                        <span class="badge badge-{{ $typeColors[$program->type] ?? 'secondary' }}">
                            {{ $typeIcons[$program->type] ?? '' }} {{ $program->type_display }}
                        </span>
                    </td>
                    <td>
                        @if($program->required_packs && count($program->required_packs) > 0)
                            <div style="display: flex; gap: 0.25rem; flex-wrap: wrap;">
                                @foreach($program->required_packs as $pack)
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
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($program->duration_weeks)
                            <span class="badge badge-info">{{ $program->duration_weeks }} semaines</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-secondary">{{ $program->steps_count }} étape(s)</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $program->order }}</span>
                    </td>
                    <td>
                        @if($program->is_active)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-secondary">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.programs.show', $program) }}" class="btn btn-sm btn-info" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.programs.manage-steps', $program) }}" class="btn btn-sm btn-primary" title="Gérer les étapes">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-sm btn-secondary" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')">
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
                    <td colspan="8" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📚</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucun programme trouvé</p>
                        <p style="margin-bottom: 1.5rem;">Créez un nouveau programme pour aider les candidats</p>
                        <a href="{{ route('admin.programs.create') }}" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouveau Programme
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
