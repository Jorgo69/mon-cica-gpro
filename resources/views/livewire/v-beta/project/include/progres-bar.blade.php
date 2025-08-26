{{-- <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
    <h2 class="text-2xl font-bold mb-4">Progression globale</h2>
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 mb-2 overflow-hidden">
        @php
            $progress = $project->calculateProgress();
            $progressColor = 'bg-blue-500';
            if ($progress < 25) {
                $progressColor = 'bg-red-500';
            } elseif ($progress < 75) {
                $progressColor = 'bg-yellow-500';
            } else {
                $progressColor = 'bg-green-500';
            }
        @endphp
        <div class="h-4 rounded-full transition-all duration-500 ease-in-out {{ $progressColor }}" style="width: {{ $progress }}%;"></div>
    </div>
    <div class="text-sm font-semibold text-gray-600 dark:text-gray-400">
        <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($progress, 2) }}%</span> achevé
    </div>
</div> --}}

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
    <h2 class="text-2xl font-bold mb-4">Progression du projet</h2>

    @php
        $progress = $project->calculateProjectProgress(); // ✅ Méthode du modèle

        $progressColor = 'bg-red-500';
        if ($progress >= 25 && $progress < 75) {
            $progressColor = 'bg-yellow-500';
        } elseif ($progress >= 75) {
            $progressColor = 'bg-green-500';
        }
    @endphp

    <!-- Barre de progression -->
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