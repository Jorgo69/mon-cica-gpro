@props(['title' => '', 'icon' => null])

<div class="flex items-center gap-3 mb-4">
    @if($icon)
        <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
            <x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
        </div>
    @endif
    <h3 class="font-semibold text-heading">{{ $title }}</h3>
</div>
