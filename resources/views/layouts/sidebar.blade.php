<!-- ========== SIDEBAR ========== -->
<aside id="sidebar" 
       x-show="sidebarOpen || !isMobile"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="-translate-x-full opacity-0"
       x-transition:enter-end="translate-x-0 opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="translate-x-0 opacity-100"
       x-transition:leave-end="-translate-x-full opacity-0"
       :class="{ '-translate-x-full': isMobile && !sidebarOpen, 'translate-x-0': !isMobile || sidebarOpen }"
       class="fixed left-0 top-14 w-60 h-[calc(100vh-3.5rem)] bg-white dark:bg-slate-900 border-r border-slate-200/80 dark:border-slate-800 z-40 overflow-y-auto">
    
    <nav class="px-3 py-4 pb-20">

        {{-- ── PRINCIPAL ── --}}
        <p class="sidebar-section-title">Principal</p>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" 
           class="nav-item @if(Route::is('dashboard*')) nav-item-active @endif">
            <x-lucide-layout-dashboard class="nav-icon" />
            <span class="nav-label">{{ __('navigation.sidebar.Dashboard') }}</span>
        </a>

        {{-- Projets --}}
        <a href="{{ route('project.list') }}" 
           class="nav-item @if(Route::is('creator.proposal.project*') || Route::is('project*')) nav-item-active @endif">
            <x-lucide-folder-kanban class="nav-icon" />
            <span class="nav-label">{{ __('navigation.sidebar.Project') }}</span>
        </a>

        {{-- Ressources --}}
        <a href="{{ route('resource.index') }}" 
           class="nav-item @if(Route::is('resource*')) nav-item-active @endif">
            <x-lucide-boxes class="nav-icon" />
            <span class="nav-label">{{ __('Ressource') }}</span>
        </a>

        {{-- Activités --}}
        <a href="{{ route('activity.index') }}" 
           class="nav-item @if(Route::is('activity*')) nav-item-active @endif">
            <x-lucide-list-checks class="nav-icon" />
            <span class="nav-label">{{ __('navigation.sidebar.Activity') }}</span>
        </a>


        {{-- ── ADMINISTRATION ── --}}
        @if (auth()->user()->role === \App\Enums\AccountType::ADMIN)
        <p class="sidebar-section-title">Administration</p>

        <div x-data="{ open: {{ Route::is('admin*') ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="nav-item w-full justify-between @if(Route::is('admin*')) nav-item-active @endif">
                <div class="flex items-center gap-3">
                    <x-lucide-shield class="nav-icon" />
                    <span class="nav-label">{{ __('navigation.sidebar.administrator') }}</span>
                </div>
                <x-lucide-chevron-right class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" x-bind:class="{ 'rotate-90': open }" />
            </button>
            <div x-show="open" x-collapse>
                <div class="nav-submenu">
                    <a href="{{ route('admin.it.type.of.project') }}" class="nav-submenu-item @if(Route::is('admin.it.type.of.project*')) nav-submenu-item-active @endif">
                        {{ __('Type de Projet') }}
                    </a>
                    <a href="{{ route('admin.it.project.list') }}" class="nav-submenu-item @if(Route::is('admin.it.project.list*')) nav-submenu-item-active @endif">
                        {{ __('Liste de Projet') }}
                    </a>
                    <a href="{{ route('admin.it.category.list') }}" class="nav-submenu-item @if(Route::is('admin.it.category.list*')) nav-submenu-item-active @endif">
                        {{ __('navigation.sidebar.categories list') }}
                    </a>
                    <a href="{{ route('admin.it.member.list') }}" class="nav-submenu-item @if(Route::is('admin.it.member.list*')) nav-submenu-item-active @endif">
                        {{ __('navigation.sidebar.members') }}
                    </a>
                    <a href="{{ route('admin.it.trash.management') }}" class="nav-submenu-item @if(Route::is('admin.it.trash.management*')) nav-submenu-item-active @endif">
                        {{ __('Corbeilles') }}
                    </a>
                </div>
            </div>
        </div>
        @endif


        {{-- ── PARAMÈTRES ── --}}
        <p class="sidebar-section-title">Paramètres</p>

        <div x-data="{ open: {{ (Route::is('setting*') || Route::is('profile*')) ? 'true' : 'false' }} }">
            <button @click="open = !open" 
                    class="nav-item w-full justify-between @if(Route::is('setting*') || Route::is('profile*')) nav-item-active @endif">
                <div class="flex items-center gap-3">
                    <x-lucide-settings class="nav-icon" />
                    <span class="nav-label">{{ __('navigation.sidebar.Settings') }}</span>
                </div>
                <x-lucide-chevron-right class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" x-bind:class="{ 'rotate-90': open }" />
            </button>
            <div x-show="open" x-collapse>
                <div class="nav-submenu">
                    <a href="{{ route('setting') }}" class="nav-submenu-item @if(Route::is('setting')) nav-submenu-item-active @endif">
                        General
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-submenu-item @if(Route::is('profile*')) nav-submenu-item-active @endif">
                        Profile
                    </a>
                </div>
            </div>
        </div>

    </nav>
</aside>
