@extends('admin.layouts.app')

@section('title', 'Éditer Recruteur')
@section('page-title', 'Éditer le Recruteur')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $recruiter->user->name }}</h3>
            <a href="{{ route('admin.recruiters.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <form action="{{ route('admin.recruiters.update', $recruiter) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Utilisateur</label>
                    <input type="text" class="form-control" value="{{ $recruiter->user->name }} ({{ $recruiter->user->email }})" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Entreprise *</label>
                    <select name="company_id" class="form-control" required>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $recruiter->company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Poste</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position', $recruiter->position) }}" placeholder="Ex: RH Manager, Recruteur Senior">
                </div>

                <h4 style="margin: 2rem 0 1rem; font-weight: 600;">Permissions</h4>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_publish" value="1" {{ old('can_publish', $recruiter->can_publish) ? 'checked' : '' }}>
                        <span>Peut publier des offres d'emploi</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_view_applications" value="1" {{ old('can_view_applications', $recruiter->can_view_applications) ? 'checked' : '' }}>
                        <span>Peut voir les candidatures</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_modify_company" value="1" {{ old('can_modify_company', $recruiter->can_modify_company) ? 'checked' : '' }}>
                        <span>Peut modifier les informations de l'entreprise</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="{{ route('admin.recruiters.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
