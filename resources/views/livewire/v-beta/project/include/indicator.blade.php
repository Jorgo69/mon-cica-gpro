<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Activités totales -->
    @php
        $totalActivitiesCount = $allActivities->count();
        $completedActivitiesCount = $allActivities->where('progress_percentage', 100)->count();
        $ongoingActivitiesCount = $allActivities->where('progress_percentage', '<', 100)->count();
        $lateActivitiesCount = $allActivities->where('status', 'En retard')->count();
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 transition-transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 text-blue-500 dark:text-blue-400">
                <x-dynamic-component component="lucide-list-checks" class="w-8 h-8" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Activités</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalActivitiesCount }}</p>
            </div>
        </div>
    </div>
    
    <!-- Activités terminées -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 transition-transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 text-green-500 dark:text-green-400">
                <x-dynamic-component component="lucide-check-circle-2" class="w-8 h-8" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terminées</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $completedActivitiesCount }}</p>
            </div>
        </div>
    </div>
    
    <!-- Activités en cours -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 transition-transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 text-yellow-500 dark:text-yellow-400">
                <x-dynamic-component component="lucide-loader-2" class="w-8 h-8 animate-spin" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En cours</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $ongoingActivitiesCount }}</p>
            </div>
        </div>
    </div>
    
    <!-- Activités en retard -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 transition-transform hover:scale-105">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0 text-red-500 dark:text-red-400">
                <x-dynamic-component component="lucide-alert-triangle" class="w-8 h-8" />
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En retard</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $lateActivitiesCount }}</p>
            </div>
        </div>
    </div>
</div>