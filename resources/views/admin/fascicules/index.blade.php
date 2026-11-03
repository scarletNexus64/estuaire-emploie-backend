@extends('admin.layouts.app')

@section('title', 'Fascicules - Mode Étudiant')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📘 Fascicules</h1>
            <p class="text-gray-600 mt-1">Supports de cours PDF réservés au Pack Étudiant</p>
        </div>
        <a href="{{ route('admin.fascicules.create') }}" class="btn btn-primary">
            <i class="mdi mdi-plus"></i>
            Ajouter un fascicule
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
            <form method="GET" action="{{ route('admin.fascicules.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Titre, matière, auteur..." class="form-control">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filière</label>
                    <select name="filiere" class="form-control">
                        <option value="">Toutes</option>
                        @foreach($filieres as $filiere)
                            <option value="{{ $filiere }}" {{ request('filiere') == $filiere ? 'selected' : '' }}>{{ $filiere }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select name="level" class="form-control">
                        <option value="">Tous</option>
                        @foreach($levels as $level)
                            <option value="{{ $level }}" {{ request('level') == $level ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="is_published" class="form-control">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_published') === '1' ? 'selected' : '' }}>Publiés</option>
                        <option value="0" {{ request('is_published') === '0' ? 'selected' : '' }}>Brouillons</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-magnify"></i> Rechercher
                    </button>
                    <a href="{{ route('admin.fascicules.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                📋 Liste des fascicules
                <span class="badge badge-primary ml-2">{{ $fascicules->total() }}</span>
            </h3>
        </div>
        <div class="card-body p-0">
            @if($fascicules->count() > 0)
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Couverture</th>
                                <th>Titre</th>
                                <th>Filière</th>
                                <th>Niveau</th>
                                <th>Matière</th>
                                <th>Fichier</th>
                                <th>Stats</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($fascicules as $fascicule)
                                <tr>
                                    <td class="font-mono text-sm">#{{ $fascicule->id }}</td>
                                    <td>
                                        @if($fascicule->cover_url)
                                            <img src="{{ $fascicule->cover_url }}" alt="" style="width:40px;height:56px;object-fit:cover;border-radius:4px;">
                                        @else
                                            <i class="mdi mdi-book-open-page-variant text-gray-400 text-2xl"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-medium text-gray-900">{{ $fascicule->title }}</div>
                                        @if($fascicule->author)
                                            <div class="text-sm text-gray-500">{{ $fascicule->author }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($fascicule->filiere)
                                            <span class="badge badge-info">{{ $fascicule->filiere }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($fascicule->level)
                                            <span class="badge badge-secondary">{{ $fascicule->level }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $fascicule->subject ?? '-' }}</td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <i class="mdi mdi-file-pdf text-red-500"></i>
                                            <span class="text-sm">{{ $fascicule->formatted_file_size }}</span>
                                        </div>
                                        @if($fascicule->pages_count)
                                            <div class="text-xs text-gray-500">{{ $fascicule->pages_count }} pages</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-xs text-gray-600">
                                            <div><i class="mdi mdi-download"></i> {{ $fascicule->downloads_count }} lectures</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($fascicule->is_published)
                                            <span class="badge badge-success">Publié</span>
                                            @if($fascicule->published_at)
                                                <div class="text-xs text-gray-500">{{ $fascicule->published_at->format('d/m/Y') }}</div>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Brouillon</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex gap-1 justify-end">
                                            <a href="{{ route('admin.fascicules.download', $fascicule) }}"
                                               class="btn btn-sm btn-info" title="Télécharger">
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <a href="{{ route('admin.fascicules.edit', $fascicule) }}"
                                               class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.fascicules.toggle', $fascicule) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn btn-sm {{ $fascicule->is_published ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $fascicule->is_published ? 'Dépublier' : 'Publier' }}">
                                                    <i class="mdi mdi-{{ $fascicule->is_published ? 'eye-off' : 'eye' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.fascicules.destroy', $fascicule) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fascicule ?')">
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

                <div class="p-4">
                    {{ $fascicules->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="mdi mdi-book-open-page-variant"></i>
                    <p>Aucun fascicule trouvé</p>
                    <a href="{{ route('admin.fascicules.create') }}" class="btn btn-primary mt-4">
                        <i class="mdi mdi-plus"></i> Ajouter le premier fascicule
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
