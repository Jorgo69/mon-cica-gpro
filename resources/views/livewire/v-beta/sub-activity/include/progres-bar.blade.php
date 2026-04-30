<!-- Section de la barre de progression -->
@php
    $progress = $activity->calculateProgress();
    $progressColor = 'bg-accent';
    if ($progress < 25) {
        $progressColor = 'bg-error';
    } elseif ($progress < 75) {
        $progressColor = 'bg-yellow-500';
    } else {
        $progressColor = 'bg-green-500';
    }
@endphp

<div class="w-full bg-border dark:bg-surface-alt rounded-full h-4 mb-2 overflow-hidden">
    <div class="h-4 rounded-full transition-all duration-500 ease-in-out {{ $progressColor }}" 
         style="width: {{ $progress }}%">
    </div>
</div>
<div class="text-sm font-semibold text-subtle">
    <span class="text-2xl font-bold text-heading dark:text-white">
        {{ number_format($progress, 2) }}%
    </span> achevé
</div>