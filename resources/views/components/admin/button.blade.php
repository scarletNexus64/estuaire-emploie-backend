{{--
    Bouton : <x-admin.button href="{{ route(...) }}" icon="mdi-plus">Nouvelle offre</x-admin.button>
             <x-admin.button type="submit" variant="ghost">Annuler</x-admin.button>
    variant : primary | ghost | danger
--}}
@props(['href' => null, 'variant' => 'primary', 'icon' => null, 'type' => 'button'])

@php $classes = 'ad-btn ad-btn-' . $variant; @endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="mdi {{ $icon }}"></i>@endif{{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)<i class="mdi {{ $icon }}"></i>@endif{{ $slot }}
    </button>
@endif
