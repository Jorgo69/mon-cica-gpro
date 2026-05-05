@props([
    'label' => null,
    'value' => null,
])

<label class="flex items-center gap-2.5 text-[11px] text-body cursor-pointer group">
    <input type="checkbox"
        {{ $attributes->merge(['class' => 'w-4 h-4 rounded border-2 border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-700 text-accent checked:bg-accent checked:border-accent focus:ring-2 focus:ring-accent/30 focus:ring-offset-0 dark:focus:ring-offset-slate-800 transition-colors cursor-pointer']) }}
        @if($value) value="{{ $value }}" @endif
    >
    @if($label)
        <span class="group-hover:text-heading transition-colors select-none">{{ $label }}</span>
    @else
        <span class="group-hover:text-heading transition-colors select-none">{{ $slot }}</span>
    @endif
</label>
