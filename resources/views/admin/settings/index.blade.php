@extends('admin.layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres du Système')

@section('content')
    <!-- Categories -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Catégories de Métiers</h3>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-category-form').style.display='block'">
                Ajouter
            </button>
        </div>

        <div id="add-category-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="category">
                <div class="form-group">
                    <label class="form-label">Nom de la catégorie</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-category-form').style.display='none'">
                    Annuler
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Nombre d'offres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr id="category-row-{{ $category->id }}">
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                <span class="badge badge-info">{{ $category->jobs_count }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="showEditForm('category', {{ $category->id }})">
                                    <i class="mdi mdi-pencil"></i> Modifier
                                </button>
                                <form action="{{ route('admin.settings.categories.delete', $category) }}?type=category" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="mdi mdi-delete"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="category-edit-{{ $category->id }}" style="display: none;">
                            <td colspan="4" style="padding: 1.5rem; background-color: #f9fafb;">
                                <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="category">
                                    <input type="hidden" name="id" value="{{ $category->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="form-group">
                                            <label class="form-label">Nom de la catégorie</label>
                                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm('category', {{ $category->id }})">
                                        <i class="mdi mdi-close"></i> Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Locations -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Localisations</h3>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-location-form').style.display='block'">
                Ajouter
            </button>
        </div>

        <div id="add-location-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="location">
                <div class="form-group">
                    <label class="form-label">Nom de la ville</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" class="form-control" value="Cameroun">
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-location-form').style.display='none'">
                    Annuler
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th>Pays</th>
                        <th>Nombre d'offres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr id="location-row-{{ $location->id }}">
                            <td><strong>{{ $location->name }}</strong></td>
                            <td>{{ $location->country }}</td>
                            <td>
                                <span class="badge badge-info">{{ $location->jobs_count }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="showEditForm('location', {{ $location->id }})">
                                    <i class="mdi mdi-pencil"></i> Modifier
                                </button>
                                <form action="{{ route('admin.settings.categories.delete', $location) }}?type=location" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="mdi mdi-delete"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="location-edit-{{ $location->id }}" style="display: none;">
                            <td colspan="4" style="padding: 1.5rem; background-color: #f9fafb;">
                                <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="location">
                                    <input type="hidden" name="id" value="{{ $location->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="form-group">
                                            <label class="form-label">Nom de la ville</label>
                                            <input type="text" name="name" class="form-control" value="{{ $location->name }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Pays</label>
                                            <input type="text" name="country" class="form-control" value="{{ $location->country }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm('location', {{ $location->id }})">
                                        <i class="mdi mdi-close"></i> Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Contract Types -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Types de Contrat</h3>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-contract-form').style.display='block'">
                Ajouter
            </button>
        </div>

        <div id="add-contract-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="contract_type">
                <div class="form-group">
                    <label class="form-label">Nom du type de contrat</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: CDI, CDD, Stage, Freelance">
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-contract-form').style.display='none'">
                    Annuler
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Slug</th>
                        <th>Nombre d'offres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contractTypes as $type)
                        <tr id="contract_type-row-{{ $type->id }}">
                            <td><strong>{{ $type->name }}</strong></td>
                            <td>{{ $type->slug }}</td>
                            <td>
                                <span class="badge badge-info">{{ $type->jobs_count }}</span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="showEditForm('contract_type', {{ $type->id }})">
                                    <i class="mdi mdi-pencil"></i> Modifier
                                </button>
                                <form action="{{ route('admin.settings.categories.delete', $type) }}?type=contract_type" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                        <i class="mdi mdi-delete"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="contract_type-edit-{{ $type->id }}" style="display: none;">
                            <td colspan="4" style="padding: 1.5rem; background-color: #f9fafb;">
                                <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="type" value="contract_type">
                                    <input type="hidden" name="id" value="{{ $type->id }}">
                                    <div class="form-group mb-4">
                                        <label class="form-label">Nom du type de contrat</label>
                                        <input type="text" name="name" class="form-control" value="{{ $type->name }}" required placeholder="Ex: CDI, CDD, Stage, Freelance">
                                    </div>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm('contract_type', {{ $type->id }})">
                                        <i class="mdi mdi-close"></i> Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function showEditForm(type, id) {
        // Hide all other edit forms first
        document.querySelectorAll('tr[id$="-edit-' + id + '"]').forEach(function(row) {
            if (row.id !== type + '-edit-' + id) {
                row.style.display = 'none';
            }
        });

        // Show the selected edit form
        const editRow = document.getElementById(type + '-edit-' + id);
        if (editRow) {
            editRow.style.display = 'table-row';
        }
    }

    function hideEditForm(type, id) {
        const editRow = document.getElementById(type + '-edit-' + id);
        if (editRow) {
            editRow.style.display = 'none';
        }
    }
</script>
@endpush
