<div class="space-y-6">
    <div class="flex items-center justify-between px-1">
        <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em]">Plan d'Action</label>
        <button type="button" wire:click="addActivity" class="text-[10px] font-black text-accent hover:opacity-80 uppercase flex items-center gap-1.5 transition-opacity tracking-widest">
            <i class="fas fa-plus-circle"></i> Ajouter
        </button>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @foreach($activities as $index => $activity)
            <div wire:key="activity-{{ $index }}-{{ $activity['id'] ?? $loop->index }}" 
                 class="relative p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm rounded-3xl animate-fadeIn transition-colors hover:border-slate-300 dark:hover:border-slate-700">
                <button type="button" wire:click="removeActivity({{ $index }})" 
                        class="absolute top-6 right-6 text-slate-300 hover:text-rose-500 transition-colors bg-white dark:bg-slate-900 rounded-full w-8 h-8 flex items-center justify-center border border-transparent shadow-sm hover:border-rose-100 hover:bg-rose-50 dark:hover:bg-rose-900/20 z-10">
                    <i class="fas fa-trash-alt text-[10px]"></i>
                </button>

                <div class="flex gap-5">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center font-black text-slate-500 text-[10px] shadow-inner border border-slate-100 dark:border-slate-700">A{{ $index + 1 }}</div>
                    
                    <div class="flex-1 space-y-5 pt-1 pr-8">
                        <input type="hidden" wire:model="activities.{{ $index }}.id">
                        
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Activité <span class="text-rose-500">*</span></label>
                            <textarea wire:model.defer="activities.{{ $index }}.description" rows="2"
                                      class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-sm shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all @error('activities.' . $index . '.description') border-rose-500 ring-2 ring-rose-500/20 @enderror"
                                      placeholder="Description de l'activité..."></textarea>
                            @error('activities.' . $index . '.description') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Responsable <span class="text-rose-500">*</span></label>
                                <select wire:model.defer="activities.{{ $index }}.responsible_user_id"
                                        class="block w-full px-4 py-2.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-[11px] shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all @error('activities.' . $index . '.responsible_user_id') border-rose-500 ring-2 ring-rose-500/20 @enderror">
                                    <option value="">Sélectionner</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('activities.' . $index . '.responsible_user_id') <p class="text-[9px] text-rose-500 font-bold italic mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Début - Fin <span class="text-rose-500">*</span></label>
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <input type="date" wire:model="activities.{{ $index }}.start_date" 
                                               class="block w-full px-3 py-2.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-[10px] shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all @error('activities.' . $index . '.start_date') border-rose-500 ring-2 ring-rose-500/20 @enderror">
                                        @error('activities.' . $index . '.start_date') <p class="text-[9px] text-rose-500 font-bold italic mt-1 ml-1 uppercase tracking-tight line-clamp-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex-1">
                                        <input type="date" wire:model="activities.{{ $index }}.end_date" 
                                               class="block w-full px-3 py-2.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-[10px] shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all @error('activities.' . $index . '.end_date') border-rose-500 ring-2 ring-rose-500/20 @enderror">
                                        @error('activities.' . $index . '.end_date') <p class="text-[9px] text-rose-500 font-bold italic mt-1 ml-1 uppercase tracking-tight line-clamp-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">Statut <span class="text-rose-500">*</span></label>
                                <select wire:model.defer="activities.{{ $index }}.status"
                                        class="block w-full px-4 py-2.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-[10px] font-bold shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all @error('activities.' . $index . '.status') border-rose-500 ring-2 ring-rose-500/20 @enderror">
                                    <option value="En cours">En cours</option>
                                    <option value="Terminée">Terminée</option>
                                    <option value="En attente">En attente</option>
                                    <option value="En retard">En retard</option>
                                </select>
                                @error('activities.' . $index . '.status') <p class="text-[9px] text-rose-500 font-bold italic mt-1 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 mt-2 border-t border-slate-100 dark:border-slate-800">
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" wire:model.defer="activities.{{ $index }}.is_milestone" class="w-4 h-4 text-accent rounded border-slate-300 focus:ring-accent">
                                <span class="text-[11px] font-bold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-slate-200 transition-colors">Marquer comme Jalon</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.15em]">Budget Estimé</span>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                        <span class="text-slate-400 font-bold text-[10px] tracking-widest">CFA</span>
                                    </div>
                                    <input type="number" wire:model.defer="activities.{{ $index }}.budget" placeholder="0.00"
                                           class="w-32 pl-12 pr-4 py-2 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-[11px] font-mono font-bold text-slate-800 dark:text-slate-200 shadow-inner focus:ring-1 focus:ring-accent focus:border-accent transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>