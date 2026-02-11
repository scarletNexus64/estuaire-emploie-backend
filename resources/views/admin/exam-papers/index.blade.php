@extends('admin.layouts.app')

@section('title', 'Épreuves d\'Examen - Mode Étudiant')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📚 Épreuves d'Examen</h1>
            <p class="text-gray-600 mt-1">Gestion des sujets et corrigés pour le Mode Étudiant</p>
        </div>
        <a href="{{ route('admin.exam-papers.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i>
            Ajouter une épreuve
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Filtres -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🔍 Filtres de recherche</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.exam-papers.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Titre, description..."
                        class="form-control"
                    >
                </div>

                <!-- Spécialité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité</label>
                    <select name="specialty" class="form-control">
                        <option value="">Toutes</option>
                        @foreach($specialties as $key => $specialty)
                            <option value="{{ $key }}" {{ request('specialty') == $key ? 'selected' : '' }}>
                                {{ $specialty }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Matière -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                    <input
                        type="text"
                        name="subject"
                        value="{{ request('subject') }}"
                        placeholder="Toutes"
                        class="form-control"
                        list="subjects-list"
                    >
                    <datalist id="subjects-list">
                        @foreach($subjects as $subject)
                            <option value="{{ $subject }}">
                        @endforeach
                    </datalist>
                </div>

                <!-- Niveau -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select name="level" class="form-control">
                        <option value="">Tous</option>
                        @foreach($levels as $levelValue => $levelLabel)
                            <option value="{{ $levelValue }}" {{ request('level') == $levelValue ? 'selected' : '' }}>
                                {{ $levelLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="is_correction" class="form-control">
                        <option value="">Tous</option>
                        <option value="0" {{ request('is_correction') === '0' ? 'selected' : '' }}>Sujets</option>
                        <option value="1" {{ request('is_correction') === '1' ? 'selected' : '' }}>Corrigés</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="md:col-span-5 flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-magnify"></i> Rechercher
                    </button>
                    {{-- <a href="{{ route('admin.exam-papers.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i> Réinitialiser
                    </a> --}}
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des épreuves -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                📋 Liste des épreuves
                <span class="badge badge-primary ml-2">{{ $examPapers->total() }}</span>
            </h3>
        </div>
        <div class="card-body p-0">
            @if($examPapers->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Spécialité</th>
                                <th>Matière</th>
                                <th>Niveau</th>
                                <th>Année</th>
                                <th>Type</th>
                                <th>Fichier</th>
                                <th>Stats</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($examPapers as $paper)
                                <tr>
                                    <td class="font-mono text-sm">#{{ $paper->id }}</td>
                                    <td>
                                        <div class="font-medium text-gray-900">{{ $paper->title }}</div>
                                        @if($paper->description)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ Str::limit($paper->description, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $paper->specialty }}</span>
                                    </td>
                                    <td>{{ $paper->subject }}</td>
                                    <td>
                                        <span class="badge badge-secondary">Niv. {{ $paper->level }}</span>
                                    </td>
                                    <td>{{ $paper->year ?? '-' }}</td>
                                    <td>
                                        @if($paper->is_correction)
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-check"></i> Corrigé
                                            </span>
                                        @else
                                            <span class="badge badge-primary">
                                                <i class="mdi mdi-file-document"></i> Sujet
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <i class="mdi mdi-file-pdf text-red-500"></i>
                                            <span class="text-sm">{{ $paper->formatted_file_size }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs text-gray-600">
                                            <div><i class="mdi mdi-eye"></i> {{ $paper->views_count }} vues</div>
                                            <div><i class="mdi mdi-download"></i> {{ $paper->downloads_count }} DL</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($paper->is_active)
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-danger">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex gap-1 justify-end">
                                            <a href="{{ route('admin.exam-papers.download', $paper) }}"
                                               class="btn btn-sm btn-info"
                                               title="Télécharger">
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <a href="{{ route('admin.exam-papers.edit', $paper) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Modifier">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.exam-papers.toggle', $paper) }}"
                                                  method="POST"
                                                  class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm {{ $paper->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $paper->is_active ? 'Désactiver' : 'Activer' }}">
                                                    <i class="mdi mdi-{{ $paper->is_active ? 'eye-off' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.exam-papers.destroy', $paper) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette épreuve ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4">
                    {{ $examPapers->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="mdi mdi-file-document-outline"></i>
                    <p>Aucune épreuve trouvée</p>
                    <a href="{{ route('admin.exam-papers.create') }}" class="btn btn-primary mt-4">
                        <i class="mdi mdi-plus"></i> Ajouter la première épreuve
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
