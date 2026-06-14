@extends('admin.layouts.app')

@section('title', 'Estuaire Mail')

@section('breadcrumb')
    <span>Communications</span>
    <span>/</span>
    <span class="font-semibold">Estuaire Mail</span>
@endsection

@push('styles')
<style>
    .mbx-wrap { height: calc(100vh - 7.5rem); }
    .mbx-scroll { scrollbar-width: thin; }
    .mbx-row:hover { background:#f8fafc; }
    .mbx-row .row-actions { opacity: 0; transition: opacity .15s; }
    .mbx-row:hover .row-actions { opacity: 1; }
</style>
@endpush

@section('content')
@php $isTrash = $filter === 'trash'; @endphp
<div class="mbx-wrap flex gap-4" x-data="inbox()">

    @include('admin.messagerie._rail', ['active' => $filter])

    {{-- Pane principal --}}
    <section class="mbx-anim flex-1 min-w-0 bg-white rounded-2xl shadow-sm flex flex-col overflow-hidden">

        {{-- Formulaire d'action groupée (caché) --}}
        <form method="POST" action="{{ route('admin.messagerie.bulk') }}" x-ref="bulkForm" class="hidden">
            @csrf
            <input type="hidden" name="action" :value="bulkAction">
            <template x-for="id in selected" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
        </form>

        {{-- Barre du haut --}}
        <div class="flex items-center gap-3 p-3 border-b shrink-0">
            <input type="checkbox" @change="toggleAll($event)" :checked="allChecked"
                   class="w-4 h-4 rounded border-gray-300 text-primary cursor-pointer" title="Tout sélectionner">

            {{-- Actions groupées (si sélection) --}}
            <div x-show="selected.length" x-cloak class="flex items-center gap-2 flex-1">
                <span class="text-sm text-gray-600" x-text="selected.length + ' sélectionné(s)'"></span>
                @if($isTrash)
                    <button type="button" @click="submitBulk('restore')" class="mbx-btn inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 rounded-lg text-sm">
                        <i class="mdi mdi-restore"></i> Restaurer
                    </button>
                    <button type="button" @click="submitBulk('force')" class="mbx-btn inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-sm">
                        <i class="mdi mdi-delete-forever"></i> Supprimer définitivement
                    </button>
                @else
                    <button type="button" @click="submitBulk('delete')" class="mbx-btn inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-sm">
                        <i class="mdi mdi-delete"></i> Supprimer
                    </button>
                @endif
                <button type="button" @click="selected = []" class="text-sm text-gray-400 hover:text-gray-600">Annuler</button>
            </div>

            {{-- Recherche (si pas de sélection) --}}
            <form x-show="!selected.length" method="GET" action="{{ route('admin.messagerie.index') }}" class="flex-1 flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2">
                <i class="mdi mdi-magnify text-gray-400 text-lg"></i>
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher un contact, un objet..."
                       class="flex-1 bg-transparent outline-none text-sm">
                @if(request('search'))
                    <a href="{{ route('admin.messagerie.index', ['filter' => $filter]) }}" class="text-gray-400 hover:text-gray-600"><i class="mdi mdi-close"></i></a>
                @endif
            </form>

            <a href="{{ route('admin.messagerie.compose') }}" class="md:hidden p-2 bg-primary text-white rounded-full shrink-0"><i class="mdi mdi-pencil"></i></a>
        </div>

        {{-- Liste --}}
        <div class="flex-1 overflow-y-auto mbx-scroll divide-y">
            @forelse($threads as $thread)
                @php $last = $thread->latestMessage->first(); $unread = $thread->unread_count > 0; @endphp
                <div class="mbx-row flex items-center gap-3 px-4 py-3 {{ $unread ? 'bg-blue-50/50' : '' }}">
                    <input type="checkbox" value="{{ $thread->id }}" x-model.number="selected"
                           class="w-4 h-4 rounded border-gray-300 text-primary cursor-pointer shrink-0">

                    <a href="{{ route('admin.messagerie.show', $thread) }}" class="flex-1 min-w-0 flex items-center gap-3">
                        <div class="mbx-avatar w-9 h-9 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-sm font-bold uppercase shrink-0">
                            {{ mb_substr($thread->contact_name ?: $thread->contact_email, 0, 1) }}
                        </div>
                        <div class="w-44 shrink-0 truncate {{ $unread ? 'font-bold text-gray-900' : 'text-gray-700' }}">
                            {{ $thread->contact_name ?: $thread->contact_email }}
                        </div>
                        <div class="flex-1 min-w-0 truncate text-sm">
                            <span class="{{ $unread ? 'font-semibold text-gray-900' : 'text-gray-700' }}">{{ $thread->subject ?: '(sans objet)' }}</span>
                            <span class="text-gray-400"> — {{ \Illuminate\Support\Str::limit(strip_tags(optional($last)->body_text ?? optional($last)->body_html ?? ''), 60) }}</span>
                        </div>
                        <div class="w-16 text-right text-xs {{ $unread ? 'text-primary font-semibold' : 'text-gray-400' }} shrink-0">
                            {{ optional($thread->last_message_at)->locale('fr')->isoFormat('DD MMM') }}
                        </div>
                    </a>

                    {{-- Actions par ligne --}}
                    <div class="row-actions flex items-center gap-1 shrink-0">
                        @if($isTrash)
                            <button type="button" @click="rowAction({{ $thread->id }}, 'restore')" class="p-1.5 text-gray-400 hover:text-green-600" title="Restaurer"><i class="mdi mdi-restore"></i></button>
                            <button type="button" @click="rowAction({{ $thread->id }}, 'force')" class="p-1.5 text-gray-400 hover:text-red-600" title="Supprimer définitivement"><i class="mdi mdi-delete-forever"></i></button>
                        @else
                            <button type="button" @click="rowAction({{ $thread->id }}, 'delete')" class="p-1.5 text-gray-400 hover:text-red-600" title="Mettre à la corbeille"><i class="mdi mdi-delete"></i></button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-gray-400">
                    <i class="mdi {{ $isTrash ? 'mdi-delete-empty' : 'mdi-email-outline' }} text-6xl"></i>
                    <p class="mt-3">{{ $isTrash ? 'La corbeille est vide.' : 'Aucune conversation ici.' }}</p>
                    @unless($isTrash)
                        <a href="{{ route('admin.messagerie.compose') }}" class="text-primary underline">Composer un message</a>
                    @endunless
                </div>
            @endforelse
        </div>

        @if($threads->hasPages())
            <div class="p-3 border-t shrink-0">{{ $threads->links() }}</div>
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
    function inbox() {
        return {
            selected: [],
            bulkAction: 'delete',
            allIds: @json($threads->pluck('id')),
            get allChecked() { return this.allIds.length > 0 && this.selected.length === this.allIds.length; },
            toggleAll(e) { this.selected = e.target.checked ? [...this.allIds] : []; },
            submitBulk(action) {
                if (!this.selected.length) return;
                if (action === 'force' && !confirm('Supprimer définitivement ces conversations ? Cette action est irréversible.')) return;
                this.bulkAction = action;
                this.$nextTick(() => this.$refs.bulkForm.submit());
            },
            rowAction(id, action) {
                if (action === 'force' && !confirm('Supprimer définitivement cette conversation ?')) return;
                this.selected = [id];
                this.bulkAction = action;
                this.$nextTick(() => this.$refs.bulkForm.submit());
            },
        }
    }
</script>
@endpush
