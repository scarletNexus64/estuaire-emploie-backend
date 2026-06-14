@extends('admin.layouts.app')

@section('title', 'Nouvelle Roadmap')
@section('page-title', 'Créer une nouvelle roadmap')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.roadmaps.index') }}">Roadmaps</a>
    <span> / </span>
    <span>Nouvelle</span>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations de la Roadmap</h5>
            </div>
            <div style="padding: 1.5rem;">
                <form method="POST" action="{{ route('admin.roadmaps.store') }}">
                    @csrf
                    @include('admin.roadmaps._form', ['roadmap' => null])

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Créer la Roadmap
                        </button>
                        <a href="{{ route('admin.roadmaps.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">💡 Comment ça marche ?</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="alert alert-info">
                    <strong>Une roadmap = un parcours de niveaux.</strong>
                    <p class="mb-0">L'étudiant progresse niveau par niveau (comme un jeu). Pour passer au niveau suivant, il doit réussir le QCM du niveau courant (score ≥ seuil).</p>
                </div>
                <div class="alert alert-warning">
                    <strong>📝 Prochaine étape :</strong>
                    <p class="mb-0">Après la création, vous ajouterez les niveaux (contenu rédigé + QCM).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
