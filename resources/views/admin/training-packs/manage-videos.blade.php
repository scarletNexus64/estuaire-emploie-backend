@extends('admin.layouts.app')

@section('title', 'Gérer les Vidéos')
@section('page-title', 'Gérer les Vidéos du Pack')

@section('breadcrumbs')
    <span> / </span>
    <span><a href="{{ route('admin.training-packs.index') }}">Packs de Formation</a></span>
    <span> / </span>
    <span><a href="{{ route('admin.training-packs.edit', $trainingPack) }}">{{ $trainingPack->name }}</a></span>
    <span> / </span>
    <span>Gérer les Vidéos</span>
@endsection

@section('content')
<div class="row">
    <!-- Vidéos Actuelles -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vidéos du Pack ({{ $trainingPack->trainingVideos->count() }})</h3>
            </div>
            <div class="card-body">
                @if($trainingPack->trainingVideos->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Durée</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trainingPack->trainingVideos->sortBy('pivot.display_order') as $video)
                                    <tr>
                                        <td>
                                            <strong>{{ $video->title }}</strong>
                                            @if($video->is_preview)
                                                <span class="badge bg-info">👁️ Aperçu</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($video->video_type === 'upload')
                                                📹 Upload
                                            @elseif($video->video_type === 'youtube')
                                                <span style="color: #FF0000;">▶️ YouTube</span>
                                            @else
                                                <span style="color: #1ab7ea;">▶️ Vimeo</span>
                                            @endif
                                        </td>
                                        <td>{{ $video->duration_formatted ?? '-' }}</td>
                                        <td>{{ $video->pivot->section_name ?? 'Général' }}</td>
                                        <td>
                                            <form action="{{ route('admin.training-packs.remove-video', [$trainingPack, $video]) }}" method="POST" style="display: inline;" onsubmit="return confirm('Retirer cette vidéo du pack ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    🗑️ Retirer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        Aucune vidéo dans ce pack. Ajoutez-en depuis la liste ci-contre.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Vidéos Disponibles -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajouter une Vidéo</h3>
            </div>
            <div class="card-body">
                @if($availableVideos->count() > 0)
                    <form action="{{ route('admin.training-packs.add-video', $trainingPack) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="training_video_id">Sélectionner une vidéo</label>
                            <select name="training_video_id" id="training_video_id" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($availableVideos as $video)
                                    <option value="{{ $video->id }}">
                                        {{ $video->title }} ({{ $video->duration_formatted ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_name">Nom de la Section</label>
                            <input type="text" name="section_name" id="section_name" class="form-control" value="Module Principal" placeholder="Ex: Introduction, Chapitre 1">
                            <small class="form-text text-muted">Optionnel - pour organiser les vidéos</small>
                        </div>

                        <div class="form-group">
                            <label for="section_order">Ordre de la Section</label>
                            <input type="number" name="section_order" id="section_order" class="form-control" value="0" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ➕ Ajouter au Pack
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        Toutes les vidéos disponibles sont déjà dans ce pack.
                    </div>
                @endif

                <hr>

                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.training-videos.create') }}" class="btn btn-secondary" style="width: 100%;">
                        🎥 Créer Nouvelle Vidéo
                    </a>
                </div>

                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.training-packs.edit', $trainingPack) }}" class="btn btn-outline-secondary" style="width: 100%;">
                        ← Retour au Pack
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
