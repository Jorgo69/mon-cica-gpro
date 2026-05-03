<div>
    @if(!\App\Services\AI\AiService::isConfigured())
        {{-- AI not configured, don't render anything --}}
    @else
        {{-- Trigger Button --}}
        @if($context === 'description')
            <button wire:click="generateDescription" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                <x-lucide-sparkles class="w-3.5 h-3.5" />
                <span wire:loading.remove wire:target="generateDescription">{{ __('ai.generate_description') }}</span>
                <span wire:loading wire:target="generateDescription">{{ __('ai.thinking') }}</span>
            </button>
        @endif

        @if($context === 'logframe')
            <button wire:click="suggestLogframe" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                <x-lucide-sparkles class="w-3.5 h-3.5" />
                <span wire:loading.remove wire:target="suggestLogframe">{{ __('ai.suggest_logframe') }}</span>
                <span wire:loading wire:target="suggestLogframe">{{ __('ai.thinking') }}</span>
            </button>
        @endif

        @if($context === 'summary')
            <button wire:click="generateSummary" wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors">
                <x-lucide-sparkles class="w-3.5 h-3.5" />
                <span wire:loading.remove wire:target="generateSummary">{{ __('ai.generate_summary') }}</span>
                <span wire:loading wire:target="generateSummary">{{ __('ai.thinking') }}</span>
            </button>
        @endif

        {{-- Loading indicator --}}
        <div wire:loading class="mt-3">
            <div class="flex items-center gap-2 text-xs text-purple-500">
                <div class="w-4 h-4 border-2 border-purple-500 border-t-transparent rounded-full animate-spin"></div>
                {{ __('ai.analyzing') }}
            </div>
        </div>

        {{-- Result: Description --}}
        @if($result && in_array($context, ['description', 'summary']))
            <div class="mt-3 p-4 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <div class="flex items-center gap-1.5">
                        <x-lucide-sparkles class="w-3.5 h-3.5 text-purple-500" />
                        <span class="text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">{{ __('ai.suggestion') }}</span>
                    </div>
                    @if($context === 'description')
                        <button wire:click="applyDescription" class="text-[10px] font-bold text-purple-600 hover:underline">
                            {{ __('ai.apply') }}
                        </button>
                    @endif
                </div>
                <p class="text-sm text-body leading-relaxed whitespace-pre-line">{{ $result }}</p>
            </div>
        @endif

        {{-- Result: Logframe --}}
        @if($logframeSuggestion && $context === 'logframe')
            <div class="mt-3 p-4 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl">
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="flex items-center gap-1.5">
                        <x-lucide-sparkles class="w-3.5 h-3.5 text-purple-500" />
                        <span class="text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">{{ __('ai.logframe_suggestion') }}</span>
                    </div>
                    <button wire:click="applyLogframe" class="text-[10px] font-bold text-purple-600 hover:underline">
                        {{ __('ai.apply') }}
                    </button>
                </div>

                {{-- General Objective --}}
                @if(isset($logframeSuggestion['general_objective']))
                    <div class="mb-3 p-2 bg-purple-100/50 dark:bg-purple-900/20 rounded-lg">
                        <span class="text-[9px] font-bold text-purple-500 uppercase">{{ __('ai.general_objective') }}</span>
                        <p class="text-xs text-heading mt-0.5">{{ $logframeSuggestion['general_objective'] }}</p>
                    </div>
                @endif

                {{-- Specific Objectives --}}
                @foreach($logframeSuggestion['specific_objectives'] ?? [] as $i => $so)
                    <div class="mb-2 ml-3 pl-3 border-l-2 border-purple-200 dark:border-purple-700">
                        <span class="text-[9px] font-bold text-purple-400">OS {{ $i + 1 }}</span>
                        <p class="text-xs text-heading">{{ $so['description'] }}</p>

                        @foreach($so['results'] ?? [] as $j => $result)
                            <div class="ml-3 mt-1 pl-2 border-l border-purple-100 dark:border-purple-800">
                                <span class="text-[8px] font-bold text-muted">R{{ $i+1 }}.{{ $j+1 }}</span>
                                <p class="text-[11px] text-subtle">{{ $result['description'] }}</p>
                                @foreach($result['activities'] ?? [] as $activity)
                                    <p class="text-[10px] text-muted ml-2">- {{ $activity }}</p>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
