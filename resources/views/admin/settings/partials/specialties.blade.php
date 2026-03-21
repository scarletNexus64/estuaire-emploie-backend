<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="mdi mdi-school-outline"></i> Spécialités Académiques
        </h3>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-specialty-form').style.display='block'">
            <i class="mdi mdi-plus"></i> Ajouter
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-specialty-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border); background: #f9fafb;">
        <form action="{{ route('admin.settings.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="specialty">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label class="form-label required">Nom de la spécialité</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: Informatique" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Couleur (Hex)</label>
                    <input type="color" name="color" class="form-control" value="#0277BD">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Description de la spécialité"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="display_order" class="form-control" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Statut</label>
                        <select name="is_active" class="form-control">
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="mdi mdi-check"></i> Ajouter
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-specialty-form').style.display='none'">
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
                    <th>Couleur</th>
                    <th>Ordre</th>
                    <th>Épreuves</th>
                    <th>Packs</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($specialties as $specialty)
                    <tr id="specialty-row-{{ $specialty->id }}">
                        <td><strong>{{ $specialty->name }}</strong></td>
                        <td><code>{{ $specialty->slug }}</code></td>
                        <td>
                            @if($specialty->color)
                                <div style="display: inline-block; width: 30px; height: 30px; background-color: {{ $specialty->color }}; border-radius: 4px; border: 1px solid #ccc;"></div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $specialty->display_order }}</td>
                        <td><span class="badge badge-info">{{ $specialty->exam_papers_count }}</span></td>
                        <td><span class="badge badge-info">{{ $specialty->exam_packs_count }}</span></td>
                        <td>
                            @if($specialty->is_active)
                                <span class="badge badge-success">Actif</span>
                            @else
                                <span class="badge badge-secondary">Inactif</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="showEditForm('specialty', {{ $specialty->id }})">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.settings.categories.delete', $specialty->id) }}?type=specialty" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette spécialité ?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Form Row -->
                    <tr id="specialty-edit-{{ $specialty->id }}" style="display: none;">
                        <td colspan="8" style="padding: 1.5rem; background-color: #f9fafb;">
                            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="specialty">
                                <input type="hidden" name="id" value="{{ $specialty->id }}">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label required">Nom</label>
                                        <input type="text" name="name" class="form-control" value="{{ $specialty->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Couleur</label>
                                        <input type="color" name="color" class="form-control" value="{{ $specialty->color ?? '#0277BD' }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="2">{{ $specialty->description }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="form-group">
                                            <label class="form-label">Ordre</label>
                                            <input type="number" name="display_order" class="form-control" value="{{ $specialty->display_order }}" min="0">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Statut</label>
                                            <select name="is_active" class="form-control">
                                                <option value="1" {{ $specialty->is_active ? 'selected' : '' }}>Actif</option>
                                                <option value="0" {{ !$specialty->is_active ? 'selected' : '' }}>Inactif</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="mdi mdi-check"></i> Enregistrer
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm('specialty', {{ $specialty->id }})">
                                        <i class="mdi mdi-close"></i> Annuler
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Aucune spécialité configurée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
