<div x-data="calendarApp(@js($events), '{{ $date->format('Y-m-d') }}', '{{ $viewMode }}')" class="space-y-4">

    {{-- Toolbar --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        {{-- Navigation --}}
        <div class="flex items-center gap-2">
            <button wire:click="navigate('prev')" class="p-2 rounded-lg bg-surface hover:bg-surface-alt transition-colors">
                <x-lucide-chevron-left class="w-4 h-4 text-heading" />
            </button>
            <button wire:click="goToToday" class="px-3 py-1.5 text-xs font-bold text-accent hover:bg-accent/5 rounded-lg transition-colors">
                {{ __('calendar.today') }}
            </button>
            <button wire:click="navigate('next')" class="p-2 rounded-lg bg-surface hover:bg-surface-alt transition-colors">
                <x-lucide-chevron-right class="w-4 h-4 text-heading" />
            </button>
            <h2 class="text-sm font-black text-heading ml-2">
                @switch($viewMode)
                    @case('year')
                        {{ $date->format('Y') }}
                        @break
                    @case('day')
                        {{ $date->translatedFormat('l j F Y') }}
                        @break
                    @case('week')
                        {{ __('calendar.week') }} {{ $date->weekOfYear }} — {{ $date->translatedFormat('F Y') }}
                        @break
                    @default
                        {{ $date->translatedFormat('F Y') }}
                @endswitch
            </h2>
        </div>

        {{-- View modes --}}
        <div class="flex items-center gap-1 bg-surface rounded-xl p-1">
            @foreach(['year', 'semester', 'quarter', 'month', 'week', 'day'] as $mode)
                <button wire:click="setView('{{ $mode }}')"
                        class="px-2.5 py-1 text-[10px] font-bold rounded-lg transition-colors {{ $viewMode === $mode ? 'bg-accent text-white' : 'text-muted hover:text-heading' }}">
                    {{ __('calendar.' . $mode) }}
                </button>
            @endforeach
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-2">
            <select wire:model.live="projectFilter" class="input-field text-xs py-1.5">
                <option value="">{{ __('calendar.all_projects') }}</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                @endforeach
            </select>
            <button wire:click="exportIcal" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-accent bg-accent/5 hover:bg-accent/10 transition-colors">
                <x-lucide-download class="w-3.5 h-3.5" />
                iCal
            </button>
        </div>
    </div>

    {{-- Calendar Grid --}}
    @if($viewMode === 'year')
        @include('livewire.v-beta.calendar.partials.year-view', ['date' => $date, 'events' => $events])
    @elseif(in_array($viewMode, ['semester', 'quarter']))
        @include('livewire.v-beta.calendar.partials.multi-month-view', ['date' => $date, 'events' => $events, 'months' => $viewMode === 'semester' ? 6 : 3])
    @elseif($viewMode === 'month')
        @include('livewire.v-beta.calendar.partials.month-view', ['date' => $date, 'events' => $events])
    @elseif($viewMode === 'week')
        @include('livewire.v-beta.calendar.partials.week-view', ['date' => $date, 'events' => $events])
    @elseif($viewMode === 'day')
        @include('livewire.v-beta.calendar.partials.day-view', ['date' => $date, 'events' => $events])
    @endif

    {{-- Activity Detail Modal --}}
    @if($selectedActivity)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="closeActivityModal">
        <div class="bg-card rounded-2xl shadow-2xl border border-border-light w-full max-w-md p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="text-sm font-black text-heading">{{ $selectedActivity->description }}</p>
                    <p class="text-[10px] text-muted mt-1">
                        {{ $selectedActivity->result?->specificObjective?->logicalFramework?->project?->title }}
                    </p>
                </div>
                <button wire:click="closeActivityModal" class="text-muted hover:text-heading">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background: {{ $selectedActivity->status?->hex() }}"></span>
                    <span class="font-bold">{{ $selectedActivity->status?->label() }}</span>
                    <span class="text-muted">{{ $selectedActivity->progress_percentage }}%</span>
                </div>

                @if($selectedActivity->responsibleUser)
                <div class="flex items-center gap-2 text-body">
                    <x-lucide-user class="w-3.5 h-3.5 text-muted" />
                    {{ $selectedActivity->responsibleUser->name }}
                </div>
                @endif

                <div class="flex items-center gap-2 text-body">
                    <x-lucide-calendar class="w-3.5 h-3.5 text-muted" />
                    {{ $selectedActivity->start_date?->format('d/m/Y') }} → {{ $selectedActivity->end_date?->format('d/m/Y') }}
                </div>

                @if($selectedActivity->budget)
                <div class="flex items-center gap-2 text-body">
                    <x-lucide-banknote class="w-3.5 h-3.5 text-muted" />
                    {{ number_format($selectedActivity->budget, 0, ',', ' ') }}
                </div>
                @endif

                {{-- Progress bar --}}
                <div class="w-full bg-surface rounded-full h-2 mt-2">
                    <div class="h-2 rounded-full transition-all" style="width: {{ $selectedActivity->progress_percentage }}%; background: {{ $selectedActivity->status?->hex() }}"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('alpine-js')
<script>
function calendarApp(events, currentDate, viewMode) {
    return {
        events: events,
        getEventsForDate(dateStr) {
            return this.events.filter(e => {
                if (!e.start) return false;
                const start = e.start;
                const end = e.end || e.start;
                return dateStr >= start && dateStr <= end;
            });
        },
        getEventsForMonth(year, month) {
            const prefix = year + '-' + String(month).padStart(2, '0');
            return this.events.filter(e => {
                if (!e.start) return false;
                return e.start.startsWith(prefix) || (e.end && e.end.startsWith(prefix));
            });
        }
    };
}
</script>
@endpush
