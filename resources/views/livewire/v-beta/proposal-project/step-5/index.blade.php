<div class="space-y-8">
    <div class="flex items-center justify-between px-1">
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em]">Plan d'Action</label>
        <x-ui.button type="button" variant="ghost" size="sm" icon="plus-circle" wire:click="addActivity">
            Ajouter
        </x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @foreach($activities as $index => $activity)
            <x-ui.card wire:key="activity-{{ $index }}-{{ $activity['id'] ?? $loop->index }}" class="relative overflow-visible" :noPadding="false">
                <button type="button" wire:click="removeActivity({{ $index }})" 
                        class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white dark:bg-slate-800 shadow-md border border-slate-100 dark:border-slate-700 flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 z-10">
                    <x-lucide-trash-2 class="w-4 h-4" />
                </button>

                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center font-black text-accent text-[10px] shadow-inner border border-slate-100 dark:border-slate-700">A{{ $index + 1 }}</div>
                    
                    <div class="flex-1 space-y-6">
                        <input type="hidden" wire:model="activities.{{ $index }}.id">
                        
                        <x-ui.input 
                            label="Désignation de l'activité" 
                            wire:model.defer="activities.{{ $index }}.description" 
                            placeholder="Description de l'activité..." 
                            icon="clipboard-list"
                            :error="$errors->first('activities.' . $index . '.description')"
                            required
                        />

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <x-ui.select 
                                label="Responsable" 
                                wire:model.defer="activities.{{ $index }}.responsible_user_id"
                                icon="user"
                                :error="$errors->first('activities.' . $index . '.responsible_user_id')"
                                required
                            >
                                <option value="">Sélectionner</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </x-ui.select>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Période d'exécution</label>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <x-ui.input 
                                            type="date" 
                                            wire:model="activities.{{ $index }}.start_date" 
                                            min="{{ $projectStartDate }}" 
                                            max="{{ $projectEndDate }}"
                                            :error="$errors->first('activities.' . $index . '.start_date')"
                                        />
                                    </div>
                                    <div class="flex-1">
                                        <x-ui.input 
                                            type="date" 
                                            wire:model="activities.{{ $index }}.end_date" 
                                            min="{{ $projectStartDate }}" 
                                            max="{{ $projectEndDate }}"
                                            :error="$errors->first('activities.' . $index . '.end_date')"
                                        />
                                    </div>
                                </div>
                            </div>

                            <x-ui.select 
                                label="Statut" 
                                wire:model.defer="activities.{{ $index }}.status"
                                icon="activity"
                                :error="$errors->first('activities.' . $index . '.status')"
                                required
                            >
                                <option value="En cours">En cours</option>
                                <option value="Terminée">Terminée</option>
                                <option value="En attente">En attente</option>
                                <option value="En retard">En retard</option>
                            </x-ui.select>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" wire:model.defer="activities.{{ $index }}.is_milestone" class="w-5 h-5 text-accent rounded-lg border-slate-200 focus:ring-accent/20 transition-all">
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">Marquer comme Jalon stratégique</span>
                            </label>
                            
                            <div class="w-full sm:w-auto">
                                <x-ui.input 
                                    type="number" 
                                    label="Budget Estimé (CFA)" 
                                    wire:model.defer="activities.{{ $index }}.budget" 
                                    placeholder="0.00" 
                                    icon="banknote"
                                    class="font-mono text-right"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        @endforeach
    </div>
</div>