<x-ui.page-layout>

    <x-ui.page-header :title="__('settings.title')" :subtitle="__('settings.subtitle')" />

    {{-- Onglets --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        @foreach([
            'appearance' => ['label' => __('settings.appearance'), 'icon' => 'palette'],
            'language' => ['label' => __('settings.language'), 'icon' => 'languages'],
            'notifications' => ['label' => __('settings.notifications'), 'icon' => 'bell'],
            'accounts' => ['label' => __('settings.linked_accounts'), 'icon' => 'link'],
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
        <x-ui.section :title="__('settings.theme.dark')" icon="moon" :noPadding="false">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-heading">{{ __('settings.theme.title') }}</p>
                    <p class="text-xs text-subtle mt-0.5">{{ __('settings.theme.desc') }}</p>
                </div>
                <div x-data="{ dark: @entangle('theme') }" class="flex gap-2">
                    <button @click="dark = 'light'; $wire.set('theme', 'light'); document.documentElement.classList.remove('dark'); localStorage.setItem('darkMode', 'false')"
                        :class="dark === 'light' ? 'bg-amber-100 text-amber-600 border-amber-300 shadow-sm' : 'bg-surface text-subtle border-border-light hover:bg-surface-alt'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all">
                        <x-lucide-sun class="w-4 h-4" />
                        {{ __('settings.theme.light') }}
                    </button>
                    <button @click="dark = 'dark'; $wire.set('theme', 'dark'); document.documentElement.classList.add('dark'); localStorage.setItem('darkMode', 'true')"
                        :class="dark === 'dark' ? 'bg-indigo-100 text-indigo-600 border-indigo-300 shadow-sm dark:bg-indigo-900/50 dark:text-indigo-300 dark:border-indigo-700' : 'bg-surface text-subtle border-border-light hover:bg-surface-alt'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all">
                        <x-lucide-moon class="w-4 h-4" />
                        {{ __('settings.theme.dark') }}
                    </button>
                </div>
            </div>
        </x-ui.section>

        {{-- Densite --}}
        <x-ui.section :title="__('settings.density.title')" icon="layout-grid" :noPadding="false">
            <p class="text-xs text-subtle mb-4">{{ __('settings.theme.desc') }}</p>
            <div class="grid grid-cols-3 gap-4">
                @foreach([
                    'compact' => ['label' => __('settings.density.compact'), 'desc' => __('settings.density.compact_desc'), 'icon' => 'align-justify'],
                    'comfortable' => ['label' => __('settings.density.comfortable'), 'desc' => __('settings.density.comfortable_desc'), 'icon' => 'align-center'],
                    'spacious' => ['label' => __('settings.density.spacious'), 'desc' => __('settings.density.spacious_desc'), 'icon' => 'maximize-2'],
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
        <x-ui.section :title="__('settings.language_settings.title')" icon="globe" :noPadding="false">
            <div class="space-y-6">
                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">{{ __('settings.language') }}</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach([
                            'fr' => ['label' => __('settings.language_settings.french'), 'flag' => 'FR'],
                            'en' => ['label' => __('settings.language_settings.english'), 'flag' => 'EN'],
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
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">{{ __('settings.date_format.title') }}</label>
                    <select wire:model.live="dateFormat"
                        class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                        <option value="dd/MM/yyyy">{{ __('settings.date_format.french') }}</option>
                        <option value="MM/dd/yyyy">{{ __('settings.date_format.american') }}</option>
                        <option value="yyyy-MM-dd">{{ __('settings.date_format.iso') }}</option>
                    </select>
                </div>
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- TAB NOTIFICATIONS --}}
    @if($activeTab === 'notifications')
    <div class="space-y-6">
        <x-ui.section :title="__('settings.notifications')" icon="bell-ring" :noPadding="false">
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-surface rounded-xl border border-border-light">
                    <div>
                        <p class="text-sm font-bold text-heading">{{ __('settings.email_notifications.title') }}</p>
                        <p class="text-xs text-subtle mt-0.5">{{ __('settings.email_notifications.desc') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="emailNotifications" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-300 dark:bg-surface-alt rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:border-gray-200 dark:after:border-gray-600 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                    </label>
                </div>

                {{-- Fuseau horaire --}}
                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">{{ __('settings.timezone.title') }}</label>
                    <p class="text-xs text-subtle mb-3">{{ __('settings.timezone.desc') }}</p>
                    <select wire:model.live="timezone"
                        class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                        @foreach(config('gpro.timezones', []) as $tz => $label)
                            <option value="{{ $tz }}">{{ $label }}</option>
                        @endforeach
                    </select>
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

        {{-- Preferences par type --}}
        <x-ui.section title="Preferences par type" icon="sliders" :noPadding="false">
            <p class="text-xs text-subtle mb-4">Choisissez les canaux pour chaque type de notification</p>
            <div class="space-y-3">
                <div class="grid grid-cols-[1fr_70px_70px_70px] gap-2 px-2 pb-2 border-b border-border-light">
                    <span class="text-[10px] font-black text-muted uppercase tracking-widest">Type</span>
                    <span class="text-[10px] font-black text-muted uppercase tracking-widest text-center">In-app</span>
                    <span class="text-[10px] font-black text-muted uppercase tracking-widest text-center">Email</span>
                    <span class="text-[10px] font-black text-muted uppercase tracking-widest text-center">Push</span>
                </div>
                @foreach($notificationTypes as $type)
                    @php $prefs = $notificationPreferences[$type->value] ?? $type->defaultChannels(); @endphp
                    <div class="grid grid-cols-[1fr_70px_70px_70px] gap-2 items-center px-2 py-2 rounded-lg hover:bg-surface/50 transition-colors">
                        <div class="flex items-center gap-2">
                            <x-dynamic-component :component="'lucide-' . $type->icon()" class="w-4 h-4 text-{{ $type->color() }}" />
                            <span class="text-xs font-bold text-body">{{ $type->label() }}</span>
                        </div>
                        <div class="flex justify-center">
                            <button wire:click="toggleNotificationChannel('{{ $type->value }}', 'database')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center transition-all
                                    {{ in_array('database', $prefs) ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted hover:text-body' }}">
                                <x-lucide-check class="w-4 h-4" />
                            </button>
                        </div>
                        <div class="flex justify-center">
                            <button wire:click="toggleNotificationChannel('{{ $type->value }}', 'mail')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center transition-all
                                    {{ in_array('mail', $prefs) ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted hover:text-body' }}">
                                <x-lucide-check class="w-4 h-4" />
                            </button>
                        </div>
                        <div class="flex justify-center">
                            <button wire:click="toggleNotificationChannel('{{ $type->value }}', 'fcm')"
                                class="w-8 h-8 rounded-lg flex items-center justify-center transition-all
                                    {{ in_array('fcm', $prefs) ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted hover:text-body' }}">
                                <x-lucide-check class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- TAB COMPTES LIES --}}
    @if($activeTab === 'accounts')
    <div class="space-y-6">
        <x-ui.section :title="__('settings.linked_accounts')" icon="link" :noPadding="false">
            <p class="text-xs text-subtle mb-6">Connectez vos comptes sociaux pour simplifier la connexion</p>

            @php
                $providers = [
                    'google' => ['label' => 'Google', 'color' => 'text-red-500', 'icon' => '<svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>'],
                    'facebook' => ['label' => 'Facebook', 'color' => 'text-blue-600', 'icon' => '<svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'],
                    'microsoft' => ['label' => 'Microsoft', 'color' => 'text-blue-500', 'icon' => '<svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#F25022" d="M1 1h10v10H1z"/><path fill="#7FBA00" d="M13 1h10v10H13z"/><path fill="#00A4EF" d="M1 13h10v10H1z"/><path fill="#FFB900" d="M13 13h10v10H13z"/></svg>'],
                ];
                $linked = $socialAccounts->pluck('provider')->toArray();
            @endphp

            <div class="space-y-3">
                @foreach($providers as $key => $provider)
                    @if(config("services.{$key}.client_id"))
                    <div class="flex items-center justify-between p-4 bg-surface rounded-xl border border-border-light">
                        <div class="flex items-center gap-3">
                            {!! $provider['icon'] !!}
                            <div>
                                <p class="text-sm font-bold text-heading">{{ $provider['label'] }}</p>
                                @if(in_array($key, $linked))
                                    <p class="text-[10px] text-success font-bold uppercase tracking-wider">Connecte</p>
                                @else
                                    <p class="text-[10px] text-muted font-bold uppercase tracking-wider">Non connecte</p>
                                @endif
                            </div>
                        </div>
                        @if(in_array($key, $linked))
                            <button wire:click="unlinkSocial('{{ $key }}')" wire:confirm="Delier ce compte {{ $provider['label'] }} ?"
                                class="px-4 py-2 text-xs font-bold text-error bg-error/5 rounded-xl hover:bg-error/10 transition-all">
                                Delier
                            </button>
                        @else
                            <a href="{{ route('social.redirect', $key) }}"
                                class="px-4 py-2 text-xs font-bold text-accent bg-accent/5 rounded-xl hover:bg-accent/10 transition-all">
                                Connecter
                            </a>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>
        </x-ui.section>
    </div>
    @endif

</x-ui.page-layout>
