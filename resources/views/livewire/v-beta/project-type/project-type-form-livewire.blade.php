<x-ui.page-layout maxWidth="900px">
    <form wire:submit.prevent="save" class="space-y-6">

        {{-- Page Header --}}
        <x-ui.page-header 
            :title="isset($projectTypeId) ? 'Modifier le type de projet' : 'Nouveau type de projet'" 
            subtitle="Configurez les informations et champs dynamiques" />

        {{-- Key Information --}}
        <x-ui.section title="Informations Clés" icon="info">
            <div class="space-y-4">
                <x-ui.input wire:model.defer="name" name="name" label="Nom du type de projet" required 
                            :error="$errors->first('name')" placeholder="Ex: Projet de Développement" />
                
                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Description</label>
                    <textarea wire:model.defer="description" rows="3" 
                              class="block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 px-4 transition-all"></textarea>
                </div>

                <x-ui.select wire:model.defer="category" name="category" label="Catégorie" icon="tag">
                    <option value="">------</option>
                    @forelse ($projectCategories as $projectCategory)
                        <option value="{{ $projectCategory->name }}">{{ $projectCategory->name }}</option>
                    @empty
                        <option value="">Aucune catégorie</option>
                    @endforelse
                </x-ui.select>
            </div>
        </x-ui.section>

        {{-- Dynamic Fields --}}
        <x-ui.section title="Champs Dynamiques" icon="puzzle">
            <div class="space-y-4">
                @foreach ($fields as $index => $field)
                    <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/30 relative">
                        <button type="button" wire:click="removeField({{ $index }})" class="absolute top-3 right-3 p-1 rounded-lg text-slate-400 hover:text-error hover:bg-error/5 transition-all">
                            <x-lucide-x class="w-4 h-4" />
                        </button>

                        <div class="grid grid-cols-1 gap-4 pr-8">
                            <x-ui.input wire:model.defer="fields.{{ $index }}.question_text" label="Libellé de la question" required
                                        :error="$errors->first('fields.' . $index . '.question_text')" />

                            <x-ui.select wire:model.live="fields.{{ $index }}.input_type" label="Type de champ" icon="type">
                                <option value="text">Texte (simple)</option>
                                <option value="textarea">Zone de texte (long)</option>
                                <option value="select">Liste déroulante</option>
                                <option value="date">Date</option>
                                <option value="number">Nombre</option>
                            </x-ui.select>

                            <x-ui.input wire:model.defer="fields.{{ $index }}.field_name" label="Nom du champ" required
                                        :error="$errors->first('fields.' . $index . '.field_name')" />

                            @if ($field['input_type'] === 'select')
                                <x-ui.select wire:model.defer="fields.{{ $index }}.render_as" label="Type de rendu" icon="layout" required
                                             :error="$errors->first('fields.' . $index . '.render_as')">
                                    <option value="">---</option>
                                    <option value="select">Liste déroulante</option>
                                    <option value="radio">Boutons radio</option>
                                    <option value="checkbox">Cases à cocher</option>
                                </x-ui.select>

                                <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-3">Options de la liste</p>
                                    <div class="space-y-2">
                                        @foreach ($field['options'] as $optionIndex => $option)
                                            <div class="flex items-center gap-2">
                                                <input type="text" wire:model.defer="fields.{{ $index }}.options.{{ $optionIndex }}.label" 
                                                       class="flex-1 block border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-lg shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-sm py-2 px-3" placeholder="Libellé">
                                                <input type="text" wire:model.defer="fields.{{ $index }}.options.{{ $optionIndex }}.value" 
                                                       class="flex-1 block border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-lg shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-sm py-2 px-3" placeholder="Valeur">
                                                <button type="button" wire:click="removeOption({{ $index }}, {{ $optionIndex }})" class="p-1.5 rounded-lg text-slate-400 hover:text-error hover:bg-error/5 transition-all">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" wire:click="addOption({{ $index }})" class="mt-3 w-full flex justify-center items-center gap-2 px-4 py-2.5 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                                        <x-lucide-plus class="w-4 h-4" />
                                        Ajouter une option
                                    </button>
                                    @error('fields.' . $index . '.options') <span class="text-error text-xs font-medium mt-1">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <x-ui.input wire:model.defer="fields.{{ $index }}.order" type="number" label="Ordre" required
                                            :error="$errors->first('fields.' . $index . '.order')" />
                                <x-ui.input wire:model.defer="fields.{{ $index }}.target_project_field" label="Champ cible" 
                                            placeholder="Ex: title" />
                            </div>

                            <x-ui.input wire:model.defer="fields.{{ $index }}.section" label="Section" placeholder="Ex: Informations de base" />

                            <div class="flex items-center gap-2">
                                <input id="is_required-{{ $index }}" wire:model.defer="fields.{{ $index }}.is_required" type="checkbox" 
                                       class="rounded border-slate-300 text-accent focus:ring-accent h-4 w-4">
                                <label for="is_required-{{ $index }}" class="text-sm font-medium text-slate-700 dark:text-slate-300">Obligatoire</label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" wire:click="addField" class="mt-4 w-full flex justify-center items-center gap-2 px-4 py-3 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl text-sm font-semibold text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <x-lucide-plus class="w-4 h-4" />
                Ajouter un champ
            </button>
        </x-ui.section>

        {{-- Submit --}}
        <div class="flex justify-end">
            <x-ui.button type="submit" variant="accent" icon="save" size="lg">
                Sauvegarder
            </x-ui.button>
        </div>
    </form>
</x-ui.page-layout>