<div x-show="currentStep === 2" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Contexte & Documents</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Fournissez une description du contexte du projet et téléchargez les documents pertinents.</p>

    <div>
        <label for="contextDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description du Contexte (Optionnel)</label>
        <textarea id="contextDescription" wire:model.defer="contextDescription" rows="5"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Décrivez le contexte général dans lequel le projet s'inscrit."></textarea>
        @error('contextDescription') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="problemAnalysis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Analyse du probleme (Optionnel)</label>
        <textarea id="problemAnalysis" wire:model.defer="problemAnalysis" rows="5"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Analyse des probleme auquel le project repondra."></textarea>
        @error('problemAnalysis') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="strategy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Strategie (Optionnel)</label>
        <textarea id="strategy" wire:model.defer="strategy" rows="5"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Décrivez le contexte général dans lequel le projet s'inscrit."></textarea>
        @error('strategy') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="justification" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Justification (Optionnel)</label>
        <textarea id="justification" wire:model.defer="justification" rows="5"
                    class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Décrivez le probleme a resoudre dans lequel le projet s'inscrit."></textarea>
        @error('justification') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="uploadedDocuments" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Documents Pertinents (Optionnel)</label>
        <input type="file" id="uploadedDocuments" wire:model="uploadedDocuments" multiple
                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                        dark:file:bg-blue-800 dark:file:text-blue-200 dark:hover:file:bg-blue-700">
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Formats acceptés : PDF, DOCX, XLSX, JPG, PNG. Taille max : 50MB par fichier.</p>
        @error('uploadedDocuments.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        

        {{-- Affiche les fichiers existants (ceux déjà en BDD en mode édition) --}}
        @if (count($existingDocuments) > 0)
            <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                Fichiers existants :
                <ul class="list-disc list-inside">
                    @foreach ($existingDocuments as $document)
                        <li class="flex items-center justify-between">
                            <span>{{ $document->file_name }}</span>
                            <button type="button"
                                    wire:click="confirmDeleteDocument('{{ $document->id }}')"
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce document ? Cette action est irréversible."
                                    class="ml-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-500 focus:outline-none">
                                Retirer
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    {{-- Affiche les nouveaux fichiers en attente d'upload --}}
    @if (count($uploadedDocuments) > 0)
        <div class="mt-4 text-sm text-gray-600 dark:text-gray-300">
            Nouveaux fichiers en attente d'upload :
            <ul class="list-disc list-inside">
                @foreach ($uploadedDocuments as $index => $file)
                    <li class="flex items-center justify-between">
                        <span>{{ $file->getClientOriginalName() }} ({{ round($file->getSize() / 1024 / 1024, 2) }} MB)</span>
                        <button type="button"
                                wire:click="removeUploadedFile({{ $index }})"
                                wire:confirm="Êtes-vous sûr de vouloir supprimer ce document ? Cette action est irréversible."
                                class="ml-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-500 focus:outline-none">
                            Retirer
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


    </div>

    
</div>