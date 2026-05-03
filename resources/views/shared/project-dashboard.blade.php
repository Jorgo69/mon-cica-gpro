<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — {{ __('shared.title') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-surface text-body min-h-screen">

    {{-- Top Bar --}}
    <header class="bg-card border-b border-border-light sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @php $shareOrg = $project->organization; @endphp
                @if($shareOrg?->logo_path)
                    <img src="{{ asset('storage/' . $shareOrg->logo_path) }}" alt="{{ $shareOrg->name }}" class="w-8 h-8 rounded-lg object-contain" />
                @else
                    <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center">
                        <x-lucide-share-2 class="w-4 h-4 text-accent" />
                    </div>
                @endif
                <div>
                    <h1 class="text-sm font-bold text-heading">{{ $project->title }}</h1>
                    <p class="text-[10px] text-muted">
                        {{ $shareOrg?->name ?? config('app.name') }} &middot; {{ $project->project_code }} &middot; {{ __('shared.read_only') }}
                    </p>
                </div>
            </div>
            @if($shareToken->label)
                <span class="text-[10px] font-medium text-muted bg-surface px-2 py-1 rounded-md">{{ $shareToken->label }}</span>
            @endif
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- Progress Overview --}}
        <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('shared.progress') }}</span>
                <span class="text-2xl font-black text-accent">{{ number_format($stats['progress'], 1) }}%</span>
                <div class="mt-2 w-full bg-surface rounded-full h-1.5">
                    <div class="bg-accent h-1.5 rounded-full transition-all" style="width: {{ min($stats['progress'], 100) }}%"></div>
                </div>
            </div>
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('shared.activities') }}</span>
                <span class="text-2xl font-black text-heading">{{ $stats['total_activities'] }}</span>
                <p class="text-[10px] text-muted mt-1">
                    <span class="text-success">{{ $stats['completed'] }}</span> {{ __('shared.done') }} &middot;
                    <span class="text-accent">{{ $stats['in_progress'] }}</span> {{ __('shared.ongoing') }}
                </p>
            </div>
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('shared.budget') }}</span>
                <span class="text-2xl font-black text-heading">{{ $budget['percent'] }}%</span>
                <p class="text-[10px] text-muted mt-1">{{ number_format($budget['spent'], 0, ',', ' ') }} / {{ number_format($budget['planned'], 0, ',', ' ') }}</p>
            </div>
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('shared.period') }}</span>
                <span class="text-sm font-bold text-heading">
                    {{ $project->start_date?->format('d/m/Y') ?? '—' }}
                </span>
                <p class="text-[10px] text-muted mt-1">{{ __('common.to') }} {{ $project->end_date?->format('d/m/Y') ?? '—' }}</p>
            </div>
        </section>

        {{-- Overdue Alert --}}
        @if($stats['overdue'] > 0)
            <div class="bg-error/10 border border-error/20 rounded-xl p-4 flex items-center gap-3">
                <x-lucide-alert-triangle class="w-5 h-5 text-error flex-shrink-0" />
                <p class="text-sm text-error font-medium">{{ __('shared.overdue_alert', ['count' => $stats['overdue']]) }}</p>
            </div>
        @endif

        {{-- Logical Framework Summary --}}
        @if($project->logicalFramework)
        <section class="bg-card rounded-2xl border border-border-light p-6">
            <h2 class="text-sm font-black text-heading uppercase tracking-wider mb-4">{{ __('shared.logframe') }}</h2>

            @if($project->logicalFramework->general_objective)
                <div class="mb-4 p-3 bg-accent/5 rounded-xl border border-accent/10">
                    <span class="text-[9px] font-black text-accent uppercase tracking-widest">{{ __('shared.general_objective') }}</span>
                    <p class="text-sm text-heading mt-1">{{ $project->logicalFramework->general_objective }}</p>
                </div>
            @endif

            @foreach($project->logicalFramework->specificObjectives as $i => $so)
                <div class="mb-3 p-3 bg-surface rounded-xl">
                    <span class="text-[9px] font-black text-muted uppercase tracking-widest">{{ __('shared.specific_objective') }} {{ $i + 1 }}</span>
                    <p class="text-sm text-heading mt-1">{{ $so->description }}</p>

                    @foreach($so->results as $j => $result)
                        <div class="ml-4 mt-2 pl-3 border-l-2 border-border-light">
                            <span class="text-[9px] font-bold text-muted">{{ __('shared.result') }} {{ $i + 1 }}.{{ $j + 1 }}</span>
                            <p class="text-xs text-subtle">{{ $result->description }}</p>

                            @if($result->activities->count())
                                <div class="mt-2 space-y-1">
                                    @foreach($result->activities as $activity)
                                        <div class="flex items-center justify-between gap-2 py-1">
                                            <div class="flex items-center gap-2 min-w-0">
                                                @php
                                                    $color = match($activity->status) {
                                                        \App\Enums\ActivityStatus::COMPLETED => 'bg-success',
                                                        \App\Enums\ActivityStatus::ONGOING => 'bg-accent',
                                                        default => 'bg-muted/30',
                                                    };
                                                @endphp
                                                <div class="w-2 h-2 rounded-full {{ $color }} flex-shrink-0"></div>
                                                <span class="text-xs text-body truncate">{{ $activity->description }}</span>
                                            </div>
                                            <span class="text-[10px] font-bold text-muted flex-shrink-0">{{ $activity->progress_percentage }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </section>
        @endif

        {{-- Budget Breakdown --}}
        @if($project->budgets->count())
        <section class="bg-card rounded-2xl border border-border-light p-6">
            <h2 class="text-sm font-black text-heading uppercase tracking-wider mb-4">{{ __('shared.budget_breakdown') }}</h2>
            <div class="space-y-3">
                @foreach($project->budgets as $budgetLine)
                    @php
                        $lineSpent = $project->expenses->where('budget_id', $budgetLine->id)->sum('amount');
                        $linePlanned = (float) ($budgetLine->total_cost ?? 0);
                        $linePercent = $linePlanned > 0 ? round(($lineSpent / $linePlanned) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-body">{{ $budgetLine->description }}</span>
                            <span class="text-[10px] font-bold {{ $linePercent > 100 ? 'text-error' : 'text-muted' }}">{{ $linePercent }}%</span>
                        </div>
                        <div class="w-full bg-surface rounded-full h-1.5">
                            <div class="{{ $linePercent > 100 ? 'bg-error' : ($linePercent > 80 ? 'bg-warning' : 'bg-accent') }} h-1.5 rounded-full" style="width: {{ min($linePercent, 100) }}%"></div>
                        </div>
                        <p class="text-[10px] text-muted mt-0.5">{{ number_format($lineSpent, 0, ',', ' ') }} / {{ number_format($linePlanned, 0, ',', ' ') }}</p>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Indicators --}}
        @if($project->logicalFramework)
            @php
                $indicators = collect();
                foreach ($project->logicalFramework->specificObjectives as $so) {
                    if (method_exists($so, 'indicatorItems')) {
                        $indicators = $indicators->merge($so->indicatorItems);
                    }
                    foreach ($so->results as $result) {
                        if (method_exists($result, 'indicatorItems')) {
                            $indicators = $indicators->merge($result->indicatorItems);
                        }
                    }
                }
            @endphp

            @if($indicators->count())
            <section class="bg-card rounded-2xl border border-border-light p-6">
                <h2 class="text-sm font-black text-heading uppercase tracking-wider mb-4">{{ __('shared.key_indicators') }}</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="text-left text-muted">
                                <th class="pb-2 font-bold">{{ __('common.description') }}</th>
                                <th class="pb-2 font-bold">{{ __('common.baseline') }}</th>
                                <th class="pb-2 font-bold">{{ __('common.target') }}</th>
                                <th class="pb-2 font-bold">{{ __('common.verification_source') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light">
                            @foreach($indicators as $ind)
                                <tr>
                                    <td class="py-2 text-body">{{ $ind->description }}</td>
                                    <td class="py-2 text-subtle">{{ $ind->baseline_value ?? '—' }}</td>
                                    <td class="py-2 font-bold text-heading">{{ $ind->target_value ?? '—' }}</td>
                                    <td class="py-2 text-subtle">{{ $ind->verification_source ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif
        @endif

    </main>

    {{-- Footer --}}
    <footer class="border-t border-border-light mt-12 py-6 text-center">
        <p class="text-[10px] text-muted">
            {{ __('shared.powered_by') }} CICA-GPRO &middot; {{ __('shared.generated_on') }} {{ now()->format('d/m/Y H:i') }}
        </p>
    </footer>

</body>
</html>
