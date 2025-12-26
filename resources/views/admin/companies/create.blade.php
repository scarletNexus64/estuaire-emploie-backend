@extends('admin.layouts.app')

@section('title', 'Nouvelle Entreprise')
@section('page-title', 'Créer une Entreprise')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.companies.index') }}" style="color: inherit; text-decoration: none;">Entreprises</a>
    <span> / </span>
    <span>Créer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nouvelle Entreprise</h3>
    </div>

    <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Logo Section -->
        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">Logo de l'entreprise</label>
            <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/jpg">
            <small style="color: var(--secondary); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                Formats acceptés: PNG, JPG, JPEG - Taille max: 2 MB
            </small>
            @error('logo')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Nom de l'entreprise *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                @error('email')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+237 690 000 000">
                @error('phone')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Secteur *</label>
                <input type="text" name="sector" class="form-control" value="{{ old('sector') }}" placeholder="Ex: Technologie, Finance, Santé..." required>
                @error('sector')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Site web</label>
                <input type="url" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://example.com">
                @error('website')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Ville</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="Douala">
                @error('city')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Pays</label>
                <input type="text" name="country" class="form-control" value="{{ old('country') }}" placeholder="Cameroun">
                @error('country')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Statut *</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="verified" {{ old('status') === 'verified' ? 'selected' : '' }}>Vérifiée</option>
                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspendue</option>
                </select>
                @error('status')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Plan d'abonnement *</label>
                <select name="subscription_plan" class="form-control" required>
                    <option value="free" {{ old('subscription_plan') === 'free' ? 'selected' : '' }}>Gratuit</option>
                    <option value="premium" {{ old('subscription_plan') === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
                @error('subscription_plan')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Adresse</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="123 Rue Principale">
            @error('address')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Description de l'entreprise...">{{ old('description') }}</textarea>
            @error('description')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer l'entreprise
            </button>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
