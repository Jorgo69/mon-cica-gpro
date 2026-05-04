<x-ui.page-layout>
    @if ($activity)
        {{-- Progress Bar --}}
        @include('livewire.v1.activity.include.progres-bar')

        {{-- Page Header --}}
        <x-ui.page-header :title="$activity->description">
            <x-slot:actions>
                <x-ui.button tag="a" :href="route('activity.index')" variant="outline" icon="arrow-left" size="sm">
                    {{ __('common.back') }}
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>
        @if($activity->project)
            <p class="text-sm text-subtle -mt-6 mb-6">
                {{ __('activities.project') }} : <a href="#" class="font-semibold text-accent hover:underline">{{ $activity->project->title }}</a>
            </p>
        @endif

        <div class="space-y-6">

            {{-- Section 1: Informations Générales --}}
            <x-ui.section :title="__('activities.general_info')" icon="info">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28 shrink-0 pt-0.5">{{ __('activities.status') }}</span>
                            @php $actStatus = $activity->status; @endphp
                            <x-ui.badge :variant="$actStatus?->color() ?? 'slate'">
                                {{ $actStatus instanceof \App\Enums\ActivityStatus ? $actStatus->label() : ($actStatus instanceof \BackedEnum ? $actStatus->value : (string)$actStatus) }}
                            </x-ui.badge>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.start') }}</span>
                            <span class="text-body">{{ $activity->start_date }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.end') }}</span>
                            <span class="text-body">{{ $activity->end_date }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.responsible') }}</span>
                            <span class="text-body">{{ $activity->responsibleUser->name ?? __('activities.not_assigned') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.budget') }}</span>
                            <span class="text-body">{{ $activity->budget ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.created') }}</span>
                            <span class="text-body">{{ $activity->created_at }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.updated') }}</span>
                            <span class="text-body">{{ $activity->updated_at }}</span>
                        </div>
                        @if($activity->result)
                        <div class="mt-4 pt-3 border-t border-border-light">
                            <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ __('activities.contributes_to_result') }}</p>
                            <p class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-3 rounded-xl">{{ $activity->result->description }}</p>
                        </div>
                        @endif
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.project_creator') }}</span>
                            <span class="text-body">{{ $activity->result->specificObjective->logicalFramework->project->creator->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </x-ui.section>

            {{-- Section 2: Contexte du projet --}}
            @if($activity->project)
                <x-ui.section :title="__('activities.project_context')" icon="briefcase">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.title') }}</span>
                                <span class="text-body">{{ $activity->project->title }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.code') }}</span>
                                <span class="text-xs font-mono font-bold text-subtle bg-surface-alt px-2 py-0.5 rounded-lg">{{ $activity->project->project_code }}</span>
                            </div>
                            @if($activity->project->projectType)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.type') }}</span>
                                <x-ui.badge variant="accent" size="sm">{{ $activity->project->projectType->name }}</x-ui.badge>
                            </div>
                            @endif
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.period') }}</span>
                                <span class="text-body">{{ $activity->project->start_date }} → {{ $activity->project->end_date }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('activities.status') }}</span>
                                @php
                                    $projStatusEnum = $activity->project->status instanceof \App\Enums\ProjectStatus ? $activity->project->status : \App\Enums\ProjectStatus::tryFrom($activity->project->status);
                                    $projBadgeVariant = $projStatusEnum ? $projStatusEnum->color() : 'slate';
                                @endphp
                                <x-ui.badge :variant="$projBadgeVariant">{{ $projStatusEnum ? $projStatusEnum->label() : $activity->project->status }}</x-ui.badge>
                            </div>
                        </div>
                    </div>
                </x-ui.section>
            @endif

            {{-- Section 3: Sous-activités --}}
            <x-ui.section :title="__('activities.sub_activities_management')" icon="git-branch">
                @can('create', $activity)
                    <x-slot:headerActions>
                        <x-ui.button wire:click="openModalForSubActivity" variant="accent" icon="plus" size="sm">
                            {{ __('common.add') }}
                        </x-ui.button>
                    </x-slot:headerActions>
                @endcan

                {{-- Sub-Activity Modal --}}
                @if($showModalForSubActivity)
                    <x-ui.modal :show="true" :title="__('sub_activities.sub_activity')" closeAction="closeModalForSubActivity">
                        @livewire('v1.sub-activity.sub-activity-form-livewire', [
                            'activityId' => $activity->id,
                            'subActivityToEditId' => $editingSubActivityId,
                        ], key('modal-sub-' . ($editingSubActivityId ?? 'new')))
                    </x-ui.modal>
                @endif

                @include('messages.index', ['context' => 'sub-activity'])
                @include('livewire.v1.sub-activity.include.sub-activity')
            </x-ui.section>

            {{-- Section 4: Ressources --}}
            <x-ui.section :title="__('activities.resources_management')" icon="package">
                @can('create', $activity)
                    <x-slot:headerActions>
                        <x-ui.button wire:click="openModal" variant="accent" icon="plus" size="sm">
                            {{ __('common.add') }}
                        </x-ui.button>
                    </x-slot:headerActions>
                @endcan

                {{-- Resource Modal --}}
                @if($showModal)
                    <x-ui.modal :show="true" :title="__('resources.resource')" closeAction="closeModal">
                        @livewire('v1.resource.resource-form-livewire', [
                            'activityId' => $activity->id,
                            'resourceToEditId' => $editingResourceId,
                        ], key('modal-res-' . ($editingResourceId ?? 'new')))
                    </x-ui.modal>
                @endif

                @include('messages.index', ['context' => 'resource'])
                @include('livewire.v1.resource.include.resource')
            </x-ui.section>

        </div>
    @else
        <x-ui.empty-state icon="activity" :title="__('activities.not_found')" :description="__('activities.not_found_desc')" />
    @endif
</x-ui.page-layout>
