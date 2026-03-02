@extends('admin.layouts.app')

@section('title', 'Entreprises')
@section('page-title', 'Gestion des Entreprises')

@section('breadcrumbs')
    <span> / </span>
    <span>Entreprises</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Entreprise
    </a>
@endsection

@section('content')
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.companies.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $companies->total() }}</div>
            </div>
            <div class="stat-icon">🏢</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En attente</div>
                <div class="stat-value">{{ $companies->where('status', 'pending')->count() }}</div>
            </div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vérifiées</div>
                <div class="stat-value">{{ $companies->where('status', 'verified')->count() }}</div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Suspendues</div>
                <div class="stat-value">{{ $companies->where('status', 'suspended')->count() }}</div>
            </div>
            <div class="stat-icon">⛔</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.companies.index') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, email..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Vérifiées</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspendues</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Plan</label>
                <select name="plan" class="form-control">
                    <option value="">Tous</option>
                    <option value="free" {{ request('plan') === 'free' ? 'selected' : '' }}>Gratuit</option>
                    <option value="premium" {{ request('plan') === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'status', 'plan']))
                <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
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
        Supprimer les sélectionnées
    </button>
</div>

<!-- Companies Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                    </th>
                    <th>Entreprise</th>
                    <th>Secteur</th>
                    <th>Localisation</th>
                    <th>Statut</th>
                    <th>Plan</th>
                    <th>Offres</th>
                    <th>Recruteurs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $company->id }}">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($company->logo)
                                <img src="{{ $company->logo_url }}"
                                     alt="Logo {{ $company->name }}"
                                     style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 2px solid #e5e7eb;"
                                     onerror="this.onerror=null; this.outerHTML='<div style=\'width: 48px; height: 48px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;\'>{{ strtoupper(substr($company->name, 0, 1)) }}</div>';">
                            @else
                                <div style="width: 48px; height: 48px; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.25rem;">
                                    {{ strtoupper(substr($company->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <strong style="display: block;">{{ $company->name }}</strong>
                                <small style="color: var(--secondary); display: block;">{{ $company->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $company->sector }}</td>
                    <td>
                        @if($company->city || $company->country)
                            {{ $company->city }}{{ $company->city && $company->country ? ', ' : '' }}{{ $company->country }}
                        @else
                            <span style="color: var(--secondary);">Non spécifié</span>
                        @endif
                    </td>
                    <td>
                        @if($company->status === 'verified')
                            <span class="badge badge-success">Vérifiée</span>
                        @elseif($company->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($company->status === 'suspended')
                            <span class="badge badge-danger">Suspendue</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($company->status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($company->subscription_plan === 'premium')
                            <span class="badge badge-primary">Premium</span>
                        @else
                            <span class="badge badge-secondary">Gratuit</span>
                        @endif
                    </td>
                    <td><strong>{{ $company->jobs_count }}</strong></td>
                    <td><strong>{{ $company->recruiters_count }}</strong></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-secondary" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            @if($company->status === 'pending')
                            <form method="POST" action="{{ route('admin.companies.verify', $company) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Vérifier" onclick="return confirm('Vérifier cette entreprise ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            @if($company->status !== 'suspended')
                            <form method="POST" action="{{ route('admin.companies.suspend', $company) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning" title="Suspendre" onclick="return confirm('Suspendre cette entreprise ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')">
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
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🏢</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucune entreprise trouvée</p>
                        <p style="margin-bottom: 1.5rem;">Commencez par créer une nouvelle entreprise</p>
                        <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle Entreprise
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($companies->hasPages())
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        {{ $companies->links('vendor.pagination.custom') }}
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
            alert('Veuillez sélectionner au moins une entreprise');
            return;
        }

        if (!confirm(`Supprimer ${selected.length} entreprise(s) sélectionnée(s) ?\n\nCette action est irréversible.`)) {
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
