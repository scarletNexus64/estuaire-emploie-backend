<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="mdi mdi-hammer-wrench"></i> Catégories de Services Rapides
        </h3>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-service-category-form').style.display='block'">
            <i class="mdi mdi-plus"></i> Ajouter
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-service-category-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border); background: #f9fafb;">
        <form action="{{ route('admin.settings.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="service_category">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label class="form-label required">Nom de la catégorie</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: Plomberie" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Icône (MDI)</label>
                    <input type="text" name="icon" class="form-control" placeholder="Ex: mdi-wrench">
                    <small class="text-muted">Voir: <a href="https://materialdesignicons.com/" target="_blank">materialdesignicons.com</a></small>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Description de la catégorie"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Couleur (Hex)</label>
                        <input type="color" name="color" class="form-control" value="#FF6B35">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="display_order" class="form-control" value="0" min="0">
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="form-label">Statut</label>
                <select name="is_active" class="form-control">
                    <option value="1">Actif</option>
                    <option value="0">Inactif</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="mdi mdi-check"></i> Ajouter
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-service-category-form').style.display='none'">
                    <i class="mdi mdi-close"></i> Annuler
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Icône</th>
                    <th>Couleur</th>
                    <th>Ordre</th>
                    <th>Services</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($serviceCategories as $category)
                    <tr id="service-category-row-{{ $category->id }}">
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            @if($category->icon)
                                <i class="mdi {{ $category->icon }}" style="font-size: 24px; color: {{ $category->color ?? '#333' }};"></i>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($category->color)
                                <div style="display: inline-block; width: 30px; height: 30px; background-color: {{ $category->color }}; border-radius: 4px; border: 1px solid #ccc;"></div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $category->display_order }}</td>
                        <td><span class="badge badge-info">{{ $category->quick_services_count }}</span></td>
                        <td>
                            @if($category->is_active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="showEditForm('service-category', {{ $category->id }})">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.settings.categories.delete', $category->id) }}?type=service_category" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette catégorie ?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Form Row -->
                    <tr id="service-category-edit-{{ $category->id }}" style="display: none;">
                        <td colspan="8" style="padding: 1.5rem; background-color: #f9fafb;">
                            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="service_category">
                                <input type="hidden" name="id" value="{{ $category->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label required">Nom</label>
                                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Icône (MDI)</label>
                                        <input type="text" name="icon" class="form-control" value="{{ $category->icon }}" placeholder="Ex: mdi-wrench">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $category->description }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-group">
                                            <label class="form-label">Couleur</label>
                                            <input type="color" name="color" class="form-control" value="{{ $category->color ?? '#FF6B35' }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Ordre</label>
                                            <input type="number" name="display_order" class="form-control" value="{{ $category->display_order }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Statut</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $category->is_active ? 'selected' : '' }}>Actif</option>
                                        <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm('service-category', {{ $category->id }})">
                                        <i class="mdi mdi-close"></i> Annuler
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Aucune catégorie de service configurée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
