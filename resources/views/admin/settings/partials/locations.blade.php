<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="mdi mdi-map-marker"></i> Localisations
        </h3>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-location-form').style.display='block'">
            <i class="mdi mdi-plus"></i> Ajouter
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-location-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border); background: #f9fafb;">
        <form action="{{ route('admin.settings.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="location">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label class="form-label">Nom de la localisation</label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: Douala" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" class="form-control" value="Cameroun">
                </div>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-location-form').style.display='none'">
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
                    <th>Pays</th>
                    <th>Nombre d'offres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($locations as $location)
                    <tr id="location-row-{{ $location->id }}">
                        <td><strong>{{ $location->name }}</strong></td>
                        <td><code>{{ $location->slug }}</code></td>
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

                    <!-- Edit Form Row -->
                    <tr id="location-edit-{{ $location->id }}" style="display: none;">
                        <td colspan="5" style="padding: 1.5rem; background-color: #f9fafb;">
                            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="location">
                                <input type="hidden" name="id" value="{{ $location->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div class="form-group">
                                        <label class="form-label">Nom de la localisation</label>
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
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune localisation configurée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
