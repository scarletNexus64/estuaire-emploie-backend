@extends('admin.layouts.app')

@section('title', 'Candidats')
@section('page-title', 'Gestion des Candidats')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Candidats</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Expérience</th>
                        <th>Candidatures</th>
                        <th>Score</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>
                                @if($user->experience_level)
                                    <span class="badge badge-info">{{ ucfirst($user->experience_level) }}</span>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $user->applications_count }}</span>
                            </td>
                            <td>{{ $user->visibility_score }}/100</td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">Voir</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">
                                Aucun candidat trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div style="padding: 1.5rem;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
