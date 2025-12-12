@extends('admin.layouts.app')

@section('title', $isEdit ? 'Modifier le Service' : 'Créer un Service')
@section('page-title', $isEdit ? 'Modifier le Service Premium' : 'Créer un Service Premium')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.premium-services.index') }}">Services Premium</a> / {{ $isEdit ? 'Modifier' : 'Créer' }}</span>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.premium-services.update', $service->id) : route('admin.premium-services.store') }}" method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du Service</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Nom du Service</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $service->name ?? '') }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $service->description ?? '') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Prix (FCFA)</label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $service->price ?? 0) }}" required min="0" step="0.01">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Durée (jours)</label>
                            <input type="number" name="duration_days" class="form-control @error('duration_days') is-invalid @enderror"
                                   value="{{ old('duration_days', $service->duration_days ?? '') }}" min="1" placeholder="Vide = Permanent">
                            @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Type de Service</label>
                        <select name="service_type" class="form-control @error('service_type') is-invalid @enderror" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="cv_premium" {{ old('service_type', $service->service_type ?? '') == 'cv_premium' ? 'selected' : '' }}>CV Premium</option>
                            <option value="verified_badge" {{ old('service_type', $service->service_type ?? '') == 'verified_badge' ? 'selected' : '' }}>Badge Vérifié</option>
                            <option value="sms_alerts" {{ old('service_type', $service->service_type ?? '') == 'sms_alerts' ? 'selected' : '' }}>Alertes SMS/WhatsApp</option>
                            <option value="cv_review" {{ old('service_type', $service->service_type ?? '') == 'cv_review' ? 'selected' : '' }}>Révision CV</option>
                            <option value="interview_coaching" {{ old('service_type', $service->service_type ?? '') == 'interview_coaching' ? 'selected' : '' }}>Coaching Entretien</option>
                            <option value="custom" {{ old('service_type', $service->service_type ?? '') == 'custom' ? 'selected' : '' }}>Service Personnalisé</option>
                        </select>
                        @error('service_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Apparence</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Icône</label>
                        <input type="text" name="icon" class="form-control" value="{{ old('icon', $service->icon ?? '✨') }}" maxlength="10">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Couleur</label>
                        <input type="color" name="color" class="form-control" value="{{ old('color', $service->color ?? '#667eea') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ordre</label>
                        <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $service->display_order ?? 0) }}" min="0">
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-check-box">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active"><strong>Actif</strong></label>
                    </div>
                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular', $service->is_popular ?? false) ? 'checked' : '' }}>
                        <label for="is_popular"><strong>Populaire</strong></label>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        {{ $isEdit ? 'Mettre à Jour' : 'Créer' }}
                    </button>
                    <a href="{{ route('admin.premium-services.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
