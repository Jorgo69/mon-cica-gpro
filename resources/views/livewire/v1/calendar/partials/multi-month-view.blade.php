{{-- Multi-Month View (Semester/Quarter) --}}
@php $startMonth = $date->copy()->startOfMonth(); @endphp
<div class="grid grid-cols-1 md:grid-cols-{{ min($months, 3) }} gap-6">
    @for($i = 0; $i < $months; $i++)
        @php $monthDate = $startMonth->copy()->addMonths($i); @endphp
        <div class="bg-card rounded-xl border border-border-light p-4">
            <button wire:click="$set('currentDate', '{{ $monthDate->format('Y-m-d') }}'); $wire.setView('month')"
                    class="text-sm font-black text-heading mb-3 block hover:text-accent transition-colors">
                {{ $monthDate->translatedFormat('F Y') }}
            </button>

            {{-- Mini day grid --}}
            <div class="grid grid-cols-7 gap-0.5 text-[9px]">
                @foreach(['L','M','M','J','V','S','D'] as $d)
                    <div class="text-center font-bold text-muted">{{ $d }}</div>
                @endforeach

                @php
                    $firstDay = $monthDate->copy()->startOfMonth();
                    $startPad = ($firstDay->dayOfWeekIso - 1);
                    $daysInMonth = $monthDate->daysInMonth;
                @endphp

                @for($p = 0; $p < $startPad; $p++)
                    <div></div>
                @endfor

                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $dayStr = $monthDate->format('Y-m-') . str_pad($d, 2, '0', STR_PAD_LEFT);
                        $dayEvents = collect($events)->filter(fn($e) => ($e['start'] ?? '') <= $dayStr && ($e['end'] ?? $e['start'] ?? '') >= $dayStr);
                        $isToday = $dayStr === now()->format('Y-m-d');
                    @endphp
                    <button wire:click="goToDay('{{ $dayStr }}')"
                            class="h-6 rounded text-center leading-6 transition-colors
                                {{ $isToday ? 'bg-accent text-white font-black' : ($dayEvents->count() > 0 ? 'bg-accent/10 text-accent font-bold hover:bg-accent/20' : 'text-body hover:bg-surface') }}">
                        {{ $d }}
                    </button>
                @endfor
            </div>

            {{-- Month event list --}}
            @php $monthEvts = collect($events)->filter(fn($e) => str_starts_with($e['start'] ?? '', $monthDate->format('Y-m')))->take(5); @endphp
            @if($monthEvts->count() > 0)
                <div class="mt-3 space-y-1">
                    @foreach($monthEvts as $evt)
                        <button wire:click="selectActivity('{{ $evt['id'] }}')"
                                class="w-full text-left px-2 py-1 rounded text-[10px] truncate hover:bg-surface transition-colors"
                                style="border-left: 3px solid {{ $evt['color'] }}">
                            {{ Str::limit($evt['title'], 30) }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endfor
</div>
