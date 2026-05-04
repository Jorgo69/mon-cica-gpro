{{-- Week View: 7 columns --}}
@php
    $startOfWeek = $date->copy()->startOfWeek();
    $dayNames = [__('calendar.mon'), __('calendar.tue'), __('calendar.wed'), __('calendar.thu'), __('calendar.fri'), __('calendar.sat'), __('calendar.sun')];
@endphp

<div class="bg-card rounded-xl border border-border-light overflow-hidden">
    <div class="grid grid-cols-7">
        @for($d = 0; $d < 7; $d++)
            @php
                $day = $startOfWeek->copy()->addDays($d);
                $dayStr = $day->format('Y-m-d');
                $isToday = $dayStr === now()->format('Y-m-d');
                $dayEvents = collect($events)->filter(fn($e) => ($e['start'] ?? '') <= $dayStr && ($e['end'] ?? $e['start'] ?? '') >= $dayStr)->values();
            @endphp
            <div class="border-r border-border-light last:border-r-0 min-h-[300px]">
                {{-- Day header --}}
                <div class="px-2 py-2 text-center border-b border-border-light {{ $isToday ? 'bg-accent/10' : 'bg-surface' }}">
                    <p class="text-[9px] font-bold text-muted uppercase">{{ $dayNames[$d] }}</p>
                    <button wire:click="goToDay('{{ $dayStr }}')"
                            class="w-8 h-8 rounded-full text-sm font-black mx-auto flex items-center justify-center
                                {{ $isToday ? 'bg-accent text-white' : 'text-heading hover:bg-surface-alt' }}">
                        {{ $day->day }}
                    </button>
                </div>

                {{-- Events --}}
                <div class="p-1.5 space-y-1">
                    @foreach($dayEvents as $evt)
                        <button wire:click="selectActivity('{{ $evt['id'] }}')"
                                class="w-full text-left p-2 rounded-lg text-[10px] transition-opacity hover:opacity-80"
                                style="background: {{ $evt['color'] }}15; border-left: 3px solid {{ $evt['color'] }}">
                            <p class="font-bold truncate" style="color: {{ $evt['color'] }}">{{ Str::limit($evt['title'], 25) }}</p>
                            @if($evt['responsible'])
                                <p class="text-[9px] text-muted mt-0.5">{{ $evt['responsible'] }}</p>
                            @endif
                            <div class="w-full bg-white/50 rounded-full h-1 mt-1">
                                <div class="h-1 rounded-full" style="width: {{ $evt['progress'] }}%; background: {{ $evt['color'] }}"></div>
                            </div>
                        </button>
                    @endforeach

                    @if($dayEvents->isEmpty())
                        <p class="text-[9px] text-muted text-center py-4">—</p>
                    @endif
                </div>
            </div>
        @endfor
    </div>
</div>
