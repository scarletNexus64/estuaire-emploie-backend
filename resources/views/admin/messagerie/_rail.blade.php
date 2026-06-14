@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .mbx-wrap { height: calc(100vh - 7.5rem); }
    .mbx-scroll { scrollbar-width: thin; }
    .mbx-scroll::-webkit-scrollbar { width: 8px; }
    .mbx-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 8px; }
    .mbx-anim { animation: mbxIn .4s cubic-bezier(.2,.8,.2,1) both; }
    @keyframes mbxIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
    .mbx-row { transition: background .15s ease, transform .15s ease; }
    .mbx-row:hover { transform: translateX(3px); }
    .mbx-fold { transition: background .2s ease, color .2s ease, padding-left .2s ease; }
    .mbx-fold:hover { padding-left: 1.25rem; }
    .mbx-btn { transition: transform .15s ease, box-shadow .25s ease, opacity .2s ease; }
    .mbx-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -8px rgba(0,0,0,.35); }
    .mbx-btn:active { transform: translateY(0); }
    .mbx-bubble { animation: mbxBubble .32s ease both; }
    @keyframes mbxBubble { from { opacity: 0; transform: scale(.96) translateY(4px); } to { opacity: 1; transform: none; } }
    .mbx-avatar { transition: transform .2s ease; }
    .mbx-row:hover .mbx-avatar { transform: scale(1.08); }
</style>
@endpush

@php
    // Rail latéral d'Estuaire Mail (réutilisé sur toutes les pages du module)
    $active = $active ?? 'all';
    try {
        $railUnread = \App\Models\MailboxMessage::where('direction', 'inbound')->where('is_read', false)->whereHas('thread')->count();
        $railTotal  = \App\Models\MailboxThread::count();
        $railOpen   = \App\Models\MailboxThread::where('status', 'open')->count();
        $railTrash  = \App\Models\MailboxThread::onlyTrashed()->count();
    } catch (\Throwable $e) {
        $railUnread = $railTotal = $railOpen = $railTrash = 0;
    }
    $railFolders = [
        ['key' => 'all',    'label' => 'Boîte de réception', 'icon' => 'mdi-inbox',       'count' => $railUnread],
        ['key' => 'unread', 'label' => 'Non lus',            'icon' => 'mdi-email-alert', 'count' => $railUnread],
        ['key' => 'sent',   'label' => 'Envoyés',            'icon' => 'mdi-send',        'count' => null],
        ['key' => 'trash',  'label' => 'Corbeille',          'icon' => 'mdi-delete',      'count' => $railTrash ?: null],
    ];
@endphp

<aside class="hidden md:flex flex-col w-60 shrink-0">
    <div class="flex items-center gap-2 px-2 mb-4 text-gray-800">
        <i class="mdi mdi-email-fast text-2xl text-primary"></i>
        <span class="font-bold text-lg">Estuaire Mail</span>
    </div>

    <a href="{{ route('admin.messagerie.compose') }}"
       class="mbx-btn inline-flex items-center gap-3 px-5 py-3.5 mb-4 rounded-2xl shadow-md font-medium
              {{ $active === 'compose' ? 'bg-primary text-white ring-2 ring-primary/30' : 'bg-gradient-to-r from-primary to-secondary text-white' }}">
        <i class="mdi mdi-pencil text-xl"></i> Composer
    </a>

    <nav class="space-y-1">
        @foreach($railFolders as $f)
            <a href="{{ route('admin.messagerie.index', ['filter' => $f['key']]) }}"
               class="mbx-fold flex items-center gap-3 px-4 py-2.5 rounded-full text-sm {{ $active === $f['key'] ? 'bg-primary/15 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="mdi {{ $f['icon'] }} text-lg"></i>
                <span class="flex-1">{{ $f['label'] }}</span>
                @if(!empty($f['count']))
                    <span class="text-xs font-bold {{ $active === $f['key'] ? 'text-primary' : 'text-gray-400' }}">{{ $f['count'] }}</span>
                @endif
            </a>
        @endforeach
    </nav>

    <form method="POST" action="{{ route('admin.messagerie.fetch') }}" class="mt-4">
        @csrf
        <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-full hover:bg-gray-50">
            <i class="mdi mdi-refresh"></i> Actualiser
        </button>
    </form>

    <div class="mt-auto text-xs text-gray-400 px-2 leading-relaxed">
        <div><i class="mdi mdi-forum"></i> {{ $railTotal }} conversations</div>
        <div><i class="mdi mdi-folder-open"></i> {{ $railOpen }} ouvertes</div>
    </div>
</aside>
