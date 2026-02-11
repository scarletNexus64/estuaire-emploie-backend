@extends('admin.layouts.app')

@section('title', $isEdit ? 'Modifier le Plan' : 'Créer un Plan')
@section('page-title', $isEdit ? 'Modifier le Plan d\'Abonnement' : 'Créer un Plan d\'Abonnement')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.subscription-plans.job-seekers.index') }}">Plans & Tarifs</a> / {{ $isEdit ? 'Modifier' : 'Créer' }}</span>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.subscription-plans.job-seekers.update', $plan->id) : route('admin.subscription-plans.job-seekers.store') }}" method="POST">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Colonne Principale -->
        <div>
            <!-- Informations de Base -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du Plan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Nom du Plan</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $plan->name ?? '') }}" required placeholder="Ex: Premium, Business, Entreprise">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Le slug sera généré automatiquement à partir du nom</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                  placeholder="Décrivez brièvement ce plan d'abonnement">{{ old('description', $plan->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Prix (FCFA)</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $plan->price ?? 0) }}" required min="0" step="0.01">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Durée (jours)</label>
                            <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror"
                                   value="{{ old('duration_days', $plan->duration_days ?? 30) }}" required min="1">
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">30 jours = 1 mois</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités Incluses dans le Plan -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Fonctionnalités Incluses dans le Plan</h3>
                    <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        Cochez les fonctionnalités à inclure dans ce plan d'abonnement
                    </p>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                        @foreach($availableFeatures as $key => $label)
                            <div class="form-check-box">
                                <input type="checkbox" name="feature_{{ $key }}" id="feature_{{ $key }}" value="1"
                                       {{ old('feature_' . $key, isset($plan->features[$key]) ? $plan->features[$key] : false) ? 'checked' : '' }}>
                                <label for="feature_{{ $key }}">
                                    <strong>{{ $label }}</strong>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Secondaire -->
        <div>
            <!-- Apparence -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Apparence</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Icône / Emoji</label>
                        <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                               value="{{ old('icon', $plan->icon ?? '') }}" maxlength="10" placeholder="💼">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Couleur</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="color" name="color" class="form-control @error('color') is-invalid @enderror"
                                   value="{{ old('color', $plan->color ?? '#667eea') }}" style="width: 80px; padding: 0.25rem;">
                            <input type="text" class="form-control" value="{{ old('color', $plan->color ?? '#667eea') }}" readonly>
                        </div>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ordre d'Affichage</label>
                        <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror"
                               value="{{ old('display_order', $plan->display_order ?? 0) }}" min="0">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text">Plus petit = affiché en premier</small>
                    </div>
                </div>
            </div>

            <!-- Paramètres -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-check-box">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">
                            <strong>Plan Actif</strong>
                            <small>Visible pour les entreprises</small>
                        </label>
                    </div>

                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1"
                               {{ old('is_popular', $plan->is_popular ?? false) ? 'checked' : '' }}>
                        <label for="is_popular">
                            <strong>Marquer comme Populaire</strong>
                            <small>Badge "POPULAIRE" affiché</small>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        {{ $isEdit ? 'Mettre à Jour' : 'Créer le Plan' }}
                    </button>
                    <a href="{{ route('admin.subscription-plans.job-seekers.index') }}" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.form-check-box {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.form-check-box:hover {
    border-color: #667eea;
}

.form-check-box input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.form-check-box label {
    margin-left: 0.75rem;
    cursor: pointer;
    display: inline-block;
    margin-bottom: 0;
}

.form-check-box label strong {
    display: block;
    color: #1e293b;
    font-size: 0.875rem;
}

.form-check-box label small {
    display: block;
    color: #64748b;
    font-size: 0.75rem;
    margin-top: 0.125rem;
}

.form-check-box input[type="checkbox"]:checked + label {
    color: #667eea;
}

.form-check-box input[type="checkbox"]:checked + label strong {
    color: #667eea;
}
</style>

<script>
// Sync color picker with text input
document.querySelector('input[type="color"]').addEventListener('input', function(e) {
    document.querySelector('input[type="text"][readonly]').value = e.target.value;
});
</script>
@endsection
