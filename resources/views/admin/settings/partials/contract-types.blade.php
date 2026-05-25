<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title">
            <i class="mdi mdi-file-document-outline"></i> Types de Contrat
        </h3>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-contract-type-form').style.display='block'">
            <i class="mdi mdi-plus"></i> Ajouter
        </button>
    </div>

    <!-- Add Form -->
    <div id="add-contract-type-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border); background: #f9fafb;">
        <form action="{{ route('admin.settings.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="contract_type">
            <div class="form-group">
                <label class="form-label">Nom du type de contrat</label>
                <input type="text" name="name" class="form-control" placeholder="Ex: CDI, CDD, Stage..." required>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-contract-type-form').style.display='none'">
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
                @forelse($contractTypes as $contractType)
                    <tr id="contract_type-row-{{ $contractType->id }}">
                        <td><strong>{{ $contractType->name }}</strong></td>
                        <td><code>{{ $contractType->slug }}</code></td>
                        <td>
                            <span class="badge badge-info">{{ $contractType->jobs_count }}</span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" onclick="showEditForm('contract_type', {{ $contractType->id }})">
                                <i class="mdi mdi-pencil"></i> Modifier
                            </button>
                            <form action="{{ route('admin.settings.categories.delete', $contractType) }}?type=contract_type" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                    <i class="mdi mdi-delete"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Form Row -->
                    <tr id="contract_type-edit-{{ $contractType->id }}" style="display: none;">
                        <td colspan="4" style="padding: 1.5rem; background-color: #f9fafb;">
                            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="contract_type">
                                <input type="hidden" name="id" value="{{ $contractType->id }}">
                                <div class="form-group mb-4">
                                    <label class="form-label">Nom du type de contrat</label>
                                    <input type="text" name="name" class="form-control" value="{{ $contractType->name }}" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="mdi mdi-check"></i> Enregistrer
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="hideEditForm('contract_type', {{ $contractType->id }})">
                                    <i class="mdi mdi-close"></i> Annuler
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Aucun type de contrat configuré</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
