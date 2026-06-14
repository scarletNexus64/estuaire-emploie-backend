<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\MailboxMessage;
use App\Models\MailboxThread;
use App\Models\Resume;
use App\Models\User;
use App\Services\MailboxService;
use App\Services\Resume\ResumePdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MessagerieController extends Controller
{
    public function __construct(
        protected MailboxService $mailbox,
        protected ResumePdfService $cvPdf,
    ) {}

    /**
     * Boîte de réception : liste des conversations.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all | unread | sent | trash

        $threads = MailboxThread::query()
            ->when($filter === 'trash', fn ($q) => $q->onlyTrashed())
            ->when($request->search, function ($q) use ($request) {
                $s = $request->search;
                $q->where(function ($qq) use ($s) {
                    $qq->where('contact_email', 'like', "%{$s}%")
                        ->orWhere('contact_name', 'like', "%{$s}%")
                        ->orWhere('subject', 'like', "%{$s}%");
                });
            })
            ->when($filter === 'unread', fn ($q) => $q->where('unread_count', '>', 0))
            ->when($filter === 'sent', fn ($q) => $q->whereHas('messages', fn ($m) => $m->where('direction', 'outbound')))
            ->withCount('messages')
            ->with(['latestMessage' => fn ($q) => $q->limit(1)])
            ->orderByDesc('last_message_at')
            ->paginate(25)
            ->withQueryString();

        $stats = [
            'total'  => MailboxThread::count(),
            'unread' => MailboxMessage::where('direction', 'inbound')->where('is_read', false)->whereHas('thread')->count(),
            'open'   => MailboxThread::where('status', 'open')->count(),
            'trash'  => MailboxThread::onlyTrashed()->count(),
        ];

        return view('admin.messagerie.index', compact('threads', 'stats', 'filter'));
    }

    /**
     * Action groupée : corbeille (delete = soft), restaurer, ou suppression définitive.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'action' => 'required|in:delete,restore,force',
            'ids'    => 'required|array',
            'ids.*'  => 'integer',
        ]);

        $count = count($data['ids']);

        switch ($data['action']) {
            case 'delete':
                MailboxThread::whereIn('id', $data['ids'])->delete(); // soft -> corbeille
                $msg = "{$count} conversation(s) déplacée(s) vers la corbeille.";
                break;
            case 'restore':
                MailboxThread::onlyTrashed()->whereIn('id', $data['ids'])->restore();
                $msg = "{$count} conversation(s) restaurée(s).";
                break;
            case 'force':
                MailboxThread::withTrashed()->whereIn('id', $data['ids'])->forceDelete();
                $msg = "{$count} conversation(s) supprimée(s) définitivement.";
                break;
        }

        if ($request->boolean('to_index')) {
            return redirect()->route('admin.messagerie.index')->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Affiche une conversation et marque les entrants comme lus.
     */
    public function show(MailboxThread $thread)
    {
        $thread->load(['messages.attachments', 'assignedAdmin']);

        $thread->messages()->where('direction', 'inbound')->where('is_read', false)->update(['is_read' => true]);
        $thread->update(['unread_count' => 0]);

        return view('admin.messagerie.show', compact('thread'));
    }

    /**
     * Formulaire de composition d'un nouveau message.
     */
    public function compose()
    {
        $templates = config('mail_templates.templates', []);

        return view('admin.messagerie.compose', compact('templates'));
    }

    /**
     * Envoi d'un nouveau message (ou réponse si thread_id fourni).
     */
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'to'              => 'nullable|array',
            'to.*'            => 'email',
            'to_email'        => 'nullable|email',      // envoi simple / réponse
            'cc'              => 'nullable|array',
            'cc.*'            => 'email',
            'to_name'         => 'nullable|string|max:255',
            'subject'         => 'required|string|max:255',
            'body_html'       => 'required|string',
            'thread_id'       => 'nullable|exists:mailbox_threads,id',
            'attachments.*'   => 'nullable|file|max:10240', // 10 Mo / fichier
            'cv_ids'          => 'nullable|array',
            'cv_ids.*'        => 'integer|exists:resumes,id',
        ]);

        $toList = array_values(array_filter($validated['to'] ?? array_filter([$validated['to_email'] ?? null])));
        $ccList = array_values(array_filter($validated['cc'] ?? []));

        if (empty($toList)) {
            return back()->withInput()->with('error', 'Veuillez indiquer au moins un destinataire.');
        }

        $attachmentPaths = $this->collectAttachments($request);

        try {
            $this->mailbox->send([
                'to'          => $toList,
                'cc'          => $ccList,
                'to_name'     => $validated['to_name'] ?? null,
                'subject'     => $validated['subject'],
                'body_html'   => $validated['body_html'],
                'thread_id'   => $validated['thread_id'] ?? null,
                'attachments' => $attachmentPaths,
                'admin_id'    => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            Log::error('[Messagerie] Echec envoi', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', "Échec de l'envoi : " . $e->getMessage());
        }

        if (!empty($validated['thread_id'])) {
            return redirect()->route('admin.messagerie.show', $validated['thread_id'])
                ->with('success', 'Réponse envoyée.');
        }

        $count = count($toList) + count($ccList);

        return redirect()->route('admin.messagerie.index')
            ->with('success', 'Message envoyé à ' . $count . ' destinataire(s).');
    }

    /**
     * Récupère immédiatement les nouveaux mails (bouton "Actualiser").
     */
    public function fetchNow(): RedirectResponse
    {
        try {
            $count = $this->mailbox->syncInbox();

            return back()->with('success', "{$count} nouveau(x) message(s) récupéré(s).");
        } catch (\Throwable $e) {
            Log::error('[Messagerie] Echec fetch', ['error' => $e->getMessage()]);

            return back()->with('error', 'Impossible de relever la boîte : ' . $e->getMessage());
        }
    }

    /**
     * Recherche AJAX de destinataires (candidats, recruteurs, entreprises).
     */
    public function searchRecipients(Request $request): JsonResponse
    {
        $s = trim((string) $request->get('q', ''));
        if (mb_strlen($s) < 2) {
            return response()->json([]);
        }

        $users = User::whereIn('role', ['candidate', 'recruiter'])
            ->whereNotNull('email')
            ->where(fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->limit(10)
            ->get(['id', 'name', 'email', 'role'])
            ->map(fn ($u) => [
                'type'  => $u->role === 'recruiter' ? 'Recruteur' : 'Candidat',
                'name'  => $u->name,
                'email' => $u->email,
            ]);

        $companies = Company::whereNotNull('email')
            ->where(fn ($q) => $q->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"))
            ->limit(10)
            ->get(['id', 'name', 'email'])
            ->map(fn ($c) => [
                'type'  => 'Entreprise',
                'name'  => $c->name,
                'email' => $c->email,
            ]);

        return response()->json($users->concat($companies)->values());
    }

    /**
     * Recherche AJAX de CV à joindre (uniquement ceux ayant un PDF disponible).
     */
    public function searchCvs(Request $request): JsonResponse
    {
        $s = trim((string) $request->get('q', ''));

        $resumes = Resume::with('user')
            ->whereNotNull('pdf_path')
            ->when($s !== '', function ($q) use ($s) {
                $q->where(function ($qq) use ($s) {
                    $qq->where('title', 'like', "%{$s}%")
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
                });
            })
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn ($r) => [
                'id'        => $r->id,
                'title'     => $r->title ?: 'CV',
                'candidate' => $r->user?->name ?? '—',
                'email'     => $r->user?->email,
                // Aperçu : version corrigée rendue à la volée (le fichier stocké n'est pas modifié)
                'url'       => route('admin.messagerie.cv.pdf', $r->id),
            ]);

        return response()->json($resumes);
    }

    /**
     * Aperçu / téléchargement d'un CV avec le template CORRIGÉ, rendu à la volée.
     * Ne modifie pas le fichier stocké dans la bibliothèque.
     */
    public function cvPdf(Resume $resume)
    {
        try {
            $bytes = $this->cvPdf->renderPdfBytes($resume);
        } catch (\Throwable $e) {
            Log::error('[Messagerie] Rendu CV échoué', ['resume' => $resume->id, 'error' => $e->getMessage()]);
            abort(404, 'CV indisponible');
        }

        $name = 'CV_' . Str::slug($resume->user?->name ?? 'candidat') . '_' . $resume->id . '.pdf';

        return response($bytes, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $name . '"',
        ]);
    }

    /**
     * Retourne le contenu d'un template (objet + corps) dans la langue choisie,
     * en remplaçant les variables connues.
     */
    public function template(Request $request): JsonResponse
    {
        $key    = $request->get('key');
        $locale = $request->get('locale', 'fr') === 'en' ? 'en' : 'fr';

        $tpl = config("mail_templates.templates.{$key}.{$locale}");
        if (!$tpl) {
            return response()->json(['subject' => '', 'body' => '']);
        }

        $replacements = [
            '{{recipient_name}}' => $request->get('recipient_name', '{{recipient_name}}'),
            '{{candidate_name}}' => $request->get('candidate_name', '{{candidate_name}}'),
            '{{company_name}}'   => $request->get('company_name', '{{company_name}}'),
            '{{position}}'       => $request->get('position', '{{position}}'),
            '{{admin_name}}'     => auth()->user()->name ?? '{{admin_name}}',
            '{{date}}'           => now()->translatedFormat($locale === 'en' ? 'F j, Y' : 'd F Y'),
        ];

        return response()->json([
            'subject' => strtr($tpl['subject'], $replacements),
            'body'    => strtr($tpl['body'], $replacements),
        ]);
    }

    /**
     * Change le statut d'une conversation (open / pending / closed).
     */
    public function updateStatus(Request $request, MailboxThread $thread): RedirectResponse
    {
        $validated = $request->validate(['status' => 'required|in:open,pending,closed']);
        $thread->update(['status' => $validated['status']]);

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * Rassemble les pièces jointes : fichiers uploadés + CV sélectionnés.
     * Retourne des chemins relatifs au disque "public".
     */
    protected function collectAttachments(Request $request): array
    {
        $paths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('mailbox_attachments/sent/' . date('Y/m'), 'public');
            }
        }

        foreach ((array) $request->input('cv_ids', []) as $cvId) {
            $resume = Resume::with('user')->find($cvId);
            if (!$resume) {
                continue;
            }

            $name = 'CV_' . Str::slug($resume->user?->name ?? 'candidat') . '_' . $resume->id . '.pdf';
            $path = 'mailbox_attachments/cv/' . date('Y/m') . '/' . Str::random(6) . '_' . $name;

            try {
                // Version CORRIGÉE rendue à la volée pour l'envoi (le fichier de la bibliothèque n'est pas modifié)
                Storage::disk('public')->put($path, $this->cvPdf->renderPdfBytes($resume));
                $paths[] = $path;
            } catch (\Throwable $e) {
                Log::error('[Messagerie] Rendu CV (PJ) échoué, repli sur le fichier stocké', ['resume' => $resume->id, 'error' => $e->getMessage()]);
                if ($resume->pdf_path) {
                    $relative = ltrim(Str::after($resume->pdf_path, 'public/'), '/');
                    if (Storage::disk('public')->exists($relative)) {
                        $paths[] = $relative;
                    }
                }
            }
        }

        return $paths;
    }
}
