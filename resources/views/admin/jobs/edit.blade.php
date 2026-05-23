@extends('admin.layouts.app')

@section('title', 'Éditer Offre')
@section('page-title', 'Éditer l\'Offre d\'Emploi')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.jobs.index') }}" style="color: inherit; text-decoration: none;">Offres d'emploi</a>
    <span> / </span>
    <span>Éditer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Modifier {{ $job->title }}</h3>
    </div>

    <form method="POST" action="{{ route('admin.jobs.update', $job) }}">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Titre de l'offre *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $job->title) }}" required>
                @error('title')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Entreprise *</label>
                <select name="company_id" id="company_id" class="form-control" required>
                    <option value="">Sélectionner une entreprise</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $job->company_id) == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Secteur (niveau 3)</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">— Aucun —</option>
                </select>
                <small id="categoryHint" style="color: #6b7280;">
                    Les secteurs proposés correspondent aux niveaux 2 des catégories de l'entreprise.
                </small>
                @error('category_id')
                    <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Visibilité *</label>
                <select name="visibility" class="form-control" required>
                    <option value="national" {{ old('visibility', $job->visibility) === 'national' ? 'selected' : '' }}>Nationale (visible partout)</option>
                    <option value="local" {{ old('visibility', $job->visibility) === 'local' ? 'selected' : '' }}>Locale (ville de l'entreprise uniquement)</option>
                </select>
                @error('visibility')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Type de contrat *</label>
                <select name="contract_type_id" class="form-control" required>
                    <option value="">Sélectionner un type</option>
                    @foreach($contractTypes as $type)
                        <option value="{{ $type->id }}" {{ old('contract_type_id', $job->contract_type_id) == $type->id ? 'selected' : '' }}>
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
                <input type="text" name="experience_level" class="form-control" value="{{ old('experience_level', $job->experience_level) }}" placeholder="Ex: Junior, Senior, Expert...">
                @error('experience_level')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Salaire minimum</label>
                <input type="text" name="salary_min" class="form-control" value="{{ old('salary_min', $job->salary_min) }}" placeholder="Ex: 500000 FCFA">
                @error('salary_min')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Salaire maximum</label>
                <input type="text" name="salary_max" class="form-control" value="{{ old('salary_max', $job->salary_max) }}" placeholder="Ex: 1000000 FCFA">
                @error('salary_max')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date limite de candidature</label>
                <input type="date" name="application_deadline" class="form-control" value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}">
                @error('application_deadline')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Statut *</label>
                <select name="status" class="form-control" required>
                    <option value="draft" {{ old('status', $job->status) === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending" {{ old('status', $job->status) === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="published" {{ old('status', $job->status) === 'published' ? 'selected' : '' }}>Publiée</option>
                    <option value="closed" {{ old('status', $job->status) === 'closed' ? 'selected' : '' }}>Fermée</option>
                    <option value="expired" {{ old('status', $job->status) === 'expired' ? 'selected' : '' }}>Expirée</option>
                </select>
                @error('status')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="salary_negotiable" id="salary_negotiable" {{ old('salary_negotiable', $job->salary_negotiable) ? 'checked' : '' }}>
                    <label for="salary_negotiable" style="margin: 0; cursor: pointer;">Salaire négociable</label>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_featured" id="is_featured" {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}>
                    <label for="is_featured" style="margin: 0; cursor: pointer;">⭐ Mettre en avant</label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description *</label>
            <textarea name="description" class="form-control" rows="6" required>{{ old('description', $job->description) }}</textarea>
            @error('description')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Exigences</label>
            <textarea name="requirements" class="form-control" rows="4">{{ old('requirements', $job->requirements) }}</textarea>
            @error('requirements')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Avantages</label>
            <textarea name="benefits" class="form-control" rows="4">{{ old('benefits', $job->benefits) }}</textarea>
            @error('benefits')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer les modifications
            </button>
            <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
const companyLevel3Map = @json($companyLevel3Map);
const oldCategoryId = @json(old('category_id', $job->category_id));

const companySelect = document.getElementById('company_id');
const categorySelect = document.getElementById('category_id');
const categoryHint = document.getElementById('categoryHint');

function updateCategoryOptions() {
    const cid = companySelect.value;
    categorySelect.innerHTML = '';
    if (!cid) {
        categorySelect.innerHTML = '<option value="">— Sélectionnez d\'abord une entreprise —</option>';
        categoryHint.textContent = 'Les secteurs proposés correspondent aux niveaux 2 des catégories de l\'entreprise.';
        categoryHint.style.color = '#6b7280';
        return;
    }
    const options = companyLevel3Map[cid] || [];
    if (options.length === 0) {
        categorySelect.innerHTML = '<option value="">— Aucun secteur niveau 3 disponible —</option>';
        categoryHint.textContent = 'Cette entreprise n\'a aucun secteur niveau 3 rattaché. Ajoutez des catégories à l\'entreprise.';
        categoryHint.style.color = '#dc2626';
        return;
    }
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = '— Aucun (optionnel) —';
    categorySelect.appendChild(placeholder);
    options.forEach(opt => {
        const o = document.createElement('option');
        o.value = opt.id;
        o.textContent = opt.label;
        if (String(opt.id) === String(oldCategoryId)) o.selected = true;
        categorySelect.appendChild(o);
    });
    categoryHint.textContent = `${options.length} secteur(s) niveau 3 disponible(s).`;
    categoryHint.style.color = '#10b981';
}

companySelect.addEventListener('change', updateCategoryOptions);
updateCategoryOptions();
</script>
@endsection
