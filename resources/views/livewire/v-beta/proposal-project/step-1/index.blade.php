<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Project Type --}}
        <div class="md:col-span-2">
            <x-ui.select
                label="{{ __('project.step_1.form.input_1') }}"
                wire:model.live="selectedProjectTypeId"
                icon="layers"
                :error="$errors->first('selectedProjectTypeId')"
            >
                <option value="">{{ __('project.step_1.form.option') }}</option>
                @foreach($allProjectTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </x-ui.select>
            
            @if ($selectedProjectTypeId)
                <div class="mt-3 p-4 bg-surface dark:bg-surface-alt/50 border border-border rounded-xl text-[10px] text-subtle italic flex gap-3">
                    <x-lucide-info class="w-4 h-4 text-accent flex-shrink-0" />
                    <div>
                        <span class="font-black uppercase tracking-widest text-body not-italic mr-1">Note :</span>
                        {{ $allProjectTypes->where('id', $selectedProjectTypeId)->first()->description ?? 'N/A' }}
                    </div>
                </div>
            @endif
        </div>

        {{-- Project Title --}}
        <div class="md:col-span-2">
            <x-ui.input 
                label="{{ __('project.step_1.form.input_2') }}" 
                wire:model.defer="projectTitle"
                placeholder="Ex: Système de gestion de projet IA"
                icon="tag"
                :error="$errors->first('projectTitle')"
                required
            />
        </div>

        {{-- AI Assistant: Generate Description --}}
        <div class="md:col-span-2">
            <livewire:v-beta.a-i.ai-assistant-livewire context="description" :projectTitle="$projectTitle ?? ''" />
        </div>

        {{-- Project Code --}}
        <x-ui.input
            label="{{ __('project.step_1.form.input_3') }}" 
            wire:model.defer="projectCode"
            placeholder="PRJ-2024-X"
            icon="code-2"
            class="uppercase"
            :error="$errors->first('projectCode')"
            required
        />

        {{-- Short Title --}}
        <x-ui.input 
            label="{{ __('project.step_1.form.input_4') }}" 
            wire:model.defer="projectShortTitle"
            placeholder="Ex: SysGProj IA"
            icon="type"
            :error="$errors->first('projectShortTitle')"
        />

        {{-- Dates --}}
        <x-ui.input 
            type="date"
            label="{{ __('project.step_1.form.input_5') }}" 
            wire:model.defer="projectStartDate"
            icon="calendar"
            :error="$errors->first('projectStartDate')"
            required
        />

        <x-ui.input 
            type="date"
            label="{{ __('project.step_1.form.input_6') }}" 
            wire:model.defer="projectEndDate"
            icon="calendar"
            :error="$errors->first('projectEndDate')"
            required
        />
    </div>
    
    {{-- Dynamic Fields --}}
    <div class="mt-8 pt-8 border-t border-border-light dark:border-surface-alt">
        @include('livewire.v-beta.proposal-project.dynamic-fields-section')
    </div>
</div>