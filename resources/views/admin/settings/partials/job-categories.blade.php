<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="mdi mdi-briefcase"></i> Catégories de Métiers
        </h3>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-category-form').style.display='block'">
            <i class="mdi mdi-plus"></i> Ajouter
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-category-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border); background: #f9fafb;">
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

    <!-- Table -->
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
                @forelse($categories as $category)
                    <tr id="category-row-{{ $category->id }}">
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
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

                    <!-- Edit Form Row -->
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
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucune catégorie configurée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
