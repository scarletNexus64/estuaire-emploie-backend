<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b0e14">
    <meta name="color-scheme" content="dark">
    <title>@yield('title', 'Dashboard') · Estuaire Emploi Admin</title>

    <!-- Fonts : Inter + Noto Emoji (emojis monochromes) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Emoji:wght@400;500&display=swap">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Material Design Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Noto Emoji', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                    },
                    colors: {
                        // Palette dark : un seul bleu primaire, pas de couleurs vives
                        primary: { DEFAULT: '#3f72d6', dark: '#3463c0', light: '#4a7fe0' },
                        secondary: { DEFAULT: '#3463c0', dark: '#2c559f', light: '#4a7fe0' },
                        tertiary: { DEFAULT: '#b94a4a', dark: '#a54141', light: '#c75656' },
                        accent: '#a5781f',
                    }
                }
            }
        }

        // Sections du menu repliables (état mémorisé par navigateur)
        // Repliées par défaut, sauf la section de la page active (ou celles ouvertes par l'utilisateur)
        function navSection(key, hasActive, names) {
            let saved = null;
            try { saved = localStorage.getItem('admin-nav:' + key); } catch (e) {}
            return {
                open: hasActive || saved === '1',
                names: names.map(navNormalize),
                toggle() {
                    this.open = !this.open;
                    try { localStorage.setItem('admin-nav:' + key, this.open ? '1' : '0'); } catch (e) {}
                },
                matches(q) {
                    const n = navNormalize(q);
                    return !n || this.names.some(name => name.includes(n));
                }
            };
        }

        function navNormalize(s) {
            return String(s || '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();
        }

        // Recherche dans le menu : ⌘K / Ctrl+K pour focus, Entrée ouvre le premier résultat
        function navSearch(all) {
            return {
                q: '',
                all: all,
                hit(name) {
                    const n = navNormalize(this.q);
                    return !n || navNormalize(name).includes(n);
                },
                first() {
                    const link = [...this.$root.querySelectorAll('.ad-nav-link')].find(a => a.offsetParent !== null);
                    if (link) link.click();
                },
                focus() {
                    this.sidebarOpen = true;
                    this.$nextTick(() => this.$refs.navq && this.$refs.navq.focus());
                }
            };
        }
    </script>

    @php
        $themeVersion = @filemtime(public_path('admin-theme/theme.css')) . '-' . @filemtime(public_path('admin-theme/tailwind-dark.css'));
    @endphp
    <link rel="stylesheet" href="{{ asset('admin-theme/theme.css') }}?v={{ $themeVersion }}">
    <link rel="stylesheet" href="{{ asset('admin-theme/tailwind-dark.css') }}?v={{ $themeVersion }}">

    <style data-theme-skip>
        [x-cloak] { display: none !important; }

        /* Classes de marque Tailwind en texte : versions lisibles sur fond sombre */
        .text-primary, .text-secondary { color: var(--primary) !important; }
        .text-primary-dark, .text-secondary-dark,
        .hover\:text-primary-dark:hover, .hover\:text-secondary-dark:hover,
        .hover\:text-primary:hover { color: var(--primary-strong) !important; }
        .text-tertiary { color: var(--danger) !important; }

        /* Sidebar mobile */
        .ad-sidebar { transition: transform .2s ease; }
        @media (max-width: 1023.98px) {
            .ad-sidebar { transform: translateX(-100%); }
            .ad-sidebar.is-open { transform: none; box-shadow: 0 0 0 100vmax rgb(0 0 0 / 0.55); }
        }
        @media (min-width: 1024px) {
            .ad-main { padding-left: var(--sidebar-w); }
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen antialiased" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">

    @php
        $menuItems = \App\Services\NavigationService::getFilteredMenuItems(auth()->user());
        $currentRoute = request()->route()?->getName() ?? '';

        $iconMapping = [
            'fas fa-tachometer-alt' => 'mdi-view-dashboard-outline',
            'fas fa-building' => 'mdi-office-building-outline',
            'fas fa-briefcase' => 'mdi-briefcase-outline',
            'fas fa-file-alt' => 'mdi-file-document-outline',
            'fas fa-users' => 'mdi-account-group-outline',
            'fas fa-user-graduate' => 'mdi-school-outline',
            'fas fa-user-tie' => 'mdi-account-tie-outline',
            'fas fa-tools' => 'mdi-tools',
            'fas fa-clipboard-check' => 'mdi-clipboard-check-outline',
            'fas fa-id-card' => 'mdi-card-account-details-outline',
            'fas fa-box' => 'mdi-package-variant-closed',
            'fas fa-graduation-cap' => 'mdi-school-outline',
            'fas fa-video' => 'mdi-video-outline',
            'fas fa-bullhorn' => 'mdi-bullhorn-outline',
            'fas fa-tags' => 'mdi-tag-multiple-outline',
            'fas fa-crown' => 'mdi-crown-outline',
            'fas fa-credit-card' => 'mdi-credit-card-outline',
            'fas fa-wallet' => 'mdi-wallet-outline',
            'fas fa-globe-africa' => 'mdi-earth',
            'fas fa-university' => 'mdi-bank-outline',
            'fas fa-star' => 'mdi-star-outline',
            'fas fa-puzzle-piece' => 'mdi-puzzle-outline',
            'fas fa-file-pdf' => 'mdi-file-pdf-box',
            'fas fa-ad' => 'mdi-advertisements',
            'fas fa-chart-line' => 'mdi-chart-line',
            'fas fa-book' => 'mdi-book-open-outline',
            'fas fa-list' => 'mdi-format-list-bulleted',
            'fas fa-user-shield' => 'mdi-shield-account-outline',
            'fas fa-bell' => 'mdi-bell-outline',
            'fas fa-mobile' => 'mdi-cellphone',
            'fas fa-mobile-alt' => 'mdi-cellphone-link',
            'fas fa-cog' => 'mdi-cog-outline',
            'fas fa-wrench' => 'mdi-wrench-outline',
            'fas fa-envelope' => 'mdi-email-outline',
            'fas fa-map-signs' => 'mdi-map-marker-path',
        ];

        // Lien actif : route exacte, ou sous-route directe (create/edit/show...) d'une route .index
        $isActiveItem = function ($item) use ($currentRoute) {
            if (! isset($item['route'])) {
                return false;
            }
            if (request()->routeIs($item['route'])) {
                return true;
            }
            if (str_ends_with($item['route'], '.index')) {
                $baseRoute = substr($item['route'], 0, -strlen('.index'));
                if (str_starts_with($currentRoute, $baseRoute . '.')) {
                    $suffix = substr($currentRoute, strlen($baseRoute) + 1);
                    return in_array($suffix, ['create', 'edit', 'show', 'update', 'destroy', 'toggle']);
                }
            }
            return false;
        };
    @endphp

    <!-- Sidebar -->
    <aside class="ad-sidebar fixed inset-y-0 left-0 z-50 flex flex-col" :class="{ 'is-open': sidebarOpen }" aria-label="Navigation principale"
           x-data="navSearch(@js(collect($menuItems)->flatMap(fn ($sec) => collect($sec['items'])->map(fn ($i) => $i['name'] . ' ' . $sec['section']))->values()))" @keydown.window.meta.k.prevent="focus()" @keydown.window.ctrl.k.prevent="focus()">
        <!-- Marque -->
        <div class="ad-brand flex items-center justify-between gap-3 px-4 flex-shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0" style="text-decoration:none">
                <span class="ad-brand-mark"><img src="{{ asset('images/logo-estuaire-emploi.png') }}" alt=""></span>
                <span class="min-w-0">
                    <span class="block text-[14px] font-semibold leading-tight" style="color:var(--text)">Estuaire Emploi</span>
                    <span class="block text-[11.5px] leading-tight" style="color:var(--subtle)">Administration</span>
                </span>
            </a>
            <button type="button" class="ad-icon-btn lg:hidden" @click="sidebarOpen = false" aria-label="Fermer le menu">
                <i class="mdi mdi-close text-lg"></i>
            </button>
        </div>

        <!-- Recherche dans le menu -->
        <div class="px-3 pt-3 pb-1 flex-shrink-0">
            <label class="ad-nav-search">
                <i class="mdi mdi-magnify"></i>
                <input type="search" x-ref="navq" x-model="q" placeholder="Rechercher…" aria-label="Rechercher dans le menu"
                       @keydown.enter.prevent="first()" @keydown.escape.stop="q = ''; $el.blur()">
                <kbd x-show="!q">⌘K</kbd>
            </label>
        </div>

        <!-- Navigation -->
        <nav id="ad-nav" class="flex-1 overflow-y-auto px-3 pb-6">

            @foreach($menuItems as $section)
                @php
                    $sectionHasActive = collect($section['items'])->contains(fn ($i) => $isActiveItem($i));
                    $sectionKey = \Illuminate\Support\Str::slug($section['section']);
                @endphp
                <div x-data="navSection('{{ $sectionKey }}', {{ $sectionHasActive ? 'true' : 'false' }}, @js(collect($section['items'])->pluck('name')->push($section['section'])->values()))"
                     x-show="matches(q)">
                    <button type="button" class="ad-section-btn" @click="toggle()" :aria-expanded="open || !!q">
                        <span>{{ $section['section'] }}</span>
                        <i class="mdi mdi-chevron-down" :style="(open || q) ? '' : 'transform: rotate(-90deg)'"></i>
                    </button>
                    <div x-show="open || q">
                        @foreach($section['items'] as $item)
                            @php
                                $isActive = $isActiveItem($item);
                                $mdiIcon = $iconMapping[$item['icon']] ?? 'mdi-circle-small';
                                $route = $item['route'] ?? '';
                                $count = null;
                                $countClass = '';
                                try {
                                    if ($route === 'admin.company-recoveries.index') {
                                        $count = \App\Models\CompanyRecoveryRequest::where('status', 'pending')->count();
                                        $countClass = 'is-warn';
                                    } elseif ($route === 'admin.applications.index' && isset($pendingApplications)) {
                                        $count = $pendingApplications;
                                        $countClass = 'is-alert';
                                    } elseif ($route === 'admin.messagerie.index' && class_exists('\App\Models\MailboxMessage')) {
                                        $count = \App\Models\MailboxMessage::where('direction', 'inbound')->where('is_read', false)->count();
                                        $countClass = 'is-alert';
                                    } elseif ($route === 'admin.device-change-requests.index' && class_exists('\App\Models\DeviceChangeRequest')) {
                                        $count = \App\Models\DeviceChangeRequest::where('status', 'pending')->count();
                                        $countClass = 'is-warn';
                                    } elseif ($route === 'admin.subscriptions.index' && class_exists('\App\Models\Subscription')) {
                                        $count = \App\Models\Subscription::where('status', 'active')->count();
                                    }
                                } catch (\Throwable $e) {
                                    $count = null;
                                }
                            @endphp
                            <a href="{{ isset($item['url']) ? $item['url'] : route($item['route']) }}"
                               x-show="hit(@js($item['name'] . ' ' . $section['section']))"
                               class="ad-nav-link {{ $isActive ? 'is-active' : '' }}"
                               @if($isActive) aria-current="page" @endif
                               @if(isset($item['external']) && $item['external']) target="_blank" rel="noopener" @endif>
                                <i class="mdi {{ $mdiIcon }}"></i>
                                <span class="flex-1 truncate">{{ $item['name'] }}</span>
                                @if($count)
                                    <span class="ad-count {{ $countClass }}">{{ $count > 99 ? '99+' : $count }}</span>
                                @endif
                                @if(isset($item['external']) && $item['external'])
                                    <i class="mdi mdi-open-in-new" style="font-size:13px"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <p x-show="q && !all.some(n => hit(n))" x-cloak class="px-3 py-6 text-[13px]" style="color:var(--subtle)">Aucun résultat</p>
        </nav>

        <!-- Compte -->
        <div class="flex items-center gap-3 px-4 py-3 flex-shrink-0" style="border-top:1px solid var(--border)">
            <span class="ad-avatar flex-shrink-0">{{ strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-medium truncate m-0" style="color:var(--text)">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-[11.5px] truncate m-0" style="color:var(--subtle)">{{ auth()->user()->email ?? '' }}</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="ad-icon-btn" title="Déconnexion" aria-label="Déconnexion">
                    <i class="mdi mdi-logout text-lg"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Fond mobile -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 lg:hidden" aria-hidden="true"
         x-transition:enter="transition-opacity duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <!-- Contenu -->
    <div class="ad-main min-h-screen flex flex-col">
        <header class="ad-header sticky top-0 z-30 flex-shrink-0">
            <div class="h-full flex items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 min-w-0">
                    <button type="button" @click="sidebarOpen = true" class="ad-icon-btn lg:hidden -ml-1" aria-label="Ouvrir le menu">
                        <i class="mdi mdi-menu text-xl"></i>
                    </button>
                    <div class="min-w-0">
                        <h1 class="text-[15px] sm:text-base font-semibold leading-tight truncate m-0" style="color:var(--text)">@yield('title', 'Dashboard')</h1>
                        @hasSection('breadcrumb')
                            <div class="ad-breadcrumb hidden sm:flex items-center gap-1.5 mt-0.5 truncate">
                                @yield('breadcrumb')
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    @hasSection('header-actions')
                        <div class="hidden md:flex items-center gap-2">
                            @yield('header-actions')
                        </div>
                    @endif

                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-lg px-1.5 py-1 hover:bg-white/5" :aria-expanded="open" aria-haspopup="menu">
                            <span class="ad-avatar">{{ strtoupper(mb_substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                            <i class="mdi mdi-chevron-down hidden sm:inline" style="color:var(--subtle)"></i>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="ad-menu absolute right-0 mt-2 w-60 p-1.5 z-50" role="menu">
                            <div class="px-3 py-2 mb-1" style="border-bottom:1px solid var(--border)">
                                <p class="text-[13px] font-medium truncate m-0" style="color:var(--text)">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[12px] truncate m-0" style="color:var(--subtle)">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('admin.profile') }}" class="ad-menu-item" role="menuitem"><i class="mdi mdi-account-circle-outline"></i> Mon profil</a>
                            <a href="{{ route('admin.settings.index') }}" class="ad-menu-item" role="menuitem"><i class="mdi mdi-cog-outline"></i> Paramètres</a>
                            <form method="POST" action="{{ route('admin.logout') }}" class="m-0 mt-1 pt-1" style="border-top:1px solid var(--border)">
                                @csrf
                                <button type="submit" class="ad-menu-item is-danger" role="menuitem"><i class="mdi mdi-logout"></i> Déconnexion</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 w-full max-w-[1600px] mx-auto px-4 py-5 sm:px-6 lg:px-8 lg:py-7">
            @hasSection('header-actions')
                <div class="md:hidden flex flex-wrap items-center gap-2 mb-4">
                    @yield('header-actions')
                </div>
            @endif

            @if (session('success'))
                <div class="ad-alert ad-alert-success" role="status">
                    <i class="mdi mdi-check-circle-outline"></i>
                    <div class="flex-1">{{ session('success') }}</div>
                    <button type="button" onclick="this.parentElement.remove()" class="ad-alert-close" aria-label="Fermer"><i class="mdi mdi-close"></i></button>
                </div>
            @endif

            @if (session('error'))
                <div class="ad-alert ad-alert-error" role="alert">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <div class="flex-1">{{ session('error') }}</div>
                    <button type="button" onclick="this.parentElement.remove()" class="ad-alert-close" aria-label="Fermer"><i class="mdi mdi-close"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div class="ad-alert ad-alert-error" role="alert">
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <div class="flex-1">
                        <strong class="font-semibold">Erreurs de validation</strong>
                        <ul class="mt-1.5 list-disc list-inside space-y-0.5" style="padding-left:0">
                            @foreach($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="this.closest('.ad-alert').remove()" class="ad-alert-close" aria-label="Fermer"><i class="mdi mdi-close"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Barre d'actions groupées -->
    <div id="bulkActionsToolbar" class="ad-menu fixed bottom-4 left-1/2 -translate-x-1/2 px-4 py-2.5 items-center gap-3 z-50 max-w-[calc(100vw-2rem)]" style="display: none;">
        <span class="text-sm font-medium whitespace-nowrap" style="color:var(--text-2)">
            <span id="selectedCount">0</span> sélectionné(s)
        </span>
        <button onclick="bulkActions.deselectAll()" class="ad-btn ad-btn-ghost">Désélectionner</button>
        <button onclick="bulkActions.confirmDelete()" class="ad-btn" style="background:var(--danger-solid);color:#fff">
            <i class="mdi mdi-delete-outline"></i> Supprimer
        </button>
    </div>

    <!-- Adaptation dark des couleurs codées en dur dans les anciennes pages -->
    <script src="{{ asset('admin-theme/adapter.js') }}?v={{ @filemtime(public_path('admin-theme/adapter.js')) }}"></script>

    <script>
        // Mémorise la position de scroll du menu
        (function () {
            const nav = document.getElementById('ad-nav');
            if (!nav) return;
            try {
                const saved = sessionStorage.getItem('sidebarScrollPosition');
                if (saved) nav.scrollTop = parseInt(saved, 10);
            } catch (e) {}
            let t;
            nav.addEventListener('scroll', function () {
                clearTimeout(t);
                t = setTimeout(() => { try { sessionStorage.setItem('sidebarScrollPosition', nav.scrollTop); } catch (e) {} }, 100);
            });
        })();

        // Bulk Actions Manager
        const bulkActions = {
            selectedIds: new Set(),
            toolbar: null,
            checkboxes: null,
            selectAllCheckbox: null,

            init() {
                this.toolbar = document.getElementById('bulkActionsToolbar');
                if (!this.toolbar) return;

                this.checkboxes = document.querySelectorAll('.row-checkbox');
                this.selectAllCheckbox = document.getElementById('selectAll');

                if (this.selectAllCheckbox) {
                    this.selectAllCheckbox.addEventListener('change', () => this.toggleAll());
                }

                this.checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', () => this.toggle(checkbox));
                });

                this.updateUI();
            },

            toggle(checkbox) {
                const id = checkbox.value;
                const row = checkbox.closest('tr');

                if (checkbox.checked) {
                    this.selectedIds.add(id);
                    row?.classList.add('bg-blue-50');
                } else {
                    this.selectedIds.delete(id);
                    row?.classList.remove('bg-blue-50');
                }

                this.updateUI();
            },

            toggleAll() {
                const isChecked = this.selectAllCheckbox.checked;

                this.checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                    const row = checkbox.closest('tr');

                    if (isChecked) {
                        this.selectedIds.add(checkbox.value);
                        row?.classList.add('bg-blue-50');
                    } else {
                        this.selectedIds.delete(checkbox.value);
                        row?.classList.remove('bg-blue-50');
                    }
                });

                this.updateUI();
            },

            deselectAll() {
                this.checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.closest('tr')?.classList.remove('bg-blue-50');
                });

                if (this.selectAllCheckbox) {
                    this.selectAllCheckbox.checked = false;
                }

                this.selectedIds.clear();
                this.updateUI();
            },

            updateUI() {
                const count = this.selectedIds.size;
                document.getElementById('selectedCount').textContent = count;

                if (count > 0) {
                    this.toolbar.style.display = 'flex';
                } else {
                    this.toolbar.style.display = 'none';
                }

                if (this.selectAllCheckbox && this.checkboxes.length > 0) {
                    const allChecked = Array.from(this.checkboxes).every(cb => cb.checked);
                    this.selectAllCheckbox.checked = allChecked;
                }
            },

            async confirmDelete() {
                if (this.selectedIds.size === 0) return;

                if (!confirm(`Êtes-vous sûr de vouloir supprimer ${this.selectedIds.size} élément(s) ?`)) {
                    return;
                }

                await this.bulkDelete();
            },

            async bulkDelete() {
                const form = document.getElementById('bulkDeleteForm');
                if (!form) {
                    console.error('Bulk delete form not found');
                    return;
                }

                const idsInput = document.createElement('input');
                idsInput.type = 'hidden';
                idsInput.name = 'ids';
                idsInput.value = JSON.stringify(Array.from(this.selectedIds));
                form.appendChild(idsInput);

                form.submit();
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            bulkActions.init();
        });

        // Bootstrap-like Tabs JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');

            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetPane = document.querySelector(targetId);

                    if (!targetPane) return;

                    const allTabs = this.closest('.nav-tabs').querySelectorAll('.nav-link');
                    const allPanes = targetPane.closest('.tab-content').querySelectorAll('.tab-pane');

                    allTabs.forEach(tab => tab.classList.remove('active'));
                    allPanes.forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });

                    this.classList.add('active');
                    targetPane.classList.add('show', 'active');
                });
            });
        });
    </script>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
