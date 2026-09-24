@extends('admin.layouts.app')

@section('title', 'Support in-app')

@section('breadcrumb')
    <span>Communications</span>
    <span>/</span>
    <span class="font-semibold">Support in-app</span>
@endsection

@section('content')
<div class="space-y-4">

    {{-- Le compte support est le destinataire de toutes ces conversations :
         sans lui, rien ne peut arriver ici. --}}
    @if (! $support)
        <div class="rounded-lg border border-amber-300 bg-amber-50 p-4 text-amber-800">
            <p class="font-semibold">Compte support absent</p>
            <p class="mt-1 text-sm">
                Aucun utilisateur ne porte l'adresse <code>support@estuaire-emploi.com</code>.
                Exécutez <code>php artisan db:seed --class=SupportAccountSeeder</code>
                pour le créer : les demandes envoyées depuis l'application lui sont adressées.
            </p>
        </div>
    @else

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Demandes de support</h1>
            <p class="text-sm text-slate-500">
                Conversations ouvertes depuis l'application vers « {{ $support->name }} ».
                Les réponses arrivent directement dans le chat de l'utilisateur.
            </p>
        </div>

        <form method="GET" class="flex items-center gap-2">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <input type="search" name="search" value="{{ $search }}"
                   placeholder="Nom ou e-mail…"
                   class="rounded-lg border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
            <button class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-medium text-white hover:bg-slate-700">
                Rechercher
            </button>
        </form>
    </div>

    <div class="flex gap-2">
        @foreach (['all' => 'Toutes', 'unanswered' => 'Sans réponse'] as $key => $label)
            <a href="{{ route('admin.support.index', ['filter' => $key, 'search' => $search]) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium
                      {{ $filter === $key ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        @forelse ($conversations as $conversation)
            @php
                $contact = (int) $conversation->user_one === (int) $support->id
                    ? $conversation->userTwo
                    : $conversation->userOne;
                $last = $conversation->lastMessage;
                $waiting = $last && (int) $last->sender_id !== (int) $support->id;
            @endphp

            <a href="{{ route('admin.support.show', $conversation) }}"
               class="flex items-center gap-4 border-b border-slate-100 p-4 last:border-0 hover:bg-slate-50">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700">
                    {{ mb_strtoupper(mb_substr($contact->name ?? '?', 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="truncate font-semibold text-slate-800">
                            {{ $contact->name ?? 'Utilisateur supprimé' }}
                        </span>
                        @if ($waiting)
                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-bold text-amber-700">
                                EN ATTENTE
                            </span>
                        @endif
                    </div>
                    <p class="truncate text-sm text-slate-500">
                        {{ $last?->message ?? 'Aucun message' }}
                    </p>
                </div>

                <div class="shrink-0 text-right text-xs text-slate-400">
                    <div>{{ optional($last?->created_at)->diffForHumans() }}</div>
                    <div class="mt-1">{{ $conversation->messages_count }} message(s)</div>
                </div>
            </a>
        @empty
            <div class="p-10 text-center text-slate-400">
                <p class="font-medium">Aucune demande</p>
                <p class="mt-1 text-sm">
                    Les messages envoyés depuis le bouton « Contacter le support » de l'application apparaîtront ici.
                </p>
            </div>
        @endforelse
    </div>

    @endif
</div>
@endsection
