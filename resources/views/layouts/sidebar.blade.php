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
       class="fixed left-0 top-14 w-60 h-[calc(100vh-3.5rem)] bg-card border-r border-border/80 dark:border-surface-alt z-40 overflow-y-auto">
    
    <nav class="px-3 py-4 pb-20">

        {{-- ── PRINCIPAL ── --}}
        <p class="sidebar-section-title">{{ __('navigation.main') }}</p>

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="nav-item @if(Route::is('dashboard*')) nav-item-active @endif">
            <x-lucide-layout-dashboard class="nav-icon" />
            <span class="nav-label">{{ __('navigation.dashboard') }}</span>
        </a>

        {{-- Projets (avec sous-menu Templates) --}}
        @can('view-projects')
        <div x-data="{ open: {{ Route::is('project*') || Route::is('creator.proposal.project*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-item w-full justify-between @if(Route::is('project.list') || Route::is('project.show') || Route::is('project.dashboard') || Route::is('creator.proposal.project*')) nav-item-active @endif">
                <div class="flex items-center gap-3">
                    <x-lucide-folder-kanban class="nav-icon" />
                    <span class="nav-label">{{ __('navigation.projects') }}</span>
                </div>
                <x-lucide-chevron-down class="w-3.5 h-3.5 text-muted transition-transform" ::class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse class="ml-6 mt-0.5 space-y-0.5 border-l border-border-light pl-2">
                <a href="{{ route('project.list') }}"
                   class="nav-item text-[11px] @if(Route::is('project.list')) nav-item-active @endif">
                    <x-lucide-list class="nav-icon w-3.5 h-3.5" />
                    <span class="nav-label">{{ __('navigation.all_projects') }}</span>
                </a>
                <a href="{{ route('project.templates') }}"
                   class="nav-item text-[11px] @if(Route::is('project.templates')) nav-item-active @endif">
                    <x-lucide-layout-template class="nav-icon w-3.5 h-3.5" />
                    <span class="nav-label">{{ __('navigation.templates') }}</span>
                </a>
            </div>
        </div>
        @endcan

        {{-- Ressources --}}
        @can('view-projects')
        <a href="{{ route('resource.index') }}"
           class="nav-item @if(Route::is('resource*')) nav-item-active @endif">
            <x-lucide-boxes class="nav-icon" />
            <span class="nav-label">{{ __('navigation.resources') }}</span>
        </a>
        @endcan

        {{-- Activités --}}
        @can('view-projects')
        <a href="{{ route('activity.index') }}"
           class="nav-item @if(Route::is('activity*')) nav-item-active @endif">
            <x-lucide-list-checks class="nav-icon" />
            <span class="nav-label">{{ __('navigation.activities') }}</span>
        </a>
        @endcan


        {{-- ── SYSTÈME (ROOT / SYSTEM_ADMIN) ── --}}
        @if (auth()->user()->role === \App\Enums\AccountType::ROOT)
        <p class="sidebar-section-title">{{ __('navigation.system') }}</p>

        <div x-data="{ open: {{ Route::is('system*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-item w-full justify-between @if(Route::is('system*')) nav-item-active @endif">
                <div class="flex items-center gap-3 text-rose-600 dark:text-rose-400">
                    <x-lucide-server class="nav-icon" />
                    <span class="nav-label font-bold text-xs uppercase tracking-tight">{{ __('navigation.configuration') }}</span>
                </div>
                <x-lucide-chevron-right class="w-3.5 h-3.5 text-muted transition-transform duration-200" x-bind:class="{ 'rotate-90': open }" />
            </button>
            <div x-show="open" x-collapse>
                <div class="nav-submenu">
                    <a href="{{ route('system.dashboard') }}" class="nav-submenu-item @if(Route::is('system.dashboard*')) nav-submenu-item-active @endif">
                        {{ __('navigation.supervision') }}
                    </a>
                    <a href="{{ route('system.roles') }}" class="nav-submenu-item @if(Route::is('system.roles*')) nav-submenu-item-active @endif">
                        {{ __('navigation.roles_permissions') }}
                    </a>
                    <a href="{{ route('system.organizations') }}" class="nav-submenu-item @if(Route::is('system.organizations*')) nav-submenu-item-active @endif">
                        {{ __('navigation.organizations') }}
                    </a>
                    <a href="{{ route('system.plans') }}" class="nav-submenu-item @if(Route::is('system.plans*')) nav-submenu-item-active @endif">
                        {{ __('plans.plan') }}
                    </a>
                    <a href="{{ route('system.audit.logs') }}" class="nav-submenu-item @if(Route::is('system.audit.logs*')) nav-submenu-item-active @endif">
                        {{ __('navigation.global_audit_logs') }}
                    </a>
                </div>
            </div>
        </div>
        @endif


        {{-- ── ADMINISTRATION (ORG ADMIN & ADMINS) ── --}}
        @php
            $isAdmin = in_array(auth()->user()->role, [\App\Enums\AccountType::ROOT, \App\Enums\AccountType::ORG_ADMIN]);
        @endphp

        @if ($isAdmin)
        <p class="sidebar-section-title">{{ __('navigation.administration') }}</p>

        <div x-data="{ open: {{ Route::is('admin*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-item w-full justify-between @if(Route::is('admin*')) nav-item-active @endif">
                <div class="flex items-center gap-3">
                    <x-lucide-shield-check class="nav-icon text-indigo-500" />
                    <span class="nav-label font-bold text-xs uppercase tracking-tight">{{ __('navigation.org_management') }}</span>
                </div>
                <x-lucide-chevron-right class="w-3.5 h-3.5 text-muted transition-transform duration-200" x-bind:class="{ 'rotate-90': open }" />
            </button>
            <div x-show="open" x-collapse>
                <div class="nav-submenu">
                    <a href="{{ route('admin.member.list') }}" class="nav-submenu-item @if(Route::is('admin.member.list*')) nav-submenu-item-active @endif">
                        {{ __('navigation.members') }}
                    </a>
                    <a href="{{ route('admin.category.list') }}" class="nav-submenu-item @if(Route::is('admin.category.list*')) nav-submenu-item-active @endif">
                        {{ __('navigation.categories') }}
                    </a>
                    <a href="{{ route('admin.type.of.project') }}" class="nav-submenu-item @if(Route::is('admin.type.of.project*')) nav-submenu-item-active @endif">
                        {{ __('navigation.project_types') }}
                    </a>
                    <a href="{{ route('admin.invitation.list') }}" class="nav-submenu-item @if(Route::is('admin.invitation.list*')) nav-submenu-item-active @endif">
                        {{ __('navigation.invitations') }}
                    </a>
                    <a href="{{ route('admin.exchange-rates') }}" class="nav-submenu-item @if(Route::is('admin.exchange-rates*')) nav-submenu-item-active @endif">
                        {{ __('navigation.exchange_rates') }}
                    </a>
                    <a href="{{ route('admin.permissions') }}" class="nav-submenu-item @if(Route::is('admin.permissions*')) nav-submenu-item-active @endif">
                        {{ __('navigation.permissions') }}
                    </a>
                    <a href="{{ route('admin.audit') }}" class="nav-submenu-item @if(Route::is('admin.audit*')) nav-submenu-item-active @endif">
                        {{ __('navigation.audit') }}
                    </a>
                    <a href="{{ route('admin.trash.management') }}" class="nav-submenu-item @if(Route::is('admin.trash.management*')) nav-submenu-item-active @endif">
                        {{ __('navigation.trash') }}
                    </a>
                </div>
            </div>
        </div>
        @endif


        {{-- ── PARAMÈTRES ── --}}
        <p class="sidebar-section-title">{{ __('navigation.settings_section') }}</p>

        <div x-data="{ open: {{ (Route::is('setting*') || Route::is('profile*')) ? 'true' : 'false' }} }">
            <button @click="open = !open"
                    class="nav-item w-full justify-between @if(Route::is('setting*') || Route::is('profile*')) nav-item-active @endif">
                <div class="flex items-center gap-3">
                    <x-lucide-settings class="nav-icon" />
                    <span class="nav-label font-bold text-xs uppercase tracking-tight">{{ __('navigation.settings_section') }}</span>
                </div>
                <x-lucide-chevron-right class="w-3.5 h-3.5 text-muted transition-transform duration-200" x-bind:class="{ 'rotate-90': open }" />
            </button>
            <div x-show="open" x-collapse>
                <div class="nav-submenu">
                    <a href="{{ route('setting') }}" class="nav-submenu-item @if(Route::is('setting')) nav-submenu-item-active @endif">
                        {{ __('navigation.general') }}
                    </a>
                    <a href="{{ route('profile.edit') }}" class="nav-submenu-item @if(Route::is('profile*')) nav-submenu-item-active @endif">
                        {{ __('navigation.my_profile') }}
                    </a>
                    <a href="{{ route('faq') }}" class="nav-submenu-item @if(Route::is('faq')) nav-submenu-item-active @endif">
                        {{ __('navigation.faq') }}
                    </a>
                </div>
            </div>
        </div>

    </nav>
</aside>
