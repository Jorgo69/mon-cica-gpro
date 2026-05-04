<x-ui.page-layout>
    @if ($project)
        {{-- Page Header --}}
        <x-ui.page-header :title="__('projects.show.title')" :subtitle="$project->project_code">
            <x-slot:actions>
                @can('update', $project)
                    <x-ui.button tag="a" :href="route('creator.proposal.project.edit', ['projectId' => $project->id])" variant="outline" icon="pencil" size="sm" wire:navigate>
                        {{ __('common.edit') }}
                    </x-ui.button>
                @endcan
                @if(\App\Services\AI\AiService::isConfigured())
                <x-ui.button wire:click="aiGenerateSummary" variant="outline" icon="sparkles" size="sm"
                    wire:loading.attr="disabled" wire:target="aiGenerateSummary"
                    class="!text-purple-600 !border-purple-200 hover:!bg-purple-50 dark:!text-purple-400 dark:!border-purple-800">
                    <span wire:loading.remove wire:target="aiGenerateSummary">{{ __('ai.generate_summary') }}</span>
                    <span wire:loading wire:target="aiGenerateSummary">{{ __('ai.thinking') }}</span>
                </x-ui.button>
                @endif
                <x-ui.button tag="a" :href="route('projects.export.pdf', [ $project->id, 'template' => 'modern'])" variant="accent" icon="file-down" size="sm">
                    {{ __('projects.show.premium_report') }}
                </x-ui.button>
                <x-ui.button tag="a" :href="route('projects.export.excel', $project->id)" variant="outline" icon="file-spreadsheet" size="sm">
                    {{ __('common.export_excel') }}
                </x-ui.button>
                <x-ui.button tag="a" :href="route('project.templates')" variant="outline" icon="copy-plus" size="sm" wire:navigate
                    x-data x-on:click.prevent="if(confirm('{{ __('projects.templates.duplicate_project') }} ?')) window.location='{{ route('project.templates') }}?duplicate={{ $project->id }}'">
                    {{ __('projects.templates.duplicate_project') }}
                </x-ui.button>
                @can('update', $project)
                    <x-ui.button wire:click="toggleTemplate" variant="{{ $project->is_template ? 'accent' : 'ghost' }}" icon="{{ $project->is_template ? 'layout-template' : 'layout-template' }}" size="sm">
                        {{ $project->is_template ? __('projects.templates.unmark_template') : __('projects.templates.mark_as_template') }}
                    </x-ui.button>
                @endcan
                <livewire:v-beta.project.project-share-livewire :projectId="$project->id" />
                <x-ui.button tag="a" :href="route('project.list')" variant="ghost" icon="arrow-left" size="sm" wire:navigate>
                    {{ __('common.back') }}
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Tabs Navigation --}}
        <div class="flex items-center gap-2 border-b border-border-light mb-6 overflow-x-auto no-scrollbar">
            <button wire:click="switchTab('overview')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'overview' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.overview') }}
                @if($activeTab === 'overview') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('logframe')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'logframe' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.logframe') }}
                @if($activeTab === 'logframe') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('documents')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'documents' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.documents') }}
                @if($activeTab === 'documents') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('analytics')" 
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'analytics' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.analyses') }}
                @if($activeTab === 'analytics') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('timeline')"
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'timeline' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.timeline') }}
                @if($activeTab === 'timeline') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('tracking')"
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'tracking' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.tracking') }}
                @if($activeTab === 'tracking') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
            <button wire:click="switchTab('history')"
                class="px-4 py-2 text-sm font-medium transition-colors relative whitespace-nowrap {{ $activeTab === 'history' ? 'text-accent' : 'text-subtle hover:text-body' }}">
                {{ __('projects.show.tabs.history') }}
                @if($activeTab === 'history') <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-accent"></div> @endif
            </button>
        </div>

        <div class="space-y-6">

            @if($activeTab === 'overview')
                {{-- Section 1: Informations Générales --}}
                <x-ui.section :title="__('projects.show.general_info')" icon="info">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.title') }}</span>
                                <span class="font-semibold text-heading">{{ $project->title }}</span>
                            </div>
                            @if($project->short_title)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('projects.show.short_title') }}</span>
                                <span class="text-body">{{ $project->short_title }}</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.status') }}</span>
                                @php
                                    $statusEnum = $project->status instanceof \App\Enums\ProjectStatus ? $project->status : \App\Enums\ProjectStatus::tryFrom($project->status);
                                    $badgeVariant = $statusEnum ? $statusEnum->color() : 'slate';
                                @endphp
                                <x-ui.badge :variant="$badgeVariant">{{ $statusEnum ? $statusEnum->label() : $project->status }}</x-ui.badge>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.period') }}</span>
                                <span class="text-body">
                                    {{ $project->start_date?->format('d/m/Y') }} → {{ $project->end_date?->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.created_at') }}</span>
                                <span class="text-body">{{ $project->created_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.updated_at') }}</span>
                                <span class="text-body">{{ $project->updated_at?->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($project->creator)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.created_by') }}</span>
                                <span class="text-body">{{ $project->creator->name }}</span>
                            </div>
                            @endif
                            @if($project->projectType)
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-28">{{ __('common.type') }}</span>
                                <x-ui.badge variant="accent" size="sm">{{ $project->projectType->name }}</x-ui.badge>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($project->description)
                        <div class="mt-6 pt-5 border-t border-border-light">
                            <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-2">{{ __('common.description') }}</p>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl text-sm leading-relaxed">
                                {!! $project->description !!}
                            </div>
                        </div>
                    @endif
                </x-ui.section>

                {{-- Section 2: Contexte du projet --}}
                @if($project->context_description || $project->problem_analysis || $project->strategy || $project->justification)
                    <x-ui.section :title="__('projects.show.context')" icon="file-text">
                        <div class="space-y-4">
                            @if($project->context_description)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ __('projects.show.context_desc') }}</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl text-justify leading-relaxed">{!! clean($project->context_description) !!}</div>
                            </div>
                            @endif
                            @if($project->justification)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ __('projects.show.justification') }}</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{!! $project->justification !!}</div>
                            </div>
                            @endif
                            @if($project->strategy)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ __('projects.show.strategy') }}</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{!! $project->strategy !!}</div>
                            </div>
                            @endif
                            @if($project->problem_analysis)
                            <div>
                                <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ __('projects.show.problem_analysis') }}</p>
                                <div class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{!! $project->problem_analysis !!}</div>
                            </div>
                            @endif
                        </div>
                    </x-ui.section>
                @endif

                {{-- Section 3: Champs Dynamiques (lu depuis general_objectives JSON) --}}
                @if(is_array($dynamicFormFields) && count($dynamicFormFields) > 0)
                    @php $fieldValues = $project->general_objectives ?? []; @endphp
                    @foreach($dynamicFormFields as $section => $fields)
                        <x-ui.section :title="ucfirst($section)" icon="puzzle">
                            <div class="space-y-4">
                                @foreach($fields as $fieldDef)
                                    @php
                                        $value = $fieldValues[$fieldDef['field_name']] ?? null;
                                        if (is_array($value)) $value = implode(', ', $value);
                                    @endphp
                                    @if($value)
                                        <div>
                                            <p class="text-[11px] font-bold text-muted uppercase tracking-wider mb-1.5">{{ $fieldDef['question_text'] }}</p>
                                            <p class="text-sm text-body bg-surface dark:bg-surface-alt/50 p-4 rounded-xl leading-relaxed">{{ $value }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </x-ui.section>
                    @endforeach
                @endif
            @endif

            @if($activeTab === 'logframe')
                    {{-- Section 4: Cadre Logique --}}
                    @if($project->logicalFramework)

                        {{-- Selecteur de format --}}
                        <div class="flex items-center gap-2 mb-6">
                            <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ __('projects.show.format') }} :</span>
                            @foreach(\App\Enums\LogframeDisplayFormat::cases() as $format)
                                <button type="button"
                                    wire:click="setLogframeFormat('{{ $format->value }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $logframeFormat === $format->value ? 'bg-accent text-white shadow-sm' : 'bg-surface-alt text-subtle hover:bg-border dark:hover:bg-surface-alt' }}">
                                    <x-dynamic-component :component="'lucide-' . $format->icon()" class="w-3.5 h-3.5" />
                                    {{ $format->label() }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Contenu selon le format choisi --}}
                        @include('livewire.v-beta.project.logframe.format-' . $logframeFormat)

                    @endif

                @endif

                @if($activeTab === 'documents')
                    {{-- Section: Documents --}}
                    @if($project->documents->isNotEmpty())
                        <x-ui.section :title="__('projects.show.documents')" icon="paperclip">
                            <ul class="space-y-2">
                                @foreach($project->documents as $document)
                                    <li>
                                        <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" 
                                        class="flex items-center gap-3 p-3 rounded-xl hover:bg-surface dark:hover:bg-surface-alt/50 transition-colors group">
                                            <x-lucide-file class="w-4 h-4 text-accent opacity-60" />
                                            <span class="text-sm font-medium text-body group-hover:text-accent transition-colors">
                                                {{ $document->file_name }}
                                            </span>
                                            <x-ui.badge variant="slate" size="sm">{{ strtoupper($document->file_type ?? 'N/A') }}</x-ui.badge>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </x-ui.section>
                    @else
                        <x-ui.empty-state icon="file-question" :title="__('projects.show.no_documents')" :description="__('projects.show.no_documents_desc')" />
                    @endif
                @endif

                @if($activeTab === 'analytics')
                    <x-ui.section :title="__('indicators.tracking_title')" icon="target">
                        @livewire('v-beta.project.indicator-tracking-livewire', ['projectId' => $project->id])
                    </x-ui.section>
                @endif

                @if($activeTab === 'timeline')
                    @include('livewire.v-beta.project.include.gantt-timeline', ['project' => $project])
                @endif

                @if($activeTab === 'tracking')
                    {{-- Section: Suivi de progression --}}
                    @livewire('v-beta.project.project-progress-comparison-livewire', ['projectId' => $project->id])
                @endif

                @if($activeTab === 'history')
                    {{-- Section: Historique --}}
                    <x-ui.section :title="__('projects.show.audit_trail')" icon="history">
                        @livewire('v-beta.audit.activity-history-livewire', ['subject' => $project])
                    </x-ui.section>
                @endif

        </div>
        {{-- AI Summary Modal --}}
        @if($aiSummary ?? false)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" wire:click.self="$set('aiSummary', null)">
            <div class="bg-card rounded-2xl shadow-2xl border border-purple-200 dark:border-purple-800 max-w-xl w-full mx-4 overflow-hidden">
                <div class="p-4 bg-purple-50 dark:bg-purple-900/20 border-b border-purple-200 dark:border-purple-800 flex items-center justify-between">
                    <span class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest flex items-center gap-1.5">
                        <x-lucide-sparkles class="w-4 h-4" /> {{ __('ai.generate_summary') }}
                    </span>
                    <button wire:click="$set('aiSummary', null)" class="text-purple-400 hover:text-purple-600">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-5">
                    <p class="text-sm text-body leading-relaxed whitespace-pre-line">{{ $aiSummary }}</p>
                </div>
                <div class="px-5 pb-4 flex justify-end gap-2">
                    <button wire:click="aiGenerateSummary" class="text-[10px] font-bold text-purple-500 hover:text-purple-700 px-3 py-1.5 rounded-lg hover:bg-purple-50 transition-colors">
                        {{ __('ai.regenerate') }}
                    </button>
                    <button wire:click="$set('aiSummary', null)" class="text-[10px] font-bold text-white bg-purple-600 hover:bg-purple-700 px-3 py-1.5 rounded-lg transition-colors">
                        {{ __('common.close') }}
                    </button>
                </div>
            </div>
        </div>
        @endif

    @else
        <x-ui.empty-state icon="folder-x" :title="__('projects.show.not_found')" :description="__('projects.show.not_found_desc')">
            <x-ui.button tag="a" :href="route('project.list')" variant="outline" icon="arrow-left" size="sm" wire:navigate>
                {{ __('common.back') }}
            </x-ui.button>
        </x-ui.empty-state>
    @endif
</x-ui.page-layout>
