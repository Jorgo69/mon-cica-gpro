@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'icon' => null,
    'error' => null,
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
                <x-lucide-{{ $icon }} class="h-4 w-4 text-slate-300 group-focus-within:text-accent" />
            </div>
        @endif

        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => 'block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 transition-all ' . ($icon ? 'pl-11' : 'pl-4') . ' ' . ($error ? 'border-rose-500 ring-2 ring-rose-500/20' : '')
            ]) }}
        >
        
        @if($error)
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                <x-lucide-alert-circle class="h-4 w-4 text-rose-500" />
            </div>
        @endif
    </div>

    @if($error)
        <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $error }}</p>
    @elseif($attributes->get('helperText'))
        <p class="text-[10px] text-slate-400 mt-1.5 ml-1 font-medium italic">{{ $attributes->get('helperText') }}</p>
    @endif
</div>
