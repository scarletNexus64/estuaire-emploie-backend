{{-- État vide : <x-admin.empty-state icon="mdi-briefcase-off-outline" title="Aucune offre" text="…" /> --}}
@props(['icon' => 'mdi-inbox-outline', 'title' => 'Aucun élément', 'text' => null])

<div {{ $attributes->merge(['class' => 'ad-empty']) }}>
    <i class="mdi {{ $icon }}"></i>
    <p class="ad-empty-title">{{ $title }}</p>
    @if($text)<p class="ad-empty-text">{{ $text }}</p>@endif
    {{ $slot }}
</div>
