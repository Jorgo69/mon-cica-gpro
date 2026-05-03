{{-- Gantt Timeline --}}
@php
    $activities = $project->getAllActivities();

    // Determine project time bounds
    $projectStart = $project->start_date ?? $activities->min('start_date') ?? now();
    $projectEnd = $project->end_date ?? $activities->max('end_date') ?? now()->addMonths(6);

    $startCarbon = \Carbon\Carbon::parse($projectStart)->startOfMonth();
    $endCarbon = \Carbon\Carbon::parse($projectEnd)->endOfMonth();

    $totalDays = max($startCarbon->diffInDays($endCarbon), 1);

    // Build months for header
    $months = [];
    $cursor = $startCarbon->copy();
    while ($cursor->lte($endCarbon)) {
        $monthStart = $cursor->copy();
        $monthEnd = $cursor->copy()->endOfMonth();
        if ($monthEnd->gt($endCarbon)) $monthEnd = $endCarbon->copy();
        $monthDays = $monthStart->diffInDays($monthEnd) + 1;
        $months[] = [
            'label' => $cursor->translatedFormat('M Y'),
            'width' => ($monthDays / $totalDays) * 100,
        ];
        $cursor->addMonth()->startOfMonth();
    }

    // Build activity rows with positioning
    $rows = [];
    if ($project->logicalFramework) {
        foreach ($project->logicalFramework->specificObjectives as $so) {
            foreach ($so->results as $result) {
                foreach ($result->activities as $activity) {
                    $aStart = $activity->start_date ? \Carbon\Carbon::parse($activity->start_date) : null;
                    $aEnd = $activity->end_date ? \Carbon\Carbon::parse($activity->end_date) : null;

                    if (!$aStart || !$aEnd) continue;

                    $left = max(0, $startCarbon->diffInDays($aStart) / $totalDays * 100);
                    $width = max(1, $aStart->diffInDays($aEnd) / $totalDays * 100);

                    $color = match($activity->status) {
                        \App\Enums\ActivityStatus::COMPLETED => 'bg-success',
                        \App\Enums\ActivityStatus::ONGOING => 'bg-accent',
                        \App\Enums\ActivityStatus::OVERDUE => 'bg-error',
                        \App\Enums\ActivityStatus::SUSPENDED => 'bg-warning',
                        \App\Enums\ActivityStatus::STOPPED, \App\Enums\ActivityStatus::ABANDONED => 'bg-error/40',
                        \App\Enums\ActivityStatus::PENDING => 'bg-amber-400',
                        default => 'bg-muted/30',
                    };

                    $rows[] = [
                        'activity' => $activity,
                        'left' => $left,
                        'width' => $width,
                        'color' => $color,
                        'result' => $result->description,
                    ];
                }
            }
        }
    }

    // Today marker
    $today = now();
    $todayPos = $today->between($startCarbon, $endCarbon)
        ? ($startCarbon->diffInDays($today) / $totalDays * 100)
        : null;
@endphp

<x-ui.section :title="__('projects.show.tabs.timeline')" icon="gantt-chart">

    @if(count($rows) === 0)
        <x-ui.empty-state icon="gantt-chart" :title="__('projects.timeline.no_data')" :description="__('projects.timeline.no_data_desc')" />
    @else
        {{-- Legend --}}
        <div class="flex items-center gap-4 mb-4 text-[10px]">
            <div class="flex items-center gap-1"><div class="w-3 h-2 rounded bg-success"></div> {{ __('common.completed') }}</div>
            <div class="flex items-center gap-1"><div class="w-3 h-2 rounded bg-accent"></div> {{ __('common.in_progress') }}</div>
            <div class="flex items-center gap-1"><div class="w-3 h-2 rounded bg-warning"></div> {{ __('common.pending') }}</div>
            <div class="flex items-center gap-1"><div class="w-3 h-2 rounded bg-muted/30"></div> {{ __('common.draft') }}</div>
            @if($todayPos)
                <div class="flex items-center gap-1"><div class="w-0.5 h-3 bg-error"></div> {{ __('projects.timeline.today') }}</div>
            @endif
        </div>

        <div class="overflow-x-auto">
            <div class="min-w-[800px]">

                {{-- Month Header --}}
                <div class="flex border-b border-border-light mb-1">
                    <div class="w-48 flex-shrink-0 px-2 py-1 text-[9px] font-black text-muted uppercase tracking-widest">{{ __('activities.description') }}</div>
                    <div class="flex-1 flex relative">
                        @foreach($months as $m)
                            <div class="text-center text-[9px] font-bold text-muted py-1 border-l border-border-light/50" style="width: {{ $m['width'] }}%">
                                {{ $m['label'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Activity Rows --}}
                @foreach($rows as $i => $row)
                    <div class="flex items-center group hover:bg-surface/50 transition-colors relative hover:z-30 {{ $i % 2 === 0 ? '' : 'bg-surface/30' }}"
                         x-data="{ show: false }" @mouseenter="show = true" @mouseleave="show = false">
                        {{-- Label --}}
                        <div class="w-48 flex-shrink-0 px-2 py-1.5 border-r border-border-light">
                            <p class="text-[11px] text-body truncate font-medium">{{ Str::limit(strip_tags($row['activity']->description), 35) }}</p>
                            <p class="text-[9px] text-muted truncate">{{ Str::limit(strip_tags($row['result']), 30) }}</p>
                        </div>

                        {{-- Bar Area --}}
                        <div class="flex-1 relative h-8">
                            {{-- Today marker --}}
                            @if($todayPos)
                                <div class="absolute top-0 bottom-0 w-px bg-error/60 z-10" style="left: {{ $todayPos }}%"></div>
                            @endif

                            {{-- Bar --}}
                            <div class="absolute top-1 h-6 rounded-md {{ $row['color'] }} flex items-center transition-all"
                                 style="left: {{ $row['left'] }}%; width: {{ $row['width'] }}%; min-width: 4px;">

                                {{-- Progress fill --}}
                                @if($row['activity']->progress_percentage > 0)
                                    <div class="absolute inset-y-0 left-0 rounded-l-md bg-black/15" style="width: {{ $row['activity']->progress_percentage }}%"></div>
                                @endif

                                {{-- Progress text --}}
                                @if($row['width'] > 5)
                                    <span class="relative z-10 text-[9px] font-bold text-white px-1.5 drop-shadow-sm">{{ $row['activity']->progress_percentage }}%</span>
                                @endif
                            </div>

                            {{-- Tooltip --}}
                            <div x-show="show" x-cloak
                                 class="absolute z-50 bottom-full mb-1 bg-card border border-border-light rounded-lg shadow-xl p-2 text-[10px] w-56 pointer-events-none"
                                 style="left: {{ $row['left'] }}%">
                                <p class="font-bold text-heading mb-1">{{ strip_tags($row['activity']->description) }}</p>
                                <div class="space-y-0.5 text-muted">
                                    <p>{{ __('common.status') }}: <span class="text-body">{{ $row['activity']->status?->label() }}</span></p>
                                    <p>{{ __('common.start_date') }}: {{ $row['activity']->start_date?->format('d/m/Y') }}</p>
                                    <p>{{ __('common.end_date') }}: {{ $row['activity']->end_date?->format('d/m/Y') }}</p>
                                    <p>{{ __('activities.progress') }}: <span class="font-bold text-body">{{ $row['activity']->progress_percentage }}%</span></p>
                                    @if($row['activity']->responsibleUser)
                                        <p>{{ __('common.responsible') }}: {{ $row['activity']->responsibleUser->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    @endif

</x-ui.section>
