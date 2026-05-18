@extends('admin.layouts.app')

@section('title', 'Device Management')

@section('breadcrumb')
    <i class="mdi mdi-chevron-right"></i>
    <span>Device Management</span>
@endsection

@section('header-actions')
    <div class="flex items-center gap-2 text-sm text-gray-600">
        <i class="mdi mdi-cellphone-link text-primary text-lg"></i>
        <span>{{ $requests->total() }} demande(s)</span>
        @if($pendingCount > 0)
            <span class="badge badge-warning">{{ $pendingCount }} en attente</span>
        @endif
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="filter-bar">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.device-change-requests.index', ['status' => 'pending']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === 'pending' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                <i class="mdi mdi-clock-outline"></i> En attente
                @if($pendingCount > 0)
                    <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.device-change-requests.index', ['status' => 'approved']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === 'approved' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                <i class="mdi mdi-check-circle-outline"></i> Approuvées
            </a>
            <a href="{{ route('admin.device-change-requests.index', ['status' => 'rejected']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === 'rejected' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                <i class="mdi mdi-close-circle-outline"></i> Rejetées
            </a>
            <a href="{{ route('admin.device-change-requests.index', ['status' => 'all']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $status === 'all' ? 'bg-primary text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}">
                <i class="mdi mdi-view-list"></i> Toutes
            </a>
        </div>
    </div>

    <!-- Requests List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="mdi mdi-cellphone-link text-primary"></i>
                Demandes de changement d'appareil
            </h3>
        </div>
        <div class="card-body p-0">
            @if($requests->isEmpty())
                <div class="empty-state">
                    <i class="mdi mdi-cellphone-link text-gray-300"></i>
                    <p class="text-gray-500">Aucune demande trouvée pour ce statut.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Utilisateur</th>
                                <th>Contact</th>
                                <th>Ancien Appareil</th>
                                <th>Nouvel Appareil</th>
                                <th>Raison</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td class="font-mono text-sm">#{{ $request->id }}</td>
                                    <td>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $request->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">ID: {{ $request->user_id }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->user->email)
                                            <div class="text-sm">{{ $request->user->email }}</div>
                                        @endif
                                        @if($request->user->phone)
                                            <div class="text-sm text-gray-600">{{ $request->user->phone }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <code class="text-xs">{{ Str::limit($request->old_device_id, 20) }}</code>
                                    </td>
                                    <td>
                                        <code class="text-xs">{{ Str::limit($request->new_device_id, 20) }}</code>
                                        @if($request->device_name)
                                            <div class="text-xs text-gray-500 mt-1">{{ $request->device_name }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="max-w-xs">
                                            {{ Str::limit($request->reason ?? 'Non spécifiée', 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm">{{ $request->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $request->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge badge-warning">
                                                <i class="mdi mdi-clock-outline"></i> En attente
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-check-circle"></i> Approuvée
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="mdi mdi-close-circle"></i> Rejetée
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <div class="flex items-center gap-2">
                                                <!-- Approve Button -->
                                                <button
                                                    onclick="showApproveModal({{ $request->id }}, '{{ $request->user->name }}')"
                                                    class="btn btn-success btn-sm"
                                                    title="Approuver">
                                                    <i class="mdi mdi-check"></i>
                                                </button>

                                                <!-- Reject Button -->
                                                <button
                                                    onclick="showRejectModal({{ $request->id }}, '{{ $request->user->name }}')"
                                                    class="btn btn-danger btn-sm"
                                                    title="Rejeter">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-xs text-gray-500">
                                                @if($request->reviewed_at)
                                                    Traitée le {{ $request->reviewed_at->format('d/m/Y') }}
                                                @endif
                                                @if($request->admin_notes)
                                                    <div class="mt-1 text-xs text-gray-600" title="{{ $request->admin_notes }}">
                                                        <i class="mdi mdi-note-text"></i> Notes: {{ Str::limit($request->admin_notes, 30) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" onclick="if(event.target === this) closeModals()">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="mdi mdi-check-circle text-green-500"></i>
                Approuver la demande
            </h3>
            <form id="approveForm" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-gray-700 mb-2">Approuver la demande de <strong id="approveUserName"></strong> ?</p>
                    <p class="text-sm text-gray-600">L'utilisateur pourra se connecter avec son nouvel appareil et recevra un SMS de confirmation.</p>
                </div>
                <div class="form-group">
                    <label>Notes admin (optionnel)</label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Ex: Demande légitime, changement de téléphone..."></textarea>
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="closeModals()" class="btn btn-secondary flex-1">Annuler</button>
                    <button type="submit" class="btn btn-success flex-1">
                        <i class="mdi mdi-check"></i> Approuver
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50" onclick="if(event.target === this) closeModals()">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="mdi mdi-close-circle text-red-500"></i>
                Rejeter la demande
            </h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <p class="text-gray-700 mb-2">Rejeter la demande de <strong id="rejectUserName"></strong> ?</p>
                    <p class="text-sm text-gray-600">L'utilisateur recevra un SMS avec la raison du rejet.</p>
                </div>
                <div class="form-group">
                    <label>Raison du rejet <span class="text-red-500">*</span></label>
                    <textarea name="admin_notes" class="form-control" rows="3" placeholder="Ex: Documents non conformes, suspicion de fraude..." required></textarea>
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="button" onclick="closeModals()" class="btn btn-secondary flex-1">Annuler</button>
                    <button type="submit" class="btn btn-danger flex-1">
                        <i class="mdi mdi-close"></i> Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showApproveModal(requestId, userName) {
    document.getElementById('approveUserName').textContent = userName;
    document.getElementById('approveForm').action = `/admin/device-change-requests/${requestId}/approve`;
    document.getElementById('approveModal').style.display = 'flex';
}

function showRejectModal(requestId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = `/admin/device-change-requests/${requestId}/reject`;
    document.getElementById('rejectModal').style.display = 'flex';
}

function closeModals() {
    document.getElementById('approveModal').style.display = 'none';
    document.getElementById('rejectModal').style.display = 'none';
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModals();
    }
});
</script>
@endpush
@endsection
