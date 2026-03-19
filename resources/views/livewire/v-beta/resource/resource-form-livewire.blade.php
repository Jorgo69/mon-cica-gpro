<div class="space-y-6">
    <form wire:submit.prevent="saveResources">

        <div class="space-y-4">
            @foreach ($resourcesData as $index => $resourceData)
            <x-ui.card class="relative overflow-visible" :noPadding="false">
                {{-- Bouton de suppression --}}
                @if(count($resourcesData) > 1 && !$editing)
                <button type="button" wire:click="removeResource({{ $index }})" 
                        class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow-md border border-slate-100 dark:border-slate-700 flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all z-10"
                        title="Supprimer cette ressource">
                    <x-lucide-x-circle class="w-5 h-5" />
                </button>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Nom --}}
                    <x-ui.input 
                        label="Désignation" 
                        wire:model.blur="resourcesData.{{ $index }}.name" 
                        placeholder="Ex: Expert en Logique" 
                        icon="type"
                        :error="$errors->first('resourcesData.'.$index.'.name')"
                        required
                    />

                    {{-- Type --}}
                    <x-ui.select 
                        label="Type de ressource" 
                        wire:model.live="resourcesData.{{ $index }}.type" 
                        icon="layers"
                        :error="$errors->first('resourcesData.'.$index.'.type')"
                        required
                    >
                        <option value="">Sélectionner...</option>
                        <option value="Humain">Humain</option>
                        <option value="Materiel">Matériel</option>
                        <option value="Financier">Financier</option>
                    </x-ui.select>

                    {{-- Catégorie --}}
                    <x-ui.input 
                        label="Catégorie" 
                        wire:model.blur="resourcesData.{{ $index }}.category" 
                        placeholder="Ex: Consulting / Équipement" 
                        icon="tag"
                        :error="$errors->first('resourcesData.'.$index.'.category')"
                    />

                    {{-- Quantité --}}
                    <x-ui.input 
                        type="number"
                        label="Quantité" 
                        wire:model.live="resourcesData.{{ $index }}.quantity" 
                        icon="hash"
                        :error="$errors->first('resourcesData.'.$index.'.quantity')"
                        required
                    />

                    {{-- Coût Unitaire --}}
                    <x-ui.input 
                        type="number"
                        step="0.01"
                        label="Coût Unitaire" 
                        wire:model.live="resourcesData.{{ $index }}.unit_cost" 
                        icon="banknote"
                        :error="$errors->first('resourcesData.'.$index.'.unit_cost')"
                        required
                    />

                    {{-- Coût Total (Lecture seule) --}}
                    <div class="space-y-2 cursor-not-allowed opacity-80">
                         <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Coût Total</label>
                         <div class="bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm font-black text-accent flex items-center gap-2">
                             <x-lucide-calculator class="w-4 h-4 text-slate-300" />
                             {{ number_format((float)$resourcesData[$index]['total_cost'], 2, ',', ' ') }}
                         </div>
                    </div>

                    {{-- Responsable --}}
                    <x-ui.select 
                        label="Responsable" 
                        wire:model.defer="resourcesData.{{ $index }}.responsible_user_id" 
                        icon="user"
                        :error="$errors->first('resourcesData.'.$index.'.responsible_user_id')"
                    >
                        <option value="">Sélectionner un responsable</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            </x-ui.card>
            @endforeach
        </div>
        
        {{-- Boutons d'action --}}
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-800">
            <div>
                @if(!$editing)
                    <x-ui.button type="button" variant="ghost" size="sm" icon="plus" wire:click="addBlankResource">
                        Ajouter une autre
                    </x-ui.button>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <x-ui.button 
                    type="button" 
                    variant="ghost" 
                    size="md" 
                    wire:click="$parent.closeModal"
                >
                    Annuler
                </x-ui.button>
                <x-ui.button 
                    type="submit" 
                    variant="accent" 
                    icon="save" 
                    size="md" 
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>{{ $editing ? 'Mettre à jour' : 'Enregistrer' }}</span>
                    <span wire:loading>Traitement...</span>
                </x-ui.button>
            </div>
        </div>
    </form>
</div>

