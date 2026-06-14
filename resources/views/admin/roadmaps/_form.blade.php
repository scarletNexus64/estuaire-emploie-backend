{{-- Champs partagés entre create et edit. $roadmap est null en création. --}}
@php($r = $roadmap ?? null)

<div class="form-group">
    <label for="title" class="form-label">Titre de la Roadmap *</label>
    <input type="text" class="form-control @error('title') is-invalid @enderror"
           id="title" name="title" value="{{ old('title', $r->title ?? '') }}"
           placeholder="Ex: Devenir Développeur Backend" required>
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="domain" class="form-label">Domaine *</label>
            <select class="form-control @error('domain') is-invalid @enderror" id="domain" name="domain" required>
                <option value="">Sélectionnez un domaine</option>
                @foreach($domains as $key => $label)
                    <option value="{{ $key }}" {{ old('domain', $r->domain ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('domain')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="difficulty" class="form-label">Difficulté *</label>
            <select class="form-control @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                @foreach($difficulties as $key => $label)
                    <option value="{{ $key }}" {{ old('difficulty', $r->difficulty ?? 'beginner') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('difficulty')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Packs Requis</label>
    <div class="@error('required_packs') is-invalid @enderror">
        @php($selectedPacks = old('required_packs', $r->required_packs ?? []))
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pack_c1" name="required_packs[]" value="C1"
                   {{ is_array($selectedPacks) && in_array('C1', $selectedPacks) ? 'checked' : '' }}>
            <label class="form-check-label" for="pack_c1">🥈 PACK C1 (ARGENT)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pack_c2" name="required_packs[]" value="C2"
                   {{ is_array($selectedPacks) && in_array('C2', $selectedPacks) ? 'checked' : '' }}>
            <label class="form-check-label" for="pack_c2">🥇 PACK C2 (OR)</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="pack_c3" name="required_packs[]" value="C3"
                   {{ is_array($selectedPacks) && in_array('C3', $selectedPacks) ? 'checked' : '' }}>
            <label class="form-check-label" for="pack_c3">💎 PACK C3 (DIAMANT)</label>
        </div>
    </div>
    @error('required_packs')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
    <small class="text-muted">Aucune case cochée = roadmap gratuite (accessible à tous).</small>
</div>

<div class="form-group">
    <label for="description" class="form-label">Description *</label>
    <textarea class="form-control @error('description') is-invalid @enderror"
              id="description" name="description" rows="3" required>{{ old('description', $r->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="objectives" class="form-label">Objectifs</label>
    <textarea class="form-control @error('objectives') is-invalid @enderror"
              id="objectives" name="objectives" rows="3"
              placeholder="Listez les objectifs (un par ligne)">{{ old('objectives', $r->objectives ?? '') }}</textarea>
    @error('objectives')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label for="icon" class="form-label">Icône</label>
            <input type="text" class="form-control @error('icon') is-invalid @enderror"
                   id="icon" name="icon" value="{{ old('icon', $r->icon ?? '🗺️') }}" placeholder="🗺️" maxlength="10">
            <small class="text-muted">Emoji</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="color" class="form-label">Couleur</label>
            <input type="color" class="form-control @error('color') is-invalid @enderror"
                   id="color" name="color" value="{{ old('color', $r->color ?? '#6366F1') }}" style="height: 42px; padding: 4px;">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="pass_threshold" class="form-label">Seuil QCM (%)</label>
            <input type="number" class="form-control @error('pass_threshold') is-invalid @enderror"
                   id="pass_threshold" name="pass_threshold" value="{{ old('pass_threshold', $r->pass_threshold ?? 70) }}"
                   min="0" max="100" placeholder="70">
            <small class="text-muted">Score min. pour valider</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="order" class="form-label">Ordre</label>
            <input type="number" class="form-control @error('order') is-invalid @enderror"
                   id="order" name="order" value="{{ old('order', $r->order ?? 0) }}" min="0" placeholder="0">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
               {{ old('is_active', $r->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Roadmap active</label>
    </div>
</div>
