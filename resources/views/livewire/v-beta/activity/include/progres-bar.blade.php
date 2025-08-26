<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
    <h2 class="text-2xl font-bold mb-4">Progression des sous-activités</h2>

    
    @php
        $progress = $activity->calculateProgress();
        $progressColor = 'bg-red-500';
        if ($progress >= 25 && $progress < 75) {
            $progressColor = 'bg-yellow-500';
        } elseif ($progress >= 75) {
            $progressColor = 'bg-green-500';
        }
    @endphp

    <!-- Barre -->
    <div class="relative w-full bg-gray-200 dark:bg-gray-700 rounded-full h-6 overflow-hidden">
        <div 
            class="h-6 {{ $progressColor }} rounded-full transition-all duration-700 ease-in-out" 
            style="width: {{ $progress }}%"
            aria-valuenow="{{ $progress }}"
            aria-valuemin="0"
            aria-valuemax="100"
            role="progressbar"
        ></div>
        <span class="absolute inset-0 flex items-center justify-center text-sm font-semibold text-white">
            {{ number_format($progress, 0) }}%
        </span>
    </div>

    

    <!-- Légende -->
    <div class="flex justify-between mt-4 text-sm text-gray-600 dark:text-gray-400">
        <span>0%</span>
        <span>50%</span>
        <span>100%</span>
    </div>
</div>