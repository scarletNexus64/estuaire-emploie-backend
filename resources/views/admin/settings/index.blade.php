@extends('admin.layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres du Système')

@section('content')
    <div class="card" style="overflow: hidden;">
        <!-- Tabs Navigation -->
        <div class="settings-tabs" role="tablist">
            <button
                id="tab-btn-jobs"
                class="tab-button active"
                onclick="switchSettingsTab('jobs')"
                type="button" role="tab"
            >
                <i class="mdi mdi-briefcase"></i> Configuration Emplois
                <span class="tab-count">4</span>
            </button>
            <button
                id="tab-btn-companies"
                class="tab-button"
                onclick="switchSettingsTab('companies')"
                type="button" role="tab"
            >
                <i class="mdi mdi-domain"></i> Catégories Entreprises
                <span class="tab-count">{{ $companyCategories->total() }}</span>
            </button>
            <button
                id="tab-btn-academic"
                class="tab-button"
                onclick="switchSettingsTab('academic')"
                type="button" role="tab"
            >
                <i class="mdi mdi-school"></i> Configuration Académique
                <span class="tab-count">2</span>
            </button>
            <button
                id="tab-btn-referral"
                class="tab-button"
                onclick="switchSettingsTab('referral')"
                type="button" role="tab"
            >
                <i class="mdi mdi-account-multiple"></i> Parrainage
                <span class="tab-count">3</span>
            </button>
            <button
                id="tab-btn-wallet"
                class="tab-button"
                onclick="switchSettingsTab('wallet')"
                type="button" role="tab"
            >
                <i class="mdi mdi-wallet"></i> Wallet
                <span class="tab-count">1</span>
            </button>
        </div>

        <!-- Tab Content Container -->
        <div style="position: relative; overflow: hidden;">
            <!-- Tab: Configuration Emplois -->
            <div id="tab-content-jobs" class="settings-tab-content" style="display: block;">
                @include('admin.settings.partials.job-categories', ['categories' => $categories])
                @include('admin.settings.partials.contract-types', ['contractTypes' => $contractTypes])
                @include('admin.settings.partials.service-categories', ['serviceCategories' => $serviceCategories])
            </div>

            <!-- Tab: Catégories Entreprises -->
            <div id="tab-content-companies" class="settings-tab-content" style="display: none;">
                @include('admin.settings.partials.company-categories', ['companyCategories' => $companyCategories, 'level1Options' => $level1Options])
            </div>

            <!-- Tab: Configuration Académique -->
            <div id="tab-content-academic" class="settings-tab-content" style="display: none;">
                @include('admin.settings.partials.specialties', ['specialties' => $specialties])
                @include('admin.settings.partials.training-categories', ['trainingCategories' => $trainingCategories])
            </div>

            <!-- Tab: Parrainage -->
            <div id="tab-content-referral" class="settings-tab-content" style="display: none;">
                @include('admin.settings.partials.referral-config')
                @include('admin.settings.partials.referral-users')
                @include('admin.settings.partials.referral-commissions')
            </div>

            <!-- Tab: Wallet -->
            <div id="tab-content-wallet" class="settings-tab-content" style="display: none;">
                @include('admin.settings.partials.wallet-config')
            </div>
        </div>
    </div>

    <style>
        /* Onglets */
        .settings-tabs {
            display: flex; gap: 4px; padding: 0 12px;
            border-bottom: 1px solid var(--border);
            overflow-x: auto; scrollbar-width: none;
        }
        .settings-tabs::-webkit-scrollbar { display: none; }
        .tab-button {
            flex: 0 0 auto; display: inline-flex; align-items: center; gap: 8px;
            padding: 14px 12px; margin-bottom: -1px;
            font-size: 13.5px; font-weight: 500; white-space: nowrap;
            color: var(--muted); background: none; border: 0; border-bottom: 2px solid transparent;
            cursor: pointer; transition: color .12s, border-color .12s;
        }
        .tab-button .mdi { font-size: 17px; }
        .tab-button:hover { color: var(--text); }
        .tab-button.active { color: var(--text); border-bottom-color: var(--primary-hover); }
        .tab-button.active .mdi { color: var(--primary); }
        .tab-count {
            min-width: 20px; padding: 1px 7px; border-radius: 999px;
            font-size: 11.5px; font-weight: 500; text-align: center;
            background: var(--surface-3); color: var(--muted);
        }
        .tab-button.active .tab-count { background: var(--primary-soft); color: var(--primary); }

        /* Tab content animation */
        .settings-tab-content {
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <script>
        // Tab Switching Function
        function switchSettingsTab(tabName) {
            console.log('Switching to tab:', tabName);

            // Hide all tab contents
            const allContents = document.querySelectorAll('.settings-tab-content');
            allContents.forEach(content => {
                content.style.display = 'none';
                console.log('Hiding:', content.id);
            });

            // Remove active class from all buttons
            const allButtons = document.querySelectorAll('.tab-button');
            allButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab content
            const selectedContent = document.getElementById('tab-content-' + tabName);
            if (selectedContent) {
                selectedContent.style.display = 'block';
                console.log('Showing:', selectedContent.id);
            } else {
                console.error('Content not found for tab:', tabName);
            }

            // Add active class to selected button
            const selectedButton = document.getElementById('tab-btn-' + tabName);
            if (selectedButton) {
                selectedButton.classList.add('active');
                console.log('Activated button:', selectedButton.id);
            }
        }

        // Edit Form Functions (pour les formulaires dans les partials)
        function showEditForm(type, id) {
            const row = document.getElementById(`${type}-row-${id}`);
            const edit = document.getElementById(`${type}-edit-${id}`);
            if (row) row.style.display = 'none';
            if (edit) edit.style.display = 'table-row';
        }

        function hideEditForm(type, id) {
            const edit = document.getElementById(`${type}-edit-${id}`);
            const row = document.getElementById(`${type}-row-${id}`);
            if (edit) edit.style.display = 'none';
            if (row) row.style.display = 'table-row';
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Settings page loaded');

            // Check if there's a tab parameter in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');

            if (tabParam && ['jobs', 'companies', 'academic', 'referral', 'wallet'].includes(tabParam)) {
                switchSettingsTab(tabParam);
            } else {
                // Make sure first tab is active
                switchSettingsTab('jobs');
            }
        });
    </script>
@endsection
