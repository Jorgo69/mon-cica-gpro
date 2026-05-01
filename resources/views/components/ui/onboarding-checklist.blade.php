@props(['progress'])

@php
    $steps = $progress['steps'];
    $percent = $progress['percent'];
    $done = $progress['done'];
    $total = $progress['total'];
@endphp

<div class="bg-card rounded-2xl border border-accent/20 shadow-lg overflow-hidden mb-6" x-data="{ expanded: true }">
    {{-- Header --}}
    <div class="p-5 bg-gradient-to-r from-accent/5 to-transparent flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center">
                <x-lucide-rocket class="w-5 h-5 text-accent" />
            </div>
            <div>
                <h3 class="text-sm font-black text-heading">{{ __('onboarding.title') }}</h3>
                <p class="text-[10px] text-muted mt-0.5">{{ __('onboarding.subtitle', ['done' => $done, 'total' => $total]) }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            {{-- Progress ring --}}
            <div class="relative w-10 h-10">
                <svg class="w-10 h-10 -rotate-90" viewBox="0 0 36 36">
                    <path class="text-surface" stroke="currentColor" stroke-width="3" fill="none"
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-accent" stroke="currentColor" stroke-width="3" fill="none"
                          stroke-dasharray="{{ $percent }}, 100"
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <span class="absolute inset-0 flex items-center justify-center text-[9px] font-black text-accent">{{ $percent }}%</span>
            </div>
            <x-lucide-chevron-down class="w-4 h-4 text-muted transition-transform" ::class="expanded ? 'rotate-180' : ''" />
        </div>
    </div>

    {{-- Steps --}}
    <div x-show="expanded" x-collapse>
        {{-- Progress bar --}}
        <div class="px-5 pt-2">
            <div class="w-full bg-surface rounded-full h-1.5">
                <div class="bg-accent h-1.5 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
            </div>
        </div>

        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($steps as $step)
                <a href="{{ route($step['route']) }}"
                   class="flex items-start gap-3 p-3 rounded-xl border transition-all
                          {{ $step['done']
                              ? 'border-success/20 bg-success/5'
                              : 'border-border-light hover:border-accent/30 hover:bg-accent/5' }}">
                    {{-- Icon --}}
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                {{ $step['done'] ? 'bg-success/10' : 'bg-surface' }}">
                        @if($step['done'])
                            <x-lucide-check class="w-4 h-4 text-success" />
                        @else
                            <x-dynamic-component :component="'lucide-' . $step['icon']" class="w-4 h-4 text-muted" />
                        @endif
                    </div>
                    {{-- Text --}}
                    <div class="min-w-0">
                        <p class="text-xs font-bold {{ $step['done'] ? 'text-success line-through' : 'text-heading' }}">
                            {{ $step['label'] }}
                        </p>
                        <p class="text-[10px] text-muted mt-0.5">{{ $step['desc'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Dismiss --}}
        @if($percent === 100)
            <div class="px-5 pb-4 text-center">
                <button wire:click="dismissOnboarding" class="text-xs text-accent font-bold hover:underline">
                    {{ __('onboarding.all_done') }}
                </button>
            </div>
        @else
            <div class="px-5 pb-4 text-right">
                <button wire:click="dismissOnboarding" class="text-[10px] text-muted hover:text-heading">
                    {{ __('onboarding.dismiss') }}
                </button>
            </div>
        @endif
    </div>
</div>
