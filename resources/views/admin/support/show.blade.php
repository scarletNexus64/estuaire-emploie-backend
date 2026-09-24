@extends('admin.layouts.app')

@section('title', 'Conversation de support')

@section('breadcrumb')
    <span>Communications</span>
    <span>/</span>
    <a href="{{ route('admin.support.index') }}" class="hover:underline">Support in-app</a>
    <span>/</span>
    <span class="font-semibold">{{ $contact->name ?? 'Conversation' }}</span>
@endsection

@section('content')
<div class="mx-auto max-w-3xl space-y-4">

    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-lg font-bold text-emerald-700">
            {{ mb_strtoupper(mb_substr($contact->name ?? '?', 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="font-semibold text-slate-800">{{ $contact->name ?? 'Utilisateur supprimé' }}</p>
            <p class="truncate text-sm text-slate-500">
                {{ $contact->email ?? '' }}
                @if ($contact?->role)
                    · <span class="capitalize">{{ $contact->role }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('admin.support.index') }}"
           class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
            Retour
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-emerald-300 bg-emerald-50 p-3 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-3 rounded-xl border border-slate-200 bg-white p-4">
        @forelse ($messages as $message)
            @php $fromSupport = (int) $message->sender_id === (int) $support->id; @endphp

            <div class="flex {{ $fromSupport ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-2xl px-4 py-2.5
                            {{ $fromSupport
                                ? 'bg-emerald-600 text-white rounded-br-sm'
                                : 'bg-slate-100 text-slate-800 rounded-bl-sm' }}">
                    <p class="whitespace-pre-wrap text-sm leading-relaxed">{{ $message->message }}</p>
                    <p class="mt-1 text-[11px] {{ $fromSupport ? 'text-emerald-100' : 'text-slate-400' }}">
                        {{ $fromSupport ? $support->name : ($message->user->name ?? '') }}
                        · {{ $message->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        @empty
            <p class="py-8 text-center text-sm text-slate-400">Aucun message dans cette conversation.</p>
        @endforelse
    </div>

    {{-- La réponse est publiée au nom du compte support et diffusée par le
         même événement websocket que les messages entre membres : elle
         apparaît en direct dans l'application. --}}
    <form method="POST" action="{{ route('admin.support.reply', $conversation) }}"
          class="rounded-xl border border-slate-200 bg-white p-4">
        @csrf
        <label for="message" class="mb-2 block text-sm font-medium text-slate-700">
            Répondre en tant que « {{ $support->name }} »
        </label>
        <textarea name="message" id="message" rows="4" maxlength="2000" required
                  placeholder="Votre réponse…"
                  class="w-full rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('message') }}</textarea>
        @error('message')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-3 flex justify-end">
            <button class="rounded-lg bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                Envoyer la réponse
            </button>
        </div>
    </form>
</div>
@endsection
