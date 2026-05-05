@props([
    'label' => null,
    'value' => null,
])

<label class="flex items-center gap-2.5 text-[11px] text-body cursor-pointer group">
    <input type="checkbox" {{ $attributes->merge(['class' => 'w-4 h-4 rounded border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-700 text-accent focus:ring-accent focus:ring-offset-0 dark:focus:ring-offset-slate-800 transition-colors']) }}
        @if($value) value="{{ $value }}" @endif
    >
    @if($label)
        <span class="group-hover:text-heading transition-colors">{{ $label }}</span>
    @else
        <span class="group-hover:text-heading transition-colors">{{ $slot }}</span>
    @endif
</label>
