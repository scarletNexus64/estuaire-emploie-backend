@extends('admin.layouts.app')

@section('title', 'Détails Administrateur')
@section('page-title', $user->name)

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.admins.index') }}" style="color: inherit; text-decoration: none;">Administrateurs</a>
    <span> / </span>
    <span>Détails</span>
@endsection

@section('header-actions')
    @if(!$user->isSuperAdmin())
    <a href="{{ route('admin.admins.edit', $user) }}" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Modifier
    </a>
    @endif
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
    <!-- Profile Card -->
    <div class="card">
        <div style="text-align: center;">
            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700; margin: 0 auto 1.5rem;">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $user->name }}</h2>
            <p style="color: var(--secondary); margin-bottom: 1rem;">
                @if($user->isSuperAdmin())
                    ⭐ Super Administrateur
                @else
                    {{ ucfirst($user->role) }}
                @endif
            </p>

            @if($user->is_active)
                <span class="badge badge-success">Actif</span>
            @else
                <span class="badge badge-danger">Inactif</span>
            @endif

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
                <div style="display: flex; flex-direction: column; gap: 1rem; text-align: left;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Email</small>
                            <strong>{{ $user->email }}</strong>
                        </div>
                    </div>

                    @if($user->phone)
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Téléphone</small>
                            <strong>{{ $user->phone }}</strong>
                        </div>
                    </div>
                    @endif

                    <div style="display: flex; align-items: center; gap: 12px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Créé le</small>
                            <strong>{{ $user->created_at->format('d/m/Y') }}</strong>
                        </div>
                    </div>

                    @if($user->last_login_at)
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Dernière connexion</small>
                            <strong>{{ $user->last_login_at->diffForHumans() }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Permissions Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Permissions</h3>
        </div>

        @if($user->isSuperAdmin())
        <div style="text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">⭐</div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Super Administrateur</h3>
            <p style="color: var(--secondary);">Cet utilisateur a tous les droits d'administration</p>
        </div>
        @else
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            @php
                $allPermissions = [
                    'manage_companies' => ['label' => 'Gérer les entreprises', 'icon' => '🏢'],
                    'manage_jobs' => ['label' => 'Gérer les offres d\'emploi', 'icon' => '💼'],
                    'manage_applications' => ['label' => 'Gérer les candidatures', 'icon' => '📝'],
                    'manage_users' => ['label' => 'Gérer les utilisateurs', 'icon' => '👥'],
                    'manage_recruiters' => ['label' => 'Gérer les recruteurs', 'icon' => '👔'],
                    'manage_settings' => ['label' => 'Gérer les paramètres', 'icon' => '⚙️'],
                    'manage_sections' => ['label' => 'Gérer les sections', 'icon' => '📂'],
                    'manage_admins' => ['label' => 'Gérer les administrateurs', 'icon' => '👤'],
                ];
            @endphp

            @foreach($allPermissions as $perm => $data)
            <div style="padding: 1rem; background: var(--light); border-radius: 8px; display: flex; align-items: center; gap: 12px;">
                <div style="font-size: 1.5rem;">{{ $data['icon'] }}</div>
                <div style="flex: 1;">
                    <strong style="display: block; margin-bottom: 0.25rem;">{{ $data['label'] }}</strong>
                    @if(in_array($perm, $user->permissions ?? []))
                        <span class="badge badge-success">Autorisé</span>
                    @else
                        <span class="badge badge-secondary">Non autorisé</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if(!$user->permissions || count($user->permissions) === 0)
        <div style="text-align: center; padding: 2rem; color: var(--secondary);">
            <p>Aucune permission spécifique attribuée à cet administrateur</p>
        </div>
        @endif
        @endif
    </div>
</div>
@endsection
