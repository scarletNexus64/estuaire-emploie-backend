@extends('admin.layouts.app')

@section('title', 'Nouveau Programme')
@section('page-title', 'Créer un nouveau programme')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.programs.index') }}">Programmes</a>
    <span> / </span>
    <span>Nouveau</span>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations du Programme</h5>
            </div>
            <div style="padding: 1.5rem;">
                <form method="POST" action="{{ route('admin.programs.store') }}">
                    @csrf

                    <div class="form-group">
                        <label for="title" class="form-label">Titre du Programme *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="type" class="form-label">Type de Programme *</label>
                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="immersion_professionnelle" {{ old('type') == 'immersion_professionnelle' ? 'selected' : '' }}>
                                🌟 Programme d'immersion professionnelle
                            </option>
                            <option value="entreprenariat" {{ old('type') == 'entreprenariat' ? 'selected' : '' }}>
                                💼 Programme en entreprenariat
                            </option>
                            <option value="transformation_professionnelle" {{ old('type') == 'transformation_professionnelle' ? 'selected' : '' }}>
                                🚀 Programme de transformation professionnelle et personnel
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Packs Requis *</label>
                        <div class="@error('required_packs') is-invalid @enderror">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pack_c1" name="required_packs[]" value="C1"
                                       {{ is_array(old('required_packs')) && in_array('C1', old('required_packs')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="pack_c1">
                                    🥈 PACK C1 (ARGENT) - 1 000 FCFA/Mois
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pack_c2" name="required_packs[]" value="C2"
                                       {{ is_array(old('required_packs')) && in_array('C2', old('required_packs')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="pack_c2">
                                    🥇 PACK C2 (OR) - 5 000 FCFA/Mois
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pack_c3" name="required_packs[]" value="C3"
                                       {{ is_array(old('required_packs')) && in_array('C3', old('required_packs')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="pack_c3">
                                    💎 PACK C3 (DIAMANT) - 10 000 FCFA/Mois
                                </label>
                            </div>
                        </div>
                        @error('required_packs')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Sélectionnez les packs qui peuvent accéder à ce programme</small>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="objectives" class="form-label">Objectifs</label>
                        <textarea class="form-control @error('objectives') is-invalid @enderror"
                                  id="objectives" name="objectives" rows="4"
                                  placeholder="Listez les objectifs du programme (un par ligne)">{{ old('objectives') }}</textarea>
                        @error('objectives')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="icon" class="form-label">Icône</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror"
                                       id="icon" name="icon" value="{{ old('icon', '📚') }}"
                                       placeholder="📚" maxlength="10">
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Utilisez un emoji</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="duration_weeks" class="form-label">Durée (semaines)</label>
                                <input type="number" class="form-control @error('duration_weeks') is-invalid @enderror"
                                       id="duration_weeks" name="duration_weeks" value="{{ old('duration_weeks') }}"
                                       min="1" placeholder="Ex: 12">
                                @error('duration_weeks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror"
                                       id="order" name="order" value="{{ old('order', 0) }}"
                                       min="0" placeholder="0">
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Programme actif
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Créer le Programme
                        </button>
                        <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">💡 Conseils</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="alert alert-info">
                    <strong>Type de programme :</strong>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0;">
                        <li><strong>Immersion professionnelle :</strong> Pour PLATINUM</li>
                        <li><strong>Entreprenariat :</strong> Pour PLATINUM</li>
                        <li><strong>Transformation :</strong> Pour GOLD et PLATINUM</li>
                    </ul>
                </div>

                <div class="alert alert-warning">
                    <strong>📝 Prochaine étape :</strong>
                    <p class="mb-0">Après la création, vous pourrez ajouter les étapes (steps) détaillées du programme.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
