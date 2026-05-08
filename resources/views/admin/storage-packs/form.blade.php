@extends('admin.layouts.app')

@section('title', isset($storagePack) ? 'Modifier le Pack' : 'Nouveau Pack de Stockage')
@section('page-title', isset($storagePack) ? 'Modifier le Pack de Stockage' : 'Nouveau Pack de Stockage')

@section('breadcrumbs')
    <span> / </span>
    <span><a href="{{ route('admin.storage-packs.index') }}">Packs de Stockage</a></span>
    <span> / </span>
    <span>{{ isset($storagePack) ? 'Modifier' : 'Nouveau' }}</span>
@endsection

@section('content')
<form action="{{ isset($storagePack) ? route('admin.storage-packs.update', $storagePack) : route('admin.storage-packs.store') }}" method="POST">
    @csrf
    @if(isset($storagePack))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Informations Principales -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du Pack</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nom du Pack <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $storagePack->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ex: "Pack Basic", "Pack Pro", "Pack Premium"</small>
                    </div>

                    <div class="form-group">
                        <label for="slug">Slug (URL)</label>
                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror"
                               value="{{ old('slug', $storagePack->slug ?? '') }}">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Laisser vide pour génération automatique</small>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $storagePack->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Description optionnelle du pack</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="storage_mb">Espace de Stockage (Mo) <span class="text-danger">*</span></label>
                                <input type="number" name="storage_mb" id="storage_mb" class="form-control @error('storage_mb') is-invalid @enderror"
                                       value="{{ old('storage_mb', $storagePack->storage_mb ?? '') }}" min="1" required>
                                @error('storage_mb')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ex: 250 (Mo), 512 (Mo), 1024 (1Go), 2048 (2Go)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration_days">Durée (Jours) <span class="text-danger">*</span></label>
                                <input type="number" name="duration_days" id="duration_days" class="form-control @error('duration_days') is-invalid @enderror"
                                       value="{{ old('duration_days', $storagePack->duration_days ?? '') }}" min="1" required>
                                @error('duration_days')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ex: 30 (1 mois), 90 (3 mois), 360 (1 an)</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Prix (CFA) <span class="text-danger">*</span></label>
                                <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $storagePack->price ?? '') }}" min="0" step="0.01" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Prix en Francs CFA</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="display_order">Ordre d'affichage</label>
                                <input type="number" name="display_order" id="display_order" class="form-control @error('display_order') is-invalid @enderror"
                                       value="{{ old('display_order', $storagePack->display_order ?? 0) }}" min="0">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Ordre d'affichage (0 = premier)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres et Actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="hidden" name="is_active" value="0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $storagePack->is_active ?? true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">
                                Pack Actif
                            </label>
                        </div>
                        <small class="form-text text-muted">Seuls les packs actifs sont visibles aux utilisateurs</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Actions</h3>
                </div>
                <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-block">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ isset($storagePack) ? 'Mettre à jour' : 'Créer le pack' }}
                    </button>

                    <a href="{{ route('admin.storage-packs.index') }}" class="btn btn-secondary btn-block">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Annuler
                    </a>
                </div>
            </div>

            @if(isset($storagePack))
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations</h3>
                </div>
                <div class="card-body">
                    <p><strong>Créé le:</strong><br>{{ $storagePack->created_at->format('d/m/Y à H:i') }}</p>
                    <p><strong>Modifié le:</strong><br>{{ $storagePack->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>

@push('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function(e) {
        const slug = document.getElementById('slug');
        if (!slug.value || slug.dataset.auto !== 'false') {
            const text = e.target.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            slug.value = text;
            slug.dataset.auto = 'true';
        }
    });

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.auto = 'false';
    });
</script>
@endpush
@endsection
