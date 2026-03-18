<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased auth-bg-gradient selection:bg-accent selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative Subtle Light (Not too much "AI") -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-accent/10 rounded-full blur-[120px] pointer-events-none"></div>
            
            <div class="mb-8 relative z-10">
                <a href="/" wire:navigate class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 rounded-2xl bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none flex items-center justify-center border border-slate-100 dark:border-slate-800 group-hover:scale-105 transition-transform duration-300">
                        <x-application-logo class="w-10 h-10 fill-current text-accent" />
                    </div>
                    <span class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">CICA-GPRO</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-10 glass-card overflow-hidden sm:rounded-[2.5rem] relative z-10">
                {{ $slot }}
            </div>
            
            <p class="mt-8 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] relative z-10">&copy; {{ date('Y') }} Cave-Tech. All rights reserved.</p>
        </div>
    </body>
</html>
