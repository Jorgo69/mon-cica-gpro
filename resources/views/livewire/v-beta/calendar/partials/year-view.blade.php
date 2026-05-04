{{-- Year View: 12 month mini-grids --}}
<div class="grid grid-cols-3 md:grid-cols-4 gap-4">
    @for($m = 1; $m <= 12; $m++)
        @php
            $monthDate = $date->copy()->month($m)->startOfMonth();
            $monthEvents = collect($events)->filter(function ($e) use ($monthDate) {
                $prefix = $monthDate->format('Y-m');
                return str_starts_with($e['start'] ?? '', $prefix) || str_starts_with($e['end'] ?? '', $prefix);
            });
            $count = $monthEvents->count();
        @endphp
        <button wire:click="$set('currentDate', '{{ $monthDate->format('Y-m-d') }}'); $wire.setView('month')"
                class="p-3 rounded-xl border border-border-light hover:border-accent/30 transition-all bg-card text-center {{ $monthDate->isCurrentMonth() ? 'ring-2 ring-accent/30' : '' }}">
            <p class="text-xs font-black text-heading mb-1">{{ $monthDate->translatedFormat('M') }}</p>
            @if($count > 0)
                <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-xs font-black
                    {{ $count > 10 ? 'bg-accent text-white' : ($count > 5 ? 'bg-accent/20 text-accent' : 'bg-surface text-body') }}">
                    {{ $count }}
                </div>
            @else
                <div class="w-8 h-8 mx-auto rounded-full bg-surface flex items-center justify-center">
                    <span class="text-[10px] text-muted">0</span>
                </div>
            @endif
        </button>
    @endfor
</div>
