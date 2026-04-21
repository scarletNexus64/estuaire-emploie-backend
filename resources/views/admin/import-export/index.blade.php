@extends('admin.layouts.app')

@section('title', 'Import/Export')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="importExportManager()">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Import / Export de Données</h1>
        <p class="text-gray-600">Gérez l'import et l'export de Jobs, CVs et Services Rapides</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" role="tablist">
                <button @click="activeTab = 'jobs'"
                        :class="activeTab === 'jobs' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-briefcase mr-2"></i>Jobs
                </button>
                <button @click="activeTab = 'cvs'"
                        :class="activeTab === 'cvs' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-file-alt mr-2"></i>CVs
                </button>
                <button @click="activeTab = 'services'"
                        :class="activeTab === 'services' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    <i class="fas fa-tools mr-2"></i>Services Rapides
                </button>
            </nav>
        </div>
    </div>

    <!-- Jobs Tab -->
    <div x-show="activeTab === 'jobs'" x-cloak>
        @include('admin.import-export.partials.jobs')
    </div>

    <!-- CVs Tab -->
    <div x-show="activeTab === 'cvs'" x-cloak>
        @include('admin.import-export.partials.cvs')
    </div>

    <!-- Services Tab -->
    <div x-show="activeTab === 'services'" x-cloak>
        @include('admin.import-export.partials.services')
    </div>

    <!-- Import Progress Modal -->
    <div x-show="$store.importProgress.show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$store.importProgress.show = false"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                <i class="fas fa-upload mr-2"></i>Import en cours...
                            </h3>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary h-2.5 rounded-full transition-all duration-300"
                                         :style="`width: ${$store.importProgress.percent}%`"></div>
                                </div>
                                <p class="text-sm text-gray-600 mt-2" x-text="$store.importProgress.message"></p>
                            </div>

                            <!-- Results (shown after completion) -->
                            <div x-show="$store.importProgress.results" x-cloak class="mt-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid grid-cols-3 gap-4 mb-4">
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-blue-600" x-text="$store.importProgress.results?.total || 0"></p>
                                            <p class="text-xs text-gray-600">Total</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-green-600" x-text="$store.importProgress.results?.imported || 0"></p>
                                            <p class="text-xs text-gray-600">Importés</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-2xl font-bold text-red-600" x-text="$store.importProgress.results?.failed || 0"></p>
                                            <p class="text-xs text-gray-600">Échecs</p>
                                        </div>
                                    </div>

                                    <!-- Errors List -->
                                    <div x-show="$store.importProgress.results?.errors?.length > 0" class="mt-4">
                                        <h4 class="font-semibold text-sm mb-2">Erreurs détaillées:</h4>
                                        <div class="max-h-48 overflow-y-auto space-y-2">
                                            <template x-for="error in $store.importProgress.results?.errors || []" :key="error.row">
                                                <div class="bg-red-50 border-l-4 border-red-400 p-3 text-sm">
                                                    <p class="font-semibold text-red-800">Ligne <span x-text="error.row"></span></p>
                                                    <p class="text-red-700" x-text="error.error"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button"
                            @click="$store.importProgress.show = false"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global export function
window.exportTemplate = async function(type, selectedColumns) {
    if (selectedColumns.length === 0) {
        alert('Veuillez sélectionner au moins une colonne');
        return;
    }

    try {
        const response = await fetch(`/admin/import-export/${type}/export-template`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ columns: selectedColumns })
        });

        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${type}_template_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            a.remove();
        } else {
            const errorData = await response.json().catch(() => ({ message: 'Erreur inconnue' }));
            alert('Erreur lors de l\'export du template: ' + (errorData.message || 'Erreur inconnue'));
        }
    } catch (error) {
        console.error('Export error:', error);
        alert('Erreur lors de l\'export du template: ' + error.message);
    }
};

// Global import function
window.importFile = async function(type, fileInput) {
    const file = fileInput.files[0];
    if (!file) {
        alert('Veuillez sélectionner un fichier');
        return;
    }

    // Show modal
    Alpine.store('importProgress', {
        show: true,
        percent: 0,
        message: 'Téléchargement du fichier...',
        results: null
    });

    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    try {
        // Update progress
        Alpine.store('importProgress').percent = 30;
        Alpine.store('importProgress').message = 'Traitement en cours...';

        const response = await fetch(`/admin/import-export/${type}/import`, {
            method: 'POST',
            body: formData
        });

        Alpine.store('importProgress').percent = 90;

        const data = await response.json();

        Alpine.store('importProgress').percent = 100;
        Alpine.store('importProgress').message = data.message;
        Alpine.store('importProgress').results = data.results;

        // Reset file input
        fileInput.value = '';

    } catch (error) {
        console.error('Import error:', error);
        Alpine.store('importProgress').message = 'Erreur lors de l\'import';
        Alpine.store('importProgress').results = {
            total: 0,
            imported: 0,
            failed: 0,
            errors: [{ row: 0, error: error.message }]
        };
    }
};

// Alpine.js component for the page
function importExportManager() {
    return {
        activeTab: 'jobs'
    };
}

// Initialize Alpine store for import progress
document.addEventListener('alpine:init', () => {
    Alpine.store('importProgress', {
        show: false,
        percent: 0,
        message: '',
        results: null
    });
});
</script>
@endsection
