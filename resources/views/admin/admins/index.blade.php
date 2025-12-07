@extends('admin.layouts.app')

@section('title', 'Administrateurs')
@section('page-title', 'Gestion des Administrateurs')

@section('breadcrumbs')
    <span> / </span>
    <span>Administrateurs</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvel Administrateur
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Admins</div>
                <div class="stat-value">{{ $admins->total() }}</div>
            </div>
            <div class="stat-icon">👤</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Actifs</div>
                <div class="stat-value">{{ $admins->where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Avec permissions</div>
                <div class="stat-value">{{ $admins->filter(fn($a) => $a->permissions !== null)->count() }}</div>
            </div>
            <div class="stat-icon">🔑</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Super Admin</div>
                <div class="stat-value">1</div>
            </div>
            <div class="stat-icon">⭐</div>
        </div>
    </div>
</div>

<!-- Admins Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Administrateur</th>
                    <th>Rôle</th>
                    <th>Permissions</th>
                    <th>Dernière connexion</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            <div>
                                <strong style="display: block;">{{ $admin->name }}</strong>
                                <small style="color: var(--secondary); display: block;">{{ $admin->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($admin->isSuperAdmin())
                            <span class="badge badge-primary">⭐ Super Admin</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($admin->role) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($admin->isSuperAdmin())
                            <span class="badge badge-success">Tous les droits</span>
                        @elseif($admin->permissions && count($admin->permissions) > 0)
                            <span class="badge badge-info">{{ count($admin->permissions) }} permissions</span>
                        @else
                            <span style="color: var(--secondary);">Aucune</span>
                        @endif
                    </td>
                    <td>
                        @if($admin->last_login_at)
                            {{ $admin->last_login_at->diffForHumans() }}
                        @else
                            <span style="color: var(--secondary);">Jamais</span>
                        @endif
                    </td>
                    <td>
                        @if($admin->is_active)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-danger">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            @if(!$admin->isSuperAdmin())
                            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-sm btn-secondary" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @else
                            <span class="badge badge-warning">Protégé</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">👤</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucun administrateur trouvé</p>
                        <p style="margin-bottom: 1.5rem;">Créez un nouvel administrateur</p>
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvel Administrateur
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        {{ $admins->links() }}
    </div>
    @endif
</div>
@endsection
