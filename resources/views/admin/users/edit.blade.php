@extends('admin.layouts.app')

@section('title', 'Modifier l\'utilisateur')
@section('page-title', 'Modifier l\'utilisateur')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
    <span> / </span>
    <span>Modifier</span>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Modifier: {{ $user->name }}</h3>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" style="padding: 1.5rem;">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- Left Column -->
            <div>
                <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Générales</h4>

                <!-- Name -->
                <div class="form-group">
                    <label class="form-label required">Nom complet</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $user->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Location -->
                <div class="form-group">
                    <label class="form-label">Localisation</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                           value="{{ old('location', $user->location) }}">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Profile Photo -->
                <div class="form-group">
                    <label class="form-label">Photo de profil</label>
                    @if($user->profile_photo)
                        <div style="margin-bottom: 0.5rem;">
                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                 alt="Photo actuelle"
                                 style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb;">
                        </div>
                    @endif
                    <input type="file" name="profile_photo" class="form-control @error('profile_photo') is-invalid @enderror" accept="image/*">
                    <small style="color: var(--secondary);">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Professionnelles</h4>

                <!-- Role -->
                <div class="form-group">
                    <label class="form-label required">Rôle</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="candidate" {{ old('role', $user->role) === 'candidate' ? 'selected' : '' }}>Candidat</option>
                        <option value="recruiter" {{ old('role', $user->role) === 'recruiter' ? 'selected' : '' }}>Recruteur</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status (for candidates) -->
                <div class="form-group">
                    <label class="form-label">Statut (pour candidats)</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="">Sélectionner un statut</option>
                        <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="looking" {{ old('status', $user->status) === 'looking' ? 'selected' : '' }}>En recherche</option>
                        <option value="employed" {{ old('status', $user->status) === 'employed' ? 'selected' : '' }}>Employé</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Experience Level -->
                <div class="form-group">
                    <label class="form-label">Niveau d'expérience</label>
                    <select name="experience_level" class="form-control @error('experience_level') is-invalid @enderror">
                        <option value="">Sélectionner un niveau</option>
                        <option value="junior" {{ old('experience_level', $user->experience_level) === 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="intermediate" {{ old('experience_level', $user->experience_level) === 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                        <option value="senior" {{ old('experience_level', $user->experience_level) === 'senior' ? 'selected' : '' }}>Senior</option>
                    </select>
                    @error('experience_level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Skills -->
                <div class="form-group">
                    <label class="form-label">Compétences</label>
                    <textarea name="skills" class="form-control @error('skills') is-invalid @enderror"
                              rows="3" placeholder="Ex: PHP, Laravel, JavaScript...">{{ old('skills', $user->skills) }}</textarea>
                    @error('skills')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Bio -->
                <div class="form-group">
                    <label class="form-label">Biographie</label>
                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror"
                              rows="4" placeholder="À propos de l'utilisateur...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<style>
    .required::after {
        content: ' *';
        color: var(--danger);
    }

    .invalid-feedback {
        display: block;
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: var(--danger);
    }
</style>
@endsection
