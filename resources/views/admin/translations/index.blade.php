@extends('admin.layouts.app')

@section('title', 'Traductions')
@section('page-title', 'Gestion des Traductions Multilingues')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="mdi mdi-translate text-primary"></i>
                    Centre de traductions
                </h2>
                <p class="text-gray-600 mt-1">
                    Gérez les libellés multilingues des données de référence exposées à l'API mobile.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($translationLocales as $locale)
                    <span class="px-3 py-1 bg-gray-100 border border-gray-200 rounded text-sm font-semibold uppercase">
                        {{ $translationLabels[$locale] ?? $locale }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($stats as $key => $resource)
            <a href="{{ route('admin.translations.list', ['resource' => $key]) }}"
               class="block bg-white rounded-lg shadow hover:shadow-lg transition border border-gray-100 hover:border-primary">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800">{{ $resource['label'] }}</h3>
                        <span class="text-xs text-gray-500">{{ $resource['total'] }} entrée(s)</span>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    @foreach($resource['by_locale'] as $locale => $stat)
                        @php
                            $pct = $stat['percent'];
                            $color = $pct === 100 ? 'bg-green-500' : ($pct >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-medium uppercase">{{ $translationLabels[$locale] ?? $locale }}</span>
                                <span class="text-gray-500">{{ $stat['translated'] }}/{{ $stat['total'] }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="{{ $color }} h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
