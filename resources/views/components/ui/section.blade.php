@props([
    'title' => null,
    'icon' => null,
    'collapsible' => false,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                @if($icon)
                    <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 text-accent opacity-70" />
                @endif
                <h3 class="text-[13px] font-bold text-slate-800 dark:text-slate-200 tracking-tight">{{ $title }}</h3>
            </div>
            @if(isset($headerActions))
                <div class="flex items-center gap-2">
                    {{ $headerActions }}
                </div>
            @endif
        </div>
    @endif

    @if($noPadding)
        {{ $slot }}
    @else
        <div class="p-6">
            {{ $slot }}
        </div>
    @endif

    @if(isset($footer))
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
            {{ $footer }}
        </div>
    @endif
</div>
