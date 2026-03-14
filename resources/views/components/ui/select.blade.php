@props([
    'label' => null,
    'name' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'error' => null,
    'options' => [],
])

<div class="space-y-2">
    @if($label)
        <label for="{{ $name }}" class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">
            {{ $label }}
            @if($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative group">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none group-focus-within:text-accent transition-colors">
                <x-dynamic-component :component="'lucide-' . $icon" class="h-4 w-4 text-slate-300 group-focus-within:text-accent" />
            </div>
        @endif

        <select
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 transition-all appearance-none ' . ($icon ? 'pl-11' : 'pl-4') . ' pr-10 ' . ($error ? 'border-rose-500 ring-2 ring-rose-500/20' : '')
            ]) }}
        >
            {{ $slot }}
        </select>

        {{-- Chevron indicator --}}
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <x-lucide-chevron-down class="h-4 w-4 text-slate-400" />
        </div>
    </div>

    @if($error)
        <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $error }}</p>
    @endif
</div>
