<main class="min-h-screen flex items-center justify-center py-6 px-4 sm:px-6 lg:px-8 bg-gray-100 dark:bg-gray-900">
    <div class="max-w-4xl w-full space-y-6 bg-white dark:bg-gray-800 p-4 sm:p-6 lg:p-8 rounded-xl shadow-lg">
        
        <form wire:submit.prevent="save" class="space-y-6">
            @if (session()->has('message'))
                <div class="p-4 text-sm rounded-lg bg-green-100 text-green-800" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="p-4 text-sm rounded-lg bg-red-100 text-red-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Informations Clés -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 sm:p-6 rounded-lg shadow-inner">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4">Informations Clés</h3>
                <div class="space-y-4">
                    <div>
                        <label for="project-type-name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom du type de projet</label>
                        <input type="text" id="project-type-name" wire:model.defer="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="project-type-description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                        <textarea id="project-type-description" wire:model.defer="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2"></textarea>
                    </div>
                    <div>
                        <label for="project-type-category" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Catégorie</label>
                        <select id="project-type-category" wire:model.defer="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2">
                            <option value="">------</option>
                            @forelse ($projectCategories as $projectCategory)
                                <option value="{{ $projectCategory->name }}">{{ $projectCategory->name }}</option>
                            @empty
                                <option value="">Vide</option>
                            @endforelse
                        </select>
                    </div>
                </div>
            </div>

            <!-- Champs Dynamiques -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 sm:p-6 rounded-lg shadow-inner">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white mb-4">Champs Dynamiques</h3>
                <div class="space-y-4">
                    @foreach ($fields as $index => $field)
                        <div class="dynamic-field p-4 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 relative">
                            <button type="button" wire:click="removeField({{ $index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Libellé de la question</label>
                                    <input type="text" wire:model.defer="fields.{{ $index }}.question_text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2">
                                    @error('fields.' . $index . '.question_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type de champ</label>
                                    <select wire:model.live="fields.{{ $index }}.input_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2">
                                        <option value="text">Texte (simple)</option>
                                        <option value="textarea">Zone de texte (long)</option>
                                        <option value="select">Liste déroulante</option>
                                        <option value="date">Date</option>
                                        <option value="number">Nombre</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nom du champ</label>
                                    <input type="text" wire:model.defer="fields.{{ $index }}.field_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2">
                                    @error('fields.' . $index . '.field_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Type de rendu pour les listes déroulantes -->
                                @if ($field['input_type'] === 'select')
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Type de rendu</label>
                                        <select wire:model.defer="fields.{{ $index }}.render_as" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2" required>
                                            <option value="">---</option>
                                            <option value="select">Liste déroulante</option>
                                            <option value="radio">Boutons radio</option>
                                            <option value="checkbox">Cases à cocher</option>
                                        </select>
                                        @error('fields.' . $index . '.render_as') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <!-- Options pour les champs de type select -->
                                @if ($field['input_type'] === 'select')
                                    <div class="bg-gray-100 dark:bg-gray-600 p-4 rounded-lg">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Options de la liste</h4>
                                        <div class="space-y-2">
                                            @foreach ($field['options'] as $optionIndex => $option)
                                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                                    <input type="text" wire:model.defer="fields.{{ $index }}.options.{{ $optionIndex }}.label" class="flex-1 m-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-500 dark:border-gray-400 dark:text-white p-2" placeholder="Libellé">
                                                    <input type="text" wire:model.defer="fields.{{ $index }}.options.{{ $optionIndex }}.value" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-500 dark:border-gray-400 dark:text-white p-2" placeholder="Valeur">
                                                    <button type="button" wire:click="removeOption({{ $index }}, {{ $optionIndex }})" class="text-red-500 hover:text-red-700 px-2 py-2 sm:py-0">
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" wire:click="addOption({{ $index }})" class="mt-2 w-full flex justify-center items-center px-4 py-2 border border-dashed border-gray-400 dark:border-gray-500 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors text-sm font-medium">
                                            <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                            Ajouter une option
                                        </button>
                                        @error('fields.' . $index . '.options') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Ordre</label>
                                        <input type="number" wire:model.defer="fields.{{ $index }}.order" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2">
                                        @error('fields.' . $index . '.order') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Champ cible (ex: 'title')</label>
                                        <input type="text" wire:model.defer="fields.{{ $index }}.target_project_field" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2" placeholder="Ex: 'title'">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Section</label>
                                    <input type="text" wire:model.defer="fields.{{ $index }}.section" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:text-white p-2" placeholder="Ex: 'Informations de base'">
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="flex items-center h-5">
                                        <input id="is_required-{{ $index }}" wire:model.defer="fields.{{ $index }}.is_required" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="is_required-{{ $index }}" class="font-medium text-gray-700 dark:text-gray-200">Obligatoire</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" wire:click="addField" class="mt-4 w-full flex justify-center items-center px-4 py-2 border border-dashed border-gray-400 dark:border-gray-500 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Ajouter un champ
                </button>
            </div>
            
            <!-- Bouton de soumission -->
            <div class="flex justify-end">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors text-center">
                    Sauvegarder le type de projet
                </button>
            </div>
        </form>
    </div>
</main>