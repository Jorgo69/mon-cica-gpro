<div class="space-y-6">

    @if($indicators->isEmpty())
        <x-ui.empty-state icon="target" :title="__('indicators.no_indicators')" :description="__('indicators.no_indicators_desc')" />
    @else
        {{-- Summary Cards --}}
        @php
            $total = $indicators->count();
            $measured = $indicators->filter(fn($i) => $i->current_value !== null)->count();
            $onTrack = $indicators->filter(fn($i) => $i->progressPercent() >= 50)->count();
            $avgProgress = $total > 0 ? round($indicators->avg(fn($i) => $i->progressPercent()), 1) : 0;
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('indicators.total') }}</span>
                <span class="text-2xl font-black text-heading">{{ $total }}</span>
            </div>
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('indicators.measured') }}</span>
                <span class="text-2xl font-black text-accent">{{ $measured }}</span>
            </div>
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('indicators.on_track') }}</span>
                <span class="text-2xl font-black text-success">{{ $onTrack }}</span>
            </div>
            <div class="bg-card rounded-2xl border border-border-light p-4">
                <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">{{ __('indicators.avg_progress') }}</span>
                <span class="text-2xl font-black text-heading">{{ $avgProgress }}%</span>
            </div>
        </div>

        {{-- Indicators Table --}}
        <div class="bg-card rounded-2xl border border-border-light overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-surface-alt/50 bg-surface-alt/50">
                        <tr class="bg-surface text-muted text-left">
                            <th class="px-4 py-2.5 font-bold">{{ __('common.level') }}</th>
                            <th class="px-4 py-2.5 font-bold">{{ __('common.description') }}</th>
                            <th class="px-4 py-2.5 font-bold text-center">{{ __('common.baseline') }}</th>
                            <th class="px-4 py-2.5 font-bold text-center">{{ __('common.target') }}</th>
                            <th class="px-4 py-2.5 font-bold text-center">{{ __('indicators.current') }}</th>
                            <th class="px-4 py-2.5 font-bold text-center">{{ __('indicators.progress_label') }}</th>
                            <th class="px-4 py-2.5 font-bold text-center">{{ __('indicators.trend_label') }}</th>
                            <th class="px-4 py-2.5 font-bold text-right">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light">
                        @foreach($indicators as $indicator)
                            @php
                                $progress = $indicator->progressPercent();
                                $trend = $indicator->trend();
                                $progressColor = $progress >= 75 ? 'text-success' : ($progress >= 40 ? 'text-accent' : ($progress > 0 ? 'text-warning' : 'text-muted'));
                            @endphp
                            <tr class="hover:bg-surface/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="text-[9px] font-bold text-muted uppercase">{{ $indicator->_level }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-medium text-heading text-[11px]">{{ Str::limit($indicator->description, 60) }}</p>
                                    <p class="text-[9px] text-muted mt-0.5">{{ Str::limit($indicator->_parent, 40) }}</p>
                                </td>
                                <td class="px-4 py-3 text-center text-subtle">{{ $indicator->baseline_value ?? '—' }}</td>
                                <td class="px-4 py-3 text-center font-bold text-heading">{{ $indicator->target_value ?? '—' }}</td>
                                <td class="px-4 py-3 text-center font-bold {{ $progressColor }}">
                                    {{ $indicator->current_value ?? '—' }}
                                    @if($indicator->unit)
                                        <span class="text-muted font-normal">{{ $indicator->unit }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 bg-surface rounded-full h-1.5">
                                            <div class="{{ $progress >= 75 ? 'bg-success' : ($progress >= 40 ? 'bg-accent' : 'bg-warning') }} h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold {{ $progressColor }}">{{ $progress }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($trend === 'up')
                                        <x-lucide-trending-up class="w-4 h-4 text-success mx-auto" />
                                    @elseif($trend === 'down')
                                        <x-lucide-trending-down class="w-4 h-4 text-error mx-auto" />
                                    @else
                                        <x-lucide-minus class="w-4 h-4 text-muted mx-auto" />
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button wire:click="openMeasureForm('{{ $indicator->id }}')" class="p-1 rounded text-accent hover:bg-accent/10" title="{{ __('indicators.add_measurement') }}">
                                            <x-lucide-plus-circle class="w-4 h-4" />
                                        </button>
                                        <button wire:click="showHistory('{{ $indicator->id }}')" class="p-1 rounded text-muted hover:text-heading hover:bg-surface" title="{{ __('indicators.view_history') }}">
                                            <x-lucide-history class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Inline measurement form --}}
                            @if($activeIndicatorId === $indicator->id)
                                <tr class="bg-accent/5">
                                    <td colspan="8" class="px-4 py-3">
                                        <div class="flex items-end gap-3 flex-wrap">
                                            <div class="flex-1 min-w-[120px]">
                                                <label class="text-[9px] font-bold text-muted uppercase block mb-1">{{ __('indicators.value') }}</label>
                                                <input type="text" wire:model="measurementValue" class="w-full text-xs bg-card border border-border-light rounded-lg px-3 py-1.5 focus:ring-accent focus:border-accent" placeholder="Ex: 150" />
                                            </div>
                                            <div class="flex-1 min-w-[120px]">
                                                <label class="text-[9px] font-bold text-muted uppercase block mb-1">{{ __('common.date') }}</label>
                                                <input type="date" wire:model="measurementDate" class="w-full text-xs bg-card border border-border-light rounded-lg px-3 py-1.5 focus:ring-accent focus:border-accent" />
                                            </div>
                                            <div class="flex-[2] min-w-[200px]">
                                                <label class="text-[9px] font-bold text-muted uppercase block mb-1">{{ __('indicators.comment') }}</label>
                                                <input type="text" wire:model="measurementComment" class="w-full text-xs bg-card border border-border-light rounded-lg px-3 py-1.5 focus:ring-accent focus:border-accent" placeholder="{{ __('indicators.comment_placeholder') }}" />
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <x-ui.button wire:click="saveMeasurement" variant="accent" size="sm" icon="check">{{ __('common.save') }}</x-ui.button>
                                                <x-ui.button wire:click="$set('activeIndicatorId', null)" variant="ghost" size="sm" icon="x">{{ __('common.cancel') }}</x-ui.button>
                                            </div>
                                        </div>
                                        @error('measurementValue') <p class="text-error text-[10px] mt-1">{{ $message }}</p> @enderror
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- History Modal --}}
    @if($historyIndicator)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-data @click.self="$wire.set('historyIndicatorId', null)">
            <div class="bg-card rounded-2xl border border-border-light shadow-xl w-full max-w-lg mx-4 max-h-[70vh] overflow-y-auto">
                <div class="p-5 border-b border-border-light flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-heading">{{ __('indicators.measurement_history') }}</h3>
                        <p class="text-[10px] text-muted mt-0.5">{{ Str::limit($historyIndicator->description, 60) }}</p>
                    </div>
                    <button wire:click="$set('historyIndicatorId', null)" class="text-muted hover:text-heading">
                        <x-lucide-x class="w-4 h-4" />
                    </button>
                </div>

                <div class="p-5">
                    {{-- Indicator info --}}
                    <div class="grid grid-cols-3 gap-3 mb-4 text-center">
                        <div class="bg-surface rounded-xl p-2">
                            <span class="text-[9px] font-bold text-muted block">{{ __('common.baseline') }}</span>
                            <span class="text-sm font-bold text-heading">{{ $historyIndicator->baseline_value ?? '—' }}</span>
                        </div>
                        <div class="bg-surface rounded-xl p-2">
                            <span class="text-[9px] font-bold text-muted block">{{ __('indicators.current') }}</span>
                            <span class="text-sm font-bold text-accent">{{ $historyIndicator->current_value ?? '—' }}</span>
                        </div>
                        <div class="bg-surface rounded-xl p-2">
                            <span class="text-[9px] font-bold text-muted block">{{ __('common.target') }}</span>
                            <span class="text-sm font-bold text-heading">{{ $historyIndicator->target_value ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- Measurements list --}}
                    @forelse($historyIndicator->measurements as $m)
                        <div class="flex items-start gap-3 py-2.5 {{ !$loop->last ? 'border-b border-border-light' : '' }}">
                            <div class="w-2 h-2 rounded-full bg-accent mt-1.5 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-heading">{{ $m->value }} {{ $historyIndicator->unit }}</span>
                                    <span class="text-[10px] text-muted">{{ $m->measured_at->format('d/m/Y') }}</span>
                                </div>
                                @if($m->comment)
                                    <p class="text-[10px] text-subtle mt-0.5">{{ $m->comment }}</p>
                                @endif
                                <p class="text-[9px] text-muted mt-0.5">{{ $m->measuredBy?->name }}</p>
                            </div>
                            <button wire:click="deleteMeasurement('{{ $m->id }}')" wire:confirm="{{ __('common.confirm_delete') }}" class="p-1 text-muted hover:text-error">
                                <x-lucide-trash-2 class="w-3 h-3" />
                            </button>
                        </div>
                    @empty
                        <p class="text-xs text-muted text-center py-4">{{ __('indicators.no_measurements') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

</div>
