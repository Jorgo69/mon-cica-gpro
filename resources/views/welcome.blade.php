<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Pilotage de Développement & Expertise Méthodologique</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
</head>
<body 
    class="bg-surface dark:bg-primary-dark text-heading font-sans selection:bg-accent/20 selection:text-accent"
    x-data="{ scrolled: false }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
>
    
    <!-- Premium Geometric Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-accent/5 dark:bg-accent/10 blur-[120px]"></div>
        <div class="absolute top-[20%] -right-[5%] w-[30%] h-[30%] rounded-full bg-primary/5 dark:bg-primary/20 blur-[100px]"></div>
        <div class="absolute -bottom-[10%] left-[20%] w-[50%] h-[50%] rounded-full bg-accent/5 dark:bg-accent/10 blur-[150px]"></div>
    </div>

    <!-- Navigation Area -->
    <nav 
        class="sticky top-0 z-50 transition-all duration-300 border-b"
        x-bind:class="scrolled ? 'bg-card/80 backdrop-blur-md border-border py-2' : 'bg-transparent border-transparent py-4'"
        x-data="{ mobileMenu: false }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-primary dark:bg-surface-alt rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                        <span class="text-white font-black text-xl">C</span>
                    </div>
                    <h1 class="text-xl font-black tracking-tighter text-primary dark:text-white">{{ config('app.name') }}</h1>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="#solutions" class="px-4 py-2 text-sm font-bold text-subtle hover:text-primary dark:hover:text-accent transition-colors">Solutions</a>
                    <a href="#expertise" class="px-4 py-2 text-sm font-bold text-subtle hover:text-primary dark:hover:text-accent transition-colors">Expertise</a>
                    <a href="#process" class="px-4 py-2 text-sm font-bold text-subtle hover:text-primary dark:hover:text-accent transition-colors">Processus</a>
                    <a href="#faq" class="px-4 py-2 text-sm font-bold text-subtle hover:text-primary dark:hover:text-accent transition-colors">FAQ</a>
                    <a href="#contact" class="px-4 py-2 text-sm font-bold text-subtle hover:text-primary dark:hover:text-accent transition-colors">Contact</a>
                    
                    <div class="h-6 w-px bg-border mx-4"></div>
                    
                    @auth
                        <x-ui.button tag="a" :href="route('dashboard')" variant="primary" size="md">
                            Mon Espace
                        </x-ui.button>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-bold text-subtle hover:text-primary dark:hover:text-white transition-colors">Connexion</a>
                        <x-ui.button tag="a" :href="route('register')" variant="accent" size="md">
                            Débuter
                        </x-ui.button>
                    @endauth
                </div>

                <!-- Mobile toggle -->
                <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 text-subtle">
                    <x-lucide-menu x-show="!mobileMenu" class="w-6 h-6" />
                    <x-lucide-x x-show="mobileMenu" class="w-6 h-6" />
                </button>
            </div>
        </div>
        
        <!-- Mobile Dropdown -->
        <div x-show="mobileMenu" x-transition class="md:hidden bg-card border-t border-border-light p-4 space-y-2">
            @foreach(['solutions' => 'Solutions', 'expertise' => 'Expertise', 'process' => 'Processus', 'faq' => 'FAQ'] as $id => $label)
                <a href="#{{ $id }}" @click="mobileMenu = false" class="block px-4 py-3 text-lg font-bold text-body">{{ $label }}</a>
            @endforeach
            <div class="pt-4 border-t border-border-light flex flex-col gap-3">
                <a href="{{ route('login') }}" class="text-center py-3 font-bold text-subtle">Connexion</a>
                <x-ui.button tag="a" :href="route('register')" variant="accent" size="lg" class="w-full">
                    Débuter l'expérience
                </x-ui.button>
            </div>
        </div>
    </nav>

    <main>
        <!-- Hero Section -->
        <section class="relative pt-12 pb-24 md:pt-24 md:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent/10 border border-accent/20 mb-8">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-accent opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-accent"></span>
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-accent">Ingénierie de Projet Certifiée</span>
                    </div>
                    
                    <h2 class="text-5xl md:text-8xl font-black tracking-tightest text-primary dark:text-white mb-8 max-w-5xl mx-auto leading-[0.9]">
                        Concevez avec Rigueur. <br> Gérez avec <span class="text-accent underline decoration-accent/30 underline-offset-8">Expertise</span>.
                    </h2>
                    
                    <p class="text-lg md:text-xl text-subtle mb-12 max-w-2xl mx-auto leading-relaxed">
                        L'outil de référence pour les professionnels exigeant une conformité absolue aux cadres logiques internationaux et une gestion rigoureuse des performances.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <x-ui.button tag="a" :href="route('register')" variant="primary" size="xl" icon="zap" class="w-full sm:w-auto">
                            Initialiser un projet
                        </x-ui.button>
                        <x-ui.button tag="a" href="#solutions" variant="ghost" size="xl" icon-right="chevron-right" class="w-full sm:w-auto">
                            Explorer les solutions
                        </x-ui.button>
                    </div>

                    <!-- App Preview Mockup -->
                    <div class="mt-20 relative px-4 md:px-0">
                        <div class="glass-card rounded-[2rem] p-4 md:p-6 shadow-2xl shadow-primary/20 max-w-5xl mx-auto overflow-hidden">
                            <div class="bg-surface-alt rounded-xl aspect-[16/9] flex items-center justify-center">
                                <x-lucide-activity class="w-20 h-20 text-accent/40 animate-pulse" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Solutions Section -->
        <section id="solutions" class="py-24 bg-card border-y border-border-light">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">Solutions d'Ingénierie</h3>
                        <h4 class="text-4xl md:text-5xl font-black tracking-tight text-primary dark:text-white mb-6">Un écosystème conçu pour la haute performance.</h4>
                        <p class="text-subtle text-lg mb-10 leading-relaxed">
                            Chaque module est calibré pour répondre aux standards de l'Approche du Cadre Logique (ACL) et de la Gestion Axée sur les Résultats (GAR).
                        </p>
                        
                        <div class="space-y-6">
                            @foreach([
                                ['brain', 'Analyse Environnementale', 'Définissez vos parties prenantes et vos arbres de problèmes avec une rigueur systématique.'],
                                ['layout', 'Matrice de Cadre Logique', 'Générez des matrices robustes avec indicateurs, sources de vérification et hypothèses.'],
                                ['calculator', 'Pilotage Budgétaire', 'Associez chaque activité à ses ressources pour un suivi financier en temps réel.']
                            ] as $feat)
                            <div class="flex gap-4 p-4 rounded-2xl hover:bg-surface transition-colors group">
                                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <x-dynamic-component :component="'lucide-' . $feat[0]" class="w-6 h-6 text-accent" />
                                </div>
                                <div>
                                    <h5 class="font-bold text-primary dark:text-white mb-1">{{ $feat[1] }}</h5>
                                    <p class="text-sm text-subtle">{{ $feat[2] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4 pt-12">
                            <div class="glass-card p-6 rounded-3xl h-64 border-accent/20">
                                <x-lucide-trending-up class="w-10 h-10 text-accent mb-4" />
                                <p class="font-black text-xl text-primary dark:text-white">Suivi KPI</p>
                                <p class="text-xs text-subtle mt-2 italic">Visualisation dynamique des indicateurs clés.</p>
                            </div>
                            <div class="bg-primary p-6 rounded-3xl h-48">
                                <p class="text-white font-black text-4xl">100%</p>
                                <p class="text-muted text-[10px] font-bold uppercase tracking-widest mt-2">Conformité Standards</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-accent p-6 rounded-3xl h-48">
                                <x-lucide-award class="text-white w-10 h-10" />
                                <p class="text-white font-bold mt-4 uppercase text-[10px] tracking-widest">Expertise Intégrée</p>
                            </div>
                            <div class="glass-card p-6 rounded-3xl h-64 border-border">
                                <x-lucide-shield-check class="w-10 h-10 text-primary dark:text-accent mb-4" />
                                <p class="font-black text-xl text-primary dark:text-white">Sécurité Totale</p>
                                <p class="text-xs text-subtle mt-2">Isolation multi-tenant de niveau entreprise.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section id="process" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">Le Processus</h3>
                    <h4 class="text-3xl md:text-5xl font-black text-primary dark:text-white">Concevoir n'a jamais été aussi <span class="italic">ordonné</span>.</h4>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @foreach([
                        ['01', 'Analyse', 'Identification des problèmes et objectifs stratégiques.', 'search'],
                        ['02', 'Planification', 'Élaboration de la matrice et du plan d\'action.', 'list-todo'],
                        ['03', 'Exécution', 'Activités, ressources et suivi au quotidien.', 'play'],
                        ['04', 'Reporting', 'Génération de rapports d\'étape et bilan.', 'file-pie-chart']
                    ] as $step)
                    <div class="relative p-8 rounded-[2rem] bg-card border border-border-light hover:border-accent group transition-all">
                        <span class="absolute -top-4 left-8 bg-accent text-white font-black text-xs px-3 py-1 rounded-full">{{ $step[0] }}</span>
                        <div class="w-12 h-12 rounded-xl bg-surface-alt flex items-center justify-center mb-6 text-muted group-hover:text-accent transition-colors">
                            <x-dynamic-component :component="'lucide-' . $step[3]" class="w-6 h-6" />
                        </div>
                        <h5 class="font-bold text-primary dark:text-white mb-2">{{ $step[1] }}</h5>
                        <p class="text-sm text-subtle">{{ $step[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- FAQ Section -->
        <section id="faq" class="py-24 bg-surface-alt dark:bg-primary-dark/50">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">FAQ</h3>
                    <h4 class="text-3xl font-black text-primary dark:text-white">Questions Fréquentes</h4>
                </div>
                
                <div class="space-y-4" x-data="{ active: null }">
                    @foreach([
                        ['Qu\'est-ce que l\'Approche du Cadre Logique (ACL) ?', 'L\'ACL est une méthodologie participative utilisée pour concevoir, exécuter et évaluer des projets. Elle permet de structurer les objectifs et les activités de manière cohérente.'],
                        ['Est-ce adapté aux petites structures ?', 'Absolument. La plateforme est conçue pour guider les novices tout en offrant la rigueur nécessaire aux grandes organisations internationales.'],
                        ['Puis-je exporter mes rapports de projet ?', 'Oui, tous vos cadres logiques et rapports d\'avancement peuvent être exportés dans des formats standards pour vos partenaires financiers.'],
                        ['Mes données sont-elles sécurisées ?', 'Nous utilisons une architecture multi-tenant stricte. Chaque organisation possède son propre espace de données hermétique et chiffré.']
                    ] as $index => $faq)
                    <div class="bg-card rounded-2xl border border-border overflow-hidden">
                        <button 
                            @click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full px-6 py-5 text-left flex justify-between items-center group"
                        >
                            <span class="font-bold text-body group-hover:text-accent transition-colors">{{ $faq[0] }}</span>
                            <x-lucide-chevron-down 
                                class="w-5 h-5 text-muted transition-transform duration-300"
                                x-bind:class="active === {{ $index }} ? 'rotate-180' : ''"
                            />
                        </button>
                        <div 
                            x-show="active === {{ $index }}" 
                            x-collapse
                            class="px-6 pb-5 text-sm text-subtle leading-relaxed"
                        >
                            {{ $faq[1] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- CTA Professional -->
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative bg-primary dark:bg-surface rounded-[3rem] px-8 py-20 md:p-24 overflow-hidden shadow-2xl shadow-primary/40 dark:shadow-black">
                    <!-- Decor -->
                    <div class="absolute top-0 right-0 w-64 h-64 bg-accent/20 blur-[100px]"></div>
                    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-accent/10 blur-[100px]"></div>
                    
                    <div class="relative text-center max-w-3xl mx-auto">
                        <h3 class="text-3xl md:text-5xl font-black text-white mb-8 tracking-tight">
                            Excellence et Rigueur Opérationnelle.
                        </h3>
                        <p class="text-muted mb-12 text-lg">
                            Rejoignez les experts qui utilisent {{ config('app.name') }} pour maximiser l'impact de leurs projets de développement.
                        </p>
                        <x-ui.button tag="a" :href="route('register')" variant="accent" size="xl" icon="arrow-right">
                            Initialiser mon espace
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="contact" class="bg-surface dark:bg-primary-dark border-t border-border py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 bg-primary dark:bg-surface-alt rounded-lg flex items-center justify-center">
                            <span class="text-white font-black text-sm">C</span>
                        </div>
                        <h1 class="text-lg font-black tracking-tight text-primary dark:text-white">{{ config('app.name') }}</h1>
                    </div>
                    <p class="text-subtle text-sm max-w-xs leading-relaxed">
                        Plateforme dédiée à l'ingénierie et au pilotage stratégique de projets de développement. Réduisez les risques, maximisez les impacts.
                    </p>
                    <div class="mt-8 flex gap-4">
                        <a href="mailto:contact@{{ strtolower(config('app.name')) }}.com" class="p-3 rounded-xl bg-card border border-border text-subtle hover:text-accent transition-colors shadow-sm">
                            <x-lucide-mail class="w-5 h-5" />
                        </a>
                        <a href="#" class="p-3 rounded-xl bg-card border border-border text-subtle hover:text-accent transition-colors shadow-sm">
                            <x-lucide-phone class="w-5 h-5" />
                        </a>
                    </div>
                </div>
                <div>
                    <h6 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">Plateforme</h6>
                    <ul class="space-y-4">
                        <li><a href="#solutions" class="text-sm font-bold text-subtle hover:text-accent transition-colors">Solutions</a></li>
                        <li><a href="#expertise" class="text-sm font-bold text-subtle hover:text-accent transition-colors">Expertise</a></li>
                        <li><a href="{{ route('login') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">Portail Client</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">Ressources</h6>
                    <ul class="space-y-4">
                        <li><a href="#faq" class="text-sm font-bold text-subtle hover:text-accent transition-colors">Aide & FAQ</a></li>
                        <li><a href="#" class="text-sm font-bold text-subtle hover:text-accent transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="text-sm font-bold text-subtle hover:text-accent transition-colors">Conditions</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-16 pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs text-muted font-bold uppercase tracking-widest">© 2024 {{ config('app.name') }}. Tous droits réservés.</p>
                <button 
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-muted hover:text-primary dark:hover:text-accent transition-colors"
                >
                    Retour en haut 
                    <x-lucide-arrow-up class="w-4 h-4 group-hover:-translate-y-1 transition-transform" />
                </button>
            </div>
        </div>
    </footer>

    <!-- Back to top sticky button -->
    <div 
        class="fixed bottom-8 right-8 z-[60] transition-all duration-500"
        x-bind:class="scrolled ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10 pointer-events-none'"
    >
        <button 
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-12 h-12 bg-primary dark:bg-accent text-white rounded-full shadow-2xl shadow-primary/40 flex items-center justify-center hover:scale-110 active:scale-95 transition-all"
        >
            <x-lucide-arrow-up class="w-6 h-6" />
        </button>
    </div>

</body>
</html>
