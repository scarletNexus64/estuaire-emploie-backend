@extends('admin.layouts.app')

@section('title', 'Nouvel Administrateur')
@section('page-title', 'Créer un Administrateur')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.admins.index') }}" style="color: inherit; text-decoration: none;">Administrateurs</a>
    <span> / </span>
    <span>Créer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nouvel Administrateur</h3>
    </div>

    <form method="POST" action="{{ route('admin.admins.store') }}">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Nom complet *</label>
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
                <label class="form-label">Mot de passe *</label>
                <input type="password" name="password" class="form-control" required>
                @error('password')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Confirmer le mot de passe *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" style="font-weight: 600; font-size: 1rem; margin-bottom: 1rem; display: block;">Permissions</label>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_companies" id="perm_companies" {{ in_array('manage_companies', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_companies" style="margin: 0; cursor: pointer;">Gérer les entreprises</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_jobs" id="perm_jobs" {{ in_array('manage_jobs', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_jobs" style="margin: 0; cursor: pointer;">Gérer les offres d'emploi</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_applications" id="perm_applications" {{ in_array('manage_applications', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_applications" style="margin: 0; cursor: pointer;">Gérer les candidatures</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_users" id="perm_users" {{ in_array('manage_users', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_users" style="margin: 0; cursor: pointer;">Gérer les utilisateurs</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_recruiters" id="perm_recruiters" {{ in_array('manage_recruiters', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_recruiters" style="margin: 0; cursor: pointer;">Gérer les recruteurs</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_settings" id="perm_settings" {{ in_array('manage_settings', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_settings" style="margin: 0; cursor: pointer;">Gérer les paramètres</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_sections" id="perm_sections" {{ in_array('manage_sections', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_sections" style="margin: 0; cursor: pointer;">Gérer les sections</label>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="permissions[]" value="manage_admins" id="perm_admins" {{ in_array('manage_admins', old('permissions', [])) ? 'checked' : '' }}>
                    <label for="perm_admins" style="margin: 0; cursor: pointer;">Gérer les administrateurs</label>
                </div>
            </div>
            @error('permissions')
                <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
            @enderror
        </div>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer l'administrateur
            </button>
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
