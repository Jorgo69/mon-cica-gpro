<div x-show="currentStep === 2" class="space-y-6">
    <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Contexte & Documents</h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6">Fournissez une description du contexte du projet et téléchargez les documents pertinents.</p>

   <div wire:ignore>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Description du Contexte (Optionnel)
    </label>
    <textarea
        id="contextDescription"
        class="summernote"
        data-field="contextDescription"
        placeholder="Décrivez le contexte général dans lequel le projet s'inscrit.">{!! $contextDescription !!}</textarea>
    @error('contextDescription') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
</div>


<div wire:ignore>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Analyse du problème (Optionnel)
    </label>
    <textarea
        id="problemAnalysis"
        class="summernote"
        data-field="problemAnalysis"
        placeholder="Analyse des problèmes auxquels le projet répondra.">{!! $problemAnalysis !!}</textarea>
    @error('problemAnalysis') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
</div>

<div wire:ignore>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Stratégie (Optionnel)
    </label>
    <textarea
        id="strategy"
        class="summernote"
        data-field="strategy"
        placeholder="Décrivez la stratégie.">{!! $strategy !!}</textarea>
    @error('strategy') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
</div>

<div wire:ignore>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Justification (Optionnel)
    </label>
    <textarea
        id="justification"
        class="summernote"
        data-field="justification"
        placeholder="Décrivez la justification.">{!! $justification !!}</textarea>
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

@include('livewire.v-beta.components.include.index')
