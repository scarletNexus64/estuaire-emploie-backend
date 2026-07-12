{{--
    Partiel : liste de cartes radio sélectionnables (pack ou service).
    Variables: $items (collection de configs), $type (plan|premium_service|addon_service),
               $oldType, $oldId (pré-sélection après erreur de validation)
--}}
@forelse($items as $item)
    @php
        $isSelected = ($oldType === $type && (string) $oldId === (string) $item->id);
        $features = is_array($item->features ?? null) ? $item->features : [];
    @endphp
    <label class="item-card {{ $isSelected ? 'selected' : '' }}">
        <input type="radio" name="item_choice" class="item-radio"
               value="{{ $type }}::{{ $item->id }}" {{ $isSelected ? 'checked' : '' }}>
        <span class="item-card-body">
            <span class="item-card-head">
                @if(!empty($item->icon))<span style="font-size:1.4rem;line-height:1;">{{ $item->icon }}</span>@endif
                <strong style="font-size:1.05rem;">{{ $item->name }}</strong>
                @if(!empty($item->is_popular))
                    <span style="background:#f59e0b;color:#fff;padding:.1rem .45rem;border-radius:4px;font-size:.7rem;font-weight:600;">POPULAIRE</span>
                @endif
            </span>

            @if(!empty($item->description))
                <span style="color:#6b7280;font-size:.88rem;">{{ $item->description }}</span>
            @endif

            <span style="display:flex;gap:1.25rem;flex-wrap:wrap;font-size:.88rem;">
                <span><strong>Prix:</strong> {{ number_format($item->price, 0, ',', ' ') }} XAF</span>
                <span><strong>Durée:</strong> {{ $item->duration_days ? $item->duration_days.' jours' : 'Permanent' }}</span>
                @if($type === 'plan')
                    <span><strong>Offres:</strong> {{ $item->jobs_limit ?? 'Illimité' }}</span>
                    <span><strong>Contacts:</strong> {{ $item->contacts_limit ?? 'Illimité' }}</span>
                @endif
            </span>

            @if(count($features) > 0)
                <span style="display:flex;gap:.4rem;flex-wrap:wrap;">
                    @foreach($features as $feature)
                        <span style="background:#f3f4f6;padding:.2rem .5rem;border-radius:4px;font-size:.78rem;">✓ {{ $feature }}</span>
                    @endforeach
                </span>
            @endif
        </span>
    </label>
@empty
    <p style="color:#6b7280;font-size:.9rem;padding:.5rem 0;">Aucun élément disponible dans cette catégorie.</p>
@endforelse
