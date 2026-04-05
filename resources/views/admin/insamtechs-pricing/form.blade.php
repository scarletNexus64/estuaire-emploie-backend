@extends('admin.layouts.app')

@section('title', $pricing ? 'Éditer le prix' : 'Configurer un prix')
@section('page-title', $pricing ? 'Éditer le prix de la formation' : 'Configurer un prix')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.insamtechs-pricing.index') }}">Tarification InsamTechs</a>
    <span> / </span>
    <span>{{ $pricing ? 'Éditer' : 'Créer' }}</span>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ $pricing ? route('admin.insamtechs-pricing.update', $pricing) : route('admin.insamtechs-pricing.store') }}">
            @csrf
            @if($pricing)
                @method('PUT')
            @endif

            @if($pricing)
                <div class="form-group">
                    <label>ID InsamTechs</label>
                    <input type="text" value="#{{ $pricing->insamtechs_formation_id }}" class="form-input" disabled>
                </div>
            @else
                <div class="form-group">
                    <label for="insamtechs_formation_id">Formation InsamTechs *</label>
                    <select name="insamtechs_formation_id" id="insamtechs_formation_id" class="form-input" required onchange="updateTitle()">
                        <option value="">-- Choisir une formation --</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation['id'] }}" data-title="{{ $formation['title'] }}">
                                #{{ $formation['id'] }} - {{ $formation['title'] }}
                            </option>
                        @endforeach
                    </select>
                    @if(empty($formations))
                        <small style="color: #dc2626;">Aucune formation disponible. Vérifiez que l'API InsamTechs est accessible.</small>
                    @endif
                </div>
            @endif

            <div class="form-group">
                <label for="formation_title">Titre de la formation *</label>
                <input type="text" name="formation_title" id="formation_title" value="{{ old('formation_title', $pricing->formation_title ?? '') }}" class="form-input" required>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div class="form-group">
                    <label for="price_xaf">Prix XAF *</label>
                    <input type="number" step="1" min="0" name="price_xaf" id="price_xaf" value="{{ old('price_xaf', $pricing->price_xaf ?? 0) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="price_usd">Prix USD</label>
                    <input type="number" step="0.01" min="0" name="price_usd" id="price_usd" value="{{ old('price_usd', $pricing->price_usd ?? 0) }}" class="form-input">
                </div>
                <div class="form-group">
                    <label for="price_eur">Prix EUR</label>
                    <input type="number" step="0.01" min="0" name="price_eur" id="price_eur" value="{{ old('price_eur', $pricing->price_eur ?? 0) }}" class="form-input">
                </div>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $pricing->is_active ?? true) ? 'checked' : '' }}>
                    Actif (visible et achetable par les utilisateurs)
                </label>
            </div>

            <div class="form-group">
                <label for="notes">Notes internes</label>
                <textarea name="notes" id="notes" rows="3" class="form-input">{{ old('notes', $pricing->notes ?? '') }}</textarea>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">{{ $pricing ? 'Mettre à jour' : 'Enregistrer' }}</button>
                <a href="{{ route('admin.insamtechs-pricing.index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<script>
function updateTitle() {
    const select = document.getElementById('insamtechs_formation_id');
    const titleInput = document.getElementById('formation_title');
    const option = select.options[select.selectedIndex];
    if (option && option.dataset.title) {
        titleInput.value = option.dataset.title;
    }
}
</script>
@endsection
