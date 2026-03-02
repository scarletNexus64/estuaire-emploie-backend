@extends('admin.layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Gestion des Utilisateurs')

@section('breadcrumbs')
    <span> / </span>
    <span>Utilisateurs</span>
@endsection

@section('content')
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.users.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Utilisateurs</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Candidats</div>
                <div class="stat-value">{{ $stats['candidates'] }}</div>
            </div>
            <div class="stat-icon">👤</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Recruteurs</div>
                <div class="stat-value">{{ $stats['recruiters'] }}</div>
            </div>
            <div class="stat-icon">💼</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Administrateurs</div>
                <div class="stat-value">{{ $stats['admins'] }}</div>
            </div>
            <div class="stat-icon">🛡️</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Actifs aujourd'hui</div>
                <div class="stat-value">{{ $stats['active_today'] }}</div>
            </div>
            <div class="stat-icon">🟢</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, email, téléphone..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-control">
                    <option value="">Tous</option>
                    <option value="candidate" {{ request('role') === 'candidate' ? 'selected' : '' }}>Candidat</option>
                    <option value="recruiter" {{ request('role') === 'recruiter' ? 'selected' : '' }}>Recruteur</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut (Candidats)</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="looking" {{ request('status') === 'looking' ? 'selected' : '' }}>En recherche</option>
                    <option value="employed" {{ request('status') === 'employed' ? 'selected' : '' }}>Employé</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Expérience</label>
                <select name="experience" class="form-control">
                    <option value="">Tous</option>
                    <option value="junior" {{ request('experience') === 'junior' ? 'selected' : '' }}>Junior</option>
                    <option value="intermediate" {{ request('experience') === 'intermediate' ? 'selected' : '' }}>Intermédiaire</option>
                    <option value="senior" {{ request('experience') === 'senior' ? 'selected' : '' }}>Senior</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'role', 'status', 'experience']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div style="margin-bottom: 1rem; display: flex; justify-content: flex-end;">
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Supprimer les sélectionnés
    </button>
</div>

<!-- Users Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                    </th>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Téléphone</th>
                    <th>Expérience</th>
                    <th>Candidatures</th>
                    <th>Score</th>
                    <th>Dernière connexion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $user->id }}">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                     alt="Photo {{ $user->name }}"
                                     style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e5e7eb;"
                                     onerror="this.onerror=null; this.outerHTML='<div style=\'width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;\'>{{ strtoupper(substr($user->name, 0, 1)) }}</div>';">
                            @else
                                <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <strong style="display: block;">{{ $user->name }}</strong>
                                <small style="color: var(--secondary); display: block;">{{ $user->email ?? 'Pas d\'email' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->role === 'candidate')
                            <span class="badge badge-info">Candidat</span>
                        @elseif($user->role === 'recruiter')
                            <span class="badge badge-warning">Recruteur</span>
                        @elseif($user->role === 'admin')
                            <span class="badge badge-danger">Admin</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($user->role) }}</span>
                        @endif
                        @if($user->is_super_admin)
                            <span class="badge badge-primary">Super Admin</span>
                        @endif
                    </td>
                    <td>{{ $user->phone ?? 'N/A' }}</td>
                    <td>
                        @if($user->experience_level)
                            @if($user->experience_level === 'junior')
                                <span class="badge badge-info">Junior</span>
                            @elseif($user->experience_level === 'intermediate')
                                <span class="badge badge-warning">Intermédiaire</span>
                            @elseif($user->experience_level === 'senior')
                                <span class="badge badge-success">Senior</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($user->experience_level) }}</span>
                            @endif
                        @else
                            <span style="color: var(--secondary);">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($user->role === 'candidate')
                            <strong>{{ $user->applications_count }}</strong>
                        @else
                            <span style="color: var(--secondary);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($user->role === 'candidate')
                            {{ $user->visibility_score ?? 0 }}/100
                        @else
                            <span style="color: var(--secondary);">-</span>
                        @endif
                    </td>
                    <td>
                        @if($user->last_login_at)
                            <span title="{{ $user->last_login_at->format('d/m/Y H:i') }}">
                                {{ $user->last_login_at->diffForHumans() }}
                            </span>
                        @else
                            <span style="color: var(--secondary);">Jamais</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">👥</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucun utilisateur trouvé</p>
                        <p style="margin-bottom: 0;">Essayez de modifier vos filtres de recherche</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        {{ $users->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk Delete
    const deleteBtn = document.getElementById('bulkDeleteBtn');
    deleteBtn?.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

        if (selected.length === 0) {
            alert('Veuillez sélectionner au moins un utilisateur');
            return;
        }

        if (!confirm(`Supprimer ${selected.length} utilisateur(s) sélectionné(s) ?\n\nCette action est irréversible.`)) {
            return;
        }

        const form = document.getElementById('bulkDeleteForm');

        // Add selected IDs as JSON
        const idsInput = document.createElement('input');
        idsInput.type = 'hidden';
        idsInput.name = 'ids';
        idsInput.value = JSON.stringify(selected);
        form.appendChild(idsInput);

        form.submit();
    });
</script>
@endsection
