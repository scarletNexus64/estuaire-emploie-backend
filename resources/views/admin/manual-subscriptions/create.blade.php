@extends('admin.layouts.app')

@section('title', 'Attribution Manuelle')
@section('page-title', 'Attribuer un Pack ou un Service')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.manual-subscriptions.index') }}" style="color: inherit; text-decoration: none;">Attributions Manuelles</a>
    <span> / </span>
    <span>Nouvelle Attribution</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.manual-subscriptions.index') }}" class="btn btn-secondary">Voir l'historique</a>
@endsection

@section('content')
@php
    $oldType = old('item_type', 'plan');
    $oldId = old('item_id');
@endphp
<div style="display:grid;gap:1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attribuer un Pack ou un Service</h3>
        </div>
        <div style="padding:1.5rem;">
            <p style="color:#6b7280;font-size:.9rem;margin:0 0 1.5rem;">
                Crée un abonnement / service actif pour l'utilisateur (comme un achat payé) et lui envoie une notification push.
            </p>

            <form method="POST" action="{{ route('admin.manual-subscriptions.store') }}" id="assignForm">
                @csrf
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}">
                <input type="hidden" name="item_type" id="item_type" value="{{ $oldType }}">
                <input type="hidden" name="item_id" id="item_id" value="{{ $oldId }}">

                {{-- ===================== 1. UTILISATEUR ===================== --}}
                <div class="form-group">
                    <label style="font-weight:600;display:block;margin-bottom:.5rem;">1. Utilisateur <span style="color:#ef4444;">*</span></label>

                    <div id="selectedUserBox" style="display:{{ $selectedUser ? 'flex' : 'none' }};align-items:center;justify-content:space-between;gap:1rem;padding:.85rem 1rem;border:2px solid #059669;border-radius:8px;background:#ecfdf5;margin-bottom:.5rem;">
                        <span>
                            <strong id="selectedUserName">{{ $selectedUser->name ?? '' }}</strong>
                            <span style="color:#6b7280;font-size:.85rem;display:block;" id="selectedUserMeta">
                                {{ $selectedUser ? ($selectedUser->email.' · '.ucfirst($selectedUser->role)) : '' }}
                            </span>
                        </span>
                        <button type="button" class="btn btn-secondary btn-sm" id="clearUserBtn">Changer</button>
                    </div>

                    <div id="userSearchWrapper" style="position:relative;display:{{ $selectedUser ? 'none' : 'block' }};">
                        <input type="text" id="userSearch" class="form-control" autocomplete="off"
                               placeholder="Rechercher par nom, email ou téléphone (min. 2 caractères)…">
                        <div id="userResults" style="position:absolute;z-index:30;left:0;right:0;top:calc(100% + 4px);background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.12);max-height:320px;overflow-y:auto;display:none;"></div>
                    </div>
                    @error('user_id')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                </div>

                {{-- ===================== 2. PACK / SERVICE ===================== --}}
                <div class="form-group" style="margin-top:1.5rem;">
                    <label style="font-weight:600;display:block;margin-bottom:.5rem;">2. Pack ou service à attribuer <span style="color:#ef4444;">*</span></label>
                    @error('item_id')<small style="color:#ef4444;display:block;margin-bottom:.5rem;">{{ $message }}</small>@enderror

                    @php
                        $sections = [
                            ['title' => '📦 Packs Recruteur', 'items' => $recruiterPlans, 'type' => 'plan'],
                            ['title' => '🎓 Packs Candidat', 'items' => $jobSeekerPlans, 'type' => 'plan'],
                            ['title' => '✨ Services Premium', 'items' => $premiumServices, 'type' => 'premium_service'],
                            ['title' => '🚀 Add-ons', 'items' => $addonServices, 'type' => 'addon_service'],
                        ];
                    @endphp

                    @foreach($sections as $section)
                        @if($section['items']->count() > 0)
                            <div style="margin-top:1rem;">
                                <h4 style="font-size:.9rem;text-transform:uppercase;letter-spacing:.03em;color:#6b7280;margin:0 0 .6rem;">{{ $section['title'] }}</h4>
                                <div style="display:grid;gap:.7rem;">
                                    @include('admin.manual-subscriptions._items', ['items' => $section['items'], 'type' => $section['type'], 'oldType' => $oldType, 'oldId' => $oldId])
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- ===================== 3. RAISON / NOTES ===================== --}}
                <div class="form-group" style="margin-top:1.5rem;">
                    <label style="font-weight:600;display:block;margin-bottom:.5rem;">Raison de l'attribution</label>
                    <input type="text" name="reason" class="form-control" value="{{ old('reason') }}"
                           placeholder="Ex: Offre promotionnelle, Compensation, Test…">
                    @error('reason')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                </div>

                <div class="form-group" style="margin-top:1rem;">
                    <label style="font-weight:600;display:block;margin-bottom:.5rem;">Notes (optionnel)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Informations supplémentaires…">{{ old('notes') }}</textarea>
                    @error('notes')<small style="color:#ef4444;">{{ $message }}</small>@enderror
                </div>

                <div style="display:flex;gap:1rem;padding-top:1.5rem;margin-top:1rem;border-top:1px solid #f3f4f6;">
                    <button type="submit" class="btn btn-primary" id="submitBtn">✓ Attribuer</button>
                    <a href="{{ route('admin.manual-subscriptions.create') }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Attributions récentes --}}
    @if($recentAssignments->count() > 0)
    <div class="card">
        <div class="card-header"><h3 class="card-title">Attributions Récentes</h3></div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr><th>Date</th><th>Utilisateur</th><th>Élément</th><th>Type</th><th>Attribué par</th><th>Raison</th></tr>
                </thead>
                <tbody>
                    @php $labels = ['plan' => 'Pack', 'premium_service' => 'Service premium', 'addon_service' => 'Add-on']; @endphp
                    @foreach($recentAssignments as $assignment)
                    <tr>
                        <td>{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $assignment->user->name ?? '—' }}</strong><br>
                            <small style="color:#6b7280;">{{ $assignment->user->email ?? '' }}</small>
                        </td>
                        <td>{{ $assignment->item_label }}</td>
                        <td><span style="background:#f3f4f6;padding:.2rem .5rem;border-radius:4px;font-size:.8rem;">{{ $labels[$assignment->item_type] ?? $assignment->item_type }}</span></td>
                        <td>{{ $assignment->assignedByAdmin->name ?? '—' }}</td>
                        <td>{{ $assignment->reason ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<style>
    /* Carte sélectionnable (label englobant un radio natif) */
    .item-card {
        display: flex; align-items: flex-start; gap: .85rem;
        border: 2px solid #e5e7eb; border-radius: 10px; padding: 1rem;
        cursor: pointer; transition: border-color .15s, background .15s; background: #fff;
    }
    .item-card:hover { border-color: #059669; background: #f0fdf4; }
    .item-card.selected { border-color: #059669; background: #ecfdf5; box-shadow: 0 0 0 1px #059669 inset; }
    .item-card .item-radio { width: 20px; height: 20px; margin-top: .15rem; accent-color: #059669; cursor: pointer; flex: 0 0 auto; }
    .item-card .item-card-body { display: flex; flex-direction: column; gap: .45rem; flex: 1; }
    .item-card .item-card-head { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
    #userResults .user-result { padding: .7rem 1rem; cursor: pointer; border-bottom: 1px solid #f3f4f6; }
    #userResults .user-result:last-child { border-bottom: none; }
    #userResults .user-result:hover { background: #f0fdf4; }
</style>

<script>
(function () {
    const searchUrl = "{{ route('admin.manual-subscriptions.search-users') }}";
    const userIdInput = document.getElementById('user_id');
    const itemTypeInput = document.getElementById('item_type');
    const itemIdInput = document.getElementById('item_id');

    // ---------- Recherche utilisateur ----------
    const searchInput = document.getElementById('userSearch');
    const resultsBox = document.getElementById('userResults');
    const selectedBox = document.getElementById('selectedUserBox');
    const searchWrapper = document.getElementById('userSearchWrapper');
    const clearBtn = document.getElementById('clearUserBtn');
    let timer = null;

    function escapeHtml(s) {
        return (s || '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }

    function selectUser(id, name, meta) {
        userIdInput.value = id;
        document.getElementById('selectedUserName').textContent = name;
        document.getElementById('selectedUserMeta').textContent = meta;
        selectedBox.style.display = 'flex';
        searchWrapper.style.display = 'none';
        resultsBox.style.display = 'none';
    }

    if (clearBtn) clearBtn.addEventListener('click', function () {
        userIdInput.value = '';
        selectedBox.style.display = 'none';
        searchWrapper.style.display = 'block';
        searchInput.value = '';
        searchInput.focus();
    });

    function doSearch(q) {
        fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(json => {
                const users = json.data || [];
                if (!users.length) {
                    resultsBox.innerHTML = '<div style="padding:.8rem 1rem;color:#6b7280;">Aucun utilisateur trouvé.</div>';
                } else {
                    resultsBox.innerHTML = users.map(u => {
                        const meta = [u.email, u.phone].filter(Boolean).join(' · ') + ' · ' + (u.role || '');
                        return '<div class="user-result" data-id="' + u.id + '" data-name="' + escapeHtml(u.name) + '" data-meta="' + escapeHtml(meta) + '">'
                            + '<strong>' + escapeHtml(u.name) + '</strong><br>'
                            + '<small style="color:#6b7280;">' + escapeHtml(meta) + '</small></div>';
                    }).join('');
                }
                resultsBox.style.display = 'block';
            })
            .catch(() => { resultsBox.style.display = 'none'; });
    }

    if (searchInput) searchInput.addEventListener('input', function () {
        const q = this.value.trim();
        clearTimeout(timer);
        if (q.length < 2) { resultsBox.style.display = 'none'; return; }
        timer = setTimeout(() => doSearch(q), 250);
    });

    if (resultsBox) resultsBox.addEventListener('click', function (e) {
        const el = e.target.closest('.user-result');
        if (!el) return;
        selectUser(el.dataset.id, el.dataset.name, el.dataset.meta);
    });

    document.addEventListener('click', function (e) {
        if (searchWrapper && !searchWrapper.contains(e.target)) resultsBox.style.display = 'none';
    });

    // ---------- Sélection d'un pack / service (radios natifs) ----------
    const radios = document.querySelectorAll('.item-radio');
    function syncSelection() {
        document.querySelectorAll('.item-card').forEach(c => c.classList.remove('selected'));
        const checked = document.querySelector('.item-radio:checked');
        if (!checked) { itemTypeInput.value = ''; itemIdInput.value = ''; return; }
        checked.closest('.item-card').classList.add('selected');
        const parts = checked.value.split('::');
        itemTypeInput.value = parts[0];
        itemIdInput.value = parts[1];
    }
    radios.forEach(r => r.addEventListener('change', syncSelection));
    syncSelection(); // état initial (si pré-sélection après erreur)

    // ---------- Validation avant envoi ----------
    document.getElementById('assignForm').addEventListener('submit', function (e) {
        if (!userIdInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un utilisateur.');
            return;
        }
        if (!itemIdInput.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un pack ou un service à attribuer.');
        }
    });
})();
</script>
@endsection
