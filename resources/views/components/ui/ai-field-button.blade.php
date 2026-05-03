@props(['field', 'soIndex' => null, 'rIndex' => null, 'aIndex' => null])

@if(\App\Services\AI\AiService::isConfigured())
@php
    $args = "'{$field}'";
    if ($soIndex !== null) $args .= ", {$soIndex}";
    if ($rIndex !== null) $args .= ", {$rIndex}";
    if ($aIndex !== null) $args .= ", {$aIndex}";
@endphp
<button wire:click="aiGenerate({{ $args }})" wire:loading.attr="disabled" type="button"
        class="inline-flex items-center gap-1 px-2 py-0.5 text-[9px] font-bold text-purple-500 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 rounded-md hover:bg-purple-100 dark:hover:bg-purple-900/40 transition-colors ml-2">
    <x-lucide-sparkles class="w-3 h-3" />
    <span wire:loading.remove wire:target="aiGenerate({{ $args }})">IA</span>
    <span wire:loading wire:target="aiGenerate({{ $args }})">...</span>
</button>
@endif
