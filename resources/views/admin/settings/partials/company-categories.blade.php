<div class="mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding: 1.25rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; color: white;">
        <div>
            <h4 style="margin: 0; font-size: 1.25rem; font-weight: 600;">Catégories d'Entreprises</h4>
            <p style="margin: 0.25rem 0 0 0; opacity: 0.9; font-size: 0.875rem;">Hiérarchie des secteurs d'activité ({{ $companyCategories->total() }} entrées)</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <button
                onclick="showAddCompanyCategoryForm()"
                style="padding: 0.625rem 1.25rem; background: white; color: #667eea; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"
                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'"
            >
                <i class="mdi mdi-plus"></i> Ajouter
            </button>
        </div>
    </div>

    <!-- Add Form (Hidden by default) -->
    <div id="add-company-category-form" style="display: none; margin-bottom: 1.5rem; padding: 1.5rem; background: #f8fafc; border-radius: 8px; border: 2px solid #667eea;">
        <h5 style="margin: 0 0 1rem 0; color: #1e293b;">Ajouter une nouvelle catégorie</h5>
        <form action="{{ route('admin.settings.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="company_category">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">Code *</label>
                    <input type="text" name="code" required
                        style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"
                        placeholder="Ex: 1.1.1">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">Niveau 1 (Secteur) *</label>
                    <input type="text" name="level_1" required
                        style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"
                        placeholder="Ex: Administration & Services Publics">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">Niveau 2 (Sous-catégorie)</label>
                    <input type="text" name="level_2"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"
                        placeholder="Ex: Ambassades et consulats">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">Niveau 3 (Sous-sous-catégorie)</label>
                    <input type="text" name="level_3"
                        style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"
                        placeholder="Ex: Ambassades">
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">Description</label>
                <textarea name="description" rows="3"
                    style="width: 100%; padding: 0.625rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"
                    placeholder="Description optionnelle..."></textarea>
            </div>

            <div style="display: flex; gap: 0.75rem;">
                <button type="submit"
                    style="padding: 0.625rem 1.25rem; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                    <i class="mdi mdi-check"></i> Enregistrer
                </button>
                <button type="button" onclick="hideAddCompanyCategoryForm()"
                    style="padding: 0.625rem 1.25rem; background: #6c757d; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">
                    <i class="mdi mdi-close"></i> Annuler
                </button>
            </div>
        </form>
    </div>

    <!-- Search and Filter -->
    <div style="margin-bottom: 1rem; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
        <form action="{{ route('admin.settings.index') }}" method="GET">
            <input type="hidden" name="tab" value="companies">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Rechercher</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;"
                        placeholder="Code, secteur, catégorie...">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 1</label>
                    <select name="level_1"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                        <option value="">Tous</option>
                        @foreach($level1Options as $option)
                            <option value="{{ $option }}" {{ request('level_1') === $option ? 'selected' : '' }}>
                                {{ $option }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Statut</label>
                    <select name="is_active"
                        style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                        <option value="">Tous</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit"
                        style="padding: 0.5rem 1rem; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; white-space: nowrap;">
                        <i class="mdi mdi-magnify"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.settings.index', ['tab' => 'companies']) }}"
                        style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                        <i class="mdi mdi-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <tr>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem;">Code</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 1</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 2</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 3</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #475569; font-size: 0.875rem;">Statut</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #475569; font-size: 0.875rem;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companyCategories as $category)
                    <tr id="company-category-row-{{ $category->id }}" style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;"
                        onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 1rem; font-size: 0.875rem; color: #1e293b; font-weight: 500;">{{ $category->code }}</td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #475569;">{{ $category->level_1 }}</td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #64748b;">{{ $category->level_2 ?? '-' }}</td>
                        <td style="padding: 1rem; font-size: 0.875rem; color: #64748b;">{{ $category->level_3 ?? '-' }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            @if($category->is_active)
                                <span style="display: inline-block; padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    Actif
                                </span>
                            @else
                                <span style="display: inline-block; padding: 0.25rem 0.75rem; background: #fee2e2; color: #991b1b; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                    Inactif
                                </span>
                            @endif
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <button
                                    onclick="showEditCompanyCategoryForm('{{ $category->id }}')"
                                    style="padding: 0.375rem 0.75rem; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.875rem;"
                                    title="Modifier"
                                >
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <form action="{{ route('admin.settings.categories.delete', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="type" value="company_category">
                                    <button type="submit"
                                        style="padding: 0.375rem 0.75rem; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 0.875rem;"
                                        title="Supprimer"
                                    >
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <!-- Edit Form Row (Hidden) -->
                    <tr id="company-category-edit-{{ $category->id }}" style="display: none; background: #f8fafc;">
                        <td colspan="6" style="padding: 1.5rem;">
                            <form action="{{ route('admin.settings.categories.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="company_category">
                                <input type="hidden" name="id" value="{{ $category->id }}">

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                    <div>
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Code</label>
                                        <input type="text" name="code" value="{{ $category->code }}" required
                                            style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 1</label>
                                        <input type="text" name="level_1" value="{{ $category->level_1 }}" required
                                            style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                                    </div>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                    <div>
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 2</label>
                                        <input type="text" name="level_2" value="{{ $category->level_2 }}"
                                            style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                                    </div>
                                    <div>
                                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">Niveau 3</label>
                                        <input type="text" name="level_3" value="{{ $category->level_3 }}"
                                            style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.875rem;">
                                    </div>
                                </div>

                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569; font-size: 0.875rem;">
                                        <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}> Actif
                                    </label>
                                </div>

                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="submit"
                                        style="padding: 0.5rem 1rem; background: #28a745; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.875rem;">
                                        <i class="mdi mdi-check"></i> Enregistrer
                                    </button>
                                    <button type="button" onclick="hideEditCompanyCategoryForm('{{ $category->id }}')"
                                        style="padding: 0.5rem 1rem; background: #6c757d; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 0.875rem;">
                                        <i class="mdi mdi-close"></i> Annuler
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #64748b;">
                            <i class="mdi mdi-information-outline" style="font-size: 2rem; display: block; margin-bottom: 0.5rem;"></i>
                            Aucune catégorie trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($companyCategories->hasPages())
        <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
            {{ $companyCategories->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function showAddCompanyCategoryForm() {
    document.getElementById('add-company-category-form').style.display = 'block';
}

function hideAddCompanyCategoryForm() {
    document.getElementById('add-company-category-form').style.display = 'none';
}

function showEditCompanyCategoryForm(id) {
    const row = document.getElementById(`company-category-row-${id}`);
    const edit = document.getElementById(`company-category-edit-${id}`);
    if (row) row.style.display = 'none';
    if (edit) edit.style.display = 'table-row';
}

function hideEditCompanyCategoryForm(id) {
    const edit = document.getElementById(`company-category-edit-${id}`);
    const row = document.getElementById(`company-category-row-${id}`);
    if (edit) edit.style.display = 'none';
    if (row) row.style.display = 'table-row';
}
</script>
