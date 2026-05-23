@extends('admin.layouts.app')

@section('title', 'Éditer Entreprise')
@section('page-title', 'Éditer l\'Entreprise')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.companies.index') }}" style="color: inherit; text-decoration: none;">Entreprises</a>
    <span> / </span>
    <span>Éditer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
@php
    $currentCategoryIds = $company->categories->pluck('id')->toArray();
    $oldCats = old('category_ids', $currentCategoryIds);
@endphp
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Modifier {{ $company->name }}</h3>
    </div>

    <form method="POST" action="{{ route('admin.companies.update', $company) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. Informations de base --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">1. Informations de base</legend>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Logo</label>
                @if($company->logo)
                    <div style="margin-bottom: 0.75rem;">
                        <img src="{{ $company->logo_url }}" alt="Logo actuel"
                             style="max-width: 120px; max-height: 120px; border-radius: 8px;">
                        <p style="color: #6b7280; font-size: 0.85rem; margin-top: 0.25rem;">Logo actuel — choisissez un fichier pour remplacer</p>
                    </div>
                @endif
                <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/jpg">
                @error('logo')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nom *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
                    @error('name')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required>
                    @error('email')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone *</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}" required>
                    @error('phone')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Site web</label>
                    <input type="url" name="website" class="form-control" value="{{ old('website', $company->website) }}">
                    @error('website')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Description * <small style="color: #6b7280;">(min. 30 caractères)</small></label>
                <textarea name="description" class="form-control" rows="4" minlength="30" required>{{ old('description', $company->description) }}</textarea>
                <small id="descCount" style="color: #6b7280; font-size: 0.85rem;"></small>
                @error('description')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
            </div>
        </fieldset>

        {{-- 2. Catégories --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">2. Catégories d'activité *</legend>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">Sélectionnez au moins une catégorie.</p>

            {{-- Barre de recherche --}}
            <div style="position: relative; margin-bottom: 0.75rem;">
                <input type="text" id="catSearch" class="form-control"
                       placeholder="🔍 Rechercher une catégorie..."
                       style="padding-left: 2.25rem;">
                <svg width="18" height="18" fill="none" stroke="#6b7280" viewBox="0 0 24 24" stroke-width="2"
                     style="position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="button" id="catClearBtn" onclick="clearCatSearch()"
                        style="display: none; position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 0.25rem;">✕</button>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.85rem; color: #6b7280;">
                <span><strong id="catSelectedCount">0</strong> catégorie(s) sélectionnée(s)</span>
                <button type="button" onclick="clearAllCats()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.85rem;">
                    Tout désélectionner
                </button>
            </div>

            <div id="catList" style="max-height: 400px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem; background: #f9fafb;">
                @foreach($categories as $level1 => $items)
                    @php
                        $hasSelected = $items->pluck('id')->intersect($oldCats)->isNotEmpty();
                    @endphp
                    <details class="cat-group" data-level1="{{ Str::lower($level1) }}" style="margin-bottom: 0.75rem;" {{ $hasSelected || count($items) <= 10 ? 'open' : '' }}>
                        <summary style="cursor: pointer; font-weight: 600; padding: 0.5rem; background: #fff; border-radius: 4px;">
                            <span class="cat-group-label">{{ $level1 }}</span>
                            <span style="color: #6b7280; font-weight: 400;">(<span class="cat-group-count">{{ count($items) }}</span>)</span>
                        </summary>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 0.5rem; padding: 0.75rem 0.5rem 0;">
                            @foreach($items as $cat)
                                @php
                                    $label2 = $cat->level_2 ?? $cat->level_1;
                                    $label3 = $cat->level_3 ?? '';
                                    $haystack = Str::lower($level1 . ' ' . $label2 . ' ' . $label3);
                                @endphp
                                <label class="cat-item" data-haystack="{{ $haystack }}"
                                       style="display: flex; align-items: center; gap: 0.5rem; padding: 0.4rem; cursor: pointer; font-size: 0.9rem;">
                                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                                           class="cat-checkbox"
                                           {{ in_array($cat->id, $oldCats) ? 'checked' : '' }}>
                                    <span>{{ $label2 }}@if($label3) <em style="color:#6b7280;">— {{ $label3 }}</em>@endif</span>
                                </label>
                            @endforeach
                        </div>
                    </details>
                @endforeach
                <div id="catNoResults" style="display: none; text-align: center; padding: 2rem; color: #6b7280;">
                    Aucune catégorie ne correspond à votre recherche.
                </div>
            </div>
            @error('category_ids')<small style="color: var(--danger); display: block; margin-top: 0.5rem;">{{ $message }}</small>@enderror
        </fieldset>

        {{-- 3. Photos --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">3. Photos (max 4)</legend>

            @if($company->photos && count($company->photos))
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.5rem;">Photos actuelles — décochez pour supprimer :</p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1rem;">
                    @foreach($company->photos as $i => $path)
                        <label style="position: relative; cursor: pointer; border: 2px solid transparent; border-radius: 8px; overflow: hidden;" class="photo-keep-label">
                            <input type="checkbox" name="keep_photos[]" value="{{ $path }}" checked
                                   style="position: absolute; top: 6px; right: 6px; z-index: 2; width: 18px; height: 18px;">
                            <img src="{{ asset('storage/' . $path) }}"
                                 style="width: 140px; height: 140px; object-fit: cover; display: block;">
                        </label>
                    @endforeach
                </div>
            @endif

            <label class="form-label">Ajouter de nouvelles photos (PNG/JPG, max 2 MB chacune)</label>
            <input type="file" name="photos[]" class="form-control" accept="image/png,image/jpeg,image/jpg" multiple>
            <div id="photosPreview" style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1rem;"></div>
            @error('photos.*')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
        </fieldset>

        {{-- 4. Localisation --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">4. Localisation *</legend>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem;">
                Cliquez sur la carte pour repositionner l'entreprise. L'adresse, la ville, le pays et les
                coordonnées GPS seront mis à jour automatiquement.
            </p>

            <div class="form-group" style="position: relative;">
                <label class="form-label">Rechercher une adresse</label>
                <input type="text" id="addressSearch" class="form-control" autocomplete="off"
                       placeholder="Tapez au moins 3 caractères…">
                <div id="addressSuggestions"
                     style="display: none; position: absolute; left: 0; right: 0; top: 100%; z-index: 1000;
                            background: #fff; border: 1px solid #e5e7eb; border-top: none;
                            border-radius: 0 0 6px 6px; max-height: 280px; overflow-y: auto;
                            box-shadow: 0 8px 16px rgba(0,0,0,0.08);">
                </div>
            </div>

            <div style="margin-top: 1rem; margin-bottom: 1rem;">
                <div id="map" style="width: 100%; height: 380px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>
                <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                    💡 Cliquez sur la carte ou déplacez le marqueur pour ajuster la position exacte.
                </small>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="address" id="address" class="form-control"
                           value="{{ old('address', $company->address) }}" readonly style="background: #f9fafb;">
                    @error('address')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ville</label>
                    <input type="text" name="city" id="city" class="form-control"
                           value="{{ old('city', $company->city) }}" readonly style="background: #f9fafb;">
                </div>
                <div class="form-group">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" id="country" class="form-control"
                           value="{{ old('country', $company->country ?? 'Cameroun') }}" readonly style="background: #f9fafb;">
                </div>
                <div class="form-group">
                    <label class="form-label">Coordonnées GPS *</label>
                    <input type="text" id="coordDisplay" class="form-control"
                           placeholder="Cliquez sur la carte" readonly style="background: #f9fafb;">
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $company->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $company->longitude) }}">
            </div>
            @error('latitude')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
            @error('longitude')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
        </fieldset>

        {{-- 5. Statut --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600;">5. Statut administratif</legend>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Statut *</label>
                    <select name="status" class="form-control" required>
                        <option value="pending" {{ old('status', $company->status) === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="verified" {{ old('status', $company->status) === 'verified' ? 'selected' : '' }}>Vérifiée</option>
                        <option value="suspended" {{ old('status', $company->status) === 'suspended' ? 'selected' : '' }}>Suspendue</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Plan (legacy)</label>
                    <select name="subscription_plan" class="form-control" required>
                        <option value="free" {{ old('subscription_plan', $company->subscription_plan) === 'free' ? 'selected' : '' }}>Gratuit</option>
                        <option value="premium" {{ old('subscription_plan', $company->subscription_plan) === 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>
                    <small style="color: #6b7280;">Pour attribuer un pack recruteur détaillé, utilisez l'écran <a href="{{ route('admin.manual-subscriptions.create') }}">Attribution manuelle</a>.</small>
                </div>
            </div>
        </fieldset>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Enregistrer
            </button>
            <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
// ============ Compteur description ============
const descTextarea = document.querySelector('textarea[name="description"]');
const descCount = document.getElementById('descCount');
function updateDescCount() {
    const n = descTextarea.value.length;
    descCount.textContent = `${n} caractères`;
    descCount.style.color = n >= 30 ? '#10b981' : '#6b7280';
}
descTextarea.addEventListener('input', updateDescCount);
updateDescCount();

// ============ Photos ============
const photosInput = document.querySelector('input[name="photos[]"]');
const photosPreview = document.getElementById('photosPreview');
photosInput.addEventListener('change', (e) => {
    photosPreview.innerHTML = '';
    Array.from(e.target.files).slice(0, 4).forEach(file => {
        const reader = new FileReader();
        reader.onload = (ev) => {
            const wrapper = document.createElement('div');
            wrapper.style.cssText = 'width: 120px; height: 120px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;';
            wrapper.innerHTML = `<img src="${ev.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
            photosPreview.appendChild(wrapper);
        };
        reader.readAsDataURL(file);
    });
});

// Visuel feedback sur les photos décochées (à garder/supprimer)
document.querySelectorAll('.photo-keep-label input[type="checkbox"]').forEach(cb => {
    const updateOpacity = () => {
        cb.parentElement.style.opacity = cb.checked ? '1' : '0.35';
        cb.parentElement.style.borderColor = cb.checked ? 'transparent' : '#ef4444';
    };
    cb.addEventListener('change', updateOpacity);
    updateOpacity();
});

// ============ Recherche catégories ============
const catSearch = document.getElementById('catSearch');
const catClearBtn = document.getElementById('catClearBtn');
const catNoResults = document.getElementById('catNoResults');
const catGroups = document.querySelectorAll('.cat-group');
const catSelectedCount = document.getElementById('catSelectedCount');

function normalize(s) {
    return (s || '').toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '');
}

function filterCategories() {
    const q = normalize(catSearch.value.trim());
    catClearBtn.style.display = q ? 'block' : 'none';
    let totalVisible = 0;
    catGroups.forEach(group => {
        let visibleInGroup = 0;
        group.querySelectorAll('.cat-item').forEach(item => {
            const haystack = normalize(item.dataset.haystack);
            const match = !q || haystack.includes(q);
            item.style.display = match ? '' : 'none';
            if (match) visibleInGroup++;
        });
        group.style.display = visibleInGroup === 0 ? 'none' : '';
        group.querySelector('.cat-group-count').textContent = visibleInGroup;
        if (q && visibleInGroup > 0) group.setAttribute('open', '');
        totalVisible += visibleInGroup;
    });
    catNoResults.style.display = totalVisible === 0 ? 'block' : 'none';
}

function clearCatSearch() { catSearch.value = ''; filterCategories(); catSearch.focus(); }
function clearAllCats() {
    document.querySelectorAll('.cat-checkbox:checked').forEach(cb => cb.checked = false);
    updateCatSelectedCount();
}
function updateCatSelectedCount() {
    const n = document.querySelectorAll('.cat-checkbox:checked').length;
    catSelectedCount.textContent = n;
    catSelectedCount.style.color = n > 0 ? '#10b981' : '#6b7280';
}
catSearch.addEventListener('input', filterCategories);
document.querySelectorAll('.cat-checkbox').forEach(cb => cb.addEventListener('change', updateCatSelectedCount));
updateCatSelectedCount();

// ============ Carte interactive Leaflet + OpenStreetMap ============
const latInput = document.getElementById('latitude');
const lngInput = document.getElementById('longitude');
const addressInput = document.getElementById('address');
const cityInput = document.getElementById('city');
const countryInput = document.getElementById('country');
const coordDisplay = document.getElementById('coordDisplay');

const initialLat = parseFloat(latInput.value) || 4.0511;
const initialLng = parseFloat(lngInput.value) || 9.7679;
const hasInitialPoint = !!(latInput.value && lngInput.value);

const lmap = L.map('map').setView([initialLat, initialLng], hasInitialPoint ? 15 : 11);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
}).addTo(lmap);

let lmarker = null;

function placeMarker(lat, lng, reverseGeocode) {
    if (lmarker) {
        lmarker.setLatLng([lat, lng]);
    } else {
        lmarker = L.marker([lat, lng], { draggable: true }).addTo(lmap);
        lmarker.on('dragend', (e) => {
            const pos = e.target.getLatLng();
            placeMarker(pos.lat, pos.lng, true);
        });
    }
    latInput.value = lat.toFixed(8);
    lngInput.value = lng.toFixed(8);
    coordDisplay.value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    if (reverseGeocode) fillAddressFromLatLng(lat, lng);
}

if (hasInitialPoint) placeMarker(initialLat, initialLng, false);

lmap.on('click', (e) => placeMarker(e.latlng.lat, e.latlng.lng, true));

function fillAddressFromLatLng(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=fr`;
    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            if (!data) return;
            if (data.display_name) addressInput.value = data.display_name.substring(0, 255);
            const a = data.address || {};
            const city = a.city || a.town || a.village || a.municipality || a.county || a.state || '';
            const country = a.country || '';
            if (city) cityInput.value = city;
            if (country) countryInput.value = country;
        })
        .catch(() => {});
}

// ============ Autocomplete adresse (Nominatim) ============
const addressSearchInput = document.getElementById('addressSearch');
const suggestionsBox = document.getElementById('addressSuggestions');
let addressDebounce = null;
let activeSuggestionIndex = -1;
let currentSuggestions = [];

addressSearchInput.addEventListener('input', () => {
    const q = addressSearchInput.value.trim();
    clearTimeout(addressDebounce);
    if (q.length < 3) { hideSuggestions(); return; }
    addressDebounce = setTimeout(() => fetchSuggestions(q), 300);
});

addressSearchInput.addEventListener('keydown', (e) => {
    if (suggestionsBox.style.display === 'none') return;
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeSuggestionIndex = Math.min(activeSuggestionIndex + 1, currentSuggestions.length - 1);
        highlightSuggestion();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeSuggestionIndex = Math.max(activeSuggestionIndex - 1, 0);
        highlightSuggestion();
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (activeSuggestionIndex >= 0 && currentSuggestions[activeSuggestionIndex]) {
            selectSuggestion(currentSuggestions[activeSuggestionIndex]);
        }
    } else if (e.key === 'Escape') {
        hideSuggestions();
    }
});

document.addEventListener('click', (e) => {
    if (!suggestionsBox.contains(e.target) && e.target !== addressSearchInput) hideSuggestions();
});

function fetchSuggestions(q) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=6&accept-language=fr&q=${encodeURIComponent(q)}`;
    fetch(url, { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(arr => { currentSuggestions = Array.isArray(arr) ? arr : []; renderSuggestions(); })
        .catch(() => hideSuggestions());
}

function renderSuggestions() {
    if (!currentSuggestions.length) {
        suggestionsBox.innerHTML = '<div style="padding: 0.75rem 1rem; color: #6b7280;">Aucun résultat</div>';
        suggestionsBox.style.display = 'block';
        return;
    }
    suggestionsBox.innerHTML = currentSuggestions.map((s, i) => `
        <div class="addr-suggestion" data-index="${i}"
             style="padding: 0.65rem 0.9rem; cursor: pointer; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem;">
            <div style="font-weight: 500;">${escapeJsHtml(s.display_name)}</div>
            <div style="color: #6b7280; font-size: 0.78rem;">${escapeJsHtml(s.type || '')} · ${escapeJsHtml(s.address?.country || '')}</div>
        </div>
    `).join('');
    suggestionsBox.style.display = 'block';
    activeSuggestionIndex = -1;
    suggestionsBox.querySelectorAll('.addr-suggestion').forEach(el => {
        el.addEventListener('mouseenter', () => { activeSuggestionIndex = parseInt(el.dataset.index); highlightSuggestion(); });
        el.addEventListener('click', () => selectSuggestion(currentSuggestions[parseInt(el.dataset.index)]));
    });
}

function highlightSuggestion() {
    suggestionsBox.querySelectorAll('.addr-suggestion').forEach((el, i) => {
        el.style.background = i === activeSuggestionIndex ? '#eff6ff' : '';
    });
}

function selectSuggestion(s) {
    if (!s) return;
    const lat = parseFloat(s.lat);
    const lng = parseFloat(s.lon);
    addressSearchInput.value = s.display_name;
    hideSuggestions();
    lmap.setView([lat, lng], 15);
    placeMarker(lat, lng, false);
    addressInput.value = (s.display_name || '').substring(0, 255);
    const a = s.address || {};
    const city = a.city || a.town || a.village || a.municipality || a.county || a.state || '';
    const country = a.country || '';
    if (city) cityInput.value = city;
    if (country) countryInput.value = country;
}

function hideSuggestions() {
    suggestionsBox.style.display = 'none';
    activeSuggestionIndex = -1;
}

function escapeJsHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

setTimeout(() => lmap.invalidateSize(), 200);

// Validation submit : lat/lng obligatoires
document.querySelector('form').addEventListener('submit', (e) => {
    if (!latInput.value || !lngInput.value) {
        e.preventDefault();
        alert('Veuillez positionner l\'entreprise sur la carte.');
        document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection
