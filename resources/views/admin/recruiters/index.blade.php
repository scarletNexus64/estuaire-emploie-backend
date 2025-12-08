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
            <a href="{{ route('admin.recruiters.create') }}" class="btn btn-primary">Ajouter un Recruteur</a>
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
            <div style="padding: 1.5rem;">
                {{ $recruiters->links() }}
            </div>
        @endif
    </div>
@endsection
