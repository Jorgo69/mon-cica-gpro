<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="appData()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <title>{{ $title ?? config('app.name') }}</title>
    @livewireStyles
    @stack('alpine-js')
    


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('message-js')
    
    @stack('styles')

    <script>
        // Initialisation immédiate du thème pour éviter le flash blanc
        if (localStorage.getItem('darkMode') === 'true' || 
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        window.appData = function() {
            return {
                sidebarOpen: false,
                isMobile: false,
                profileOpen: false,
                darkMode: localStorage.getItem('darkMode') === 'true' || 
                         (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                
                init() {
                    this.initSidebar();
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
                
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('darkMode', 'true');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('darkMode', 'false');
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

    @livewire('v-beta.search.global-search-livewire')
    <x-ui.toast-notifications />
    <x-ui.offline-banner />

    @livewireScripts
    @stack('scripts')
</body>

</html>