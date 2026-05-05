<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appData()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6366f1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <title>{{ $title ?? config('app.name') }}</title>
    @livewireStyles
    @stack('alpine-js')
    


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('message-js')
    
    @stack('styles')

    @auth
        <meta name="user-theme" content="{{ \App\Services\UserMeta::get('theme', '') }}">
    @endauth
    <script>
        // Initialisation theme : DB (meta tag) > localStorage > system preference
        // Purement JS pour survivre aux navigations wire:navigate
        (function() {
            var dbTheme = document.querySelector('meta[name="user-theme"]')?.content;
            if (dbTheme === 'dark' || dbTheme === 'light') {
                // Sync DB → localStorage au premier chargement
                localStorage.setItem('darkMode', dbTheme === 'dark' ? 'true' : 'false');
            }
            var isDark = localStorage.getItem('darkMode') === 'true' ||
                (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();

        window.appData = function() {
            return {
                sidebarOpen: false,
                isMobile: false,
                profileOpen: false,
                darkMode: localStorage.getItem('darkMode') === 'true' || 
                         (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                
                init() {
                    this.initSidebar();
                    this.listenThemeChange();
                },
                
                initSidebar() {
                    this.checkScreenSize();
                    window.addEventListener('resize', () => this.checkScreenSize());
                    if (!this.isMobile) {
                        this.sidebarOpen = true;
                    }
                },
                
                checkScreenSize() {
                    this.isMobile = window.innerWidth < 1024;
                    if (this.isMobile) {
                        this.sidebarOpen = false;
                    } else {
                        this.sidebarOpen = true;
                    }
                },
                
                // Ecouter le changement de theme depuis Settings Livewire
                listenThemeChange() {
                    window.addEventListener('theme-changed', (e) => {
                        const theme = e.detail?.theme ?? e.detail?.[0]?.theme;
                        if (theme) {
                            this.darkMode = theme === 'dark';
                        }
                    });
                    document.addEventListener('livewire:init', () => {
                        Livewire.on('theme-changed', (data) => {
                            const theme = Array.isArray(data) ? data[0]?.theme : data?.theme;
                            if (theme) {
                                this.darkMode = theme === 'dark';
                            }
                        });
                    });
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    const newTheme = this.darkMode ? 'dark' : 'light';
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('darkMode', 'true');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('darkMode', 'false');
                    }
                    // Persist in DB + sync Settings Livewire si present
                    fetch('/api/user-meta', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ key: 'theme', value: newTheme })
                    }).catch(() => {});
                    // Mettre a jour le composant Settings s'il est monte
                    if (window.Livewire) {
                        Livewire.dispatch('navbar-theme-changed', { theme: newTheme });
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-surface font-sans">
    <x-ui.org-impersonation-banner />
    <div class="min-h-screen transition-colors duration-300 @if(auth()->user()?->role === \App\Enums\AccountType::ROOT && session('acting_as_organization_id')) pt-8 @endif">
        @include('layouts.navbar')
        @if(auth()->user()?->role === \App\Enums\AccountType::ROOT && !session('acting_as_organization_id'))
            @include('layouts.sidebar-root')
        @else
            @include('layouts.sidebar')
        @endif

        {{-- Le slot est rendu directement — les pages utilisent <x-ui.page-layout>
             qui fournit deja lg:ml-60 pt-14 --}}
        {{ $slot }}
    </div>

    @livewire('v1.search.global-search-livewire')
    <x-ui.toast-notifications />
    <x-ui.offline-banner />

    @livewireScripts
    @stack('scripts')

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>

</html>