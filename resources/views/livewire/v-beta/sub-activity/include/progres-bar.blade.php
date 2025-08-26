<!-- Section de la barre de progression -->
@php
    $progress = $activity->calculateProgress();
    $progressColor = 'bg-blue-500';
    if ($progress < 25) {
        $progressColor = 'bg-red-500';
    } elseif ($progress < 75) {
        $progressColor = 'bg-yellow-500';
    } else {
        $progressColor = 'bg-green-500';
    }
@endphp

<div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 mb-2 overflow-hidden">
    <div class="h-4 rounded-full transition-all duration-500 ease-in-out {{ $progressColor }}" 
         style="width: {{ $progress }}%">
    </div>
</div>
<div class="text-sm font-semibold text-gray-600 dark:text-gray-400">
    <span class="text-2xl font-bold text-gray-800 dark:text-white">
        {{ number_format($progress, 2) }}%
    </span> achevé
</div>