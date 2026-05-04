<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CICA-GPRO — Plateforme open source de gestion de projets basee sur le Cadre Logique. Multi-tenant, IA integree, API REST.">
    <title>{{ config('app.name') }} — Gestion Intelligente de Projets</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body
    class="bg-surface text-heading font-sans selection:bg-blue-600/20"
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
>

    {{-- Background --}}
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-blue-500/5 blur-[120px]"></div>
        <div class="absolute top-[30%] -right-[5%] w-[30%] h-[30%] rounded-full bg-indigo-500/5 blur-[100px]"></div>
    </div>

    {{-- Navigation --}}
    <nav
        class="sticky top-0 z-50 transition-all duration-300 border-b"
        :class="scrolled ? 'bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-gray-200 dark:border-gray-800 py-2' : 'bg-transparent border-transparent py-4'"
        x-data="{ mobileMenu: false }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14">
                <div class="flex items-center gap-2">
                    <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center">
                        <span class="text-white font-black text-lg">G</span>
                    </div>
                    <span class="text-xl font-black tracking-tight text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                    <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">v2.0</span>
                </div>

                <div class="hidden md:flex items-center gap-1">
                    <a href="#features" class="px-3 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Fonctionnalites</a>
                    <a href="#how-it-works" class="px-3 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Comment ca marche</a>
                    <a href="#open-source" class="px-3 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Open Source</a>
                    @if(isSaas())
                    <a href="{{ route('pricing') }}" class="px-3 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Tarifs</a>
                    @endif
                    <a href="#faq" class="px-3 py-2 text-sm font-semibold text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">FAQ</a>

                    <div class="h-5 w-px bg-gray-200 dark:bg-gray-700 mx-3"></div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">Mon Espace</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">Connexion</a>
                        <a href="{{ route('register') }}" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">Commencer</a>
                    @endauth
                </div>

                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-gray-500">
                    <x-lucide-menu x-show="!mobileMenu" class="w-6 h-6" />
                    <x-lucide-x x-show="mobileMenu" class="w-6 h-6" x-cloak />
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileMenu" x-transition x-cloak class="md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 p-4 space-y-1">
            <a href="#features" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Fonctionnalites</a>
            <a href="#how-it-works" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Comment ca marche</a>
            <a href="#open-source" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">Open Source</a>
            <a href="#faq" @click="mobileMenu = false" class="block px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-800">FAQ</a>
            <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="text-center py-3 font-semibold text-gray-600 dark:text-gray-400">Connexion</a>
                <a href="{{ route('register') }}" class="text-center py-3 font-bold text-white bg-blue-600 rounded-lg">Commencer</a>
            </div>
        </div>
    </nav>

    <main>
        {{-- Hero --}}
        <section class="pt-16 pb-24 md:pt-28 md:pb-36">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 mb-8">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    <span class="text-xs font-bold text-blue-700 dark:text-blue-300">Open Source — MIT License</span>
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-7xl font-black tracking-tight text-gray-900 dark:text-white mb-6 leading-[1.1]">
                    Gerez vos projets<br>avec le <span class="text-blue-600">Cadre Logique</span>
                </h1>

                <p class="text-lg md:text-xl text-gray-500 dark:text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Plateforme complete pour les ONG et organisations de developpement.
                    Cadre logique, budgets, indicateurs, rapports — tout en un.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-lg shadow-blue-600/25">
                        Commencer gratuitement
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-blue-300 rounded-xl transition-colors">
                        Decouvrir les fonctionnalites
                    </a>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto">
                    @foreach([
                        ['462+', 'Tests automatises'],
                        ['9', 'Providers IA'],
                        ['15', 'Endpoints API'],
                        ['8', 'Devises supportees'],
                    ] as $stat)
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white">{{ $stat[0] }}</div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $stat[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section id="features" class="py-24 bg-gray-50 dark:bg-gray-900/50 border-y border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-3">Fonctionnalites</h2>
                    <h3 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white">Tout ce dont vous avez besoin</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach([
                        ['target', 'Cadre Logique', 'Structure hierarchique complete : Objectif General, Objectifs Specifiques, Resultats, Activites, Sous-activites.'],
                        ['bar-chart-3', 'Budgets & Finances', 'Budget planifie vs depenses reelles, multi-devise (8 devises), burn rate, alertes de depassement.'],
                        ['users', 'Multi-tenant', 'Chaque organisation a son espace isole. RBAC 4 niveaux avec permissions granulaires.'],
                        ['brain', 'IA Integree', '9 providers (Groq, OpenAI, Anthropic, Gemini...). Assistance par champ, resume executif, analyse dashboard.'],
                        ['file-text', 'Exports', 'PDF, Word, Excel multi-feuilles. Rapports automatiques trimestriels. Tableau de bord bailleur public.'],
                        ['calendar', 'Calendrier', '6 vues (annee, semestre, trimestre, mois, semaine, jour). Export iCal, sync Google Calendar / Outlook.'],
                        ['activity', 'Indicateurs', 'Suivi de progression avec mesures, tendances, alertes automatiques (stagnation, regression).'],
                        ['globe', 'API REST v1', '15 endpoints, auth Sanctum Bearer tokens, rate limiting. Documentation complete.'],
                        ['webhook', 'Webhooks', '10 evenements, signature HMAC-SHA256, auto-disable apres 10 echecs.'],
                        ['map-pin', 'Carte Projets', 'Visualisation geographique (Leaflet), 40 pays geocodes, marqueurs colores par statut.'],
                        ['puzzle', 'Marketplace', 'Systeme de plugins extensible. 8 hooks disponibles. Creez vos propres extensions.'],
                        ['shield-check', 'RGPD', 'Export donnees, anonymisation, suppression planifiee. Conformite totale.'],
                    ] as $feat)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 hover:border-blue-300 dark:hover:border-blue-700 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <x-dynamic-component :component="'lucide-' . $feat[0]" class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">{{ $feat[1] }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $feat[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- How it works --}}
        <section id="how-it-works" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-3">Comment ca marche</h2>
                    <h3 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white">Du cadre logique au rapport final</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @foreach([
                        ['1', 'Creez votre projet', 'Definissez l\'objectif general, les objectifs specifiques et les resultats attendus.', 'file-plus'],
                        ['2', 'Planifiez les activites', 'Assignez les responsables, les budgets et les echeances a chaque activite.', 'list-todo'],
                        ['3', 'Suivez la progression', 'Tableau de bord en temps reel, indicateurs, alertes automatiques, commentaires.', 'trending-up'],
                        ['4', 'Generez les rapports', 'Export PDF/Word/Excel, partage bailleur, calendrier, API pour vos outils.', 'file-bar-chart'],
                    ] as $step)
                    <div class="relative text-center">
                        <div class="w-12 h-12 mx-auto mb-4 rounded-full bg-blue-600 text-white font-black text-lg flex items-center justify-center">{{ $step[0] }}</div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">{{ $step[1] }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $step[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Open Source --}}
        <section id="open-source" class="py-24 bg-gray-50 dark:bg-gray-900/50 border-y border-gray-200 dark:border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h2 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-3">Open Source</h2>
                        <h3 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-6">Deux modes, un seul outil</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-lg mb-8 leading-relaxed">
                            CICA-GPRO est disponible en open source (MIT). Installez-le sur votre serveur ou utilisez notre version SaaS hebergee.
                        </p>

                        <div class="space-y-4">
                            <div class="flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <div class="w-10 h-10 rounded-lg bg-green-50 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                                    <x-lucide-server class="w-5 h-5 text-green-600" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">Self-hosted</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Installez sur votre serveur. Tout illimite. Docker inclus.</p>
                                </div>
                            </div>
                            <div class="flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                    <x-lucide-cloud class="w-5 h-5 text-blue-600" />
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">SaaS</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Version hebergee avec plans (Free / Pro / Enterprise). Zero maintenance.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-900 rounded-2xl p-6 font-mono text-sm overflow-hidden">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="ml-2 text-gray-500 text-xs">terminal</span>
                        </div>
                        <div class="space-y-1 text-gray-300">
                            <p><span class="text-green-400">$</span> git clone https://github.com/cave-tech/cica-gpro.git</p>
                            <p><span class="text-green-400">$</span> cd cica-gpro</p>
                            <p><span class="text-green-400">$</span> cp .env.docker .env</p>
                            <p><span class="text-green-400">$</span> make up</p>
                            <p class="text-gray-500 mt-2"># Application disponible sur http://localhost:8080</p>
                            <p class="text-gray-500"># phpMyAdmin sur http://localhost:8081</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ --}}
        <section id="faq" class="py-24">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-3">FAQ</h2>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white">Questions frequentes</h3>
                </div>

                <div class="space-y-3" x-data="{ active: null }">
                    @foreach([
                        ['Qu\'est-ce que le Cadre Logique ?', 'Le Cadre Logique (LogFrame) est une methodologie standard utilisee par les ONG et bailleurs de fonds pour concevoir, suivre et evaluer des projets de developpement. Il structure les objectifs, resultats, activites et indicateurs de maniere hierarchique.'],
                        ['GPRO est-il adapte aux petites organisations ?', 'Oui. En mode selfhosted, tout est gratuit et illimite. L\'interface guide les utilisateurs pas a pas. Le systeme d\'onboarding et la FAQ integree facilitent la prise en main.'],
                        ['Quels formats d\'export sont supportes ?', 'PDF (DomPDF ou Chromium), Word (PHPWord), Excel multi-feuilles (Maatwebsite). Les rapports peuvent etre generes automatiquement chaque trimestre et envoyes par email.'],
                        ['Mes donnees sont-elles securisees ?', 'Oui. Architecture multi-tenant stricte (isolation par organisation), chiffrement des cles API, audit logs complet, conformite RGPD (export, anonymisation, suppression). En selfhosted, vos donnees restent sur votre serveur.'],
                        ['Puis-je integrer GPRO avec d\'autres outils ?', 'Oui. API REST v1 (15 endpoints, auth Sanctum), webhooks HMAC-SHA256 (10 evenements), export iCal (sync calendrier), systeme de plugins extensible.'],
                        ['Comment installer la version selfhosted ?', 'Avec Docker : 3 commandes (git clone, cp .env.docker .env, make up). Supporte MySQL et PostgreSQL. Guide complet dans la documentation.'],
                        ['L\'IA est-elle obligatoire ?', 'Non. L\'IA est optionnelle et desactivee par defaut. Si vous la configurez (Groq gratuit recommande), elle assiste la redaction des descriptions, resume executif et analyse du dashboard.'],
                    ] as $index => $faq)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <button
                            @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full px-6 py-4 text-left flex justify-between items-center"
                        >
                            <span class="font-semibold text-gray-900 dark:text-white pr-4">{{ $faq[0] }}</span>
                            <x-lucide-chevron-down
                                class="w-5 h-5 text-gray-400 transition-transform duration-300 shrink-0"
                                ::class="active === {{ $index }} ? 'rotate-180' : ''"
                            />
                        </button>
                        <div x-show="active === {{ $index }}" x-collapse class="px-6 pb-4 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            {{ $faq[1] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-blue-600 rounded-3xl px-8 py-16 md:px-16 md:py-20 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 blur-[100px]"></div>
                    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-white/5 blur-[100px]"></div>

                    <div class="relative max-w-2xl mx-auto">
                        <h3 class="text-3xl md:text-4xl font-black text-white mb-4">Pret a structurer vos projets ?</h3>
                        <p class="text-blue-100 mb-8 text-lg">Rejoignez les organisations qui utilisent GPRO pour maximiser l'impact de leurs projets de developpement.</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 font-bold text-blue-600 bg-white hover:bg-blue-50 rounded-xl transition-colors">
                                Creer mon compte
                            </a>
                            <a href="https://github.com/cave-tech/cica-gpro" target="_blank" class="w-full sm:w-auto px-8 py-4 font-bold text-white border-2 border-white/30 hover:border-white/60 rounded-xl transition-colors">
                                Voir sur GitHub
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer id="contact" class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-black text-sm">G</span>
                        </div>
                        <span class="text-lg font-black text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs leading-relaxed mb-6">
                        Plateforme open source de gestion de projets basee sur le Cadre Logique. Concue pour les ONG et organisations de developpement.
                    </p>
                    <div class="flex gap-3">
                        <a href="mailto:contact@cave-tech.com" class="p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-blue-600 transition-colors" title="Email">
                            <x-lucide-mail class="w-4 h-4" />
                        </a>
                        <a href="https://github.com/cave-tech/cica-gpro" target="_blank" class="p-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 hover:text-blue-600 transition-colors" title="GitHub">
                            <x-lucide-github class="w-4 h-4" />
                        </a>
                    </div>
                </div>

                <div>
                    <h6 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Produit</h6>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Fonctionnalites</a></li>
                        @if(isSaas())
                        <li><a href="{{ route('pricing') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Tarifs</a></li>
                        @endif
                        <li><a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Inscription</a></li>
                    </ul>
                </div>

                <div>
                    <h6 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Legal</h6>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/privacy') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Confidentialite</a></li>
                        <li><a href="{{ url('/terms') }}" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">Conditions d'utilisation</a></li>
                        <li><a href="#faq" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">FAQ</a></li>
                        <li><a href="https://github.com/cave-tech/cica-gpro" target="_blank" class="text-sm text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">GitHub</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} {{ config('app.name') }}. Open source sous licence MIT. Developpe par Cave-Tech.</p>
                <button
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="text-xs font-semibold text-gray-400 hover:text-blue-600 transition-colors flex items-center gap-1"
                >
                    Retour en haut <x-lucide-arrow-up class="w-3 h-3" />
                </button>
            </div>
        </div>
    </footer>

    {{-- Back to top --}}
    <div
        class="fixed bottom-6 right-6 z-50 transition-all duration-500"
        :class="scrolled ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10 pointer-events-none'"
    >
        <button
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-10 h-10 bg-blue-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-blue-700 transition-colors"
        >
            <x-lucide-arrow-up class="w-5 h-5" />
        </button>
    </div>

</body>
</html>
