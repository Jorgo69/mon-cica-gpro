@props(['key'])

@php
    $text = __("help.{$key}");
    // If the key doesn't exist in translations, don't render
    $exists = $text !== "help.{$key}";
@endphp

@if($exists)
<span x-data="{ show: false }" class="relative inline-flex ml-1">
    <button @mouseenter="show = true" @mouseleave="show = false" @click="show = !show" type="button"
            class="w-4 h-4 rounded-full bg-muted/20 hover:bg-accent/20 inline-flex items-center justify-center transition-colors">
        <x-lucide-help-circle class="w-3 h-3 text-muted" />
    </button>
    <div x-show="show" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         class="absolute z-50 bottom-full mb-2 left-1/2 -translate-x-1/2 w-64 p-3 bg-card border border-border-light rounded-xl shadow-xl text-[11px] text-body leading-relaxed pointer-events-none">
        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-card border-r border-b border-border-light rotate-45"></div>
        {!! $text !!}
    </div>
</span>
@endif
