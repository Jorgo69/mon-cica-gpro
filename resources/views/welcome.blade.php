<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.name') }} — {{ __('landing.hero.subtitle') }}">
    <title>{{ config('app.name') }} — {{ __('landing.hero.title_highlight') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            var isDark = localStorage.getItem('darkMode') === 'true' ||
                (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) document.documentElement.classList.add('dark');
        })();
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body
    class="bg-surface text-heading font-sans selection:bg-accent/20 selection:text-accent"
    x-data="{
        scrolled: false,
        darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            document.documentElement.classList.toggle('dark', this.darkMode);
        }
    }"
    @scroll.window="scrolled = (window.pageYOffset > 20)"
>

    <!-- ========== NAVIGATION ========== -->
    <nav
        class="sticky top-0 z-50 bg-card border-b border-border transition-all duration-300"
        x-bind:class="scrolled ? 'shadow-sm py-2' : 'py-4'"
        x-data="{ mobileMenu: false }"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center gap-2">
                    <x-ui.logo size="md" />
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="#solutions" class="px-4 py-2 text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.solutions') }}</a>
                    <a href="#expertise" class="px-4 py-2 text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.expertise') }}</a>
                    <a href="#process" class="px-4 py-2 text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.process') }}</a>
                    <a href="#faq" class="px-4 py-2 text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.faq') }}</a>
                    <a href="#contact" class="px-4 py-2 text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.contact') }}</a>
                    @if(isSaas())
                    <a href="{{ route('pricing') }}" class="px-4 py-2 text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.pricing') }}</a>
                    @endif

                    <div class="h-6 w-px bg-border mx-3"></div>

                    {{-- Lang switch --}}
                    @php $otherLocale = app()->getLocale() === 'fr' ? 'en' : 'fr'; @endphp
                    <a href="{{ url('lang/' . $otherLocale) }}" class="px-2.5 py-1.5 text-[11px] font-black uppercase tracking-wider text-muted hover:text-accent border border-border rounded-lg transition-colors">
                        {{ __('landing.nav.lang_switch') }}
                    </a>

                    {{-- Dark mode toggle --}}
                    <button @click="toggleDark()" class="p-2 text-muted hover:text-accent transition-colors cursor-pointer" title="Toggle dark mode">
                        <x-lucide-sun x-show="darkMode" x-cloak class="w-4 h-4" />
                        <x-lucide-moon x-show="!darkMode" class="w-4 h-4" />
                    </button>

                    <div class="h-6 w-px bg-border mx-3"></div>

                    @auth
                        <x-ui.button tag="a" :href="route('dashboard')" variant="primary" size="md">
                            {{ __('landing.nav.dashboard') }}
                        </x-ui.button>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 text-sm font-bold text-subtle hover:text-heading transition-colors">{{ __('landing.nav.login') }}</a>
                        <x-ui.button tag="a" :href="route('register')" variant="accent" size="md">
                            {{ __('landing.nav.start') }}
                        </x-ui.button>
                    @endauth
                </div>

                <!-- Mobile -->
                <div class="flex items-center gap-2 md:hidden">
                    <a href="{{ url('lang/' . $otherLocale) }}" class="px-2 py-1 text-[10px] font-black uppercase tracking-wider text-muted border border-border rounded-lg">
                        {{ __('landing.nav.lang_switch') }}
                    </a>
                    <button @click="toggleDark()" class="p-2 text-muted cursor-pointer">
                        <x-lucide-sun x-show="darkMode" x-cloak class="w-4 h-4" />
                        <x-lucide-moon x-show="!darkMode" class="w-4 h-4" />
                    </button>
                    <button @click="mobileMenu = !mobileMenu" class="p-2 text-subtle cursor-pointer">
                        <x-lucide-menu x-show="!mobileMenu" class="w-6 h-6" />
                        <x-lucide-x x-show="mobileMenu" class="w-6 h-6" x-cloak />
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div x-show="mobileMenu" x-transition x-cloak class="md:hidden bg-card border-t border-border p-4 space-y-2">
            <a href="#solutions" @click="mobileMenu = false" class="block px-4 py-3 text-lg font-bold text-body">{{ __('landing.nav.solutions') }}</a>
            <a href="#expertise" @click="mobileMenu = false" class="block px-4 py-3 text-lg font-bold text-body">{{ __('landing.nav.expertise') }}</a>
            <a href="#process" @click="mobileMenu = false" class="block px-4 py-3 text-lg font-bold text-body">{{ __('landing.nav.process') }}</a>
            <a href="#faq" @click="mobileMenu = false" class="block px-4 py-3 text-lg font-bold text-body">{{ __('landing.nav.faq') }}</a>
            <div class="pt-4 border-t border-border flex flex-col gap-3">
                @auth
                    <x-ui.button tag="a" :href="route('dashboard')" variant="accent" size="lg" class="w-full">
                        {{ __('landing.nav.dashboard') }}
                    </x-ui.button>
                @else
                    <a href="{{ route('login') }}" class="text-center py-3 font-bold text-subtle">{{ __('landing.nav.login') }}</a>
                    <x-ui.button tag="a" :href="route('register')" variant="accent" size="lg" class="w-full">
                        {{ __('landing.nav.start_mobile') }}
                    </x-ui.button>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <!-- ========== HERO ========== -->
        <section class="relative pt-12 pb-24 md:pt-24 md:pb-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <span class="inline-block text-[11px] font-black uppercase tracking-[0.2em] text-accent mb-6">{{ __('landing.hero.badge') }}</span>

                    <h2 class="text-5xl md:text-8xl font-black tracking-tightest text-heading mb-8 max-w-5xl mx-auto leading-[0.9]">
                        {{ __('landing.hero.title_line1') }} <br> {{ __('landing.hero.title_line2') }} <span class="text-accent underline decoration-accent/30 underline-offset-8">{{ __('landing.hero.title_highlight') }}</span>.
                    </h2>

                    <p class="text-lg md:text-xl text-subtle mb-12 max-w-2xl mx-auto leading-relaxed">
                        {{ __('landing.hero.subtitle') }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                        <x-ui.button tag="a" :href="route('register')" variant="primary" size="xl" icon="zap" class="w-full sm:w-auto">
                            {{ __('landing.hero.cta_start') }}
                        </x-ui.button>
                        <x-ui.button tag="a" href="#solutions" variant="ghost" size="xl" iconRight="chevron-right" class="w-full sm:w-auto">
                            {{ __('landing.hero.cta_discover') }}
                        </x-ui.button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-3xl mx-auto">
                        @foreach(__('landing.stats') as $stat)
                        <div class="text-center">
                            <div class="text-3xl md:text-4xl font-black text-heading">{{ $stat[0] }}</div>
                            <div class="text-xs font-medium text-muted mt-1">{{ $stat[1] }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== SOLUTIONS ========== -->
        <section id="solutions" class="py-24 bg-card border-y border-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">{{ __('landing.solutions.tag') }}</h3>
                        <h4 class="text-4xl md:text-5xl font-black tracking-tight text-heading mb-6">{{ __('landing.solutions.title') }}</h4>
                        <p class="text-subtle text-lg mb-10 leading-relaxed">
                            {{ __('landing.solutions.subtitle') }}
                        </p>

                        <div class="space-y-6">
                            @foreach(__('landing.solutions.items') as $feat)
                            <div class="flex gap-4 p-4 rounded-2xl hover:bg-surface transition-colors group">
                                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <x-dynamic-component :component="'lucide-' . $feat[0]" class="w-6 h-6 text-accent" />
                                </div>
                                <div>
                                    <h5 class="font-bold text-heading mb-1">{{ $feat[1] }}</h5>
                                    <p class="text-sm text-subtle">{{ $feat[2] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div id="expertise" class="grid grid-cols-2 gap-4">
                        <div class="space-y-4 pt-12">
                            <div class="bg-card border border-border p-6 rounded-2xl h-64">
                                <x-lucide-trending-up class="w-10 h-10 text-accent mb-4" />
                                <p class="font-black text-xl text-heading">{{ __('landing.solutions.cards.kpi') }}</p>
                                <p class="text-xs text-subtle mt-2 italic">{{ __('landing.solutions.cards.kpi_desc') }}</p>
                            </div>
                            <div class="bg-slate-900 dark:bg-slate-800 p-6 rounded-2xl h-48">
                                <p class="text-white font-black text-4xl">{{ __('landing.solutions.cards.compliance') }}</p>
                                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-2">{{ __('landing.solutions.cards.compliance_label') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-accent p-6 rounded-2xl h-48">
                                <x-lucide-award class="text-white w-10 h-10" />
                                <p class="text-white font-bold mt-4 uppercase text-[10px] tracking-widest">{{ __('landing.solutions.cards.expertise') }}</p>
                            </div>
                            <div class="bg-card border border-border p-6 rounded-2xl h-64">
                                <x-lucide-shield-check class="w-10 h-10 text-accent mb-4" />
                                <p class="font-black text-xl text-heading">{{ __('landing.solutions.cards.security') }}</p>
                                <p class="text-xs text-subtle mt-2">{{ __('landing.solutions.cards.security_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== PROCESS ========== -->
        <section id="process" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">{{ __('landing.process.tag') }}</h3>
                    <h4 class="text-3xl md:text-5xl font-black text-heading">{{ __('landing.process.title_start') }} <span class="italic">{{ __('landing.process.title_italic') }}</span>.</h4>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @foreach(__('landing.process.steps') as $step)
                    <div class="relative p-8 rounded-[2rem] bg-card border border-border hover:border-accent group transition-all">
                        <span class="absolute -top-4 left-8 bg-accent text-white font-black text-xs px-3 py-1 rounded-full">{{ $step[0] }}</span>
                        <div class="w-12 h-12 rounded-xl bg-surface-alt flex items-center justify-center mb-6 text-muted group-hover:text-accent transition-colors">
                            <x-dynamic-component :component="'lucide-' . $step[3]" class="w-6 h-6" />
                        </div>
                        <h5 class="font-bold text-heading mb-2">{{ $step[1] }}</h5>
                        <p class="text-sm text-subtle">{{ $step[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ========== OPEN SOURCE ========== -->
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">{{ __('landing.opensource.tag') }}</h3>
                        <h4 class="text-3xl md:text-4xl font-black text-heading mb-6">{{ __('landing.opensource.title') }}</h4>
                        <p class="text-subtle text-lg mb-8 leading-relaxed">{{ __('landing.opensource.subtitle') }}</p>

                        <div class="space-y-4">
                            <div class="flex gap-4 p-4 rounded-2xl bg-surface-alt border border-border">
                                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center shrink-0">
                                    <x-lucide-server class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h5 class="font-bold text-heading">{{ __('landing.opensource.selfhosted') }}</h5>
                                    <p class="text-sm text-subtle">{{ __('landing.opensource.selfhosted_desc') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-4 p-4 rounded-2xl bg-surface-alt border border-border">
                                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center shrink-0">
                                    <x-lucide-cloud class="w-5 h-5 text-accent" />
                                </div>
                                <div>
                                    <h5 class="font-bold text-heading">{{ __('landing.opensource.saas') }}</h5>
                                    <p class="text-sm text-subtle">{{ __('landing.opensource.saas_desc') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900 dark:bg-slate-800 rounded-2xl p-6 font-mono text-sm overflow-hidden">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="ml-2 text-slate-500 text-xs">terminal</span>
                        </div>
                        <div class="space-y-1 text-slate-300">
                            <p><span class="text-accent">$</span> git clone {{ config('gpro.contact.github_url', 'https://github.com/cave-tech/cica-gpro') }}.git</p>
                            <p><span class="text-accent">$</span> cd cica-gpro</p>
                            <p><span class="text-accent">$</span> make up</p>
                            <p class="text-slate-500 mt-3">{{ __('landing.opensource.terminal_comment1') }}</p>
                            <p class="text-slate-500">{{ __('landing.opensource.terminal_comment2') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========== TRUST ========== -->
        <section class="py-16">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h3 class="text-xs font-black text-muted uppercase tracking-widest mb-10">{{ __('landing.trust.tag') }}</h3>
                <div class="flex flex-wrap items-center justify-center gap-10">
                    @for($i = 0; $i < 5; $i++)
                    <div class="w-28 h-10 rounded-lg bg-surface-alt border border-border flex items-center justify-center">
                        <span class="text-[10px] font-bold text-muted uppercase tracking-wider">{{ __('landing.trust.placeholder') }}</span>
                    </div>
                    @endfor
                </div>
            </div>
        </section>

        <!-- ========== FAQ ========== -->
        <section id="faq" class="py-24">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-xs font-black text-accent uppercase tracking-widest mb-4">{{ __('landing.faq.tag') }}</h3>
                    <h4 class="text-3xl font-black text-heading">{{ __('landing.faq.title') }}</h4>
                </div>

                <div class="space-y-4" x-data="{ active: null }">
                    @foreach(__('landing.faq.items') as $index => $faq)
                    <div class="bg-card rounded-2xl border border-border dark:border-slate-600 overflow-hidden">
                        <button
                            type="button"
                            x-on:click="active = (active === {{ $index }} ? null : {{ $index }})"
                            class="w-full px-6 py-5 text-left flex justify-between items-center group cursor-pointer"
                        >
                            <span class="font-bold text-body group-hover:text-accent transition-colors">{{ $faq[0] }}</span>
                            <x-lucide-chevron-down
                                class="w-5 h-5 text-muted transition-transform duration-300 shrink-0"
                                x-bind:class="active === {{ $index }} ? 'rotate-180' : ''"
                            />
                        </button>
                        <div
                            x-show="active === {{ $index }}"
                            x-collapse
                            x-cloak
                            class="px-6 pb-5 text-sm text-subtle leading-relaxed"
                        >
                            {{ $faq[1] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ========== CTA ========== -->
        <section class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-slate-900 dark:bg-slate-800 rounded-3xl px-8 py-20 md:px-16 md:py-24">
                    <div class="text-center max-w-3xl mx-auto">
                        <h3 class="text-3xl md:text-5xl font-black text-white mb-8 tracking-tight">
                            {{ __('landing.cta.title') }}
                        </h3>
                        <p class="text-muted mb-12 text-lg">
                            {{ str_replace(':app', config('app.name'), __('landing.cta.subtitle')) }}
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            <x-ui.button tag="a" :href="route('register')" variant="accent" size="xl" icon="arrow-right">
                                {{ __('landing.cta.button') }}
                            </x-ui.button>
                            @if(config('gpro.contact.whatsapp'))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('gpro.contact.whatsapp')) }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-4 text-sm font-bold text-white/80 hover:text-white border-2 border-white/20 hover:border-white/40 rounded-xl transition-colors uppercase tracking-wider">
                                <x-lucide-message-circle class="w-4 h-4" />
                                {{ __('landing.cta.contact') }}
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ========== FOOTER ========== -->
    <footer id="contact" class="bg-card border-t border-border py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="mb-6">
                        <x-ui.logo size="sm" />
                    </div>
                    <p class="text-subtle text-sm max-w-xs leading-relaxed">
                        {{ __('landing.footer.description') }}
                    </p>
                    <div class="mt-6 space-y-3">
                        @if(config('gpro.contact.email'))
                        <a href="mailto:{{ config('gpro.contact.email') }}" class="flex items-center gap-2 text-sm text-subtle hover:text-accent transition-colors">
                            <x-lucide-mail class="w-4 h-4" />
                            {{ config('gpro.contact.email') }}
                        </a>
                        @endif
                        @if(config('gpro.contact.whatsapp'))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('gpro.contact.whatsapp')) }}" target="_blank" class="flex items-center gap-2 text-sm text-subtle hover:text-accent transition-colors">
                            <x-lucide-message-circle class="w-4 h-4" />
                            {{ config('gpro.contact.whatsapp') }}
                        </a>
                        @endif
                        @if(config('gpro.contact.github_url'))
                        <a href="{{ config('gpro.contact.github_url') }}" target="_blank" class="flex items-center gap-2 text-sm text-subtle hover:text-accent transition-colors">
                            <x-lucide-github class="w-4 h-4" />
                            GitHub
                        </a>
                        @endif
                    </div>
                </div>
                <div>
                    <h6 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">{{ __('landing.footer.col_platform') }}</h6>
                    <ul class="space-y-4">
                        <li><a href="#solutions" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.solutions') }}</a></li>
                        <li><a href="#expertise" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.expertise') }}</a></li>
                        @if(isSaas())
                        <li><a href="{{ route('pricing') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.pricing') }}</a></li>
                        @endif
                        <li><a href="{{ route('login') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.portal') }}</a></li>
                        <li><a href="{{ route('register') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.register') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h6 class="text-[10px] font-black uppercase tracking-widest text-muted mb-6">{{ __('landing.footer.col_resources') }}</h6>
                    <ul class="space-y-4">
                        <li><a href="#faq" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.faq') }}</a></li>
                        <li><a href="{{ url('/privacy') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.privacy') }}</a></li>
                        <li><a href="{{ url('/terms') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.terms') }}</a></li>
                        @if(config('gpro.contact.github_url'))
                        <li><a href="{{ config('gpro.contact.github_url') }}" target="_blank" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.footer.github') }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-border flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-xs text-muted">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('landing.footer.copyright') }}
                    {{ __('landing.footer.built_by') }}
                    <a href="{{ config('gpro.contact.company_url', '#') }}" target="_blank" class="text-accent hover:underline">{{ config('gpro.contact.company', 'Cave-Tech') }}</a>.
                </p>
                <button
                    type="button"
                    @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                    class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-muted hover:text-accent transition-colors cursor-pointer"
                >
                    {{ __('landing.footer.back_to_top') }}
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
            type="button"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="w-12 h-12 bg-slate-900 dark:bg-accent text-white rounded-full shadow-2xl shadow-primary/40 flex items-center justify-center hover:scale-110 active:scale-95 transition-all cursor-pointer"
        >
            <x-lucide-arrow-up class="w-6 h-6" />
        </button>
    </div>

</body>
</html>
