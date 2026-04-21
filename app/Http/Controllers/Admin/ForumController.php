<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumMessage;
use App\Models\User;
use App\Events\ForumMessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Afficher la liste des messages du forum
     */
    public function index(Request $request)
    {
        $query = ForumMessage::with('user');

        // Recherche par contenu ou nom d'utilisateur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par admin
        if ($request->filled('is_admin')) {
            if ($request->is_admin == '1') {
                $query->whereHas('user', function($q) {
                    $q->where('is_forum_admin', true);
                });
            } elseif ($request->is_admin == '0') {
                $query->whereHas('user', function($q) {
                    $q->where('is_forum_admin', false);
                });
            }
        }

        // Filtrer par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $messages = $query->orderBy('created_at', 'desc')->paginate(50);

        // Statistiques
        $stats = [
            'total_messages' => ForumMessage::count(),
            'total_users' => ForumMessage::distinct('user_id')->count(),
            'total_admins' => User::where('is_forum_admin', true)->count(),
            'messages_today' => ForumMessage::whereDate('created_at', today())->count(),
        ];

        return view('admin.forum.index', compact('messages', 'stats'));
    }

    /**
     * Répondre à un message (en tant qu'admin)
     */
    public function reply(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $message = ForumMessage::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $message->load('user');

        // Broadcast l'événement
        broadcast(new ForumMessageSent($message))->toOthers();

        return redirect()->route('admin.forum.index')
            ->with('success', 'Réponse envoyée avec succès !');
    }

    /**
     * Supprimer un message
     */
    public function destroy($id)
    {
        $message = ForumMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.forum.index')
            ->with('success', 'Message supprimé avec succès !');
    }

    /**
     * Gérer les administrateurs du forum
     */
    public function admins()
    {
        $admins = User::where('is_forum_admin', true)->get();
        $users = User::where('is_forum_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.forum.admins', compact('admins', 'users'));
    }

    /**
     * Ajouter un admin
     */
    public function addAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->is_forum_admin = true;
        $user->save();

        return redirect()->route('admin.forum.admins')
            ->with('success', "{$user->name} est maintenant administrateur du forum !");
    }

    /**
     * Retirer un admin
     */
    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->is_forum_admin = false;
        $user->save();

        return redirect()->route('admin.forum.admins')
            ->with('success', "{$user->name} n'est plus administrateur du forum.");
    }
}
