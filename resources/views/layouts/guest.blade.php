<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0e7490">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

        <title>{{ config('app.name', 'CICA-GPRO') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-heading antialiased selection:bg-accent selection:text-white">
        <div class="min-h-screen flex">

            {{-- Panneau gauche : branding (visible uniquement en lg+) --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-accent via-accent-dark to-slate-900 text-white flex-col justify-between p-12">
                {{-- Decorations --}}
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/3 -translate-x-1/3"></div>

                {{-- Logo + nom --}}
                <div class="relative z-10">
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <span class="text-lg font-black text-white">G</span>
                        </div>
                        <span class="text-xl font-black tracking-tight">CICA-GPRO</span>
                    </a>
                </div>

                {{-- Contenu central --}}
                <div class="relative z-10 space-y-8">
                    <div>
                        <h1 class="text-3xl xl:text-4xl font-black leading-tight">
                            {{ __('auth.branding_title', ['default' => 'Gerez vos projets avec le Cadre Logique']) }}
                        </h1>
                        <p class="mt-4 text-white/70 text-sm leading-relaxed max-w-md">
                            {{ __('auth.branding_subtitle', ['default' => 'Plateforme complete pour les ONG et organisations de developpement. Cadre logique, budgets, indicateurs, rapports.']) }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <x-lucide-layout-dashboard class="w-4 h-4 text-white/80" />
                            </div>
                            <span class="text-sm font-medium text-white/90">{{ __('auth.feature_1', ['default' => 'Tableau de bord intelligent']) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <x-lucide-bar-chart-3 class="w-4 h-4 text-white/80" />
                            </div>
                            <span class="text-sm font-medium text-white/90">{{ __('auth.feature_2', ['default' => 'Suivi budgetaire en temps reel']) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <x-lucide-file-text class="w-4 h-4 text-white/80" />
                            </div>
                            <span class="text-sm font-medium text-white/90">{{ __('auth.feature_3', ['default' => 'Export PDF, DOCX et Excel']) }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <x-lucide-sparkles class="w-4 h-4 text-white/80" />
                            </div>
                            <span class="text-sm font-medium text-white/90">{{ __('auth.feature_4', ['default' => 'Assistant IA integre']) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="relative z-10">
                    <p class="text-xs text-white/40">&copy; {{ date('Y') }} Cave-Tech &middot; Open Source &mdash; MIT License</p>
                </div>
            </div>

            {{-- Panneau droit : formulaire --}}
            <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-4 sm:px-8 lg:px-12 py-8 sm:py-12 bg-slate-50 dark:bg-slate-900 relative min-h-screen">
                {{-- Decoration subtle --}}
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>

                {{-- Logo mobile/tablette seulement --}}
                <div class="mb-6 sm:mb-8 lg:hidden">
                    <a href="/" class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-accent rounded-2xl flex items-center justify-center shadow-lg shadow-accent/20">
                            <span class="text-xl sm:text-2xl font-black text-white">G</span>
                        </div>
                        <span class="text-base sm:text-lg font-black text-heading tracking-tight">CICA-GPRO</span>
                    </a>
                </div>

                <div class="w-full max-w-md relative z-10 bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-lg shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-200 dark:border-slate-700">
                    {{ $slot }}
                </div>

                <p class="mt-6 sm:mt-8 text-[10px] font-bold text-muted uppercase tracking-[0.2em]">&copy; {{ date('Y') }} Cave-Tech</p>
            </div>
        </div>
        <x-ui.cookie-banner />
    </body>
</html>
