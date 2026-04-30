<x-ui.page-layout>

    <x-ui.page-header title="Parametres" subtitle="Personnalisez votre experience selon vos preferences" />

    {{-- Onglets --}}
    <div class="flex gap-2 mb-8">
        @foreach([
            'appearance' => ['label' => 'Apparence', 'icon' => 'palette'],
            'language' => ['label' => 'Langue', 'icon' => 'languages'],
            'notifications' => ['label' => 'Notifications', 'icon' => 'bell'],
        ] as $tab => $info)
            <button wire:click="$set('activeTab', '{{ $tab }}')"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all
                    {{ $activeTab === $tab
                        ? 'bg-accent text-white shadow-lg shadow-accent/25'
                        : 'bg-surface text-subtle hover:bg-surface-alt hover:text-heading border border-border-light' }}">
                <x-dynamic-component :component="'lucide-' . $info['icon']" class="w-4 h-4" />
                {{ $info['label'] }}
            </button>
        @endforeach
    </div>

    {{-- TAB APPARENCE --}}
    @if($activeTab === 'appearance')
    <div class="space-y-6">
        {{-- Mode sombre --}}
        <x-ui.section title="Mode sombre" icon="moon" :noPadding="false">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-heading">Theme de l'interface</p>
                    <p class="text-xs text-subtle mt-0.5">Basculez entre le mode clair et sombre</p>
                </div>
                <div x-data="{ dark: @entangle('theme') }" class="flex gap-2">
                    <button @click="dark = 'light'; $wire.set('theme', 'light'); document.documentElement.classList.remove('dark'); localStorage.setItem('darkMode', 'false')"
                        :class="dark === 'light' ? 'bg-amber-100 text-amber-600 border-amber-300 shadow-sm' : 'bg-surface text-subtle border-border-light hover:bg-surface-alt'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all">
                        <x-lucide-sun class="w-4 h-4" />
                        Clair
                    </button>
                    <button @click="dark = 'dark'; $wire.set('theme', 'dark'); document.documentElement.classList.add('dark'); localStorage.setItem('darkMode', 'true')"
                        :class="dark === 'dark' ? 'bg-indigo-100 text-indigo-600 border-indigo-300 shadow-sm dark:bg-indigo-900/50 dark:text-indigo-300 dark:border-indigo-700' : 'bg-surface text-subtle border-border-light hover:bg-surface-alt'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all">
                        <x-lucide-moon class="w-4 h-4" />
                        Sombre
                    </button>
                </div>
            </div>
        </x-ui.section>

        {{-- Densite --}}
        <x-ui.section title="Densite d'affichage" icon="layout-grid" :noPadding="false">
            <p class="text-xs text-subtle mb-4">Ajustez l'espacement des elements de l'interface</p>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    'compact' => ['label' => 'Compact', 'desc' => 'Plus d\'informations visibles', 'icon' => 'align-justify'],
                    'comfortable' => ['label' => 'Confortable', 'desc' => 'Equilibre ideal', 'icon' => 'align-center'],
                    'spacious' => ['label' => 'Espace', 'desc' => 'Plus de respiration', 'icon' => 'maximize-2'],
                ] as $key => $opt)
                    <button wire:click="$set('density', '{{ $key }}')"
                        class="p-5 rounded-2xl border-2 text-center transition-all group
                            {{ $density === $key
                                ? 'border-accent bg-accent/5 shadow-sm'
                                : 'border-border-light bg-card hover:border-accent/30 hover:bg-surface' }}">
                        <div class="w-10 h-10 mx-auto mb-3 rounded-xl flex items-center justify-center transition-colors
                            {{ $density === $key ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted group-hover:text-accent' }}">
                            <x-dynamic-component :component="'lucide-' . $opt['icon']" class="w-5 h-5" />
                        </div>
                        <p class="text-xs font-bold {{ $density === $key ? 'text-accent' : 'text-heading' }}">{{ $opt['label'] }}</p>
                        <p class="text-[10px] text-subtle mt-1">{{ $opt['desc'] }}</p>
                    </button>
                @endforeach
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- TAB LANGUE --}}
    @if($activeTab === 'language')
    <div class="space-y-6">
        <x-ui.section title="Langue et region" icon="globe" :noPadding="false">
            <div class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Langue</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            'fr' => ['label' => 'Francais', 'flag' => 'FR'],
                            'en' => ['label' => 'English', 'flag' => 'EN'],
                        ] as $code => $lang)
                            <button wire:click="$set('locale', '{{ $code }}')"
                                class="flex items-center gap-3 p-4 rounded-xl border-2 transition-all
                                    {{ $locale === $code
                                        ? 'border-accent bg-accent/5 shadow-sm'
                                        : 'border-border-light bg-card hover:border-accent/30' }}">
                                <span class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-black
                                    {{ $locale === $code ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted' }}">{{ $lang['flag'] }}</span>
                                <div>
                                    <p class="text-sm font-bold {{ $locale === $code ? 'text-accent' : 'text-heading' }}">{{ $lang['label'] }}</p>
                                </div>
                                @if($locale === $code)
                                    <x-lucide-check-circle class="w-5 h-5 text-accent ml-auto" />
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Format de date</label>
                    <select wire:model.live="dateFormat"
                        class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                        <option value="dd/MM/yyyy">JJ/MM/AAAA (francais)</option>
                        <option value="MM/dd/yyyy">MM/DD/YYYY (americain)</option>
                        <option value="yyyy-MM-dd">AAAA-MM-JJ (ISO)</option>
                    </select>
                </div>
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- TAB NOTIFICATIONS --}}
    @if($activeTab === 'notifications')
    <div class="space-y-6">
        <x-ui.section title="Notifications" icon="bell-ring" :noPadding="false">
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-surface rounded-xl border border-border-light">
                    <div>
                        <p class="text-sm font-bold text-heading">Notifications par email</p>
                        <p class="text-xs text-subtle mt-0.5">Recevoir des notifications importantes par email</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="emailNotifications" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 dark:bg-surface-alt rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:border-gray-200 dark:after:border-gray-600 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                    </label>
                </div>

                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Resume periodique</label>
                    <p class="text-xs text-subtle mb-3">Recevez un resume de l'activite de vos projets</p>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach([
                            'never' => ['label' => 'Jamais', 'icon' => 'bell-off', 'desc' => 'Pas de resume'],
                            'weekly' => ['label' => 'Hebdomadaire', 'icon' => 'calendar-days', 'desc' => 'Chaque lundi'],
                            'monthly' => ['label' => 'Mensuel', 'icon' => 'calendar', 'desc' => 'Le 1er du mois'],
                        ] as $freq => $opt)
                            <button wire:click="$set('digestFrequency', '{{ $freq }}')"
                                class="p-4 rounded-xl border-2 text-center transition-all
                                    {{ $digestFrequency === $freq
                                        ? 'border-accent bg-accent/5 shadow-sm'
                                        : 'border-border-light bg-card hover:border-accent/30' }}">
                                <div class="w-8 h-8 mx-auto mb-2 rounded-lg flex items-center justify-center
                                    {{ $digestFrequency === $freq ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted' }}">
                                    <x-dynamic-component :component="'lucide-' . $opt['icon']" class="w-4 h-4" />
                                </div>
                                <p class="text-xs font-bold {{ $digestFrequency === $freq ? 'text-accent' : 'text-heading' }}">{{ $opt['label'] }}</p>
                                <p class="text-[9px] text-subtle mt-0.5">{{ $opt['desc'] }}</p>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-ui.section>
    </div>
    @endif

</x-ui.page-layout>
