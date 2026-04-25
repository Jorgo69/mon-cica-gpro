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
    
    <!-- jQuery is required for Summernote -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
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

{{-- <body class="bg-gray-50 dark:bg-gray-900 font-sans">
    <div class="min-h-screen transition-colors duration-300">
        @include('layouts.navbar')
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="lg:ml-60 pt-14 min-h-screen">
            <div class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewire('v-beta.search.global-search-livewire')
    <x-ui.toast-notifications />

    @livewireScripts
    @stack('scripts')
</body> --}}

<body class="bg-gray-50 dark:bg-gray-900 font-sans">
    
        @include('layouts.navbar')
        <!-- Sidebar -->
        @include('layouts.sidebar')


    {{ $slot }}
    
    @livewire('v-beta.search.global-search-livewire')
    <x-ui.toast-notifications />
    <x-ui.offline-banner />

    @livewireScripts
    @stack('scripts')
</body>

</html>