@extends('admin.layouts.app')

@section('title', 'Estuaire Mail')

@section('breadcrumb')
    <a href="{{ route('admin.messagerie.index') }}" class="hover:underline">Estuaire Mail</a>
    <span>/</span>
    <span class="font-semibold">{{ $thread->contact_name ?: $thread->contact_email }}</span>
@endsection

@push('styles')
<style>.mbx-body p { margin: 0 0 .5rem; } .mbx-body a { color: inherit; text-decoration: underline; }</style>
@endpush

@section('content')
<div class="mbx-wrap flex gap-4" x-data="{ replyOpen: {{ $errors->any() ? 'true' : 'false' }} }">

    @include('admin.messagerie._rail', ['active' => 'all'])

    {{-- Conversation --}}
    <section class="mbx-anim flex-1 min-w-0 bg-white rounded-2xl shadow-sm flex flex-col overflow-hidden">

        {{-- En-tête --}}
        <div class="flex items-center gap-3 p-4 border-b shrink-0">
            <a href="{{ route('admin.messagerie.index') }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-500 transition"><i class="mdi mdi-arrow-left text-xl"></i></a>
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold uppercase shrink-0">
                {{ mb_substr($thread->contact_name ?: $thread->contact_email, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-semibold text-gray-900 truncate">{{ $thread->subject ?: '(sans objet)' }}</div>
                <div class="text-sm text-gray-500 truncate">{{ $thread->contact_name ? $thread->contact_name.' · ' : '' }}{{ $thread->contact_email }}</div>
            </div>
            <form method="POST" action="{{ route('admin.messagerie.status', $thread) }}">
                @csrf
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600">
                    <option value="open" @selected($thread->status === 'open')>🟢 Ouverte</option>
                    <option value="pending" @selected($thread->status === 'pending')>🟡 En attente</option>
                    <option value="closed" @selected($thread->status === 'closed')>⚪ Traitée</option>
                </select>
            </form>
            <form method="POST" action="{{ route('admin.messagerie.bulk') }}" onsubmit="return confirm('Mettre cette conversation à la corbeille ?')">
                @csrf
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="ids[]" value="{{ $thread->id }}">
                <input type="hidden" name="to_index" value="1">
                <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition" title="Mettre à la corbeille">
                    <i class="mdi mdi-delete text-xl"></i>
                </button>
            </form>
        </div>

        {{-- Fil défilant --}}
        <div class="flex-1 overflow-y-auto mbx-scroll p-5 space-y-4 bg-gray-50">
            @foreach($thread->messages()->orderBy('created_at')->get() as $msg)
                @php $out = $msg->direction === 'outbound'; @endphp
                <div class="flex {{ $out ? 'justify-end' : 'justify-start' }}">
                    <div class="mbx-bubble max-w-[78%] rounded-2xl px-4 py-3 shadow-sm {{ $out ? 'bg-primary text-white rounded-br-sm' : 'bg-white border rounded-bl-sm' }}">
                        <div class="flex items-center justify-between gap-4 mb-1.5 text-xs {{ $out ? 'text-white/70' : 'text-gray-400' }}">
                            <span class="font-semibold {{ $out ? 'text-white' : 'text-gray-700' }}">
                                {{ $out ? ($msg->from_name ?: 'Support') : ($msg->from_name ?: $msg->from_email) }}
                            </span>
                            <span>{{ $msg->created_at->locale('fr')->isoFormat('DD MMM, HH:mm') }}</span>
                        </div>
                        @if($out && (($msg->recipients_to && \Illuminate\Support\Str::contains($msg->recipients_to, ',')) || $msg->cc))
                            <div class="text-[11px] {{ $out ? 'text-white/60' : 'text-gray-400' }} mb-1.5">
                                <span>À : {{ $msg->recipients_to ?: $msg->to_email }}</span>
                                @if($msg->cc)<span> · Cc : {{ $msg->cc }}</span>@endif
                            </div>
                        @endif
                        <div class="text-sm leading-relaxed {{ $out ? '' : 'text-gray-800' }} mbx-body">
                            {!! $msg->body_html ?: nl2br(e($msg->body_text)) !!}
                        </div>
                        @if($msg->attachments->count())
                            <div class="mt-2 pt-2 border-t {{ $out ? 'border-white/20' : '' }} flex flex-wrap gap-2">
                                @foreach($msg->attachments as $att)
                                    <a href="{{ asset('storage/' . $att->path) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs transition {{ $out ? 'bg-white/20 text-white hover:bg-white/30' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                        <i class="mdi mdi-paperclip"></i> {{ \Illuminate\Support\Str::limit($att->filename, 28) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Réponse --}}
        <div class="border-t shrink-0 bg-white">
            <div x-show="!replyOpen" class="p-3">
                <button @click="replyOpen = true" class="mbx-btn inline-flex items-center gap-2 px-5 py-2.5 border border-gray-300 rounded-full text-gray-600 hover:bg-gray-50">
                    <i class="mdi mdi-reply"></i> Répondre à {{ $thread->contact_name ?: $thread->contact_email }}
                </button>
            </div>

            <form x-show="replyOpen" x-cloak x-transition method="POST" action="{{ route('admin.messagerie.send') }}" enctype="multipart/form-data" class="p-4"
                  onsubmit="document.getElementById('reply_body_html').value = document.getElementById('reply_text').value.replace(/\n/g,'<br>');">
                @csrf
                <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                <input type="hidden" name="to_email" value="{{ $thread->contact_email }}">
                <input type="hidden" name="to_name" value="{{ $thread->contact_name }}">
                <input type="hidden" name="subject" value="Re: {{ $thread->subject ?: '(sans objet)' }}">
                <input type="hidden" name="body_html" id="reply_body_html">

                <textarea id="reply_text" required rows="4" placeholder="Votre réponse..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none text-sm transition"></textarea>

                <div class="flex items-center justify-between mt-3">
                    <div class="flex items-center gap-3">
                        <label class="inline-flex items-center gap-1.5 text-sm text-gray-500 cursor-pointer hover:text-gray-700 transition">
                            <i class="mdi mdi-paperclip text-lg"></i>
                            <input type="file" name="attachments[]" multiple class="hidden" onchange="document.getElementById('reply-files').textContent = this.files.length + ' fichier(s)';">
                        </label>
                        <span id="reply-files" class="text-xs text-gray-400"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="replyOpen = false" class="px-4 py-2 text-gray-500 text-sm">Annuler</button>
                        <button type="submit" class="mbx-btn inline-flex items-center gap-2 px-5 py-2 bg-primary text-white rounded-full text-sm">
                            <i class="mdi mdi-send"></i> Envoyer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
