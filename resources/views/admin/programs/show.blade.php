@extends('admin.layouts.app')

@section('title', $program->title)
@section('page-title', $program->title)

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.programs.index') }}">Programmes</a>
    <span> / </span>
    <span>{{ $program->title }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.programs.manage-steps', $program) }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Gérer les Étapes
    </a>
    <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Modifier
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Program Details Card -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 3rem;">{{ $program->icon }}</div>
                <div style="flex: 1;">
                    <h5 class="card-title mb-1">{{ $program->title }}</h5>
                    @php
                        $typeColors = [
                            'immersion_professionnelle' => 'info',
                            'entreprenariat' => 'success',
                            'transformation_professionnelle' => 'warning'
                        ];
                    @endphp
                    <span class="badge badge-{{ $typeColors[$program->type] ?? 'secondary' }}">
                        {{ $program->type_display }}
                    </span>
                </div>
                @if($program->is_active)
                    <span class="badge badge-success">Actif</span>
                @else
                    <span class="badge badge-secondary">Inactif</span>
                @endif
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Description</h6>
                    <p class="text-muted">{{ $program->description }}</p>
                </div>

                @if($program->objectives)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Objectifs</h6>
                    <div style="white-space: pre-line;" class="text-muted">{{ $program->objectives }}</div>
                </div>
                @endif

                <div class="row">
                    @if($program->duration_weeks)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <strong>Durée :</strong>
                                <span class="text-muted">{{ $program->duration_weeks }} semaines</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div>
                                <strong>Étapes :</strong>
                                <span class="text-muted">{{ $program->steps->count() }} étape(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Steps Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📝 Étapes du Programme</h5>
            </div>
            <div style="padding: 1.5rem;">
                @forelse($program->steps as $step)
                <div class="card mb-3" style="border-left: 4px solid var(--primary);">
                    <div style="padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <span class="badge badge-primary">Étape {{ $step->order }}</span>
                                    <h6 style="margin: 0; font-weight: 700;">{{ $step->title }}</h6>
                                    @if($step->is_required)
                                        <span class="badge badge-danger">Obligatoire</span>
                                    @endif
                                </div>
                                <p class="text-muted mb-2">{{ $step->description }}</p>

                                @if($step->estimated_duration_days)
                                <div class="small text-muted">
                                    ⏱ Durée estimée : {{ $step->estimated_duration_days }} jour(s)
                                </div>
                                @endif

                                @if($step->resources && count($step->resources) > 0)
                                <div class="mt-2">
                                    <strong class="small">Ressources :</strong>
                                    <ul class="small" style="margin: 0.25rem 0 0 1.5rem; padding: 0;">
                                        @foreach($step->resources as $resource)
                                        <li>
                                            <a href="{{ $resource['url'] }}" target="_blank">
                                                {{ $resource['title'] }}
                                                @if($resource['type'] === 'video') 🎥
                                                @elseif($resource['type'] === 'document') 📄
                                                @elseif($resource['type'] === 'article') 📰
                                                @else 🔗
                                                @endif
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                    <div>
                        <strong>Aucune étape pour le moment</strong>
                        <p class="mb-0">Cliquez sur "Gérer les Étapes" pour ajouter des étapes à ce programme.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📊 Statistiques</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <div class="stat-label">Total Étapes</div>
                    <div class="stat-value" style="font-size: 2rem;">{{ $program->steps->count() }}</div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Étapes Obligatoires</div>
                    <div class="stat-value" style="font-size: 2rem;">{{ $program->steps->where('is_required', true)->count() }}</div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Durée Totale Estimée</div>
                    <div class="stat-value" style="font-size: 2rem;">
                        {{ $program->steps->sum('estimated_duration_days') }} jour(s)
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">ℹ️ Informations</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <strong>Slug :</strong>
                    <div><code>{{ $program->slug }}</code></div>
                </div>
                <div class="mb-3">
                    <strong>Packs Requis :</strong>
                    @if($program->required_packs && count($program->required_packs) > 0)
                        <div style="display: flex; flex-direction: column; gap: 0.25rem; margin-top: 0.25rem;">
                            @foreach($program->required_packs as $pack)
                                @php
                                    $packInfo = [
                                        'C1' => ['name' => 'PACK C1 (ARGENT)', 'icon' => '🥈', 'color' => '#C0C0C0'],
                                        'C2' => ['name' => 'PACK C2 (OR)', 'icon' => '🥇', 'color' => '#FFD700'],
                                        'C3' => ['name' => 'PACK C3 (DIAMANT)', 'icon' => '💎', 'color' => '#E5E4E2']
                                    ][$pack] ?? ['name' => $pack, 'icon' => '📦', 'color' => '#6c757d'];
                                @endphp
                                <span class="badge" style="background-color: {{ $packInfo['color'] }}; color: #000;">
                                    {{ $packInfo['icon'] }} {{ $packInfo['name'] }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">Aucun pack requis</div>
                    @endif
                </div>
                <div class="mb-3">
                    <strong>Ordre d'affichage :</strong>
                    <div class="text-muted">{{ $program->order }}</div>
                </div>
                <div class="mb-3">
                    <strong>Créé le :</strong>
                    <div class="text-muted">{{ $program->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="mb-3">
                    <strong>Modifié le :</strong>
                    <div class="text-muted">{{ $program->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">⚡ Actions</h5>
            </div>
            <div style="padding: 1.5rem;">
                <a href="{{ route('admin.programs.manage-steps', $program) }}" class="btn btn-primary mb-2" style="width: 100%;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Gérer les Étapes
                </a>
                <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-secondary mb-2" style="width: 100%;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier le Programme
                </a>
                <form method="POST" action="{{ route('admin.programs.destroy', $program) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer le Programme
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
