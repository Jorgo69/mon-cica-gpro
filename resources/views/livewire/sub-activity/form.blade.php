<div class="space-y-6">
    <form wire:submit.prevent="saveSubActivities" class="space-y-6">
        <div class="space-y-4">
            @foreach ($subActivitiesData as $index => $subActivityData)
                <x-ui.card class="relative overflow-visible border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                    {{-- Remove Button --}}
                    @if(count($subActivitiesData) > 1 && !$editing)
                        <button 
                            type="button" 
                            wire:click="removeSubActivity({{ $index }})"
                            class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-white dark:bg-slate-800 shadow-md border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors z-10"
                        >
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </button>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Description --}}
                        <div class="md:col-span-2">
                            <x-ui.input 
                                label="Description"
                                wire:model.defer="subActivitiesData.{{ $index }}.description"
                                placeholder="Décrivez cette sous-activité..."
                                required
                            />
                            @error('subActivitiesData.'.$index.'.description') 
                                <p class="text-[10px] text-red-500 mt-1 font-medium">{{ $message }}</p> 
                            @enderror
                        </div>
                        
                        {{-- Date de début --}}
                        <x-ui.input 
                            type="date" 
                            label="Date de début"
                            wire:model.live="subActivitiesData.{{ $index }}.start_date"
                            min="{{ $activityStartDate }}"
                            max="{{ $activityEndDate }}"
                            required
                        />

                        {{-- Date de fin --}}
                        <x-ui.input 
                            type="date"
                            label="Date de fin"
                            wire:model.live="subActivitiesData.{{ $index }}.end_date"
                            min="{{ $activityStartDate }}"
                            max="{{ $activityEndDate }}"
                            required
                        />

                        {{-- Responsable --}}
                        <x-ui.select 
                            label="Responsable"
                            wire:model.defer="subActivitiesData.{{ $index }}.responsible_user_id"
                        >
                            <option value="">Sélectionner un responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </x-ui.select>

                        {{-- Important (Milestone) --}}
                        <div class="flex items-center gap-3 pt-6">
                            <button 
                                type="button"
                                wire:click="toggleMilestone({{ $index }})"
                                class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $subActivitiesData[$index]['is_milestone'] ? 'bg-accent' : 'bg-slate-200 dark:bg-slate-700' }}"
                            >
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $subActivitiesData[$index]['is_milestone'] ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-400">Marquer comme jalon (important)</span>
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800">
            <div>
                @if(!$editing)
                    <x-ui.button 
                        type="button" 
                        wire:click="addBlankSubActivity"
                        variant="ghost" 
                        size="sm" 
                        icon="plus"
                    >
                        Ajouter une autre
                    </x-ui.button>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <x-ui.button 
                    type="button"
                    wire:click="$parent.closeModalForSubActivity"
                    variant="ghost"
                    size="md"
                >
                    Annuler
                </x-ui.button>
                <x-ui.button 
                    type="submit"
                    variant="accent"
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