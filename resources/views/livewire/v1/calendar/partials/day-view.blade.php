{{-- Day View: Detailed list --}}
@php
    $dayStr = $date->format('Y-m-d');
    $dayEvents = collect($events)->filter(fn($e) => ($e['start'] ?? '') <= $dayStr && ($e['end'] ?? $e['start'] ?? '') >= $dayStr)->values();
@endphp

<div class="max-w-2xl mx-auto space-y-3">
    @forelse($dayEvents as $evt)
        <button wire:click="selectActivity('{{ $evt['id'] }}')"
                class="w-full text-left bg-card rounded-xl border border-border-light p-4 hover:border-accent/30 transition-all"
                style="border-left: 4px solid {{ $evt['color'] }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-bold text-heading">{{ $evt['title'] }}</p>
                    @if($evt['project'])
                        <p class="text-[10px] text-muted mt-0.5">{{ $evt['project'] }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold" style="background: {{ $evt['color'] }}15; color: {{ $evt['color'] }}">
                        {{ $evt['status'] }}
                    </span>
                    <span class="text-xs font-black" style="color: {{ $evt['color'] }}">{{ $evt['progress'] }}%</span>
                </div>
            </div>

            <div class="flex items-center gap-4 mt-3 text-xs text-muted">
                @if($evt['responsible'])
                    <span class="flex items-center gap-1">
                        <x-lucide-user class="w-3 h-3" /> {{ $evt['responsible'] }}
                    </span>
                @endif
                <span class="flex items-center gap-1">
                    <x-lucide-calendar class="w-3 h-3" />
                    {{ \Carbon\Carbon::parse($evt['start'])->format('d/m') }} → {{ \Carbon\Carbon::parse($evt['end'] ?? $evt['start'])->format('d/m') }}
                </span>
            </div>

            {{-- Progress bar --}}
            <div class="w-full bg-surface rounded-full h-1.5 mt-3">
                <div class="h-1.5 rounded-full transition-all" style="width: {{ $evt['progress'] }}%; background: {{ $evt['color'] }}"></div>
            </div>
        </button>
    @empty
        <div class="text-center py-12">
            <x-lucide-calendar-x class="w-12 h-12 text-muted/30 mx-auto mb-3" />
            <p class="text-sm text-muted">{{ __('calendar.no_events') }}</p>
        </div>
    @endforelse
</div>
