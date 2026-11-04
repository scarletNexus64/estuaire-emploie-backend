{{--
    Carte : <x-admin.card title="Titre" subtitle="Sous-titre">
                <x-slot:actions><a href="#" class="ad-link">Voir tout</a></x-slot:actions>
                Contenu…
            </x-admin.card>
    padding=false pour un contenu pleine largeur (tableau, liste).
--}}
@props(['title' => null, 'subtitle' => null, 'padding' => true])

<section {{ $attributes->merge(['class' => 'ad-card overflow-hidden']) }}>
    @if($title || isset($actions))
        <div class="ad-card-header">
            <div class="min-w-0">
                @if($title)<h3 class="ad-card-title">{{ $title }}</h3>@endif
                @if($subtitle)<p class="ad-card-sub">{{ $subtitle }}</p>@endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2 flex-shrink-0">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div @class(['p-4 sm:p-5' => $padding])>
        {{ $slot }}
    </div>
</section>
