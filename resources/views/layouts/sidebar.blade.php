<!-- ========== SIDEBAR ========== -->
<aside id="sidebar" 
       x-show="sidebarOpen || !isMobile"
       :class="{ '-translate-x-full': isMobile && !sidebarOpen, 'translate-x-0': !isMobile || sidebarOpen }"
       class="fixed left-0 top-16 w-64 h-full bg-white dark:bg-gray-800 shadow-lg border-r border-gray-200 dark:border-gray-700 sidebar-transition z-40">
    <div class="p-4 custom-scrollbar overflow-y-auto h-full">
        <nav class="space-y-2">
            <!-- Dashboard -->
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" 
                class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors 
                text- @if(Route::is('dashboard*')) bg-gradient-to-r from-blue-500 to-purple-600 @endif">
                    <div class="flex items-center space-x-3">
                        <x-lucide-home class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        <span class="font-medium text-gray-800 dark:text-white">{{ __('navigation.sidebar.Dashboard') }}</span>
                    </div>
                </a>
            </div>

            <!-- Projects -->
            <div class="mb-4">
                <a href="{{ route('project.list') }}" 
                class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors 
                text- @if(Route::is('creator.proposal.project*') || Route::is('project*')) bg-gradient-to-r from-blue-500 to-purple-600 @endif">
                    <div class="flex items-center space-x-3">
                        <x-lucide-folder class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        <span class="font-medium text-gray-800 dark:text-white">{{ __('navigation.sidebar.Project') }}</span>
                    </div>
                </a>
            </div>

            <!-- Ressource -->
            <div class="mb-4">
                <a href="{{ route('resource.index') }}" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text- @if(Route::is('accomodation*')) rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 @endif">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-folder text-gray-600 dark:text-gray-300"></i>
                        <span class="font-medium text-gray-800 dark:text-white">{{ __('Ressource') }}</span>
                    </div>
                </a>
            </div>

            <!-- Activity -->
            <div class="mb-4">
                <a href="{{ route('activity.index') }}" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text- @if(Route::is('activity*')) rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 @endif">
                    <div class="flex items-center space-x-3">
                        <x-lucide-activity class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        <span class="font-medium text-gray-800 dark:text-white">{{ __('navigation.sidebar.Activity') }}</span>
                    </div>
                </a>
            </div>

            <!-- Administrator -->
            @if (auth()->user()->role == 'Administrateur')
            <div class="mb-4" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left @if(Route::is('admin*')) rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 @endif">
                    <div class="flex items-center space-x-3">
                        <x-lucide-shield-check class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        <span class="font-medium text-gray-800 dark:text-white">{{ __('navigation.sidebar.administrator') }}</span>
                    </div>
                    <x-lucide-chevron-right class="w-4 h-4 text-gray-400 transition-transform" x-bind:class="{ 'transform rotate-90': open }" />
                </button>
                <div x-show="open" x-collapse class="ml-6 mt-2 space-y-1">
                    <a href="{{ route('admin.it.type.of.project') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        {{ __('Type de Projet') }}
                    </a>
                    <a href="{{ route('admin.it.project.list') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        {{ __('Liste de Projet') }}
                    </a>
                    <a href="{{ route('admin.it.category.list') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        {{ __('navigation.sidebar.categories list') }}
                    </a>
                    <a href="{{ route('admin.it.member.list') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        {{ __('navigation.sidebar.members') }}
                    </a>
                    <a href="{{ route('admin.it.trash.management') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        {{ __('Corbeilles') }}
                    </a>
                </div>
            </div>
            @endif
            
            
            <!-- Settings -->
            <div class="mb-4" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-left 
                @if(Route::is('setting*') || Route::is('profile*')) bg-gradient-to-r from-blue-500 to-purple-600 @endif">
                    <div class="flex items-center space-x-3">
                        <x-lucide-settings class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        <span class="font-medium text-gray-800 dark:text-white">{{ __('navigation.sidebar.Settings') }}</span>
                    </div>
                    <x-lucide-chevron-right class="w-4 h-4 text-gray-400 transition-transform" x-bind:class="{ 'transform rotate-90': open }" />
                </button>
                <div x-show="open" x-collapse class="ml-6 mt-2 space-y-1">
                    <a href="{{ route('setting') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">General</a>
                    <a href="{{ route('profile.edit') }}" class="block p-2 rounded text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">Profile</a>
                </div>
            </div>
            
        </nav>
    </div>
</aside>

