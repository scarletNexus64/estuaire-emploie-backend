@extends('admin.layouts.app')

@section('title', 'Modifier la Roadmap')
@section('page-title', 'Modifier la roadmap')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.roadmaps.index') }}">Roadmaps</a>
    <span> / </span>
    <span>{{ $roadmap->title }}</span>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations de la Roadmap</h5>
            </div>
            <div style="padding: 1.5rem;">
                <form method="POST" action="{{ route('admin.roadmaps.update', $roadmap) }}">
                    @csrf
                    @method('PUT')
                    @include('admin.roadmaps._form', ['roadmap' => $roadmap])

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer
                        </button>
                        <a href="{{ route('admin.roadmaps.manage-levels', $roadmap) }}" class="btn btn-primary">Gérer les niveaux</a>
                        <a href="{{ route('admin.roadmaps.index') }}" class="btn btn-outline-secondary">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">ℹ️ Informations</h5>
            </div>
            <div style="padding: 1.5rem;">
                <p><strong>Slug :</strong> <code>{{ $roadmap->slug }}</code></p>
                <p><strong>Niveaux :</strong> {{ $roadmap->levels()->count() }}</p>
                <p><strong>Créée le :</strong> {{ $roadmap->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-0"><strong>Modifiée le :</strong> {{ $roadmap->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
