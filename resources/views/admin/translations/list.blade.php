@extends('admin.layouts.app')

@section('title', 'Traductions — ' . $resourceLabel)
@section('page-title', 'Traductions : ' . $resourceLabel)

@section('content')
<div class="space-y-4">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <a href="{{ route('admin.translations.index') }}" class="text-primary hover:underline text-sm">
                <i class="mdi mdi-arrow-left"></i> Retour au centre de traductions
            </a>
            <form method="GET" action="{{ route('admin.translations.list', ['resource' => $resource]) }}" class="flex gap-2">
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Rechercher…"
                       class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:outline-none focus:border-primary">
                <button type="submit" class="bg-primary text-white px-3 py-1.5 rounded text-sm hover:bg-primary-dark">
                    <i class="mdi mdi-magnify"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Libellé (référence FR)</th>
                    @foreach($translationLocales as $locale)
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">
                            {{ strtoupper($locale) }}
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-500">#{{ $item->id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                            @foreach($fields as $field)
                                @php
                                    $rawValue = $item->getAttribute($field);
                                @endphp
                                @if($rawValue)
                                    <div class="{{ ! $loop->first ? 'text-xs text-gray-500 mt-0.5' : '' }}">
                                        @if(! $loop->first)<span class="text-gray-400">{{ $field }}:</span> @endif
                                        {{ \Illuminate\Support\Str::limit($rawValue, 80) }}
                                    </div>
                                @endif
                            @endforeach
                        </td>
                        @php
                            // Only fields that have a canonical (raw) value need translation.
                            $requiredFields = [];
                            foreach ($fields as $f) {
                                $raw = $item->getAttribute($f);
                                if ($raw !== null && $raw !== '') {
                                    $requiredFields[] = $f;
                                }
                            }
                        @endphp
                        @foreach($translationLocales as $locale)
                            @php
                                $allFilled = true;
                                foreach ($requiredFields as $f) {
                                    $tr = $item->translations->where('field', $f)->firstWhere('locale', $locale);
                                    if (! $tr || $tr->value === null || $tr->value === '') {
                                        $allFilled = false;
                                        break;
                                    }
                                }
                            @endphp
                            <td class="px-4 py-3 text-center">
                                @if($allFilled)
                                    <i class="mdi mdi-check-circle text-green-500 text-lg" title="Complet"></i>
                                @else
                                    <i class="mdi mdi-alert-circle text-yellow-500 text-lg" title="À traduire"></i>
                                @endif
                            </td>
                        @endforeach
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.translations.edit', ['resource' => $resource, 'id' => $item->id]) }}"
                               class="inline-flex items-center gap-1 bg-primary text-white text-xs px-3 py-1.5 rounded hover:bg-primary-dark">
                                <i class="mdi mdi-translate"></i> Éditer
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + count($translationLocales) }}" class="px-4 py-12 text-center text-gray-500">
                            Aucun élément trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $items->links() }}</div>
</div>
@endsection
