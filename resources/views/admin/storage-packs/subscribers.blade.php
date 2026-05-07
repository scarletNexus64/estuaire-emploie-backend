@extends('admin.layouts.app')

@section('title', 'Souscripteurs Packs de Stockage')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Souscripteurs aux Packs de Stockage</h1>
            <p class="text-muted">Tous les utilisateurs qui ont acheté un pack de stockage</p>
        </div>
        <a href="{{ route('admin.storage-packs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux packs
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Souscriptions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSubscriptions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Actifs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeSubscriptions }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Stockage Total Alloué
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalStorageAllocated / 1024, 2) }} Go
                            </div>
                            <small class="text-muted">{{ number_format($totalStorageUsed / 1024, 2) }} Go utilisés</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hdd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Revenus Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($totalRevenue, 0, ',', ' ') }} CFA
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.storage-packs.subscribers') }}" class="form-inline">
                <div class="form-group mr-3 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher un utilisateur..."
                           value="{{ request('search') }}">
                </div>

                <div class="form-group mr-3 mb-2">
                    <select name="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expirés</option>
                    </select>
                </div>

                <div class="form-group mr-3 mb-2">
                    <select name="pack_id" class="form-control">
                        <option value="">Tous les packs</option>
                        @foreach($storagePacks as $pack)
                            <option value="{{ $pack->id }}" {{ request('pack_id') == $pack->id ? 'selected' : '' }}>
                                {{ $pack->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-filter"></i> Filtrer
                </button>

                <a href="{{ route('admin.storage-packs.subscribers') }}" class="btn btn-secondary ml-2 mb-2">
                    <i class="fas fa-redo"></i> Réinitialiser
                </a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Souscripteurs</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Pack</th>
                            <th>Espace</th>
                            <th>Utilisation</th>
                            <th>Acheté le</th>
                            <th>Expire le</th>
                            <th>Prix Payé</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($userPacks as $userPack)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle mr-2">
                                            {{ strtoupper(substr($userPack->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $userPack->user->name }}</strong><br>
                                            <small class="text-muted">{{ $userPack->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $userPack->storagePack->name }}</strong><br>
                                    <small class="text-muted">{{ $userPack->storagePack->formatted_storage }}</small>
                                </td>
                                <td>{{ $userPack->formatted_total_storage }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2" style="flex: 1;">
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar
                                                    @if($userPack->usage_percentage > 80) bg-danger
                                                    @elseif($userPack->usage_percentage > 50) bg-warning
                                                    @else bg-success
                                                    @endif"
                                                    role="progressbar"
                                                    style="width: {{ $userPack->usage_percentage }}%"
                                                    aria-valuenow="{{ $userPack->usage_percentage }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                    {{ number_format($userPack->usage_percentage, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted text-nowrap">
                                            {{ $userPack->formatted_used_storage }} / {{ $userPack->formatted_total_storage }}
                                        </small>
                                    </div>
                                </td>
                                <td>{{ $userPack->purchased_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{ $userPack->expires_at->format('d/m/Y') }}<br>
                                    <small class="text-muted">
                                        @if($userPack->isExpired())
                                            Expiré
                                        @else
                                            {{ $userPack->expires_at->diffForHumans() }}
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ number_format($userPack->purchase_price, 0, ',', ' ') }} CFA</strong>
                                </td>
                                <td>
                                    @if($userPack->is_active)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-secondary">Expiré</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $userPack->user_id) }}"
                                           class="btn btn-sm btn-info" title="Voir l'utilisateur">
                                            <i class="fas fa-user"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                title="Supprimer la souscription"
                                                onclick="confirmDelete({{ $userPack->id }}, '{{ $userPack->user->name }}', '{{ $userPack->storagePack->name }}', {{ $userPack->storage_used_mb }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    <!-- Form de suppression caché -->
                                    <form id="delete-form-{{ $userPack->id }}"
                                          action="{{ route('admin.storage-packs.subscribers.destroy', $userPack->id) }}"
                                          method="POST"
                                          style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun souscripteur trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Affichage de {{ $userPacks->firstItem() ?? 0 }} à {{ $userPacks->lastItem() ?? 0 }}
                    sur {{ $userPacks->total() }} souscripteurs
                </div>
                <div>
                    {{ $userPacks->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.avatar-circle {
    width: 40px;
    height: 40px;
    background-color: #4e73df;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
}
</style>

<script>
function confirmDelete(id, userName, packName, storageUsed) {
    // Construire le message de confirmation
    let message = `Êtes-vous sûr de vouloir supprimer la souscription de ${userName} au pack "${packName}" ?`;

    // Ajouter un avertissement si des fichiers sont stockés
    if (storageUsed > 0) {
        const storageFormatted = storageUsed < 1024
            ? storageUsed + ' Mo'
            : (storageUsed / 1024).toFixed(2) + ' Go';

        message += `\n\n⚠️ ATTENTION : L'utilisateur a encore ${storageFormatted} de fichiers stockés qui seront également supprimés.`;
    }

    message += '\n\nCette action est irréversible.';

    // Demander confirmation
    if (confirm(message)) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
