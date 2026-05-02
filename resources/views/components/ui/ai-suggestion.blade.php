@props(['field', 'activeField' => null, 'suggestion' => null, 'loading' => false])

@if($activeField === $field && $loading)
<div class="mt-2 flex items-center gap-2 text-xs text-purple-500">
    <div class="w-3.5 h-3.5 border-2 border-purple-500 border-t-transparent rounded-full animate-spin"></div>
    {{ __('ai.analyzing') }}
</div>
@endif

@if($activeField === $field && $suggestion)
<div class="mt-2 p-3 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl">
    <div class="flex items-center justify-between mb-2">
        <span class="text-[9px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest flex items-center gap-1">
            <x-lucide-sparkles class="w-3 h-3" /> {{ __('ai.suggestion') }}
        </span>
        <div class="flex items-center gap-1">
            <button wire:click="aiRegenerate" wire:loading.attr="disabled" type="button"
                    class="text-[9px] font-bold text-purple-500 hover:text-purple-700 px-1.5 py-0.5 rounded hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                <span wire:loading.remove wire:target="aiRegenerate">{{ __('ai.regenerate') }}</span>
                <span wire:loading wire:target="aiRegenerate">...</span>
            </button>
            <button wire:click="aiApply" type="button"
                    class="text-[9px] font-bold text-white bg-purple-600 hover:bg-purple-700 px-2 py-0.5 rounded transition-colors">
                {{ __('ai.apply') }}
            </button>
            <button wire:click="aiDismiss" type="button"
                    class="text-[9px] text-purple-400 hover:text-purple-600 px-1 py-0.5 transition-colors">
                <x-lucide-x class="w-3 h-3" />
            </button>
        </div>
    </div>
    <p class="text-xs text-body leading-relaxed whitespace-pre-line">{{ $suggestion }}</p>
</div>
@endif
