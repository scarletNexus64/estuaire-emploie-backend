@extends('admin.layouts.app')

@section('title', 'Users Devices')

@section('breadcrumb')
    <i class="mdi mdi-chevron-right"></i>
    <span>Users Devices</span>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <i class="mdi mdi-cellphone text-primary text-lg"></i>
        <span>{{ $stats['total'] }} utilisateur(s)</span>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card info">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Total Utilisateurs</div>
                    <div class="stat-value">{{ number_format($stats['total']) }}</div>
                </div>
                <i class="mdi mdi-account-group stat-icon text-blue-500"></i>
            </div>
        </div>

        <div class="stat-card success">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Avec Appareil</div>
                    <div class="stat-value">{{ number_format($stats['with_device']) }}</div>
                </div>
                <i class="mdi mdi-cellphone-check stat-icon text-green-500"></i>
            </div>
            <div class="stat-footer">
                <span class="stat-trend">{{ $stats['total'] > 0 ? round(($stats['with_device'] / $stats['total']) * 100, 1) : 0 }}% des utilisateurs</span>
            </div>
        </div>

        <div class="stat-card warning">
            <div class="stat-header">
                <div>
                    <div class="stat-label">Sans Appareil</div>
                    <div class="stat-value">{{ number_format($stats['without_device']) }}</div>
                </div>
                <i class="mdi mdi-cellphone-off stat-icon text-orange-500"></i>
            </div>
            <div class="stat-footer">
                <span class="stat-trend">{{ $stats['total'] > 0 ? round(($stats['without_device'] / $stats['total']) * 100, 1) : 0 }}% des utilisateurs</span>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="search-box flex-1">
            <form method="GET" action="{{ route('admin.users-devices.index') }}" class="flex gap-2">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Rechercher par nom, email, téléphone ou device ID..."
                       class="form-control">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-magnify"></i> Rechercher
                </button>
                @if($search)
                    <a href="{{ route('admin.users-devices.index', ['filter' => $filter]) }}" class="btn btn-secondary">
                        <i class="mdi mdi-close"></i> Effacer
                    </a>
                @endif
            </form>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.users-devices.index', ['search' => $search, 'filter' => 'all']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filter === 'all' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                Tous
            </a>
            <a href="{{ route('admin.users-devices.index', ['search' => $search, 'filter' => 'with_device']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filter === 'with_device' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                <i class="mdi mdi-cellphone-check"></i> Avec appareil
            </a>
            <a href="{{ route('admin.users-devices.index', ['search' => $search, 'filter' => 'without_device']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $filter === 'without_device' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                <i class="mdi mdi-cellphone-off"></i> Sans appareil
            </a>
        </div>
    </div>

    <!-- Users List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="mdi mdi-cellphone text-primary"></i>
                Liste des utilisateurs et leurs appareils
            </h3>
        </div>
        <div class="card-body p-0">
            @if($users->isEmpty())
                <div class="empty-state">
                    <i class="mdi mdi-account-search text-gray-300"></i>
                    <p class="text-gray-500">Aucun utilisateur trouvé.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Utilisateur</th>
                                <th>Contact</th>
                                <th>Rôle</th>
                                <th>Device ID</th>
                                <th>Dernière Activité</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="{{ !$user->device_id ? 'bg-gray-50' : '' }}">
                                    <td class="font-mono text-sm">#{{ $user->id }}</td>
                                    <td>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">
                                                Inscrit le {{ $user->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->email)
                                            <div class="text-sm flex items-center gap-1">
                                                <i class="mdi mdi-email-outline text-gray-400"></i>
                                                {{ $user->email }}
                                            </div>
                                        @endif
                                        @if($user->phone)
                                            <div class="text-sm text-gray-600 flex items-center gap-1">
                                                <i class="mdi mdi-phone text-gray-400"></i>
                                                {{ $user->phone }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge badge-danger">
                                                <i class="mdi mdi-shield-account"></i> Admin
                                            </span>
                                        @elseif($user->role === 'recruiter')
                                            <span class="badge badge-info">
                                                <i class="mdi mdi-account-tie"></i> Recruteur
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="mdi mdi-account"></i> Candidat
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->device_id)
                                            <div class="flex items-center gap-2">
                                                <i class="mdi mdi-cellphone-check text-green-500"></i>
                                                <code class="text-xs bg-green-50 px-2 py-1 rounded">
                                                    {{ Str::limit($user->device_id, 25) }}
                                                </code>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-gray-400">
                                                <i class="mdi mdi-cellphone-off"></i>
                                                <span class="text-xs italic">Aucun appareil</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-sm">{{ $user->updated_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->updated_at->format('H:i') }}</div>
                                        <div class="text-xs text-gray-400">{{ $user->updated_at->diffForHumans() }}</div>
                                    </td>
                                    <td>
                                        @if($user->device_id)
                                            <button
                                                onclick="confirmResetDevice({{ $user->id }}, '{{ $user->name }}')"
                                                class="btn btn-warning btn-sm"
                                                title="Déconnecter l'appareil">
                                                <i class="mdi mdi-cellphone-remove"></i> Déconnecter
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200">
                    {{ $users->appends(['search' => $search, 'filter' => $filter])->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reset Device Form (hidden) -->
<form id="resetDeviceForm" method="POST" style="display: none;">
    @csrf
</form>

@push('scripts')
<script>
function confirmResetDevice(userId, userName) {
    if (confirm(`⚠️ Déconnecter l'appareil de "${userName}" ?\n\nL'utilisateur devra se reconnecter et son appareil sera réassocié automatiquement.`)) {
        const form = document.getElementById('resetDeviceForm');
        form.action = `/admin/users-devices/${userId}/reset`;
        form.submit();
    }
}
</script>
@endpush
@endsection
