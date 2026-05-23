@extends('admin.layouts.app')

@section('title', 'Nouvelle Entreprise')
@section('page-title', 'Créer une Entreprise')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.companies.index') }}" style="color: inherit; text-decoration: none;">Entreprises</a>
    <span> / </span>
    <span>Créer</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nouvelle Entreprise</h3>
    </div>

    <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data" id="companyForm">
        @csrf

        {{-- ÉTAPE 1 : Informations de base --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600; color: #1f2937;">1. Informations de base</legend>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Logo de l'entreprise</label>
                <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/jpg">
                <small style="color: var(--secondary); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                    PNG, JPG, JPEG — max 2 MB
                </small>
                @error('logo')
                    <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div class="form-group">
                    <label class="form-label">Nom de l'entreprise *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Téléphone *</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+237 690 000 000" required>
                    @error('phone')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Site web</label>
                    <input type="url" name="website" class="form-control" value="{{ old('website') }}" placeholder="https://example.com">
                    @error('website')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="form-group" style="margin-top: 1rem;">
                <label class="form-label">Description * <small style="color: #6b7280;">(min. 30 caractères)</small></label>
                <textarea name="description" class="form-control" rows="4" minlength="30" required
                          placeholder="Présentez l'entreprise, ses activités, sa mission...">{{ old('description') }}</textarea>
                <small id="descCount" style="color: #6b7280; font-size: 0.85rem;">0 / 30 caractères minimum</small>
                @error('description')
                    <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
                @enderror
            </div>
        </fieldset>

        {{-- ÉTAPE 2 : Catégories (multi-sélection level_2) --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600; color: #1f2937;">2. Catégories d'activité *</legend>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">
                Sélectionnez au moins une catégorie (niveau 2) parmi les domaines proposés.
            </p>

            @php $oldCats = old('category_ids', []); @endphp

            {{-- Barre de recherche --}}
            <div style="position: relative; margin-bottom: 0.75rem;">
                <input type="text" id="catSearch" class="form-control"
                       placeholder="🔍 Rechercher une catégorie (ex: web, finance, santé...)"
                       style="padding-left: 2.25rem;">
                <svg width="18" height="18" fill="none" stroke="#6b7280" viewBox="0 0 24 24" stroke-width="2"
                     style="position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="button" id="catClearBtn" onclick="clearCatSearch()"
                        style="display: none; position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; padding: 0.25rem;">✕</button>
            </div>

            {{-- Compteur de sélection --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; font-size: 0.85rem; color: #6b7280;">
                <span><strong id="catSelectedCount">0</strong> catégorie(s) sélectionnée(s)</span>
                <button type="button" onclick="clearAllCats()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 0.85rem;">
                    Tout désélectionner
                </button>
            </div>

            <div id="catList" style="max-height: 400px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem; background: #f9fafb;">
                @foreach($categories as $level1 => $items)
                    <details class="cat-group" data-level1="{{ Str::lower($level1) }}" style="margin-bottom: 0.75rem;" {{ count($items) <= 10 ? 'open' : '' }}>
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
            @error('category_ids')
                <small style="color: var(--danger); font-size: 0.875rem; display: block; margin-top: 0.5rem;">{{ $message }}</small>
            @enderror
            @error('category_ids.*')
                <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
            @enderror
        </fieldset>

        {{-- ÉTAPE 3 : Photos (0-4) --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600; color: #1f2937;">3. Photos de l'entreprise (optionnel)</legend>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem;">
                Jusqu'à 4 photos, formats PNG/JPG, max 2 MB chacune.
            </p>
            <input type="file" name="photos[]" class="form-control" accept="image/png,image/jpeg,image/jpg" multiple>
            <div id="photosPreview" style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1rem;"></div>
            @error('photos')
                <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
            @enderror
            @error('photos.*')
                <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
            @enderror
        </fieldset>

        {{-- ÉTAPE 4 : Localisation (carte interactive) --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600; color: #1f2937;">4. Localisation *</legend>
            <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem;">
                Cliquez sur la carte pour positionner l'entreprise. L'adresse, la ville, le pays et les
                coordonnées GPS seront remplis automatiquement.
            </p>

            {{-- Recherche d'adresse avec autocomplete --}}
            <div class="form-group" style="position: relative;">
                <label class="form-label">Rechercher une adresse</label>
                <input type="text" id="addressSearch" class="form-control" autocomplete="off"
                       placeholder="Tapez au moins 3 caractères (ex: Douala, Cameroun)…">
                <div id="addressSuggestions"
                     style="display: none; position: absolute; left: 0; right: 0; top: 100%; z-index: 1000;
                            background: #fff; border: 1px solid #e5e7eb; border-top: none;
                            border-radius: 0 0 6px 6px; max-height: 280px; overflow-y: auto;
                            box-shadow: 0 8px 16px rgba(0,0,0,0.08);">
                </div>
                <small style="color: #6b7280;">Sélectionnez une suggestion ou cliquez sur la carte pour positionner précisément.</small>
            </div>

            {{-- Carte interactive --}}
            <div style="margin-top: 1rem; margin-bottom: 1rem;">
                <div id="map" style="width: 100%; height: 380px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>
                <small style="color: #6b7280; display: block; margin-top: 0.5rem;">
                    💡 Cliquez sur la carte ou déplacez le marqueur pour ajuster la position exacte.
                </small>
            </div>

            {{-- Champs auto-remplis (lecture seule pour éviter divergence avec la carte) --}}
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="address" id="address" class="form-control"
                           value="{{ old('address') }}" placeholder="Sera remplie automatiquement" readonly
                           style="background: #f9fafb;">
                    @error('address')<small style="color: var(--danger);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ville</label>
                    <input type="text" name="city" id="city" class="form-control"
                           value="{{ old('city') }}" placeholder="Sera remplie automatiquement" readonly
                           style="background: #f9fafb;">
                </div>
                <div class="form-group">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" id="country" class="form-control"
                           value="{{ old('country') }}" placeholder="Sera rempli automatiquement" readonly
                           style="background: #f9fafb;">
                </div>
                <div class="form-group">
                    <label class="form-label">Coordonnées GPS *</label>
                    <input type="text" id="coordDisplay" class="form-control"
                           placeholder="Cliquez sur la carte" readonly style="background: #f9fafb;">
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            </div>
            @error('latitude')
                <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
            @enderror
            @error('longitude')
                <small style="color: var(--danger); font-size: 0.875rem; display: block;">{{ $message }}</small>
            @enderror
        </fieldset>

        {{-- ÉTAPE 5 : Admin & Abonnement --}}
        <fieldset style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <legend style="padding: 0 0.75rem; font-weight: 600; color: #1f2937;">5. Statut administratif</legend>

            <div class="form-group">
                <label class="form-label">Statut de vérification *</label>
                <select name="status" class="form-control" required>
                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="verified" {{ old('status', 'verified') === 'verified' ? 'selected' : '' }}>Vérifiée</option>
                    <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspendue</option>
                </select>
            </div>

            {{-- Sélection du pack recruteur --}}
            <div class="form-group" style="margin-top: 1.5rem;">
                <label class="form-label">Pack recruteur à attribuer</label>
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.75rem;">
                    Choisissez un pack à attribuer à votre compte au moment de la création de l'entreprise.
                    L'attribution est instantanée et sans paiement (manual assignment).
                </p>

                @php $oldPlan = old('recruiter_plan_id'); @endphp

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem;">
                    {{-- Option "Aucun pack" --}}
                    <label class="plan-card" style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 1rem; cursor: pointer; position: relative;">
                        <input type="radio" name="recruiter_plan_id" value=""
                               {{ empty($oldPlan) ? 'checked' : '' }}
                               style="position: absolute; top: 0.75rem; right: 0.75rem;">
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Aucun pack</div>
                        <div style="color: #6b7280; font-size: 0.85rem;">L'utilisateur pourra en choisir un plus tard.</div>
                    </label>

                    @foreach($recruiterPlans as $plan)
                        <label class="plan-card" style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 1rem; cursor: pointer; position: relative; {{ $plan->is_popular ? 'background: #fffbeb;' : '' }}">
                            <input type="radio" name="recruiter_plan_id" value="{{ $plan->id }}"
                                   {{ (string) $oldPlan === (string) $plan->id ? 'checked' : '' }}
                                   style="position: absolute; top: 0.75rem; right: 0.75rem;">
                            @if($plan->is_popular)
                                <span style="position: absolute; top: -10px; left: 12px; background: #f59e0b; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: 700;">★ POPULAIRE</span>
                            @endif
                            <div style="font-weight: 600; font-size: 1.05rem; margin-bottom: 0.25rem; padding-right: 1.5rem;">
                                {{ $plan->name }}
                            </div>
                            <div style="color: #2d96d3; font-weight: 700; font-size: 1.15rem; margin-bottom: 0.5rem;">
                                {{ number_format((float) $plan->price, 0, ',', ' ') }} FCFA
                                <small style="color: #6b7280; font-weight: 400; font-size: 0.8rem;">/ {{ $plan->duration_days }}j</small>
                            </div>
                            <div style="color: #4b5563; font-size: 0.85rem; line-height: 1.4;">
                                <div>💼 {{ $plan->jobs_limit ?? '∞' }} offres</div>
                                <div>📇 {{ $plan->contacts_limit ?? '∞' }} contacts</div>
                                @if($plan->can_access_cvtheque)<div>📚 CV-thèque</div>@endif
                                @if($plan->can_boost_jobs)<div>🚀 Boost d'offres</div>@endif
                                @if($plan->featured_company_badge)<div>⭐ Badge mis en avant</div>@endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('recruiter_plan_id')<small style="color: var(--danger); display: block;">{{ $message }}</small>@enderror
            </div>

            {{-- Plan d'abonnement legacy (free/premium) — caché par défaut, gardé pour compat --}}
            <input type="hidden" name="subscription_plan" value="{{ old('subscription_plan', 'free') }}">
        </fieldset>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer l'entreprise
            </button>
            <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">Annuler</a>
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
    descCount.textContent = `${n} / 30 caractères minimum`;
    descCount.style.color = n >= 30 ? '#10b981' : '#6b7280';
}
descTextarea.addEventListener('input', updateDescCount);
updateDescCount();

// ============ Aperçu photos ============
const photosInput = document.querySelector('input[name="photos[]"]');
const photosPreview = document.getElementById('photosPreview');
photosInput.addEventListener('change', (e) => {
    photosPreview.innerHTML = '';
    const files = Array.from(e.target.files).slice(0, 4);
    if (e.target.files.length > 4) {
        alert('Maximum 4 photos. Seules les 4 premières seront envoyées.');
    }
    files.forEach(file => {
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

// ============ Recherche catégories ============
const catSearch = document.getElementById('catSearch');
const catClearBtn = document.getElementById('catClearBtn');
const catNoResults = document.getElementById('catNoResults');
const catGroups = document.querySelectorAll('.cat-group');
const catItems = document.querySelectorAll('.cat-item');
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

function clearCatSearch() {
    catSearch.value = '';
    filterCategories();
    catSearch.focus();
}

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

// Position initiale : Douala par défaut, ou old() si présent
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

lmap.on('click', (e) => {
    placeMarker(e.latlng.lat, e.latlng.lng, true);
});

// Reverse geocoding via Nominatim (OSM) — gratuit, sans clé
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
        .catch(() => { /* silencieux : l'utilisateur peut toujours remplir manuellement */ });
}

// Recherche d'adresse → Nominatim search
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
    // Debounce 300ms pour respecter la politique d'usage Nominatim (1 req/sec)
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
        .then(arr => {
            currentSuggestions = Array.isArray(arr) ? arr : [];
            renderSuggestions();
        })
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
            <div style="font-weight: 500;">${escapeHtml(s.display_name)}</div>
            <div style="color: #6b7280; font-size: 0.78rem;">
                ${escapeHtml(s.type || '')} · ${escapeHtml(s.address?.country || '')}
            </div>
        </div>
    `).join('');
    suggestionsBox.style.display = 'block';
    activeSuggestionIndex = -1;
    suggestionsBox.querySelectorAll('.addr-suggestion').forEach(el => {
        el.addEventListener('mouseenter', () => {
            activeSuggestionIndex = parseInt(el.dataset.index);
            highlightSuggestion();
        });
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
    // Remplir adresse/ville/pays depuis le résultat (sans repasser par Nominatim)
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

function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

// ============ Plan cards : highlight de la sélection ============
const planRadios = document.querySelectorAll('input[name="recruiter_plan_id"]');
function refreshPlanCards() {
    planRadios.forEach(r => {
        const card = r.closest('.plan-card');
        if (!card) return;
        if (r.checked) {
            card.style.borderColor = '#2d96d3';
            card.style.boxShadow = '0 0 0 3px rgba(45, 150, 211, 0.15)';
        } else {
            card.style.borderColor = '#e5e7eb';
            card.style.boxShadow = '';
        }
    });
}
planRadios.forEach(r => r.addEventListener('change', refreshPlanCards));
refreshPlanCards();

// Forcer le redimensionnement de la carte au cas où le conteneur soit caché au chargement
setTimeout(() => lmap.invalidateSize(), 200);

// Validation finale : lat/lng obligatoires
document.getElementById('companyForm').addEventListener('submit', (e) => {
    if (!latInput.value || !lngInput.value) {
        e.preventDefault();
        alert('Veuillez positionner l\'entreprise sur la carte.');
        document.getElementById('map').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});
</script>
@endsection
