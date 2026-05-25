@extends('admin.layouts.app')

@section('title', 'Tokens FCM - Push Notifications')

@section('page-title', 'Tokens FCM')

@section('breadcrumbs')
    <span>/</span>
    <span class="active">Tokens FCM</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.fcm-tokens.export', ['filter' => request('filter', 'with_token')]) }}" class="btn btn-success btn-sm">
        <i class="mdi mdi-download"></i> Exporter CSV
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Total Utilisateurs</div>
                            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="mdi mdi-account-multiple"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Avec Token FCM</div>
                            <div class="stat-value">{{ number_format($stats['users_with_token']) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="mdi mdi-bell-check"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="text-muted">
                            {{ $stats['total_users'] > 0 ? round(($stats['users_with_token'] / $stats['total_users']) * 100, 1) : 0 }}% du total
                        </span>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Sans Token FCM</div>
                            <div class="stat-value">{{ number_format($stats['users_without_token']) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="mdi mdi-bell-off"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="text-muted">
                            {{ $stats['total_users'] > 0 ? round(($stats['users_without_token'] / $stats['total_users']) * 100, 1) : 0 }}% du total
                        </span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Candidats</div>
                            <div class="stat-value">{{ number_format($stats['candidates_with_token']) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="text-muted">avec token FCM</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-label">Recruteurs</div>
                            <div class="stat-value">{{ number_format($stats['recruiters_with_token']) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="mdi mdi-briefcase-account"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span class="text-muted">avec token FCM</span>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.fcm-tokens.index') }}" class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label for="filter" class="form-label">Filtre</label>
                            <select name="filter" id="filter" class="form-control">
                                <option value="with_token" {{ request('filter', 'with_token') === 'with_token' ? 'selected' : '' }}>
                                    Avec Token FCM
                                </option>
                                <option value="without_token" {{ request('filter') === 'without_token' ? 'selected' : '' }}>
                                    Sans Token FCM
                                </option>
                                <option value="all" {{ request('filter') === 'all' ? 'selected' : '' }}>
                                    Tous les utilisateurs
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">Tous les rôles</option>
                                <option value="candidate" {{ request('role') === 'candidate' ? 'selected' : '' }}>Candidat</option>
                                <option value="recruiter" {{ request('role') === 'recruiter' ? 'selected' : '' }}>Recruteur</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="search" class="form-label">Rechercher</label>
                            <input type="text" name="search" id="search" class="form-control"
                                   placeholder="Nom, email, téléphone..."
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2 mb-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="mdi mdi-magnify"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="header-title mb-0">
                            Liste des utilisateurs
                            <span class="badge badge-info">{{ $users->total() }} résultat(s)</span>
                        </h4>
                    </div>

                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i>
                        <strong>Information:</strong> Les tokens FCM permettent d'envoyer des notifications push aux applications mobiles.
                        Les utilisateurs qui ont un token FCM peuvent recevoir des notifications push.
                    </div>

                    @if($users->count() > 0)
                        <!-- Bulk Delete Form -->
                        <form id="bulkDeleteForm" method="POST" action="{{ route('admin.fcm-tokens.bulk-destroy') }}">
                            @csrf
                        </form>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="checkbox-cell">
                                            <input type="checkbox" id="selectAll" class="custom-checkbox">
                                        </th>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Rôle</th>
                                        <th>Token FCM</th>
                                        <th>Dernière MAJ</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="checkbox-cell">
                                                <input type="checkbox" class="custom-checkbox row-checkbox" value="{{ $user->id }}">
                                            </td>
                                            <td>{{ $user->id }}</td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $roleColors = [
                                                        'candidate' => 'info',
                                                        'recruiter' => 'warning',
                                                        'admin' => 'danger'
                                                    ];
                                                    $roleLabels = [
                                                        'candidate' => 'Candidat',
                                                        'recruiter' => 'Recruteur',
                                                        'admin' => 'Admin'
                                                    ];
                                                @endphp
                                                <span class="badge badge-{{ $roleColors[$user->role] ?? 'secondary' }}">
                                                    {{ $roleLabels[$user->role] ?? $user->role }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->fcm_token)
                                                    <span class="badge badge-success">
                                                        <i class="mdi mdi-check-circle"></i> Oui
                                                    </span>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            onclick="showTokenModal('{{ substr($user->fcm_token, 0, 50) }}...', '{{ $user->fcm_token }}')"
                                                            title="Voir le token complet">
                                                        <i class="mdi mdi-eye"></i>
                                                    </button>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="mdi mdi-close-circle"></i> Non
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($user->fcm_token)
                                                    <form method="POST" action="{{ route('admin.fcm-tokens.destroy', $user->id) }}"
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Supprimer le token FCM de {{ $user->name }} ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer le token">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }}
                                sur {{ $users->total() }} résultats
                            </div>
                            <div>
                                {{ $users->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="mdi mdi-information"></i>
                            Aucun utilisateur trouvé avec les critères de recherche.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Token Modal -->
<div id="tokenModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: 12px; max-width: 600px; width: 90%;">
        <h4 style="margin-bottom: 1rem;">Token FCM Complet</h4>
        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; word-break: break-all; font-family: monospace; font-size: 0.875rem; max-height: 300px; overflow-y: auto;" id="tokenContent">
        </div>
        <div style="margin-top: 1rem; display: flex; gap: 1rem; justify-content: flex-end;">
            <button onclick="copyToken()" class="btn btn-primary">
                <i class="mdi mdi-content-copy"></i> Copier
            </button>
            <button onclick="closeTokenModal()" class="btn btn-secondary">
                Fermer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentToken = '';

    function showTokenModal(preview, fullToken) {
        currentToken = fullToken;
        document.getElementById('tokenContent').textContent = fullToken;
        document.getElementById('tokenModal').style.display = 'flex';
    }

    function closeTokenModal() {
        document.getElementById('tokenModal').style.display = 'none';
    }

    function copyToken() {
        navigator.clipboard.writeText(currentToken).then(() => {
            alert('Token copié dans le presse-papiers !');
        }).catch(err => {
            console.error('Erreur lors de la copie:', err);
        });
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTokenModal();
        }
    });

    // Close modal on background click
    document.getElementById('tokenModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeTokenModal();
        }
    });
</script>
@endpush
@endsection
