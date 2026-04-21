@extends('admin.layouts.app')

@section('title', 'Forum de Discussion')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">💬 Forum de Discussion</h1>
            <p class="text-gray-600 mt-1">Questions des étudiants et réponses des formateurs</p>
        </div>
        <a href="{{ route('admin.forum.admins') }}" class="btn btn-secondary">
            <i class="mdi mdi-account-cog"></i>
            Gérer les Admins
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                    <i class="mdi mdi-message text-3xl text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Total Messages</p>
                    <p class="text-2xl font-bold">{{ $stats['total_messages'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                    <i class="mdi mdi-account-group text-3xl text-green-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Utilisateurs Actifs</p>
                    <p class="text-2xl font-bold">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                    <i class="mdi mdi-shield-account text-3xl text-purple-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Admins Forum</p>
                    <p class="text-2xl font-bold">{{ $stats['total_admins'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-500 bg-opacity-10">
                    <i class="mdi mdi-calendar-today text-3xl text-orange-500"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Aujourd'hui</p>
                    <p class="text-2xl font-bold">{{ $stats['messages_today'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🔍 Filtres de recherche</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.forum.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Contenu ou nom d'utilisateur..."
                        class="form-control"
                    >
                </div>

                <!-- Type d'utilisateur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="is_admin" class="form-control">
                        <option value="">Tous</option>
                        <option value="0" {{ request('is_admin') === '0' ? 'selected' : '' }}>Étudiants</option>
                        <option value="1" {{ request('is_admin') === '1' ? 'selected' : '' }}>Admins</option>
                    </select>
                </div>

                <!-- Date début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
                    <input
                        type="date"
                        name="date_from"
                        value="{{ request('date_from') }}"
                        class="form-control"
                    >
                </div>

                <!-- Date fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
                    <input
                        type="date"
                        name="date_to"
                        value="{{ request('date_to') }}"
                        class="form-control"
                    >
                </div>

                <!-- Boutons -->
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-magnify"></i> Rechercher
                    </button>
                    <a href="{{ route('admin.forum.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Répondre au forum -->
    <div class="card mb-6 bg-green-50 border-green-200">
        <div class="card-header bg-green-100">
            <h3 class="card-title text-green-800">✍️ Répondre au forum</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.forum.reply') }}">
                @csrf
                <div class="mb-4">
                    <textarea
                        name="content"
                        rows="4"
                        class="form-control"
                        placeholder="Écrivez votre message aux étudiants..."
                        required
                    ></textarea>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="mdi mdi-send"></i> Envoyer la réponse
                </button>
            </form>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">📋 Messages du forum ({{ $messages->total() }})</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Message</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr class="{{ $message->user && $message->user->is_forum_admin ? 'bg-green-50' : '' }}">
                            <td class="whitespace-nowrap">
                                {{ $message->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                        <i class="mdi mdi-account text-gray-500"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $message->user ? $message->user->name : 'Utilisateur supprimé' }}</div>
                                        <div class="text-sm text-gray-500">{{ $message->user ? $message->user->email : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($message->user && $message->user->is_forum_admin)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="mdi mdi-shield-check"></i> ADMIN
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="mdi mdi-school"></i> ÉTUDIANT
                                    </span>
                                @endif
                            </td>
                            <td class="max-w-md">
                                <div class="text-gray-900">{{ Str::limit($message->content, 150) }}</div>
                            </td>
                            <td class="text-right whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.forum.destroy', $message->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <i class="mdi mdi-message-off text-4xl text-gray-400"></i>
                                <p class="mt-2 text-gray-600">Aucun message pour le moment</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($messages->hasPages())
        <div class="card-footer">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
