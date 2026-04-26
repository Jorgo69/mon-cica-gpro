<!-- ========== SIDEBAR ROOT ========== -->
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

        {{-- ── SUPERVISION ── --}}
        <p class="sidebar-section-title">Supervision</p>

        <a href="{{ route('system.dashboard') }}"
           class="nav-item @if(Route::is('system.dashboard')) nav-item-active @endif">
            <x-lucide-activity class="nav-icon text-rose-500" />
            <span class="nav-label">Tableau de bord</span>
        </a>

        {{-- ── GESTION ── --}}
        <p class="sidebar-section-title">Gestion</p>

        <a href="{{ route('system.organizations') }}"
           class="nav-item @if(Route::is('system.organizations*')) nav-item-active @endif">
            <x-lucide-building-2 class="nav-icon" />
            <span class="nav-label">Organisations</span>
        </a>

        <a href="{{ route('system.users') }}"
           class="nav-item @if(Route::is('system.users*')) nav-item-active @endif">
            <x-lucide-users class="nav-icon" />
            <span class="nav-label">Utilisateurs</span>
        </a>

        <a href="{{ route('system.emails') }}"
           class="nav-item @if(Route::is('system.emails*')) nav-item-active @endif">
            <x-lucide-mail class="nav-icon" />
            <span class="nav-label">Emails</span>
        </a>

        {{-- ── CONFIGURATION ── --}}
        <p class="sidebar-section-title">Configuration</p>

        <a href="{{ route('system.roles') }}"
           class="nav-item @if(Route::is('system.roles*')) nav-item-active @endif">
            <x-lucide-shield-check class="nav-icon" />
            <span class="nav-label">Rôles & Permissions</span>
        </a>

        <a href="{{ route('system.audit.logs') }}"
           class="nav-item @if(Route::is('system.audit.logs*')) nav-item-active @endif">
            <x-lucide-scroll-text class="nav-icon" />
            <span class="nav-label">Logs d'audit</span>
        </a>

        {{-- ── PARAMÈTRES ── --}}
        <p class="sidebar-section-title">Paramètres</p>

        <a href="{{ route('profile.edit') }}"
           class="nav-item @if(Route::is('profile*')) nav-item-active @endif">
            <x-lucide-user-cog class="nav-icon" />
            <span class="nav-label">Mon Profil</span>
        </a>

    </nav>
</aside>
