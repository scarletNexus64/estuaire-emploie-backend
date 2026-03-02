@extends('admin.layouts.app')

@section('title', 'Recruteurs')
@section('page-title', 'Gestion des Recruteurs')

@section('content')
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.recruiters.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Recruteurs</h3>
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer
                </button>
                <a href="{{ route('admin.recruiters.create') }}" class="btn btn-primary">Ajouter un Recruteur</a>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                        </th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Entreprise</th>
                        <th>Poste</th>
                        <th>Permissions</th>
                        <th>Date d'ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recruiters as $recruiter)
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $recruiter->id }}">
                            </td>
                            <td><strong>{{ $recruiter->user?->name ?? 'N/A' }}</strong></td>
                            <td>{{ $recruiter->user?->email ?? 'N/A' }}</td>
                            <td>{{ $recruiter->company?->name ?? 'N/A' }}</td>
                            <td>{{ $recruiter->position ?? 'N/A' }}</td>
                            <td>
                                @if($recruiter->can_publish)
                                    <span class="badge badge-success">Publier</span>
                                @endif
                                @if($recruiter->can_view_applications)
                                    <span class="badge badge-info">Voir candidatures</span>
                                @endif
                                @if($recruiter->can_modify_company)
                                    <span class="badge badge-warning">Modifier entreprise</span>
                                @endif
                            </td>
                            <td>{{ $recruiter->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.recruiters.edit', $recruiter) }}" class="btn btn-primary btn-sm">Éditer</a>
                                <form action="{{ route('admin.recruiters.destroy', $recruiter) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">
                                Aucun recruteur trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recruiters->hasPages())
            <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
                {{ $recruiters->links('vendor.pagination.custom') }}
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
            alert('Veuillez sélectionner au moins un recruteur');
            return;
        }

        if (!confirm(`Supprimer ${selected.length} recruteur(s) sélectionné(s) ?\n\nCette action est irréversible.`)) {
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
