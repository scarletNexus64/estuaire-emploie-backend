{{--
    Indicateur : <x-admin.stat label="Offres" :value="$total" icon="mdi-briefcase-outline"
                               hint="168 publiées" tone="success" href="{{ route('admin.jobs.index') }}" />
    tone : success | warning | danger | primary (point de couleur devant le libellé)
--}}
@props(['label', 'value', 'icon' => null, 'hint' => null, 'tone' => null, 'href' => null])

<div {{ $attributes->merge(['class' => 'ad-card ad-stat']) }}>
    <div class="ad-stat-top">
        <span class="ad-stat-label">
            @if($tone)<span class="ad-dot ad-dot-{{ $tone }}"></span>@endif
            {{ $label }}
        </span>
        @if($icon)<span class="ad-stat-icon"><i class="mdi {{ $icon }}"></i></span>@endif
    </div>
    <div class="ad-stat-value">{{ is_numeric($value) ? number_format($value, 0, ',', ' ') : $value }}</div>
    @if($hint)<div class="ad-stat-hint">{{ $hint }}</div>@endif
    @if($href)
        <a href="{{ $href }}" class="ad-link mt-3">Voir tout <i class="mdi mdi-arrow-right"></i></a>
    @endif
</div>
