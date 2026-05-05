@props([
    'label' => null,
    'description' => null,
])

<div class="flex items-center justify-between p-4 bg-surface dark:bg-surface-alt/30 rounded-xl border border-border-light dark:border-slate-700/50">
    @if($label)
    <div>
        <p class="text-sm font-bold text-heading">{{ $label }}</p>
        @if($description)
            <p class="text-xs text-subtle mt-0.5">{{ $description }}</p>
        @endif
    </div>
    @endif
    <label class="relative inline-flex items-center cursor-pointer shrink-0">
        <input type="checkbox" {{ $attributes }} class="sr-only peer">
        <div class="w-11 h-6 bg-slate-300 dark:bg-slate-600 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white dark:after:bg-slate-200 after:border after:border-slate-200 dark:after:border-slate-500 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
    </label>
</div>
