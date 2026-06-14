@extends('admin.layouts.app')

@section('title', $roadmap->title)
@section('page-title', 'Détail de la Roadmap')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.roadmaps.index') }}">Roadmaps</a>
    <span> / </span>
    <span>{{ $roadmap->title }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.roadmaps.manage-levels', $roadmap) }}" class="btn btn-primary">Gérer les niveaux</a>
    <a href="{{ route('admin.roadmaps.edit', $roadmap) }}" class="btn btn-secondary">Modifier</a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3" style="background: linear-gradient(135deg, {{ $roadmap->color }} 0%, #764ba2 100%); color: white;">
            <div style="padding: 2rem; display: flex; align-items: center; gap: 1.5rem;">
                <div style="font-size: 4rem;">{{ $roadmap->icon }}</div>
                <div>
                    <h3 style="color: white; margin: 0 0 0.5rem;">{{ $roadmap->title }}</h3>
                    <p style="opacity: 0.9; margin: 0;">{{ $roadmap->description }}</p>
                </div>
            </div>
        </div>

        @if($roadmap->objectives)
        <div class="card mb-3">
            <div class="card-header"><h5 class="card-title mb-0">🎯 Objectifs</h5></div>
            <div style="padding: 1.5rem; white-space: pre-line;">{{ $roadmap->objectives }}</div>
        </div>
        @endif

        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">🗺️ Niveaux ({{ $roadmap->levels->count() }})</h5></div>
            <div style="padding: 1.5rem;">
                @forelse($roadmap->levels as $level)
                    <div class="card mb-2" style="border-left: 4px solid {{ $roadmap->color }};">
                        <div style="padding: 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span class="badge badge-primary">Niveau {{ $level->order }}</span>
                                <strong>{{ $level->title }}</strong>
                                @if($level->has_quiz)
                                    <span class="badge badge-warning">QCM · {{ $level->questions->count() }} Q.</span>
                                @endif
                                <span class="badge badge-info">+{{ $level->xp_reward }} XP</span>
                            </div>
                            @if($level->subtitle)
                                <p class="text-muted small mb-0 mt-1">{{ $level->subtitle }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info">Aucun niveau. <a href="{{ route('admin.roadmaps.manage-levels', $roadmap) }}">Ajoutez-en</a>.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">ℹ️ Informations</h5></div>
            <div style="padding: 1.5rem;">
                <p><strong>Domaine :</strong> {{ $domains[$roadmap->domain] ?? $roadmap->domain }}</p>
                <p><strong>Difficulté :</strong> {{ $difficulties[$roadmap->difficulty] ?? $roadmap->difficulty }}</p>
                <p><strong>Seuil de réussite QCM :</strong> {{ $roadmap->pass_threshold }}%</p>
                <p><strong>Ordre :</strong> {{ $roadmap->order }}</p>
                <p>
                    <strong>Packs requis :</strong>
                    @if($roadmap->required_packs && count($roadmap->required_packs))
                        @foreach($roadmap->required_packs as $pack)
                            <span class="badge badge-primary">{{ $pack }}</span>
                        @endforeach
                    @else
                        <span class="badge badge-success">Gratuit</span>
                    @endif
                </p>
                <p><strong>Statut :</strong>
                    @if($roadmap->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </p>
                <p class="mb-0"><strong>Slug :</strong> <code>{{ $roadmap->slug }}</code></p>
            </div>
        </div>
    </div>
</div>
@endsection
