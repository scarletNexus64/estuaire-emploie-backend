@extends('admin.layouts.app')

@section('title', 'Ajouter Recruteur')
@section('page-title', 'Ajouter un Nouveau Recruteur')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Nouveau Recruteur</h3>
            <a href="{{ route('admin.recruiters.index') }}" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <form action="{{ route('admin.recruiters.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Utilisateur (Recruteur) *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Entreprise *</label>
                    <select name="company_id" class="form-control" required>
                        <option value="">Sélectionner une entreprise</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Poste</label>
                    <input type="text" name="position" class="form-control" placeholder="Ex: RH Manager, Recruteur Senior">
                </div>

                <h4 style="margin: 2rem 0 1rem; font-weight: 600;">Permissions</h4>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_publish" value="1" checked>
                        <span>Peut publier des offres d'emploi</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_view_applications" value="1" checked>
                        <span>Peut voir les candidatures</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_modify_company" value="1">
                        <span>Peut modifier les informations de l'entreprise</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Créer le Recruteur</button>
                    <a href="{{ route('admin.recruiters.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
