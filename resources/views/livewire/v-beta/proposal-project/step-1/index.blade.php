<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Project Type --}}
        <div class="md:col-span-2 space-y-2">
            <label for="selectedProjectTypeId" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                {{ __('project.step_1.form.input_1') }} <span class="text-rose-500">*</span>
            </label>
            <select id="selectedProjectTypeId" wire:model.live="selectedProjectTypeId"
                    class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm @error('selectedProjectTypeId') border-rose-500 ring-2 ring-rose-500/20 @enderror">
                <option value="">{{ __('project.step_1.form.option') }}</option>
                @foreach($allProjectTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            @error('selectedProjectTypeId') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
            
            @if ($selectedProjectTypeId)
                <div class="mt-3 p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] text-slate-500 italic flex gap-3">
                    <i class="fas fa-info-circle text-accent mt-0.5"></i>
                    <div>
                        <span class="font-black uppercase tracking-widest text-slate-700 dark:text-slate-300 not-italic mr-1">Note :</span>
                        {{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->description ?? 'N/A' }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Project Title --}}
        <div class="md:col-span-2 space-y-2">
            <label for="projectTitle" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                {{ __('project.step_1.form.input_2') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="projectTitle" wire:model.defer="projectTitle"
                    class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm @error('projectTitle') border-rose-500 ring-2 ring-rose-500/20 @enderror"
                    placeholder="Ex: Système de gestion de projet IA">
            @error('projectTitle') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
        </div>

        {{-- Project Code --}}
        <div class="space-y-2">
            <label for="projectCode" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                {{ __('project.step_1.form.input_3') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" id="projectCode" wire:model.defer="projectCode"
                    class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm uppercase @error('projectCode') border-rose-500 ring-2 ring-rose-500/20 @enderror"
                    placeholder="PRJ-2024-X">
            @error('projectCode') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
        </div>

        {{-- Short Title --}}
        <div class="space-y-2">
            <label for="projectShortTitle" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                {{ __('project.step_1.form.input_4') }}
            </label>
            <input type="text" id="projectShortTitle" wire:model.defer="projectShortTitle"
                    class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm"
                    placeholder="Ex: SysGProj IA">
            @error('projectShortTitle') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
        </div>

        {{-- Dates --}}
        <div class="space-y-2">
            <label for="projectStartDate" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                 {{ __('project.step_1.form.input_5') }} <span class="text-rose-500">*</span>
            </label>
            <input type="date" id="projectStartDate" wire:model.defer="projectStartDate"
                    class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm @error('projectStartDate') border-rose-500 ring-2 ring-rose-500/20 @enderror">
            @error('projectStartDate') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="projectEndDate" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">
                {{ __('project.step_1.form.input_6') }} <span class="text-rose-500">*</span>
            </label>
            <input type="date" id="projectEndDate" wire:model.defer="projectEndDate"
                    class="block w-full px-4 py-3.5 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all text-sm @error('projectEndDate') border-rose-500 ring-2 ring-rose-500/20 @enderror">
            @error('projectEndDate') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
        </div>
    </div>
    
    {{-- Dynamic Fields --}}
    <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800">
        @include('livewire.v-beta.proposal-project.dynamic-fields-section')
    </div>
</div>