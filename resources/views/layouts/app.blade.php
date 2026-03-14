<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" x-data="appData()" x-init="initTheme()">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <title>{{ $title ?? config('app.name') }}</title>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    @stack('alpine-js')
    


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('message-js')
    
    <!-- jQuery is required for Summernote -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    @stack('styles')
    
    {{-- @livewireStyles --}}
    <x-notify::notify />
    @notifyCss
</head>
<body class="bg-gray-50 dark:bg-gray-900 font-sans">
    <!-- Removed @notifyJs to prevent Alpine.js conflict, replaced with custom logic below -->
    
        @include('layouts.navbar')
        <!-- Sidebar -->
        @include('layouts.sidebar')
    
    
    {{-- <div x-show="isMobile && sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden" x-transition>
    </div> --}}


    {{ $slot }}
    
    <script>
        // Custom logic for laravel-notify to avoid loading its bundled Alpine.js
        document.addEventListener('DOMContentLoaded', () => {
            let notifyEl = document.querySelector("div.notify");
            if (notifyEl) {
                setTimeout(() => { notifyEl.remove() }, {{ config('notify.timeout', 5000) }});
            }
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (data) => {
                alert(data.message);
            });
        });
    </script>

    @stack('scripts')
    
    <script>
        window.appData = function() {
            return {
                sidebarOpen: false,
                isMobile: false,
                profileDropdownOpen: false,
                darkMode: localStorage.getItem('darkMode') === 'true',
                
                init() {
                    this.initTheme();
                    this.initSidebar();
                },
                
                initTheme() {
                    if (localStorage.getItem('darkMode') === 'true' || 
                        (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                        this.darkMode = true;
                    } else {
                        document.documentElement.classList.remove('dark');
                        this.darkMode = false;
                    }
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
    {{-- @livewireScripts --}}
</body>
</html>