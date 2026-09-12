@extends('admin.layouts.app')

@section('title', $isEdit ? 'Modifier la Publicité' : 'Créer une Publicité')
@section('page-title', $isEdit ? 'Modifier la Publicité' : 'Créer une Publicité')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.advertisements.index') }}">Publicités</a> / {{ $isEdit ? 'Modifier' : 'Créer' }}</span>
@endsection

@section('content')
<form action="{{ $isEdit ? route('admin.advertisements.update', $ad->id) : route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la Publicité</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Titre</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $ad->title ?? '') }}" required maxlength="255"
                               placeholder="Ex: Trouvez votre emploi de rêve">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" maxlength="500" placeholder="Ex: Des milliers d'offres vous attendent">{{ old('description', $ad->description ?? '') }}</textarea>
                        <small class="form-text">Maximum 500 caractères</small>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Image de la Bannière</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" id="imageInput">
                        <small class="form-text">Format: JPG, PNG, GIF (Max: 2MB). Si aucune image n'est fournie, la couleur de fond sera utilisée.</small>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        @if($isEdit && $ad->image)
                            <div style="margin-top: 1rem;">
                                <img src="{{ asset('storage/' . $ad->image) }}" alt="Image actuelle" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">Image actuelle</p>
                            </div>
                        @endif

                        <div id="imagePreview" style="margin-top: 1rem; display: none;">
                            <img id="previewImg" src="" alt="Aperçu" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">Aperçu de la nouvelle image</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Couleur de Fond</label>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <input type="color" name="background_color" id="colorPicker" class="form-control @error('background_color') is-invalid @enderror"
                                   value="{{ old('background_color', $ad->background_color ?? '#0277BD') }}" required
                                   style="width: 80px; height: 45px; padding: 5px; cursor: pointer;">
                            <input type="text" id="colorText" class="form-control"
                                   value="{{ old('background_color', $ad->background_color ?? '#0277BD') }}"
                                   style="width: 120px;" readonly>
                            <small class="form-text">Utilisée si aucune image n'est uploadée ou comme fond de l'image</small>
                        </div>
                        @error('background_color') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button type="button" class="color-preset" data-color="#0277BD" style="background: #0277BD;"></button>
                            <button type="button" class="color-preset" data-color="#FF6F00" style="background: #FF6F00;"></button>
                            <button type="button" class="color-preset" data-color="#00695C" style="background: #00695C;"></button>
                            <button type="button" class="color-preset" data-color="#6A1B9A" style="background: #6A1B9A;"></button>
                            <button type="button" class="color-preset" data-color="#D84315" style="background: #D84315;"></button>
                            <button type="button" class="color-preset" data-color="#1565C0" style="background: #1565C0;"></button>
                            <button type="button" class="color-preset" data-color="#C62828" style="background: #C62828;"></button>
                            <button type="button" class="color-preset" data-color="#2E7D32" style="background: #2E7D32;"></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Période d'Affichage</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Date de Début</label>
                            <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{ old('start_date', $ad && $ad->start_date ? $ad->start_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Date de Fin</label>
                            <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{ old('end_date', $ad && $ad->end_date ? $ad->end_date->format('Y-m-d') : date('Y-m-d', strtotime('+30 days'))) }}" required>
                            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Type de Publicité</label>
                        <select name="ad_type" class="form-control @error('ad_type') is-invalid @enderror" required>
                            <option value="homepage_banner" {{ old('ad_type', $ad->ad_type ?? 'homepage_banner') == 'homepage_banner' ? 'selected' : '' }}>Bannière Page d'Accueil</option>
                            <option value="search_banner" {{ old('ad_type', $ad->ad_type ?? '') == 'search_banner' ? 'selected' : '' }}>Bannière Résultats de Recherche</option>
                            <option value="featured_company" {{ old('ad_type', $ad->ad_type ?? '') == 'featured_company' ? 'selected' : '' }}>Entreprise en Vedette</option>
                            <option value="sidebar" {{ old('ad_type', $ad->ad_type ?? '') == 'sidebar' ? 'selected' : '' }}>Bannière Latérale</option>
                            <option value="custom" {{ old('ad_type', $ad->ad_type ?? '') == 'custom' ? 'selected' : '' }}>Personnalisée</option>
                        </select>
                        @error('ad_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Action au clic</label>
                        <select name="redirect_type" id="redirect_type" class="form-control @error('redirect_type') is-invalid @enderror">
                            @php $currentRedirectType = old('redirect_type', $ad->redirect_type ?? 'none'); @endphp
                            <option value="none" {{ $currentRedirectType == 'none' ? 'selected' : '' }}>Aucune (bannière informative)</option>
                            <option value="internal_route" {{ $currentRedirectType == 'internal_route' ? 'selected' : '' }}>Écran de l'application</option>
                            <option value="external_url" {{ $currentRedirectType == 'external_url' ? 'selected' : '' }}>Lien externe (http/https)</option>
                            <option value="deeplink" {{ $currentRedirectType == 'deeplink' ? 'selected' : '' }}>Deeplink ({{ config('app.app_scheme') }}://…)</option>
                            <option value="whatsapp" {{ $currentRedirectType == 'whatsapp' ? 'selected' : '' }}>Contact WhatsApp</option>
                        </select>
                        @error('redirect_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group" id="redirect_target_group">
                        <label class="form-label">Destination</label>
                        <input type="text" name="redirect_target" id="redirect_target"
                               class="form-control @error('redirect_target') is-invalid @enderror"
                               value="{{ old('redirect_target', $ad->redirect_target ?? '') }}"
                               maxlength="2048">
                        <small class="form-text" id="redirect_target_help">
                            Écran : <code>/search</code>, <code>/companies-directory</code>,
                            <code>/student-space/training-packs</code>, <code>/services-list</code>, <code>/cv-library</code>.
                            Lien externe : URL complète. WhatsApp : numéro au format international, ex. <code>237696118389</code>.
                        </small>
                        @error('redirect_target') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Ordre d'Affichage</label>
                        <input type="number" name="display_order" class="form-control @error('display_order') is-invalid @enderror"
                               value="{{ old('display_order', $ad->display_order ?? 0) }}" required min="0">
                        <small class="form-text">Plus le nombre est bas, plus la publicité est prioritaire</small>
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ad->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active"><strong>Publicité Active</strong></label>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        {{ $isEdit ? 'Mettre à Jour' : 'Créer' }}
                    </button>
                    <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem; background: #f8fafc;">
                <div class="card-header">
                    <h4 style="margin: 0; font-size: 0.875rem; color: #64748b;">💡 Conseils</h4>
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div style="font-size: 0.875rem; color: #64748b; line-height: 1.6;">
                        <div style="margin-bottom: 0.5rem;">• Utilisez des titres courts et percutants</div>
                        <div style="margin-bottom: 0.5rem;">• L'image optimale est de 800x400px</div>
                        <div>• Choisissez des couleurs qui attirent l'attention</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.color-preset {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
}

.color-preset:hover {
    transform: scale(1.1);
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('colorPicker');
    const colorText = document.getElementById('colorText');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // Sync color picker and text input
    colorPicker.addEventListener('input', function() {
        colorText.value = this.value.toUpperCase();
    });

    // Color presets
    document.querySelectorAll('.color-preset').forEach(button => {
        button.addEventListener('click', function() {
            const color = this.dataset.color;
            colorPicker.value = color;
            colorText.value = color;
        });
    });

    // Image preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Le champ Destination n'a de sens que si une action au clic est choisie.
    const redirectType = document.getElementById('redirect_type');
    const redirectTargetGroup = document.getElementById('redirect_target_group');
    const redirectTarget = document.getElementById('redirect_target');

    const placeholders = {
        internal_route: '/search',
        external_url: 'https://exemple.com',
        deeplink: '{{ config('app.app_scheme') }}://job/12',
        whatsapp: '237696118389',
    };

    function syncRedirectTarget() {
        const type = redirectType.value;
        const isNone = type === 'none';
        redirectTargetGroup.style.display = isNone ? 'none' : 'block';
        redirectTarget.placeholder = placeholders[type] || '';
        if (isNone) {
            redirectTarget.value = '';
        }
    }

    redirectType.addEventListener('change', syncRedirectTarget);
    syncRedirectTarget();
});
</script>
@endsection
