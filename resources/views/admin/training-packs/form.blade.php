@extends('admin.layouts.app')

@section('title', isset($trainingPack) ? 'Modifier le Pack' : 'Nouveau Pack de Formation')
@section('page-title', isset($trainingPack) ? 'Modifier le Pack de Formation' : 'Nouveau Pack de Formation')

@section('breadcrumbs')
    <span> / </span>
    <span><a href="{{ route('admin.training-packs.index') }}">Packs de Formation</a></span>
    <span> / </span>
    <span>{{ isset($trainingPack) ? 'Modifier' : 'Nouveau' }}</span>
@endsection

@section('content')
<form action="{{ isset($trainingPack) ? route('admin.training-packs.update', $trainingPack) : route('admin.training-packs.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($trainingPack))
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
                               value="{{ old('name', $trainingPack->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ex: "Formation Laravel Complète", "Marketing Digital 2026"</small>
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug (URL)</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $trainingPack->slug ?? '') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Laisser vide pour génération automatique</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $trainingPack->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="learning_objectives">Objectifs d'Apprentissage</label>
                        <textarea name="learning_objectives" id="learning_objectives" rows="4" class="form-control @error('learning_objectives') is-invalid @enderror" placeholder="Ce que l'étudiant va apprendre...">{{ old('learning_objectives', $trainingPack->learning_objectives ?? '') }}</textarea>
                        @error('learning_objectives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Catégorie</label>
                                <select name="category" id="category" class="form-control @error('category') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    @foreach($categories as $key => $value)
                                        <option value="{{ $key }}" {{ old('category', $trainingPack->category ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="level">Niveau</label>
                                <select name="level" id="level" class="form-control @error('level') is-invalid @enderror">
                                    <option value="">Sélectionner...</option>
                                    @foreach($levels as $key => $value)
                                        <option value="{{ $key }}" {{ old('level', $trainingPack->level ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="duration_hours">Durée Totale (heures)</label>
                        <input type="number" name="duration_hours" id="duration_hours" class="form-control @error('duration_hours') is-invalid @enderror"
                               value="{{ old('duration_hours', $trainingPack->duration_hours ?? '') }}" min="0">
                        @error('duration_hours')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_xaf">Prix XAF <span class="text-danger">*</span></label>
                                <input type="number" name="price_xaf" id="price_xaf" class="form-control @error('price_xaf') is-invalid @enderror"
                                       value="{{ old('price_xaf', $trainingPack->price_xaf ?? 0) }}" step="0.01" required>
                                @error('price_xaf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_usd">Prix USD</label>
                                <input type="number" name="price_usd" id="price_usd" class="form-control @error('price_usd') is-invalid @enderror"
                                       value="{{ old('price_usd', $trainingPack->price_usd ?? '') }}" step="0.01">
                                @error('price_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_eur">Prix EUR</label>
                                <input type="number" name="price_eur" id="price_eur" class="form-control @error('price_eur') is-invalid @enderror"
                                       value="{{ old('price_eur', $trainingPack->price_eur ?? '') }}" step="0.01">
                                @error('price_eur')
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
                        @if(isset($trainingPack) && $trainingPack->cover_image)
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ asset('storage/' . $trainingPack->cover_image) }}" alt="Cover" style="max-width: 200px; border-radius: 8px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Groupe WhatsApp -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header" style="background-color: #25D366; color: white;">
                    <h3 class="card-title" style="color: white;">Groupe WhatsApp</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="whatsapp_group_link">Lien du Groupe WhatsApp</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background-color: #25D366; color: white;">
                                    <i class="fas fa-brands fa-whatsapp"></i> WhatsApp
                                </span>
                            </div>
                            <input type="url" name="whatsapp_group_link" id="whatsapp_group_link"
                                   class="form-control @error('whatsapp_group_link') is-invalid @enderror"
                                   value="{{ old('whatsapp_group_link', $trainingPack->whatsapp_group_link ?? '') }}"
                                   placeholder="https://chat.whatsapp.com/...">
                        </div>
                        @error('whatsapp_group_link')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Ce lien sera envoyé automatiquement aux acheteurs après l'achat du pack.
                            Créez un groupe WhatsApp et copiez le lien d'invitation ici.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Informations Instructeur -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Informations Instructeur</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="instructor_name">Nom de l'Instructeur</label>
                        <input type="text" name="instructor_name" id="instructor_name" class="form-control @error('instructor_name') is-invalid @enderror"
                               value="{{ old('instructor_name', $trainingPack->instructor_name ?? '') }}">
                        @error('instructor_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="instructor_bio">Biographie de l'Instructeur</label>
                        <textarea name="instructor_bio" id="instructor_bio" rows="3" class="form-control @error('instructor_bio') is-invalid @enderror">{{ old('instructor_bio', $trainingPack->instructor_bio ?? '') }}</textarea>
                        @error('instructor_bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="instructor_photo">Photo de l'Instructeur</label>
                        <input type="file" name="instructor_photo" id="instructor_photo" class="form-control @error('instructor_photo') is-invalid @enderror" accept="image/*">
                        @error('instructor_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if(isset($trainingPack) && $trainingPack->instructor_photo)
                            <div style="margin-top: 0.5rem;">
                                <img src="{{ asset('storage/' . $trainingPack->instructor_photo) }}" alt="Instructor" style="max-width: 150px; border-radius: 50%;">
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
                               value="{{ old('display_order', $trainingPack->display_order ?? 0) }}">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Plus petit = affiché en premier</small>
                    </div>

                    <div class="form-check" style="margin-bottom: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                               {{ old('is_active', $trainingPack->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            <strong>Pack Actif</strong>
                            <small class="d-block text-muted">Visible sur l'application</small>
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" value="1"
                               {{ old('is_featured', $trainingPack->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            <strong>⭐ Mettre en Avant</strong>
                            <small class="d-block text-muted">Affiché dans les recommandations</small>
                        </label>
                    </div>
                </div>
            </div>

            @if(isset($trainingPack))
                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-header">
                        <h3 class="card-title">Statistiques</h3>
                    </div>
                    <div class="card-body">
                        <div style="margin-bottom: 1rem;">
                            <small class="text-muted">Vidéos</small>
                            <div><strong>{{ $trainingPack->trainingVideos->count() }}</strong></div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <small class="text-muted">Achats</small>
                            <div><strong>{{ $trainingPack->purchases_count }}</strong></div>
                        </div>
                        <div style="margin-bottom: 1rem;">
                            <small class="text-muted">Vues</small>
                            <div><strong>{{ $trainingPack->views_count }}</strong></div>
                        </div>
                        <div>
                            <small class="text-muted">Note Moyenne</small>
                            <div>
                                @if($trainingPack->reviews_count > 0)
                                    <strong>⭐ {{ number_format($trainingPack->average_rating, 1) }}</strong> ({{ $trainingPack->reviews_count }})
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-top: 1.5rem;">
                    <div class="card-body">
                        <a href="{{ route('admin.training-packs.manage-videos', $trainingPack) }}" class="btn btn-info" style="width: 100%; margin-bottom: 0.5rem;">
                            🎥 Gérer les Vidéos
                        </a>
                    </div>
                </div>
            @endif

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                        {{ isset($trainingPack) ? 'Mettre à Jour' : 'Créer le Pack' }}
                    </button>
                    <a href="{{ route('admin.training-packs.index') }}" class="btn btn-secondary" style="width: 100%;">
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
