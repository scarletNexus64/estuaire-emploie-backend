{{--
    Badge de statut : <x-admin.badge tone="success">Publiée</x-admin.badge>
    tone : success | warning | danger | primary | neutral
--}}
@props(['tone' => 'neutral'])

<span {{ $attributes->merge(['class' => 'ad-pill' . ($tone !== 'neutral' ? ' ad-pill-' . $tone : '')]) }}>{{ $slot }}</span>
