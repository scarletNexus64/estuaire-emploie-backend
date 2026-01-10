@extends('admin.layouts.app')

@section('title', 'Nouvelle Offre')
@section('page-title', 'Créer une Offre d\'Emploi')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.jobs.index') }}" style="color: inherit; text-decoration: none;">Offres d'emploi</a>
    <span> / </span>
    <span>Créer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nouvelle Offre d'Emploi</h3>
    </div>

    <form method="POST" action="{{ route('admin.jobs.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Titre de l'offre *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                @error('title')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Entreprise *</label>
                <select name="company_id" class="form-control" required>
                    <option value="">Sélectionner une entreprise</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Catégorie *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Localisation *</label>
                <select name="location_id" class="form-control" required>
                    <option value="">Sélectionner une localisation</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
                @error('location_id')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Type de contrat *</label>
                <select name="contract_type_id" class="form-control" required>
                    <option value="">Sélectionner un type</option>
                    @foreach($contractTypes as $type)
                        <option value="{{ $type->id }}" {{ old('contract_type_id') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('contract_type_id')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Niveau d'expérience</label>
                <input type="text" name="experience_level" class="form-control" value="{{ old('experience_level') }}" placeholder="Ex: Junior, Senior, Expert...">
                @error('experience_level')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Salaire minimum</label>
                <input type="text" name="salary_min" class="form-control" value="{{ old('salary_min') }}" placeholder="Ex: 500000 FCFA">
                @error('salary_min')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Salaire maximum</label>
                <input type="text" name="salary_max" class="form-control" value="{{ old('salary_max') }}" placeholder="Ex: 1000000 FCFA">
                @error('salary_max')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date limite de candidature</label>
                <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline') }}">
                @error('application_deadline')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Statut *</label>
                <select name="status" class="form-control" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publiée</option>
                    <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Fermée</option>
                </select>
                @error('status')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="salary_negotiable" id="salary_negotiable" {{ old('salary_negotiable') ? 'checked' : '' }}>
                    <label for="salary_negotiable" style="margin: 0; cursor: pointer;">Salaire négociable</label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                    <label for="is_featured" style="margin: 0; cursor: pointer;">⭐ Mettre en avant</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description *</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
            @error('description')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Exigences</label>
            <textarea name="requirements" class="form-control" rows="4">{{ old('requirements') }}</textarea>
            @error('requirements')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Avantages</label>
            <textarea name="benefits" class="form-control" rows="4">{{ old('benefits') }}</textarea>
            @error('benefits')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer l'offre
            </button>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
