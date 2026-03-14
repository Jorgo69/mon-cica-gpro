@props([
    'icon' => 'inbox',
    'title' => 'Aucun élément trouvé',
    'description' => null,
])

<div class="py-16 flex flex-col items-center justify-center text-center">
    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
        <x-dynamic-component :component="'lucide-' . $icon" class="w-5 h-5 text-slate-300 dark:text-slate-600" />
    </div>
    <p class="text-[13px] font-bold text-slate-400 dark:text-slate-500">{{ $title }}</p>
    @if($description)
        <p class="text-[11px] text-slate-400 dark:text-slate-600 mt-1 max-w-xs">{{ $description }}</p>
    @endif
    @if($slot->isNotEmpty())
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</div>
