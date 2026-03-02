@extends('admin.layouts.app')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres du Système')

@section('content')
    <div class="card" style="border-radius: 8px; overflow: hidden;">
        <!-- Tabs Navigation -->
        <div style="display: flex; border-bottom: 2px solid #e2e8f0; background: #f8fafc;">
            <button
                id="tab-btn-jobs"
                class="tab-button active"
                onclick="switchSettingsTab('jobs')"
                style="flex: 1; padding: 1.25rem 2rem; border: none; background: transparent; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; border-bottom: 3px solid transparent; color: #64748b;"
            >
                <i class="mdi mdi-briefcase"></i> Configuration Emplois
                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; background: #667eea; color: white; border-radius: 0.25rem; margin-left: 0.5rem;">4</span>
            </button>
            <button
                id="tab-btn-academic"
                class="tab-button"
                onclick="switchSettingsTab('academic')"
                style="flex: 1; padding: 1.25rem 2rem; border: none; background: transparent; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; border-bottom: 3px solid transparent; color: #64748b;"
            >
                <i class="mdi mdi-school"></i> Configuration Académique
                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; background: #667eea; color: white; border-radius: 0.25rem; margin-left: 0.5rem;">2</span>
            </button>
            <button
                id="tab-btn-referral"
                class="tab-button"
                onclick="switchSettingsTab('referral')"
                style="flex: 1; padding: 1.25rem 2rem; border: none; background: transparent; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; border-bottom: 3px solid transparent; color: #64748b;"
            >
                <i class="mdi mdi-account-multiple"></i> Parrainage
                <span style="display: inline-block; padding: 0.25rem 0.5rem; font-size: 0.75rem; background: #667eea; color: white; border-radius: 0.25rem; margin-left: 0.5rem;">3</span>
            </button>
        </div>

        <!-- Tab Content Container -->
        <div style="position: relative; overflow: hidden;">
            <!-- Tab: Configuration Emplois -->
            <div id="tab-content-jobs" class="settings-tab-content" style="display: block;">
                @include('admin.settings.partials.job-categories', ['categories' => $categories])
                @include('admin.settings.partials.locations', ['locations' => $locations])
                @include('admin.settings.partials.contract-types', ['contractTypes' => $contractTypes])
                @include('admin.settings.partials.service-categories', ['serviceCategories' => $serviceCategories])
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
        </div>
    </div>

    <style>
        /* Tab button active state */
        .tab-button.active {
            color: #1e293b !important;
            border-bottom-color: #667eea !important;
            background: white !important;
        }

        .tab-button:hover {
            background: rgba(102, 126, 234, 0.05) !important;
        }

        .tab-button.active span {
            background: #28a745 !important;
        }

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
            // Make sure first tab is active
            switchSettingsTab('jobs');
        });
    </script>
@endsection
