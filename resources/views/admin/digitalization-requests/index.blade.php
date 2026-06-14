@extends('admin.layouts.app')

@section('title', 'Demandes de Digitalisation')
@section('page-title', 'Demandes de Digitalisation')

@section('breadcrumbs')
    <span> / </span>
    <span>Digitalisation</span>
@endsection

@section('content')
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.digitalization-requests.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Demandes</div>
                <div class="stat-value">{{ $requests->total() }}</div>
            </div>
            <div class="stat-icon">💻</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En attente</div>
                <div class="stat-value">{{ \App\Models\DigitalizationRequest::where('status', 'pending')->count() }}</div>
            </div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Traitées</div>
                <div class="stat-value">{{ \App\Models\DigitalizationRequest::where('status', 'processed')->count() }}</div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Rejetées</div>
                <div class="stat-value">{{ \App\Models\DigitalizationRequest::where('status', 'rejected')->count() }}</div>
            </div>
            <div class="stat-icon">✕</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="padding: 1.5rem;">
        <form method="GET" action="{{ route('admin.digitalization-requests.index') }}">
            <div style="display: grid; grid-template-columns: 2fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label class="form-label">Rechercher</label>
                    <input type="text" name="search" class="form-control" placeholder="Projet, entreprise, contact, ville..." value="{{ request('search') }}">
                </div>
                <div>
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-control">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processed" {{ request('status') === 'processed' ? 'selected' : '' }}>Traitées</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejetées</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">Filtrer</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.digitalization-requests.index') }}" class="btn btn-secondary">Réinitialiser</a>
                    @endif
                </div>
            </div>
        </form>
    </div>
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

<!-- Requests Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                    </th>
                    <th>Projet</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                    <th>Contact</th>
                    <th>Statut</th>
                    <th>Reçue le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $req->id }}">
                    </td>
                    <td><strong>{{ $req->project_name }}</strong></td>
                    <td>{{ $req->company_name ?? ($req->user->name ?? '—') }}</td>
                    <td>
                        @if($req->city || $req->district)
                            {{ trim(($req->city ?? '') . ' ' . ($req->district ? '· ' . $req->district : '')) }}
                        @else
                            <span style="color: var(--secondary);">—</span>
                        @endif
                    </td>
                    <td>{{ $req->contact }}</td>
                    <td>
                        @if($req->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($req->status === 'processed')
                            <span class="badge badge-success">Traitée</span>
                        @else
                            <span class="badge badge-danger">Rejetée</span>
                        @endif
                    </td>
                    <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.digitalization-requests.show', $req) }}" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.digitalization-requests.destroy', $req) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Supprimer cette demande ?')">
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
                    <td colspan="8" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">💻</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucune demande de digitalisation</p>
                        <p>Les demandes envoyées depuis l'application apparaîtront ici.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($requests->hasPages())
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        {{ $requests->links('vendor.pagination.custom') }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('selectAll')?.addEventListener('change', function() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = this.checked);
    });

    document.getElementById('bulkDeleteBtn')?.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

        if (selected.length === 0) {
            alert('Veuillez sélectionner au moins une demande');
            return;
        }

        if (!confirm(`Supprimer ${selected.length} demande(s) sélectionnée(s) ?\n\nCette action est irréversible.`)) {
            return;
        }

        const form = document.getElementById('bulkDeleteForm');
        const idsInput = document.createElement('input');
        idsInput.type = 'hidden';
        idsInput.name = 'ids';
        idsInput.value = JSON.stringify(selected);
        form.appendChild(idsInput);
        form.submit();
    });
</script>
@endsection
