<!-- ========== HEADER ========== -->
<header class="bg-card border-b border-border/80 dark:border-surface-alt fixed w-full top-0 z-50">
    <div class="flex items-center justify-between px-4 h-14">

        <!-- Logo & Mobile Toggle -->
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden navbar-action">
                <x-lucide-menu class="nav-icon" />
            </button>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-accent/10 dark:bg-accent/20 flex items-center justify-center">
                    <x-lucide-handshake class="w-[18px] h-[18px] text-accent" />
                </div>
                <span class="text-[15px] font-bold text-heading tracking-tight hidden sm:block">{{ config('app.name') }}</span>
            </a>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center gap-1">

            <!-- Global Search -->
            <button @click="$dispatch('toggle-search')" class="navbar-action group hidden sm:flex items-center gap-2 px-3 py-1.5 bg-surface dark:bg-surface-alt/50 border border-border-light dark:border-surface-alt rounded-xl transition-all hover:border-accent/30">
                <x-lucide-search class="w-4 h-4 text-muted group-hover:text-accent" />
                <span class="text-xs font-bold text-muted group-hover:text-subtle dark:group-hover:text-heading">Rechercher...</span>
                <kbd class="hidden md:inline-flex items-center px-1.5 py-0.5 rounded bg-white dark:bg-surface-alt border border-border dark:border-border text-[10px] font-black text-muted group-hover:text-accent">Ctrl+K</kbd>
            </button>

            <!-- Theme Toggle -->
            <button @click="toggleTheme()" class="navbar-action">
                <x-lucide-moon x-show="!darkMode" class="nav-icon" x-cloak />
                <x-lucide-sun x-show="darkMode" class="nav-icon" x-cloak />
            </button>
            
            <!-- Notifications -->
            @livewire('v-beta.notifications.notification-center-livewire')
            
            <!-- Profile Menu -->
            <div class="relative ml-1">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-alt transition-colors duration-150">
                    @php
                        $userName = auth()->user()->name ?? 'U';
                        $initials = collect(explode(' ', $userName))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
                    @endphp
                    <div class="avatar-initials">{{ $initials }}</div>
                    <div class="hidden sm:block text-left">
                        <div class="text-[13px] font-semibold text-body leading-tight">
                            {{ auth()->user()->name ?? 'Utilisateur' }}
                        </div>
                        <div class="text-[11px] text-muted leading-tight">
                            {{ auth()->user()->role ?? 'Aucun rôle' }}
                        </div>
                    </div>
                    <x-lucide-chevron-down class="w-3.5 h-3.5 text-muted hidden sm:block transition-transform duration-200" x-bind:class="{ 'rotate-180': profileOpen }" />
                </button>
                
                <!-- Dropdown -->
                <div x-show="profileOpen" 
                     @click.away="profileOpen = false" 
                     x-transition:enter="transition ease-out duration-150" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-100" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1"
                     class="nav-dropdown" x-cloak>
                    
                    <a href="{{ route('profile.edit') }}" class="nav-dropdown-item">
                        <x-lucide-user class="nav-icon" />
                        <span>{{ __('navigation.navbar.profile') }}</span>
                    </a>
                    <a href="{{ route('setting') }}" class="nav-dropdown-item">
                        <x-lucide-settings class="nav-icon" />
                        <span>{{ __('navigation.navbar.setting') }}</span>
                    </a>
                    <a href="#" class="nav-dropdown-item">
                        <x-lucide-life-buoy class="nav-icon" />
                        <span>{{ __('navigation.navbar.support') }}</span>
                    </a>
                    
                    @auth
                        <div class="nav-dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-dropdown-item w-full text-left text-error/80 hover:text-error dark:text-error/70 dark:hover:text-error">
                                <x-lucide-log-out class="nav-icon" />
                                <span>{{ __('navigation.navbar.logout') }}</span>
                            </button>
                        </form>
                    @else
                        <div class="nav-dropdown-divider"></div>
                        <a href="{{ route('login') }}" class="nav-dropdown-item">
                            <x-lucide-log-in class="nav-icon" />
                            <span>Connexion</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>
