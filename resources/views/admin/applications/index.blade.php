@extends('admin.layouts.app')

@section('title', 'Candidatures')
@section('page-title', 'Gestion des Candidatures')

@section('content')
    <!-- Bulk Delete Form -->
    <form id="bulkDeleteForm" action="{{ route('admin.applications.bulk-delete') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Candidatures</h3>
            <div>
                <select class="form-control" onchange="window.location.href='?status=' + this.value" style="width: auto; display: inline-block;">
                    <option value="">Tous les statuts</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>✅ Acceptées</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Rejetées</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                        </th>
                        <th>Candidat</th>
                        <th>Email</th>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>CV</th>
                        <th>Statut</th>
                        <th>Date de soumission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $application->id }}">
                            </td>
                            <td>
                                <strong>{{ $application->user?->name ?? 'N/A' }}</strong>
                            </td>
                            <td>{{ $application->user?->email ?? 'N/A' }}</td>
                            <td>{{ $application->job?->title ?? 'N/A' }}</td>
                            <td>{{ $application->job?->company?->name ?? 'N/A' }}</td>
                            <td>
                                @if($application->cv_path)
                                    <a href="{{ asset('storage/' . $application->cv_path) }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-file-download"></i> Voir CV
                                    </a>
                                @else
                                    <span style="color: #dc3545;">Aucun</span>
                                @endif
                            </td>
                            <td>
                                @if($application->status === 'accepted')
                                    <span class="badge badge-success">✅ Acceptée</span>
                                @elseif($application->status === 'rejected')
                                    <span class="badge badge-danger">❌ Rejetée</span>
                                @else
                                    <span class="badge badge-warning">⏳ En cours</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary btn-sm">Voir Détails</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem;">
                                Aucune candidature trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
                {{ $applications->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
@endsection
