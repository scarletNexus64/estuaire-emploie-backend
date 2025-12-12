@extends('admin.layouts.app')

@section('title', $isEdit ? 'Modifier la Publicité' : 'Créer une Publicité')
@section('page-title', $isEdit ? 'Modifier la Publicité' : 'Créer une Publicité')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.advertisements.index') }}">Publicité</a> / {{ $isEdit ? 'Modifier' : 'Créer' }}</span>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.advertisements.update', $ad->id) : route('admin.advertisements.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la Publicité</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Entreprise</label>
                        <select name="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une entreprise --</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $ad->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Type de Publicité</label>
                        <select name="ad_type" class="form-control @error('ad_type') is-invalid @enderror" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="homepage_banner" {{ old('ad_type', $ad->ad_type ?? '') == 'homepage_banner' ? 'selected' : '' }}>Bannière Page d'Accueil</option>
                            <option value="search_banner" {{ old('ad_type', $ad->ad_type ?? '') == 'search_banner' ? 'selected' : '' }}>Bannière Résultats de Recherche</option>
                            <option value="featured_company" {{ old('ad_type', $ad->ad_type ?? '') == 'featured_company' ? 'selected' : '' }}>Entreprise en Vedette</option>
                            <option value="sidebar" {{ old('ad_type', $ad->ad_type ?? '') == 'sidebar' ? 'selected' : '' }}>Bannière Latérale</option>
                            <option value="custom" {{ old('ad_type', $ad->ad_type ?? '') == 'custom' ? 'selected' : '' }}>Publicité Personnalisée</option>
                        </select>
                        @error('ad_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Titre</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $ad->title ?? '') }}" required maxlength="255">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $ad->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL de l'Image</label>
                        <input type="text" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
                               value="{{ old('image_url', $ad->image_url ?? '') }}" maxlength="500" placeholder="https://example.com/image.jpg">
                        <small class="form-text">Lien vers l'image publicitaire</small>
                        @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">URL Cible</label>
                        <input type="url" name="target_url" class="form-control @error('target_url') is-invalid @enderror"
                               value="{{ old('target_url', $ad->target_url ?? '') }}" maxlength="500" placeholder="https://example.com">
                        <small class="form-text">Page de destination lors du clic</small>
                        @error('target_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Tarification & Période</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Prix (FCFA)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $ad->price ?? 0) }}" required min="0" step="0.01">
                        <small class="form-text">Tarif personnalisable pour cette publicité</small>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Date de Début</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $ad && $ad->start_date ? $ad->start_date->format('Y-m-d') : '') }}" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Date de Fin</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $ad && $ad->end_date ? $ad->end_date->format('Y-m-d') : '') }}" required>
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Ordre d'Affichage</label>
                        <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror"
                               value="{{ old('display_order', $ad->display_order ?? 0) }}" required min="0">
                        <small class="form-text">Plus le nombre est bas, plus la publicité est prioritaire</small>
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ad->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active"><strong>Actif</strong></label>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Notes Internes</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="4" placeholder="Notes et remarques internes...">{{ old('notes', $ad->notes ?? '') }}</textarea>
                        @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        {{ $isEdit ? 'Mettre à Jour' : 'Créer' }}
                    </button>
                    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>

            @if(!$isEdit)
            <div class="card" style="margin-top: 1.5rem; background: #f8fafc;">
                <div class="card-header">
                    <h4 style="margin: 0; font-size: 0.875rem; color: #64748b;">💡 Tarifs Recommandés</h4>
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div style="font-size: 0.875rem; color: #64748b; line-height: 1.6;">
                        <div style="margin-bottom: 0.5rem;">📍 <strong>Page d'Accueil:</strong> 25 000 FCFA/mois</div>
                        <div style="margin-bottom: 0.5rem;">🔍 <strong>Recherche:</strong> 15 000 FCFA/mois</div>
                        <div>⭐ <strong>Vedette:</strong> Sur mesure</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection
