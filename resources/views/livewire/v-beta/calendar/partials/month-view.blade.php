{{-- Month View: Classic 7-column grid --}}
@php
    $firstDay = $date->copy()->startOfMonth();
    $lastDay = $date->copy()->endOfMonth();
    $startPad = ($firstDay->dayOfWeekIso - 1);
    $totalCells = $startPad + $lastDay->day;
    $rows = ceil($totalCells / 7);
    $dayNames = [__('calendar.mon'), __('calendar.tue'), __('calendar.wed'), __('calendar.thu'), __('calendar.fri'), __('calendar.sat'), __('calendar.sun')];
@endphp

<div class="bg-card rounded-xl border border-border-light overflow-hidden">
    {{-- Header --}}
    <div class="grid grid-cols-7 bg-surface">
        @foreach($dayNames as $day)
            <div class="px-2 py-2 text-[10px] font-black text-muted uppercase tracking-widest text-center border-b border-border-light">
                {{ $day }}
            </div>
        @endforeach
    </div>

    {{-- Days --}}
    <div class="grid grid-cols-7">
        @for($cell = 0; $cell < $rows * 7; $cell++)
            @php
                $dayNum = $cell - $startPad + 1;
                $isValidDay = $dayNum >= 1 && $dayNum <= $lastDay->day;
                $dayStr = $isValidDay ? $date->format('Y-m-') . str_pad($dayNum, 2, '0', STR_PAD_LEFT) : null;
                $isToday = $dayStr === now()->format('Y-m-d');
                $dayEvents = $dayStr ? collect($events)->filter(fn($e) => ($e['start'] ?? '') <= $dayStr && ($e['end'] ?? $e['start'] ?? '') >= $dayStr)->values() : collect();
            @endphp
            <div class="min-h-[80px] border-b border-r border-border-light p-1 {{ !$isValidDay ? 'bg-surface/50' : '' }} {{ $isToday ? 'bg-accent/5' : '' }}">
                @if($isValidDay)
                    <button wire:click="goToDay('{{ $dayStr }}')"
                            class="w-6 h-6 rounded-full text-[10px] font-bold mb-1 transition-colors
                                {{ $isToday ? 'bg-accent text-white' : 'text-heading hover:bg-surface' }}">
                        {{ $dayNum }}
                    </button>

                    @foreach($dayEvents->take(3) as $evt)
                        <button wire:click="selectActivity('{{ $evt['id'] }}')"
                                class="w-full text-left px-1.5 py-0.5 rounded text-[9px] font-bold truncate mb-0.5 transition-opacity hover:opacity-80"
                                style="background: {{ $evt['color'] }}20; color: {{ $evt['color'] }}; border-left: 2px solid {{ $evt['color'] }}">
                            {{ Str::limit($evt['title'], 20) }}
                        </button>
                    @endforeach

                    @if($dayEvents->count() > 3)
                        <button wire:click="goToDay('{{ $dayStr }}')"
                                class="text-[8px] font-bold text-accent hover:underline">
                            +{{ $dayEvents->count() - 3 }} {{ __('calendar.more') }}
                        </button>
                    @endif
                @endif
            </div>
        @endfor
    </div>
</div>
