<div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{
    selectedColumns: [],
    allColumns: [
        { key: 'title', label: 'Titre du CV' },
        { key: 'template_type', label: 'Type de template' },
        { key: 'professional_summary', label: 'Résumé professionnel' },
        { key: 'name', label: 'Nom complet' },
        { key: 'email', label: 'Email' },
        { key: 'phone', label: 'Téléphone' },
        { key: 'address', label: 'Adresse' },
        { key: 'linkedin', label: 'LinkedIn' },
        { key: 'website', label: 'Site web' },
        { key: 'skills', label: 'Compétences' },
        { key: 'languages', label: 'Langues' },
        { key: 'is_public', label: 'Public' }
    ],
    toggleColumn(key) {
        if (this.selectedColumns.includes(key)) {
            this.selectedColumns = this.selectedColumns.filter(c => c !== key);
        } else {
            this.selectedColumns.push(key);
        }
    },
    selectAll() {
        this.selectedColumns = this.allColumns.map(c => c.key);
    },
    deselectAll() {
        this.selectedColumns = [];
    }
}">
    <!-- Export Template -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-download mr-2 text-primary"></i>Exporter un Template
            </h3>
            <div class="flex gap-2">
                <button @click="selectAll()" class="text-xs text-primary hover:underline">
                    Tout sélectionner
                </button>
                <span class="text-gray-400">|</span>
                <button @click="deselectAll()" class="text-xs text-gray-600 hover:underline">
                    Tout désélectionner
                </button>
            </div>
        </div>

        <p class="text-sm text-gray-600 mb-4">
            Sélectionnez les colonnes à inclure dans le template Excel
        </p>

        <!-- Columns Selection -->
        <div class="grid grid-cols-2 gap-2 mb-6 max-h-96 overflow-y-auto p-2 bg-gray-50 rounded">
            <template x-for="column in allColumns" :key="column.key">
                <label class="flex items-center space-x-2 p-2 hover:bg-white rounded cursor-pointer transition">
                    <input type="checkbox"
                           :value="column.key"
                           @change="toggleColumn(column.key)"
                           :checked="selectedColumns.includes(column.key)"
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span class="text-sm text-gray-700" x-text="column.label"></span>
                </label>
            </template>
        </div>

        <div class="flex items-center justify-between mb-4">
            <span class="text-sm text-gray-600">
                <span x-text="selectedColumns.length"></span> colonne(s) sélectionnée(s)
            </span>
        </div>

        <button @click="window.exportTemplate('resumes', selectedColumns)"
                class="w-full btn btn-primary">
            <i class="fas fa-file-download"></i>
            Télécharger le Template Excel
        </button>
    </div>

    <!-- Import Data -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-upload mr-2 text-green-600"></i>Importer des CVs
        </h3>

        <div class="mb-6">
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Instructions:</strong>
                        </p>
                        <ol class="text-sm text-blue-700 list-decimal list-inside mt-2 space-y-1">
                            <li>Téléchargez le template Excel ci-dessus</li>
                            <li>Remplissez le fichier avec vos données</li>
                            <li>Uploadez le fichier rempli ici</li>
                            <li>Les utilisateurs inexistants seront créés automatiquement</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary transition">
                <input type="file"
                       id="resumes-import-file"
                       accept=".csv,.xlsx,.xls"
                       class="hidden"
                       @change="window.importFile('resumes', $event.target)">
                <label for="resumes-import-file" class="cursor-pointer">
                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4 block"></i>
                    <p class="text-sm text-gray-600 mb-2">
                        Cliquez pour sélectionner ou glissez-déposez votre fichier
                    </p>
                    <p class="text-xs text-gray-500">
                        Formats acceptés: CSV, XLSX, XLS (Max 10MB)
                    </p>
                </label>
            </div>
        </div>

        <button @click="document.getElementById('resumes-import-file').click()"
                class="w-full btn btn-success">
            <i class="fas fa-file-upload"></i>
            Sélectionner un Fichier
        </button>
    </div>
</div>
