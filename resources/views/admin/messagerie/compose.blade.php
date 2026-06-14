@extends('admin.layouts.app')

@section('title', 'Estuaire Mail')

@section('breadcrumb')
    <a href="{{ route('admin.messagerie.index') }}" class="hover:underline">Estuaire Mail</a>
    <span>/</span>
    <span class="font-semibold">Composer</span>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <style>
        .mbx-wrap { height: calc(100vh - 7.5rem); }
        #editor { min-height: 220px; }
        .ql-editor { font-size: 14px; }
    </style>
@endpush

@section('content')
<div class="mbx-wrap flex gap-4" x-data="composer()">

    @include('admin.messagerie._rail', ['active' => 'compose'])

    {{-- Pane composer --}}
    <section class="mbx-anim flex-1 min-w-0 bg-white rounded-2xl shadow-sm flex flex-col overflow-hidden">
        <div class="flex items-center gap-3 p-4 border-b shrink-0">
            <i class="mdi mdi-email-edit-outline text-2xl text-primary"></i>
            <h2 class="font-semibold text-gray-900">Nouveau message</h2>
        </div>

        <form method="POST" action="{{ route('admin.messagerie.send') }}" enctype="multipart/form-data"
              class="flex-1 overflow-y-auto p-6 space-y-5"
              @submit.prevent="prepareAndSubmit($event)">
            @csrf

            {{-- Destinataires (multiple) + CC --}}
            <div class="relative">
                <input type="hidden" name="to_name" :value="(toList[0] && toList[0].name) || ''">

                <div class="flex items-center justify-between mb-1">
                    <label class="block text-sm font-medium text-gray-700">Destinataires (À)</label>
                    <button type="button" @click="showCc = !showCc" class="text-xs text-primary hover:underline">
                        <span x-show="!showCc">+ Ajouter Cc (copie)</span>
                        <span x-show="showCc">– Masquer Cc</span>
                    </button>
                </div>

                {{-- Recherche --}}
                <input type="text" x-model="recipientQuery" @input.debounce.300ms="searchRecipients()"
                       placeholder="Rechercher un candidat, recruteur, entreprise... ou taper une adresse"
                       @keydown.enter.prevent="addManual('to')"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none">
                <div x-show="recipientResults.length" class="absolute z-20 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-64 overflow-auto">
                    <template x-for="r in recipientResults" :key="r.email">
                        <div class="px-3 py-2 hover:bg-gray-50 flex items-center justify-between gap-2">
                            <span class="min-w-0 truncate">
                                <span class="font-medium text-gray-800" x-text="r.name"></span>
                                <span class="text-xs text-gray-400" x-text="r.email"></span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-600" x-text="r.type"></span>
                            </span>
                            <span class="flex gap-1 shrink-0">
                                <button type="button" @click="addRecipient(r,'to')" class="text-xs px-2 py-1 bg-primary/10 text-primary rounded">+ À</button>
                                <button type="button" @click="addRecipient(r,'cc')" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">+ Cc</button>
                            </span>
                        </div>
                    </template>
                </div>

                {{-- Astuce ajout manuel --}}
                <div class="mt-1 text-[11px] text-gray-400">Entrée pour ajouter l'adresse tapée. Astuce : « + Cc » pour mettre en copie.</div>

                {{-- Puces À --}}
                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="(p,i) in toList" :key="'to'+p.email">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-full text-xs">
                            <i class="mdi mdi-account"></i>
                            <span x-text="p.name ? (p.name + ' <' + p.email + '>') : p.email"></span>
                            <button type="button" @click="toList.splice(i,1)"><i class="mdi mdi-close"></i></button>
                            <input type="hidden" name="to[]" :value="p.email">
                        </span>
                    </template>
                    <span x-show="!toList.length" class="text-xs text-gray-400 py-1">Aucun destinataire pour l'instant.</span>
                </div>

                {{-- Puces Cc --}}
                <div x-show="showCc" x-cloak class="mt-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1">En copie (Cc)</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(p,i) in ccList" :key="'cc'+p.email">
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-full text-xs">
                                <i class="mdi mdi-content-copy"></i>
                                <span x-text="p.name ? (p.name + ' <' + p.email + '>') : p.email"></span>
                                <button type="button" @click="ccList.splice(i,1)"><i class="mdi mdi-close"></i></button>
                                <input type="hidden" name="cc[]" :value="p.email">
                            </span>
                        </template>
                        <span x-show="!ccList.length" class="text-xs text-gray-400 py-1">Personne en copie.</span>
                    </div>
                </div>
            </div>

            {{-- Templates --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end bg-gray-50 p-3 rounded-lg">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modèle</label>
                    <select x-model="templateKey" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">— Choisir un modèle —</option>
                        @foreach($templates as $key => $tpl)
                            <option value="{{ $key }}">{{ $tpl['label']['fr'] }} / {{ $tpl['label']['en'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Langue</label>
                    <div class="flex gap-1">
                        <select x-model="templateLocale" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="fr">Français</option>
                            <option value="en">English</option>
                        </select>
                        <button type="button" @click="loadTemplate()" class="px-3 py-2 bg-primary text-white rounded-lg text-sm">Appliquer</button>
                    </div>
                </div>
            </div>

            {{-- Objet --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Objet</label>
                <input type="text" name="subject" id="subject" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none">
            </div>

            {{-- Corps --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <div id="editor"></div>
                <input type="hidden" name="body_html" id="body_html">
            </div>

            {{-- CV à joindre --}}
            <div class="relative">
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="mdi mdi-file-account"></i> Joindre un CV</label>
                <input type="text" x-model="cvQuery" @input.debounce.300ms="searchCvs()" @focus="searchCvs()"
                       placeholder="Rechercher un CV par nom de candidat ou titre..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <div x-show="cvResults.length" class="absolute z-20 mt-1 w-full bg-white border rounded-lg shadow-lg max-h-64 overflow-auto">
                    <template x-for="c in cvResults" :key="c.id">
                        <button type="button" @click="addCv(c)" class="w-full text-left px-3 py-2 hover:bg-gray-50 flex items-center justify-between">
                            <span>
                                <span class="font-medium text-gray-800" x-text="c.candidate"></span>
                                <span class="text-xs text-gray-400" x-text="' — ' + c.title"></span>
                            </span>
                            <i class="mdi mdi-plus text-primary"></i>
                        </button>
                    </template>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    <template x-for="cv in selectedCvs" :key="cv.id">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary/10 text-primary rounded-lg text-xs">
                            <i class="mdi mdi-file-pdf-box"></i>
                            <span x-text="cv.candidate + ' (' + cv.title + ')'"></span>
                            <a :href="cv.url" target="_blank" class="hover:underline"><i class="mdi mdi-eye"></i></a>
                            <button type="button" @click="removeCv(cv.id)"><i class="mdi mdi-close"></i></button>
                            <input type="hidden" name="cv_ids[]" :value="cv.id">
                        </span>
                    </template>
                </div>
            </div>

            {{-- Fichiers --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="mdi mdi-paperclip"></i> Autres pièces jointes</label>
                <input type="file" name="attachments[]" multiple
                       class="block w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-gray-100 file:text-gray-700">
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t">
                <a href="{{ route('admin.messagerie.index') }}" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700">Annuler</a>
                <button type="submit" class="mbx-btn px-6 py-2 bg-primary text-white rounded-lg"><i class="mdi mdi-send"></i> Envoyer</button>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window.composeQuill = new Quill('#editor', {
            theme: 'snow',
            modules: { toolbar: [['bold','italic','underline'], [{list:'ordered'},{list:'bullet'}], ['link'], ['clean']] }
        });
    });

    function composer() {
        return {
            recipientQuery: '', recipientResults: [],
            toList: [], ccList: [], showCc: false,
            cvQuery: '', cvResults: [], selectedCvs: [],
            templateKey: '', templateLocale: 'fr',

            searchRecipients() {
                if (this.recipientQuery.length < 2) { this.recipientResults = []; return; }
                fetch(`{{ route('admin.messagerie.search.recipients') }}?q=` + encodeURIComponent(this.recipientQuery))
                    .then(r => r.json()).then(d => this.recipientResults = d);
            },
            addRecipient(r, target) {
                const list = target === 'cc' ? this.ccList : this.toList;
                if (!list.find(x => x.email === r.email)) list.push({ email: r.email, name: r.name || '', type: r.type || '' });
                if (target === 'cc') this.showCc = true;
                this.recipientResults = []; this.recipientQuery = '';
            },
            addManual(target) {
                const email = (this.recipientQuery || '').trim();
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return;
                this.addRecipient({ email: email, name: '', type: '' }, target);
            },
            prepareAndSubmit(event) {
                const pendingEmail = (this.recipientQuery || "").trim();
                if (pendingEmail && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(pendingEmail)) {
                    this.addRecipient({ email: pendingEmail, name: "", type: "" }, "to");
                }

                document.getElementById("body_html").value = window.composeQuill.root.innerHTML;

                requestAnimationFrame(() => event.target.submit());
            },
            searchCvs() {
                fetch(`{{ route('admin.messagerie.search.cvs') }}?q=` + encodeURIComponent(this.cvQuery))
                    .then(r => r.json()).then(d => this.cvResults = d);
            },
            addCv(c) {
                if (!this.selectedCvs.find(x => x.id === c.id)) this.selectedCvs.push(c);
                this.cvResults = []; this.cvQuery = '';
            },
            removeCv(id) { this.selectedCvs = this.selectedCvs.filter(x => x.id !== id); },
            loadTemplate() {
                if (!this.templateKey) return;
                const first = this.toList[0] || {};
                const isCompany = first.type === 'Entreprise';
                const p = new URLSearchParams({
                    key: this.templateKey, locale: this.templateLocale,
                    recipient_name: first.name || '',
                    candidate_name: isCompany ? '' : (first.name || ''),
                    company_name: isCompany ? (first.name || '') : ''
                });
                fetch(`{{ route('admin.messagerie.template') }}?` + p.toString())
                    .then(r => r.json()).then(d => {
                        document.getElementById('subject').value = d.subject || '';
                        window.composeQuill.setContents([]);
                        window.composeQuill.clipboard.dangerouslyPasteHTML(d.body || '');
                    });
            }
        }
    }
</script>
@endpush
