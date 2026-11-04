@extends('admin.layouts.app')

@section('title', $isEdit ? 'Modifier la pub partenaire' : 'Nouvelle pub partenaire')
@section('page-title', $isEdit ? 'Modifier la pub partenaire' : 'Nouvelle pub partenaire')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.feed-partner-ads.index') }}">Pubs partenaires</a> / {{ $isEdit ? 'Modifier' : 'Créer' }}</span>
@endsection

@section('content')
@php $accent = old('accent_color', $ad->accent_color ?? '#0A66C2'); @endphp
<form action="{{ $isEdit ? route('admin.feed-partner-ads.update', $ad) : route('admin.feed-partner-ads.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Partenaire &amp; textes</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Partenaire</label>
                            <select name="partner" class="form-control @error('partner') is-invalid @enderror" required>
                                @foreach(\App\Models\FeedPartnerAd::PARTNERS as $slug => $label)
                                    <option value="{{ $slug }}" {{ old('partner', $ad->partner ?? '') === $slug ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('partner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label required">Nom affiché</label>
                            <input type="text" name="name" id="nameInput" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $ad->name ?? '') }}" required maxlength="255" placeholder="Ex : Estuaire Eat">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Accroche</label>
                        <input type="text" name="headline" id="headlineInput" class="form-control @error('headline') is-invalid @enderror"
                               value="{{ old('headline', $ad->headline ?? '') }}" required maxlength="255">
                        @error('headline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Texte</label>
                        <textarea name="body" id="bodyInput" rows="3" maxlength="1000" class="form-control @error('body') is-invalid @enderror">{{ old('body', $ad->body ?? '') }}</textarea>
                        @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Libellé du bouton</label>
                            <input type="text" name="cta_label" id="ctaInput" class="form-control @error('cta_label') is-invalid @enderror"
                                   value="{{ old('cta_label', $ad->cta_label ?? 'Télécharger l\'app') }}" maxlength="60">
                            @error('cta_label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Couleur d'accent</label>
                            <div style="display: flex; gap: 0.75rem; align-items: center;">
                                <input type="color" id="colorPicker" value="{{ $accent }}" style="width: 60px; height: 42px; padding: 4px; cursor: pointer;" class="form-control">
                                <input type="text" name="accent_color" id="colorText" class="form-control @error('accent_color') is-invalid @enderror"
                                       value="{{ $accent }}" maxlength="7" style="width: 120px;" placeholder="#E4572E">
                            </div>
                            @error('accent_color') <div class="invalid-feedback" style="display:block;">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Visuels</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Logo</label>
                            <input type="file" name="logo" id="logoInput" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                            <small class="form-text">PNG carré conseillé — 2 Mo max.</small>
                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Image de couverture (optionnelle)</label>
                            <input type="file" name="image" id="coverInput" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                            <small class="form-text">Format paysage 16:9 conseillé — 4 Mo max.</small>
                            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($isEdit && $ad->image_path)
                                <div class="form-check-box" style="margin-top: 0.5rem;">
                                    <input type="checkbox" name="remove_image" id="remove_image" value="1">
                                    <label for="remove_image">Supprimer la couverture actuelle</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Liens</h3>
                    <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">L'app ouvre le lien adapté à la plateforme du candidat, sinon le site web.</p>
                </div>
                <div class="card-body">
                    @foreach(['play_store_url' => ['Google Play Store', 'https://play.google.com/store/apps/details?id=…'], 'app_store_url' => ['Apple App Store', 'https://apps.apple.com/app/…'], 'website_url' => ['Site web', 'https://…']] as $field => [$label, $ph])
                        <div class="form-group">
                            <label class="form-label">{{ $label }}</label>
                            <input type="url" name="{{ $field }}" class="form-control @error($field) is-invalid @enderror"
                                   value="{{ old($field, $ad->{$field} ?? '') }}" maxlength="2048" placeholder="{{ $ph }}">
                            @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aperçu</h3>
                </div>
                <div class="card-body">
                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fff;">
                        <img id="coverPreview" src="{{ $isEdit ? $ad->image_url : '' }}" alt="" style="width: 100%; aspect-ratio: 16 / 9; object-fit: cover; {{ ($isEdit && $ad->image_url) ? '' : 'display:none;' }}">
                        <div style="padding: 0.875rem;">
                            <div style="font-size: 0.7rem; color: #94a3b8; margin-bottom: 0.5rem;">Sponsorisé</div>
                            <div style="display: flex; gap: 0.75rem; align-items: center;">
                                <img id="logoPreview" src="{{ $isEdit ? $ad->logo_url : '' }}" alt="" style="width: 44px; height: 44px; object-fit: contain; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff;">
                                <div>
                                    <div id="namePreview" style="font-weight: 600; color: #1e293b;">{{ old('name', $ad->name ?? 'Nom du partenaire') }}</div>
                                    <div id="headlinePreview" style="font-size: 0.8rem; color: #475569;">{{ old('headline', $ad->headline ?? 'Accroche') }}</div>
                                </div>
                            </div>
                            <div id="bodyPreview" style="font-size: 0.8rem; color: #64748b; margin-top: 0.5rem;">{{ old('body', $ad->body ?? '') }}</div>
                            <div id="ctaPreview" style="margin-top: 0.75rem; text-align: center; color: #fff; border-radius: 999px; padding: 0.5rem; font-weight: 600; font-size: 0.85rem; background: {{ $accent }};">
                                {{ old('cta_label', $ad->cta_label ?? 'Télécharger l\'app') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Ordre d'affichage</label>
                        <input type="number" name="display_order" min="0" class="form-control @error('display_order') is-invalid @enderror"
                               value="{{ old('display_order', $ad->display_order ?? 0) }}">
                        @error('display_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ad->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active"><strong>Diffuser dans le fil</strong></label>
                    </div>
                    @if($isEdit)
                        <div style="margin-top: 1rem; font-size: 0.875rem; color: #64748b;">
                            {{ number_format($ad->impressions_count, 0, ',', ' ') }} impressions ·
                            {{ number_format($ad->clicks_count, 0, ',', ' ') }} clics
                        </div>
                    @endif
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">{{ $isEdit ? 'Mettre à jour' : 'Créer' }}</button>
                    <a href="{{ route('admin.feed-partner-ads.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bind = (inputId, previewId) => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        input.addEventListener('input', () => { preview.textContent = input.value; });
    };
    bind('nameInput', 'namePreview');
    bind('headlineInput', 'headlinePreview');
    bind('bodyInput', 'bodyPreview');
    bind('ctaInput', 'ctaPreview');

    const picker = document.getElementById('colorPicker');
    const text = document.getElementById('colorText');
    const cta = document.getElementById('ctaPreview');
    picker.addEventListener('input', () => { text.value = picker.value.toUpperCase(); cta.style.background = picker.value; });
    text.addEventListener('input', () => {
        if (/^#[0-9A-Fa-f]{6}$/.test(text.value)) { picker.value = text.value; cta.style.background = text.value; }
    });

    const previewFile = (inputId, imgId) => {
        document.getElementById(inputId).addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const img = document.getElementById(imgId);
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
        });
    };
    previewFile('logoInput', 'logoPreview');
    previewFile('coverInput', 'coverPreview');
});
</script>
@endsection
