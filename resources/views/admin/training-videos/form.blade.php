@extends('admin.layouts.app')

@section('title', isset($trainingVideo) ? 'Modifier la Vidéo' : 'Nouvelle Vidéo de Formation')
@section('page-title', isset($trainingVideo) ? 'Modifier la Vidéo' : 'Nouvelle Vidéo de Formation')

@section('breadcrumbs')
    <span> / </span>
    <span><a href="{{ route('admin.training-videos.index') }}">Vidéos de Formation</a></span>
    <span> / </span>
    <span>{{ isset($trainingVideo) ? 'Modifier' : 'Nouvelle' }}</span>
@endsection

@section('content')
<form action="{{ isset($trainingVideo) ? route('admin.training-videos.update', $trainingVideo) : route('admin.training-videos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($trainingVideo))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Informations Principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la Vidéo</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="title">Titre de la Vidéo <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $trainingVideo->title ?? '') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $trainingVideo->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="video_type">Type de Vidéo <span class="text-danger">*</span></label>
                        <select name="video_type" id="video_type" class="form-control @error('video_type') is-invalid @enderror" required onchange="toggleVideoInput()">
                            <option value="upload" {{ old('video_type', $trainingVideo->video_type ?? 'upload') == 'upload' ? 'selected' : '' }}>📹 Upload MP4</option>
                            <option value="youtube" {{ old('video_type', $trainingVideo->video_type ?? '') == 'youtube' ? 'selected' : '' }}>▶️ YouTube</option>
                            <option value="vimeo" {{ old('video_type', $trainingVideo->video_type ?? '') == 'vimeo' ? 'selected' : '' }}>▶️ Vimeo</option>
                            <option value="mega" {{ old('video_type', $trainingVideo->video_type ?? '') == 'mega' ? 'selected' : '' }}>☁️ MEGA.nz</option>
                        </select>
                        @error('video_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Upload MP4 -->
                    <div class="form-group" id="video_file_group" style="display: none;">
                        <label for="video_file">Fichier Vidéo MP4 <span class="text-danger">*</span></label>
                        <input type="file" name="video_file" id="video_file" class="form-control @error('video_file') is-invalid @enderror" accept="video/mp4,video/mov,video/avi,video/wmv">
                        @error('video_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Formats acceptés: MP4, MOV, AVI, WMV (Max 500MB)</small>
                        @if(isset($trainingVideo) && $trainingVideo->video_type === 'upload' && $trainingVideo->video_path)
                            <div style="margin-top: 1rem;">
                                <small class="text-success">✅ Fichier actuel: {{ $trainingVideo->video_filename }} ({{ $trainingVideo->formatted_video_size }})</small>
                                @if($trainingVideo->videoExists())
                                    <div style="margin-top: 0.75rem; padding: 1rem; background-color: #f8f9fa; border-radius: 8px;">
                                        <strong style="display: block; margin-bottom: 0.5rem;">📹 Aperçu de la vidéo :</strong>
                                        <video controls style="width: 100%; max-width: 640px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            <source src="{{ asset('storage/' . $trainingVideo->video_path) }}" type="video/mp4">
                                            Votre navigateur ne supporte pas la lecture de vidéos.
                                        </video>
                                    </div>
                                @else
                                    <div style="margin-top: 0.5rem;">
                                        <small class="text-danger">⚠️ Le fichier vidéo n'existe plus sur le serveur</small>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- URL YouTube/Vimeo/MEGA -->
                    <div class="form-group" id="video_url_group" style="display: none;">
                        <label for="video_url">URL de la Vidéo <span class="text-danger">*</span></label>
                        <input type="url" name="video_url" id="video_url" class="form-control @error('video_url') is-invalid @enderror"
                               value="{{ old('video_url', $trainingVideo->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/... ou https://mega.nz/...">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Coller l'URL complète de la vidéo YouTube, Vimeo ou MEGA.nz</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_seconds">Durée (secondes)</label>
                                <input type="number" name="duration_seconds" id="duration_seconds" class="form-control @error('duration_seconds') is-invalid @enderror"
                                       value="{{ old('duration_seconds', $trainingVideo->duration_seconds ?? '') }}" min="0">
                                @error('duration_seconds')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ex: 600 pour 10 minutes</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Durée Formatée (aperçu)</label>
                                <input type="text" id="duration_preview" class="form-control" readonly value="{{ $trainingVideo->duration_formatted ?? '00:00' }}">
                                <small class="form-text text-muted">Format: MM:SS ou HH:MM:SS</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="thumbnail">Miniature de la Vidéo</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" accept="image/*">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(isset($trainingVideo) && $trainingVideo->thumbnail)
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ asset('storage/' . $trainingVideo->thumbnail) }}" alt="Thumbnail" style="max-width: 300px; border-radius: 8px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres & Publication -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="display_order">Ordre d'Affichage</label>
                        <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                               value="{{ old('display_order', $trainingVideo->display_order ?? 0) }}">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Plus petit = affiché en premier</small>
                    </div>

                    <div class="form-check" style="margin-bottom: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                               {{ old('is_active', $trainingVideo->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Vidéo Active</strong>
                            <small class="d-block text-muted">Visible sur l'application</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_preview" id="is_preview" class="form-check-input" value="1"
                               {{ old('is_preview', $trainingVideo->is_preview ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_preview">
                            <strong>👁️ Aperçu Gratuit</strong>
                            <small class="d-block text-muted">Visible sans acheter le pack</small>
                        </label>
                    </div>
                </div>
            </div>

            @if(isset($trainingVideo))
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Statistiques</h3>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 1rem;">
                            <small class="text-muted">Vues</small>
                            <div><strong>{{ $trainingVideo->views_count }}</strong></div>
                        </div>
                        <div>
                            <small class="text-muted">Visionnages Complets</small>
                            <div><strong>{{ $trainingVideo->completions_count }}</strong></div>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Packs Associés</h3>
                    </div>
                    <div class="card-body">
                        @if($trainingVideo->trainingPacks->count() > 0)
                            <ul style="margin: 0; padding-left: 1.2rem;">
                                @foreach($trainingVideo->trainingPacks as $pack)
                                    <li>
                                        <a href="{{ route('admin.training-packs.edit', $pack) }}">{{ $pack->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <small class="text-muted">Aucun pack associé</small>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                        {{ isset($trainingVideo) ? 'Mettre à Jour' : 'Créer la Vidéo' }}
                    </button>
                    <a href="{{ route('admin.training-videos.index') }}" class="btn btn-secondary" style="width: 100%;">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Toggle video input based on type
    function toggleVideoInput() {
        const videoType = document.getElementById('video_type').value;
        const fileGroup = document.getElementById('video_file_group');
        const urlGroup = document.getElementById('video_url_group');

        if (videoType === 'upload') {
            fileGroup.style.display = 'block';
            urlGroup.style.display = 'none';
            document.getElementById('video_url').removeAttribute('required');
            document.getElementById('video_file').setAttribute('required', 'required');
        } else {
            fileGroup.style.display = 'none';
            urlGroup.style.display = 'block';
            document.getElementById('video_file').removeAttribute('required');
            document.getElementById('video_url').setAttribute('required', 'required');
        }
    }

    // Format duration preview
    document.getElementById('duration_seconds')?.addEventListener('input', function(e) {
        const seconds = parseInt(e.target.value) || 0;
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;

        let formatted;
        if (hours > 0) {
            formatted = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        } else {
            formatted = `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        document.getElementById('duration_preview').value = formatted;
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleVideoInput();
    });
</script>
@endpush
