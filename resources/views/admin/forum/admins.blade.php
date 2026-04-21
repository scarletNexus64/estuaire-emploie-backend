@extends('admin.layouts.app')

@section('title', 'Administrateurs du Forum')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🛡️ Administrateurs du Forum</h1>
            <p class="text-gray-600 mt-1">Gérer les formateurs qui peuvent répondre aux questions</p>
        </div>
        <a href="{{ route('admin.forum.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i>
            Retour au Forum
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Liste des admins actuels -->
        <div class="card">
            <div class="card-header bg-green-50">
                <h3 class="card-title text-green-800">
                    <i class="mdi mdi-shield-check"></i>
                    Admins Actuels ({{ $admins->count() }})
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                            <tr>
                                <td>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                            <i class="mdi mdi-account text-green-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium">{{ $admin->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">
                                    <form method="POST" action="{{ route('admin.forum.remove-admin', $admin->id) }}" class="inline" onsubmit="return confirm('Retirer cet administrateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="mdi mdi-close-circle"></i> Retirer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-8">
                                    <i class="mdi mdi-account-off text-4xl text-gray-400"></i>
                                    <p class="mt-2 text-gray-600">Aucun administrateur pour le moment</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ajouter un nouvel admin -->
        <div class="card">
            <div class="card-header bg-blue-50">
                <h3 class="card-title text-blue-800">
                    <i class="mdi mdi-account-plus"></i>
                    Ajouter un Administrateur
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.forum.add-admin') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner un utilisateur
                        </label>
                        <select name="user_id" class="form-control" required>
                            <option value="">-- Choisir un utilisateur --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-full">
                        <i class="mdi mdi-shield-plus"></i>
                        Ajouter comme Admin
                    </button>
                </form>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">
                        <i class="mdi mdi-information"></i> Privilèges des Admins
                    </h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>✅ Répondre aux questions des étudiants</li>
                        <li>✅ Badge "ADMIN" sur leurs messages</li>
                        <li>✅ Notifications FCM pour nouveaux messages</li>
                        <li>✅ Répondre depuis l'application mobile</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations supplémentaires -->
    <div class="mt-6 card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="mdi mdi-help-circle"></i>
                Comment fonctionne le Forum ?
            </h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                        <i class="mdi mdi-account-school text-3xl text-blue-600"></i>
                    </div>
                    <h4 class="font-semibold mb-2">Étudiants</h4>
                    <p class="text-sm text-gray-600">
                        Posent leurs questions depuis l'app mobile dans le Forum
                    </p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                        <i class="mdi mdi-shield-account text-3xl text-green-600"></i>
                    </div>
                    <h4 class="font-semibold mb-2">Admins</h4>
                    <p class="text-sm text-gray-600">
                        Reçoivent une notification et répondent via l'app ou le dashboard
                    </p>
                </div>

                <div class="text-center p-4">
                    <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                        <i class="mdi mdi-message-reply text-3xl text-purple-600"></i>
                    </div>
                    <h4 class="font-semibold mb-2">Temps Réel</h4>
                    <p class="text-sm text-gray-600">
                        Tous les messages sont synchronisés en temps réel via WebSocket
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
