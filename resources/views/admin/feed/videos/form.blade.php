@extends('admin.layouts.app')

@section('title', $isEdit ? 'Modifier la vidéo' : 'Ajouter une vidéo')
@section('page-title', $isEdit ? 'Modifier la vidéo du fil' : 'Ajouter une vidéo au fil')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.feed-videos.index') }}">Vidéos du fil</a> / {{ $isEdit ? 'Modifier' : 'Ajouter' }}</span>
@endsection

@section('content')
@php
    $uploadMax = ini_get('upload_max_filesize');
    $postMax = ini_get('post_max_size');
@endphp
<form action="{{ $isEdit ? route('admin.feed-videos.update', $video) : route('admin.feed-videos.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contenu</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Titre</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $video->title ?? '') }}" required maxlength="255"
                               placeholder="Ex : Une journée d'infirmière à Douala">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" maxlength="2000" class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Ce que le candidat va découvrir sur ce métier">{{ old('description', $video->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Catégorie</label>
                            <input type="text" name="category" list="feedCategories" class="form-control @error('category') is-invalid @enderror"
                                   value="{{ old('category', $video->category ?? '') }}" maxlength="100" placeholder="Ex : Santé, Tech, BTP">
                            <datalist id="feedCategories">
                                @foreach(['Santé', 'Tech', 'BTP', 'Commerce', 'Agriculture', 'Éducation', 'Finance', 'Hôtellerie', 'Logistique', 'Industrie', 'Artisanat', 'Communication'] as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Durée (secondes)</label>
                            <input type="number" name="duration_seconds" id="durationInput" min="0" max="3600" class="form-control @error('duration_seconds') is-invalid @enderror"
                                   value="{{ old('duration_seconds', $video->duration_seconds ?? '') }}" placeholder="Détectée automatiquement">
                            @error('duration_seconds') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Auteur</label>
                            <input type="text" name="author_name" class="form-control @error('author_name') is-invalid @enderror"
                                   value="{{ old('author_name', $video->author_name ?? '') }}" maxlength="255">
                            @error('author_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Crédit / source</label>
                            <input type="text" name="source_credit" class="form-control @error('source_credit') is-invalid @enderror"
                                   value="{{ old('source_credit', $video->source_credit ?? '') }}" maxlength="255" placeholder="Ex : Pexels — Jane Doe">
                            @error('source_credit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Vidéo</h3>
                    <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        Envoyez un fichier <strong>ou</strong> collez une URL externe (le fichier est prioritaire). Format vertical 9:16 recommandé.
                    </p>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Fichier vidéo (MP4, MOV, WEBM — 100 Mo max)</label>
                        <input type="file" name="video_file" id="videoInput" accept="video/mp4,video/quicktime,video/webm"
                               class="form-control @error('video_file') is-invalid @enderror">
                        <small class="form-text">
                            Limites PHP actuelles du serveur : upload_max_filesize = <strong>{{ $uploadMax }}</strong>,
                            post_max_size = <strong>{{ $postMax }}</strong>. Pour envoyer jusqu'à 100 Mo, ces valeurs doivent être relevées
                            (ex. 110M / 120M) ; sinon utilisez une URL externe.
                        </small>
                        @error('video_file') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror

                        @if($isEdit && $video->video_path)
                            <div class="form-check-box" style="margin-top: 0.75rem;">
                                <input type="checkbox" name="remove_video_file" id="remove_video_file" value="1">
                                <label for="remove_video_file">Supprimer le fichier actuel (utiliser l'URL externe)</label>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL externe de la vidéo</label>
                        <input type="url" name="video_url" id="videoUrlInput" class="form-control @error('video_url') is-invalid @enderror"
                               value="{{ old('video_url', $video->video_url ?? '') }}" maxlength="2048" placeholder="https://…/video.mp4">
                        <small class="form-text">Lien direct vers un fichier lisible (mp4/webm), pas une page YouTube.</small>
                        @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Miniature (optionnelle)</label>
                            <input type="file" name="thumbnail" id="thumbInput" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror">
                            <small class="form-text">JPG, PNG, WEBP — 4 Mo max.</small>
                            @error('thumbnail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($isEdit && $video->thumbnail_path)
                                <div class="form-check-box" style="margin-top: 0.5rem;">
                                    <input type="checkbox" name="remove_thumbnail" id="remove_thumbnail" value="1">
                                    <label for="remove_thumbnail">Supprimer la miniature actuelle</label>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">ou URL de miniature</label>
                            <input type="url" name="thumbnail_url" class="form-control @error('thumbnail_url') is-invalid @enderror"
                                   value="{{ old('thumbnail_url', $video->thumbnail_url ?? '') }}" maxlength="2048" placeholder="https://…/thumb.jpg">
                            @error('thumbnail_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aperçu</h3>
                </div>
                <div class="card-body" style="display: flex; justify-content: center;">
                    <video id="videoPreview" controls playsinline muted
                           src="{{ $isEdit ? $video->playback_url : '' }}"
                           poster="{{ $isEdit ? $video->cover_url : '' }}"
                           style="width: 100%; max-width: 260px; aspect-ratio: 9 / 16; object-fit: cover; background: #0f172a; border-radius: 12px; {{ ($isEdit && $video->playback_url) ? '' : 'display:none;' }}"></video>
                    <div id="videoPlaceholder" style="width: 100%; max-width: 260px; aspect-ratio: 9 / 16; background: #e2e8f0; border-radius: 12px; display: {{ ($isEdit && $video->playback_url) ? 'none' : 'flex' }}; align-items: center; justify-content: center; color: #64748b; font-size: 0.875rem; text-align: center; padding: 1rem;">
                        Choisissez un fichier ou collez une URL pour prévisualiser
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="display_order" min="0" class="form-control @error('display_order') is-invalid @enderror"
                               value="{{ old('display_order', $video->display_order ?? 0) }}">
                        <small class="form-text">Plus le nombre est bas, plus la vidéo apparaît tôt.</small>
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active"><strong>Visible dans le fil</strong></label>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">{{ $isEdit ? 'Mettre à jour' : 'Ajouter' }}</button>
                    <a href="{{ route('admin.feed-videos.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const preview = document.getElementById('videoPreview');
    const placeholder = document.getElementById('videoPlaceholder');
    const fileInput = document.getElementById('videoInput');
    const urlInput = document.getElementById('videoUrlInput');
    const thumbInput = document.getElementById('thumbInput');
    const duration = document.getElementById('durationInput');

    function show(src) {
        if (!src) return;
        preview.src = src;
        preview.style.display = 'block';
        placeholder.style.display = 'none';
    }

    // Durée détectée à partir des métadonnées si le champ est vide
    preview.addEventListener('loadedmetadata', function () {
        if (!duration.value && isFinite(preview.duration)) {
            duration.value = Math.round(preview.duration);
        }
    });

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) show(URL.createObjectURL(file));
    });

    urlInput.addEventListener('change', function () {
        if (!fileInput.files.length && this.value) show(this.value);
    });

    thumbInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) preview.poster = URL.createObjectURL(file);
    });
});
</script>
@endsection
