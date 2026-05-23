@extends('admin.layouts.app')

@section('title', 'Éditer traductions — #' . $item->id)
@section('page-title', 'Éditer traductions : ' . $resourceLabel)

@section('content')
<div class="space-y-4" x-data="{ tab: 'fr' }">
    <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between flex-wrap gap-3">
        <a href="{{ route('admin.translations.list', ['resource' => $resource]) }}" class="text-primary hover:underline text-sm">
            <i class="mdi mdi-arrow-left"></i> Retour à la liste
        </a>
        <div class="text-sm text-gray-500">
            Élément #{{ $item->id }}
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded p-3 text-sm">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.translations.update', ['resource' => $resource, 'id' => $item->id]) }}"
          class="bg-white rounded-lg shadow overflow-hidden">
        @csrf
        @method('PUT')

        {{-- Locale tabs --}}
        <div class="flex border-b border-gray-200 bg-gray-50">
            @foreach($translationLocales as $locale)
                @php
                    $isRtl = in_array($locale, $translationRtl, true);
                    $isComplete = true;
                    foreach ($fields as $f) {
                        $val = $translations[$f][$locale] ?? null;
                        if ($val === null || $val === '') { $isComplete = false; break; }
                    }
                @endphp
                <button type="button"
                        @click="tab = '{{ $locale }}'"
                        :class="tab === '{{ $locale }}' ? 'border-primary text-primary bg-white' : 'border-transparent text-gray-600 hover:text-gray-800'"
                        class="flex-1 px-4 py-3 text-sm font-semibold border-b-2 transition flex items-center justify-center gap-2">
                    <span class="uppercase">{{ $translationLabels[$locale] ?? $locale }}</span>
                    @if($isRtl)
                        <span class="text-xs text-gray-400">(RTL)</span>
                    @endif
                    @if($isComplete)
                        <i class="mdi mdi-check-circle text-green-500"></i>
                    @else
                        <i class="mdi mdi-alert-circle text-yellow-500"></i>
                    @endif
                </button>
            @endforeach
        </div>

        {{-- Tab panels --}}
        <div class="p-6 space-y-5">
            @foreach($translationLocales as $locale)
                @php $isRtl = in_array($locale, $translationRtl, true); @endphp
                <div x-show="tab === '{{ $locale }}'" x-cloak {{ $isRtl ? 'dir=rtl' : '' }}>
                    <div class="mb-4 pb-3 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ $translationLabels[$locale] ?? strtoupper($locale) }}
                            <span class="text-xs text-gray-400 uppercase ml-2">{{ $locale }}</span>
                        </h3>
                        @if($locale === 'fr')
                            <p class="text-xs text-gray-500">Langue de référence — sert de fallback si une traduction est manquante.</p>
                        @endif
                    </div>

                    @foreach($fields as $field)
                        @php
                            $value = $translations[$field][$locale] ?? old("translations.$field.$locale");
                            $isLong = $field === 'description';
                        @endphp
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                {{ ucfirst(str_replace('_', ' ', $field)) }}
                                @if($locale === 'fr')
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            @if($isLong)
                                <textarea
                                    name="translations[{{ $field }}][{{ $locale }}]"
                                    rows="3"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-primary {{ $isRtl ? 'text-right' : '' }}"
                                    {{ $isRtl ? 'dir=rtl' : '' }}>{{ $value }}</textarea>
                            @else
                                <input
                                    type="text"
                                    name="translations[{{ $field }}][{{ $locale }}]"
                                    value="{{ $value }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-primary {{ $isRtl ? 'text-right' : '' }}"
                                    {{ $isRtl ? 'dir=rtl' : '' }}>
                            @endif
                            @if($locale !== 'fr' && ! empty($translations[$field]['fr']))
                                <p class="text-xs text-gray-500 mt-1">
                                    <span class="font-semibold">FR :</span> {{ \Illuminate\Support\Str::limit($translations[$field]['fr'], 120) }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
            <p class="text-xs text-gray-500">
                <i class="mdi mdi-information-outline"></i>
                Les champs vides retomberont automatiquement sur le français.
            </p>
            <button type="submit" class="bg-primary text-white px-5 py-2 rounded hover:bg-primary-dark font-semibold">
                <i class="mdi mdi-content-save"></i> Enregistrer les traductions
            </button>
        </div>
    </form>
</div>
@endsection
