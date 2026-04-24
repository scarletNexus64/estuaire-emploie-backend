@extends('admin.layouts.app')

@section('title', isset($examPack) ? 'Modifier le Pack' : 'Nouveau Pack d\'Épreuves')
@section('page-title', isset($examPack) ? 'Modifier le Pack d\'Épreuves' : 'Nouveau Pack d\'Épreuves')

@section('breadcrumbs')
    <span> / </span>
    <span><a href="{{ route('admin.exam-packs.index') }}">Packs d'Épreuves</a></span>
    <span> / </span>
    <span>{{ isset($examPack) ? 'Modifier' : 'Nouveau' }}</span>
@endsection

@section('content')
<form action="{{ isset($examPack) ? route('admin.exam-packs.update', $examPack) : route('admin.exam-packs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($examPack))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Informations Principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations Principales</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nom du Pack <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $examPack->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ex: "BTS 2026", "Licence Informatique 2025"</small>
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug (URL)</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $examPack->slug ?? '') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Laisser vide pour génération automatique</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $examPack->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="specialty">Spécialité</label>
                                <select name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    @foreach($specialties as $key => $value)
                                        <option value="{{ $key }}" {{ old('specialty', $examPack->specialty ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('specialty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year">Année</label>
                                <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
                                       value="{{ old('year', $examPack->year ?? date('Y')) }}" min="2000" max="2100">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exam_type">Type d'Examen</label>
                                <select name="exam_type" id="exam_type" class="form-control @error('exam_type') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    @foreach($examTypes as $key => $value)
                                        <option value="{{ $key }}" {{ old('exam_type', $examPack->exam_type ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('exam_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cover_image">Image de Couverture</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(isset($examPack) && $examPack->cover_image)
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ asset('storage/' . $examPack->cover_image) }}" alt="Cover" style="max-width: 200px; border-radius: 8px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sélection des Épreuves -->
            @if(isset($examPapers) && $examPapers->count() > 0)
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Épreuves du Pack</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Sélectionner les épreuves à inclure dans ce pack</label>
                            <small class="form-text text-muted" style="margin-bottom: 1rem;">
                                Vous pouvez également gérer les épreuves après création du pack
                            </small>

                            <div style="max-height: 400px; overflow-y: auto; border: 1px solid #dee2e6; border-radius: 4px; padding: 1rem;">
                                @foreach($examPapers as $paper)
                                    <div class="form-check" style="margin-bottom: 0.5rem;">
                                        <input class="form-check-input" type="checkbox" name="exam_papers[]" value="{{ $paper->id }}" id="paper_{{ $paper->id }}"
                                               {{ (isset($examPack) && $examPack->examPapers->contains($paper->id)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="paper_{{ $paper->id }}">
                                            <strong>{{ $paper->title }}</strong>
                                            <small class="text-muted">
                                                - {{ $paper->subject }} ({{ $paper->level_name }})
                                                @if($paper->year) - {{ $paper->year }} @endif
                                            </small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                               value="{{ old('display_order', $examPack->display_order ?? 0) }}">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Plus petit = affiché en premier</small>
                    </div>

                    <div class="form-check" style="margin-bottom: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                               {{ old('is_active', $examPack->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Pack Actif</strong>
                            <small class="d-block text-muted">Visible sur l'application</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1"
                               {{ old('is_featured', $examPack->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <strong>⭐ Mettre en Avant</strong>
                            <small class="d-block text-muted">Affiché dans les recommandations</small>
                        </label>
                    </div>
                </div>
            </div>

            @if(isset($examPack))
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Statistiques</h3>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 1rem;">
                            <small class="text-muted">Épreuves</small>
                            <div><strong>{{ $examPack->examPapers->count() }}</strong></div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <small class="text-muted">Achats</small>
                            <div><strong>{{ $examPack->purchases_count }}</strong></div>
                        </div>
                        <div>
                            <small class="text-muted">Vues</small>
                            <div><strong>{{ $examPack->views_count }}</strong></div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                        {{ isset($examPack) ? 'Mettre à Jour' : 'Créer le Pack' }}
                    </button>
                    <a href="{{ route('admin.exam-packs.index') }}" class="btn btn-secondary" style="width: 100%;">
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
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function(e) {
        const slug = e.target.value
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endpush
