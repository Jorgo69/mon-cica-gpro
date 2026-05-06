<x-ui.page-layout>

    <x-ui.page-header :title="__('settings.title')" :subtitle="__('settings.subtitle')" />

    {{-- Onglets --}}
    <div class="flex gap-2 mb-8 flex-wrap">
        @php
            $tabs = [];
            if (in_array(auth()->user()->role, [\App\Enums\AccountType::ORG_ADMIN, \App\Enums\AccountType::INDEPENDENT])) {
                $tabs['organization'] = ['label' => __('settings.organization'), 'icon' => 'building-2'];
            }
            $tabs += [
                'appearance' => ['label' => __('settings.appearance'), 'icon' => 'palette'],
                'language' => ['label' => __('settings.language'), 'icon' => 'languages'],
                'notifications' => ['label' => __('settings.notifications'), 'icon' => 'bell'],
                'accounts' => ['label' => __('settings.linked_accounts'), 'icon' => 'link'],
                ...( isSaas() ? ['plan' => ['label' => __('plans.plan'), 'icon' => 'crown']] : [] ),
            ];
            if ($showAiTab) {
                $tabs['ai'] = ['label' => __('ai.config.title'), 'icon' => 'sparkles'];
            }
            if (auth()->user()->organization_id) {
                $tabs['webhooks'] = ['label' => 'Webhooks', 'icon' => 'webhook'];
            }
            $tabs['api'] = ['label' => 'API', 'icon' => 'plug'];
        @endphp
        @foreach($tabs as $tab => $info)
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

    {{-- TAB ORGANISATION (ORG_ADMIN + INDEPENDENT) --}}
    @if($activeTab === 'organization' && auth()->user()->role === \App\Enums\AccountType::INDEPENDENT)
    <div class="space-y-6">
        <x-ui.section :title="__('settings.org_create.title')" icon="building-2" :noPadding="false">
            <p class="text-sm text-body mb-4">{{ __('settings.org_create.desc') }}</p>

            <form wire:submit="createOrganization" class="space-y-4">
                <x-ui.input wire:model="newOrgName" :label="__('settings.org_create.name_label')" :placeholder="__('settings.org_create.name_placeholder')" icon="building-2" :error="$errors->first('newOrgName')" />

                <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800">
                    <p class="text-xs text-amber-700 dark:text-amber-300">
                        <x-lucide-info class="w-3.5 h-3.5 inline" />
                        {{ __('settings.org_create.info') }}
                    </p>
                </div>

                <x-ui.button type="submit" variant="secondary" icon="building-2">
                    {{ __('settings.org_create.submit') }}
                </x-ui.button>
            </form>
        </x-ui.section>
    </div>
    @elseif($activeTab === 'organization' && auth()->user()->role === \App\Enums\AccountType::ORG_ADMIN)
    <div class="space-y-6">
        <x-ui.section :title="__('settings.org_profile')" icon="building-2" :noPadding="false">
            <form wire:submit="saveOrgProfile" class="space-y-5">

                {{-- Logo --}}
                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-3">{{ __('settings.org_logo') }}</label>
                    <div class="flex items-center gap-4">
                        {{-- Preview --}}
                        <div class="w-20 h-20 rounded-2xl border-2 border-dashed border-border flex items-center justify-center bg-surface overflow-hidden">
                            @if($orgLogo)
                                <img src="{{ $orgLogo->temporaryUrl() }}" class="w-full h-full object-contain" alt="Preview" />
                            @elseif($orgLogoUrl)
                                <img src="{{ $orgLogoUrl }}" class="w-full h-full object-contain" alt="Logo" />
                            @else
                                <x-lucide-building-2 class="w-8 h-8 text-muted" />
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-accent bg-accent/5 rounded-xl hover:bg-accent/10 transition-colors">
                                <x-lucide-upload class="w-4 h-4" />
                                {{ __('settings.upload_logo') }}
                                <input type="file" wire:model="orgLogo" accept="image/*" class="hidden" />
                            </label>
                            @if($orgLogoUrl)
                                <x-ui.button type="button" wire:click="removeOrgLogo" variant="ghost" size="sm" icon="x">
                                    {{ __('settings.remove_logo') }}
                                </x-ui.button>
                            @endif
                            <p class="text-[10px] text-muted">PNG, JPG, SVG. Max 2 Mo.</p>
                        </div>
                    </div>
                    @error('orgLogo') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Name --}}
                <x-ui.input wire:model="orgName" :label="__('settings.org_name')" required icon="building-2" />

                {{-- Description --}}
                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2 ml-1">{{ __('settings.org_description') }}</label>
                    <textarea wire:model="orgDescription" rows="3" maxlength="1000"
                        class="block w-full border-border bg-card text-body rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 px-4"
                        placeholder="{{ __('settings.org_description_placeholder') }}"></textarea>
                </div>

                {{-- Contact info --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-ui.input wire:model="orgWebsite" :label="__('settings.org_website')" type="url" icon="globe" placeholder="https://..." />
                    <x-ui.input wire:model="orgContactEmail" :label="__('settings.org_contact_email')" type="email" icon="mail" placeholder="contact@..." />
                </div>

                <x-ui.input wire:model="orgContactPhone" :label="__('settings.org_contact_phone')" icon="phone" placeholder="+229 ..." />

                <p class="text-[10px] text-muted">{{ __('settings.org_branding_hint') }}</p>

                <div class="flex justify-end">
                    <x-ui.button type="submit" variant="secondary" icon="save">
                        {{ __('common.save') }}
                    </x-ui.button>
                </div>
            </form>
        </x-ui.section>

        {{-- Ownership transfer (owner only) --}}
        @if($isOwner)
        <x-ui.section :title="__('settings.transfer.title')" icon="crown" :noPadding="false">
            <div class="p-4 bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-200 dark:border-amber-800">
                <div class="flex items-start gap-3">
                    <x-lucide-crown class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                    <div class="flex-1">
                        <p class="text-sm font-bold text-heading">{{ __('settings.transfer.you_are_owner') }}</p>
                        <p class="text-xs text-muted mt-1">{{ __('settings.transfer.desc') }}</p>
                    </div>
                </div>
            </div>

            @if($otherAdmins->count() > 0)
                <div class="mt-4">
                    <x-ui.button wire:click="openTransferModal" variant="outline" icon="arrow-right-left" size="sm">
                        {{ __('settings.transfer.transfer_btn') }}
                    </x-ui.button>
                </div>
            @else
                <p class="text-xs text-muted mt-4">{{ __('settings.transfer.no_other_admin') }}</p>
            @endif
        </x-ui.section>
        @endif

        {{-- Transfer modal --}}
        @if($showTransferModal)
        <x-ui.modal :title="__('settings.transfer.modal_title')" closeAction="closeTransferModal" maxWidth="max-w-md">
            <div class="space-y-4">
                <div class="p-3 bg-amber-50 dark:bg-amber-900/10 rounded-xl">
                    <p class="text-xs text-amber-700 dark:text-amber-300">{{ __('settings.transfer.warning') }}</p>
                </div>

                <x-ui.select wire:model="transferTargetId" :label="__('settings.transfer.select_admin')" icon="user-check">
                    <option value="">-- {{ __('settings.transfer.choose') }} --</option>
                    @foreach($otherAdmins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                    @endforeach
                </x-ui.select>
            </div>

            <x-slot:footer>
                <x-ui.button wire:click="closeTransferModal" variant="outline" size="sm">{{ __('common.cancel') }}</x-ui.button>
                <x-ui.button wire:click="transferOwnership" variant="secondary" icon="arrow-right-left" size="sm">{{ __('settings.transfer.confirm') }}</x-ui.button>
            </x-slot:footer>
        </x-ui.modal>
        @endif
    </div>
    @endif

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
                    <button @click="dark = 'light'; $wire.set('theme', 'light'); document.documentElement.classList.remove('dark'); localStorage.setItem('darkMode', 'false'); window.dispatchEvent(new CustomEvent('theme-changed', {detail: {theme: 'light'}}))"
                        :class="dark === 'light' ? 'bg-amber-100 text-amber-600 border-amber-300 shadow-sm' : 'bg-surface text-subtle border-border-light hover:bg-surface-alt'"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold transition-all">
                        <x-lucide-sun class="w-4 h-4" />
                        {{ __('settings.theme.light') }}
                    </button>
                    <button @click="dark = 'dark'; $wire.set('theme', 'dark'); document.documentElement.classList.add('dark'); localStorage.setItem('darkMode', 'true'); window.dispatchEvent(new CustomEvent('theme-changed', {detail: {theme: 'dark'}}))"
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

                <x-ui.select wire:model.live="dateFormat" :label="__('settings.date_format.title')" icon="calendar-days">
                    <option value="dd/MM/yyyy">{{ __('settings.date_format.french') }}</option>
                    <option value="MM/dd/yyyy">{{ __('settings.date_format.american') }}</option>
                    <option value="yyyy-MM-dd">{{ __('settings.date_format.iso') }}</option>
                </x-ui.select>
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- TAB NOTIFICATIONS --}}
    @if($activeTab === 'notifications')
    <div class="space-y-6">
        <x-ui.section :title="__('settings.notifications')" icon="bell-ring" :noPadding="false">
            <div class="space-y-6">
                <x-ui.toggle wire:model.live="emailNotifications" :label="__('settings.email_notifications.title')" :description="__('settings.email_notifications.desc')" />

                {{-- Fuseau horaire --}}
                <div>
                    <p class="text-xs text-subtle mb-3">{{ __('settings.timezone.desc') }}</p>
                    <x-ui.select wire:model.live="timezone" :label="__('settings.timezone.title')" icon="clock">
                        @foreach(config('gpro.timezones', []) as $tz => $label)
                            <option value="{{ $tz }}">{{ $label }}</option>
                        @endforeach
                    </x-ui.select>
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
                            <x-ui.button wire:click="unlinkSocial('{{ $key }}')" wire:confirm="Delier ce compte {{ $provider['label'] }} ?"
                                variant="danger" size="sm" icon="unlink">
                                Delier
                            </x-ui.button>
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

    {{-- TAB PLAN (SaaS only) --}}
    @if($activeTab === 'plan' && isSaas())
    <div class="space-y-6">
        @php
            $user = auth()->user();
            $currentPlan = $user->effectivePlan();
            $isIndependent = $user->role === \App\Enums\AccountType::INDEPENDENT;
            $isOrgAdmin = $user->role === \App\Enums\AccountType::ORG_ADMIN;
            $org = $user->organization;
            $expiresAt = $isIndependent ? $user->plan_expires_at : $org?->plan_expires_at;
            $payment = config('gpro.payment');
        @endphp

        {{-- Current Plan --}}
        <x-ui.section :title="__('plans.plan')" icon="crown" :noPadding="false">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center">
                    <x-lucide-crown class="w-7 h-7 {{ $currentPlan->color() }}" />
                </div>
                <div>
                    <p class="text-xl font-black {{ $currentPlan->color() }}">{{ $currentPlan->label() }}</p>
                    <p class="text-xs text-muted">
                        {{ $currentPlan->formattedPrice() }}/{{ $currentPlan->billing_period === 'month' ? __('plans.month') : __('plans.year') }}
                        @if($expiresAt)
                            &middot; {{ __('plans.expires') }}: {{ $expiresAt->format('d/m/Y') }}
                            @if($expiresAt->isPast())
                                <span class="text-error font-bold">({{ __('plans.expired') }})</span>
                            @endif
                        @endif
                    </p>
                </div>
            </div>

            {{-- Limits usage --}}
            @if($org || $isIndependent)
                @php
                    $maxProjects = $currentPlan->maxProjects();
                    $maxMembers = $currentPlan->maxMembers();
                    $usedProjects = $isIndependent
                        ? \App\Models\Project::where('creator_user_id', $user->id)->count()
                        : ($org ? $org->projects()->count() : 0);
                    $usedMembers = $org ? $org->users()->count() : 1;
                @endphp
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-surface rounded-xl">
                        <span class="text-[9px] font-black text-muted uppercase block">{{ __('plans.projects') }}</span>
                        <span class="text-lg font-black text-heading">{{ $usedProjects }}</span>
                        <span class="text-xs text-muted">/ {{ $maxProjects === -1 ? '∞' : $maxProjects }}</span>
                    </div>
                    <div class="p-3 bg-surface rounded-xl">
                        <span class="text-[9px] font-black text-muted uppercase block">{{ __('plans.members') }}</span>
                        <span class="text-lg font-black text-heading">{{ $usedMembers }}</span>
                        <span class="text-xs text-muted">/ {{ $maxMembers === -1 ? '∞' : $maxMembers }}</span>
                    </div>
                </div>
            @endif

            {{-- Upgrade CTA --}}
            @if(!$currentPlan->isUnlimited())
                <div class="p-4 bg-accent/5 rounded-xl border border-accent/20">
                    <p class="text-sm font-bold text-heading mb-2">{{ __('plans.upgrade_title') }}</p>

                    @if($payment['gateway_url'])
                        <a href="{{ $payment['gateway_url'] }}" target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white text-xs font-bold rounded-lg hover:bg-accent/90 transition-colors">
                            <x-lucide-credit-card class="w-4 h-4" />
                            {{ __('plans.pay_online') }}
                        </a>
                    @endif

                    <div class="mt-3 space-y-1 text-xs text-muted">
                        <p class="font-bold text-body">{{ __('plans.manual_payment') }}</p>
                        @if($payment['contact_whatsapp'])
                            <p>
                                <x-lucide-message-circle class="w-3.5 h-3.5 inline text-success" />
                                WhatsApp: <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $payment['contact_whatsapp']) }}" target="_blank" class="text-accent hover:underline">{{ $payment['contact_whatsapp'] }}</a>
                            </p>
                        @endif
                        @if($payment['contact_email'])
                            <p>
                                <x-lucide-mail class="w-3.5 h-3.5 inline text-accent" />
                                Email: <a href="mailto:{{ $payment['contact_email'] }}" class="text-accent hover:underline">{{ $payment['contact_email'] }}</a>
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </x-ui.section>

        {{-- Plan comparison --}}
        <x-ui.section :title="__('plans.compare')" icon="layout-grid" :noPadding="false">
            <div class="grid grid-cols-3 gap-3 text-center text-xs">
                @foreach(\App\Models\Plan::active()->ordered()->get() as $p)
                    <div class="p-3 rounded-xl {{ $currentPlan->id === $p->id ? 'bg-accent/10 border border-accent/30' : 'bg-surface' }}">
                        <p class="font-black {{ $p->color() }} mb-1">{{ $p->label() }}</p>
                        <p class="text-[10px] text-muted">{{ $p->formattedPrice() }}</p>
                        <p class="text-[10px] text-body mt-1">{{ $p->maxProjects() === -1 ? '∞' : $p->maxProjects() }} proj. / {{ $p->maxMembers() === -1 ? '∞' : $p->maxMembers() }} memb.</p>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('pricing') }}" target="_blank" class="text-xs text-accent hover:underline">{{ __('plans.see_details') }}</a>
            </div>
        </x-ui.section>
    </div>
    @endif

    {{-- TAB IA --}}
    @if($activeTab === 'ai' && $showAiTab)
    <div class="space-y-6">

        {{-- Quick Guide (collapsible) --}}
        <x-ui.section title="" icon="" :noPadding="true">
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full p-4 flex items-center justify-between hover:bg-surface/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <x-lucide-book-open class="w-4 h-4 text-purple-500" />
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-bold text-heading">{{ __('ai.guide.title') }}</p>
                            <p class="text-[10px] text-muted">{{ __('ai.guide.what_is_ai') }} — {{ __('ai.guide.how_to_get_key') }}</p>
                        </div>
                    </div>
                    <x-lucide-chevron-down class="w-4 h-4 text-muted transition-transform" x-bind:class="open && 'rotate-180'" />
                </button>

                <div x-show="open" x-collapse class="px-4 pb-4 space-y-4">
                    {{-- What is AI --}}
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/10 rounded-lg">
                        <p class="text-xs text-body leading-relaxed">{!! __('ai.guide.what_is_ai_desc') !!}</p>
                    </div>

                    {{-- Where --}}
                    <div>
                        <p class="text-xs font-bold text-heading mb-1.5">{{ __('ai.guide.where_is_ai') }}</p>
                        <ul class="space-y-1">
                            @foreach(__('ai.guide.where_is_ai_items') as $item)
                                <li class="flex items-start gap-2 text-[10px] text-body">
                                    <x-lucide-check class="w-3 h-3 text-green-500 mt-0.5 shrink-0" />
                                    <span>{!! $item !!}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- How to get key --}}
                    <div>
                        <p class="text-xs font-bold text-heading mb-1.5">{{ __('ai.guide.how_to_get_key') }}</p>
                        <ol class="space-y-1">
                            @foreach(__('ai.guide.how_to_get_key_steps') as $i => $step)
                                <li class="flex items-start gap-2 text-[10px] text-body">
                                    <span class="w-4 h-4 rounded-full bg-accent/10 text-accent text-[9px] font-bold flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                                    <span>{{ $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    {{-- Recommendation --}}
                    <div class="p-3 bg-green-50 dark:bg-green-900/10 rounded-lg">
                        <p class="text-[10px] text-body leading-relaxed">{!! __('ai.guide.recommended_desc') !!}</p>
                    </div>

                    {{-- Provider cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach($aiProviders as $p)
                            <div class="p-2 bg-card rounded-lg border border-border-light text-center">
                                <p class="text-[10px] font-bold text-heading">{{ $p->label() }}</p>
                                <p class="text-[9px] text-muted">{{ $p->pricing() }}</p>
                                @if($p->signupUrl())
                                    <a href="{{ $p->signupUrl() }}" target="_blank" rel="noopener" class="text-[9px] text-accent hover:underline">{{ __('ai.guide.get_key_at') }} &rarr;</a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Security --}}
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/10 rounded-lg">
                        <p class="text-[10px] text-body leading-relaxed"><x-lucide-shield-check class="w-3 h-3 inline text-blue-500" /> {!! __('ai.guide.security_desc') !!}</p>
                    </div>
                </div>
            </div>
        </x-ui.section>

        {{-- Mode selection --}}
        <x-ui.section :title="__('ai.config.org_config')" icon="sparkles" :noPadding="false">
            <p class="text-xs text-subtle mb-4">{{ __('ai.config.org_subtitle') }}</p>

            <div class="grid grid-cols-3 gap-3 mb-6">
                @foreach([
                    'global' => ['label' => __('ai.config.use_global'), 'icon' => 'globe', 'desc' => __('ai.config.use_global_desc')],
                    'own' => ['label' => __('ai.config.use_own'), 'icon' => 'key', 'desc' => __('ai.config.use_own_desc')],
                    'disabled' => ['label' => __('ai.config.disable_ai'), 'icon' => 'power-off', 'desc' => __('ai.config.disable_ai_desc')],
                ] as $mode => $opt)
                    <button wire:click="$set('aiMode', '{{ $mode }}')"
                        class="p-4 rounded-xl border-2 text-center transition-all
                            {{ $aiMode === $mode
                                ? 'border-accent bg-accent/5 shadow-sm'
                                : 'border-border-light bg-card hover:border-accent/30' }}">
                        <div class="w-8 h-8 mx-auto mb-2 rounded-lg flex items-center justify-center
                            {{ $aiMode === $mode ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted' }}">
                            <x-dynamic-component :component="'lucide-' . $opt['icon']" class="w-4 h-4" />
                        </div>
                        <p class="text-xs font-bold {{ $aiMode === $mode ? 'text-accent' : 'text-heading' }}">{{ $opt['label'] }}</p>
                        <p class="text-[9px] text-subtle mt-0.5">{{ $opt['desc'] }}</p>
                    </button>
                @endforeach
            </div>

            {{-- Own config form --}}
            @if($aiMode === 'own')
            <div class="space-y-4 p-4 bg-surface rounded-xl border border-border-light">
                <div>
                    <x-ui.select wire:model.live="aiProvider" :label="__('ai.config.provider')" icon="brain">
                        @foreach($aiProviders as $p)
                            <option value="{{ $p->value }}">{{ $p->label() }} — {{ $p->pricing() }}</option>
                        @endforeach
                    </x-ui.select>
                    @php $selProvider = \App\Enums\AiProvider::tryFrom($aiProvider); @endphp
                    @if($selProvider)
                        <p class="text-[10px] text-muted mt-1">{{ $selProvider->description() }}</p>
                        @if($selProvider->signupUrl())
                            <a href="{{ $selProvider->signupUrl() }}" target="_blank" rel="noopener" class="text-[10px] text-accent hover:underline">
                                {{ __('ai.guide.get_key_at') }} {{ $selProvider->label() }} &rarr;
                            </a>
                        @endif
                    @endif
                </div>

                <div>
                    @if($aiMaskedKey)
                        <p class="text-[10px] text-muted mb-1">{{ __('ai.config.current_key') }}: <code class="bg-surface-alt px-1 rounded">{{ $aiMaskedKey }}</code></p>
                    @endif
                    <x-ui.input type="password" wire:model="aiApiKey" :label="__('ai.config.api_key')" icon="key-round"
                        :placeholder="$aiMaskedKey ? __('ai.config.leave_empty') : __('ai.config.enter_key')" />
                    <p class="text-[10px] text-muted mt-1">{{ __('ai.config.key_encrypted') }}</p>
                </div>

                <x-ui.input wire:model="aiModel" :label="__('ai.config.model')" icon="cpu"
                    :placeholder="\App\Enums\AiProvider::tryFrom($aiProvider)?->defaultModel()" />

                @if($aiProvider === 'custom')
                <x-ui.input type="url" wire:model="aiBaseUrl" :label="__('ai.config.base_url')" icon="globe"
                    placeholder="https://your-ai-server.com/v1" />
                @endif
            </div>
            @endif

            <div class="mt-4">
                <x-ui.button wire:click="saveAiConfig" variant="secondary" icon="save" wire:loading.attr="disabled">
                    {{ __('common.save') }}
                </x-ui.button>
            </div>
        </x-ui.section>

        {{-- Member AI toggle (ORG_ADMIN only) --}}
        @if(auth()->user()->role === \App\Enums\AccountType::ORG_ADMIN && $orgMembers->count() > 0)
        <x-ui.section :title="__('ai.config.member_toggle')" icon="users" :noPadding="false">
            <p class="text-xs text-subtle mb-4">{{ __('ai.config.member_toggle_desc') }}</p>
            <div class="space-y-2">
                @foreach($orgMembers as $member)
                    @php $disabled = (bool) ($member->getMeta('ai.disabled_by_admin') ?? false); @endphp
                    <div class="flex items-center justify-between p-3 bg-surface rounded-xl">
                        <div>
                            <p class="text-sm font-bold text-heading">{{ $member->name }}</p>
                            <p class="text-[10px] text-muted">{{ $member->email }}</p>
                        </div>
                        <button wire:click="toggleMemberAi('{{ $member->id }}')"
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ !$disabled ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ !$disabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                @endforeach
            </div>
        </x-ui.section>
        @endif
    </div>
    @endif

    {{-- TAB WEBHOOKS --}}
    @if($activeTab === 'webhooks' && auth()->user()->organization_id)
    <div class="space-y-6">
        <x-ui.section :title="__('settings.webhooks.title')" icon="webhook" :noPadding="false">
            <p class="text-xs text-muted mb-4">{{ __('settings.webhooks.desc') }}</p>

            {{-- Form --}}
            <div class="space-y-3 mb-6 p-4 bg-surface rounded-xl">
                <x-ui.input type="url" wire:model="webhookUrl" :label="__('settings.webhooks.url')" icon="link" placeholder="https://example.com/webhook" :error="$errors->first('webhookUrl')" />
                <x-ui.input wire:model="webhookLabel" :label="__('settings.webhooks.label')" icon="tag" :placeholder="__('settings.webhooks.label_placeholder')" />
                <div>
                    <label class="text-xs font-bold text-heading block mb-2">{{ __('settings.webhooks.events') }}</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach(\App\Models\Webhook::AVAILABLE_EVENTS as $event)
                            @php $isChecked = in_array($event, $webhookEvents ?? []); @endphp
                            <button type="button" wire:click="toggleWebhookEvent('{{ $event }}')"
                                class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-[11px] font-medium
                                    {{ $isChecked ? 'bg-accent/10 text-accent border border-accent/20' : 'bg-surface-alt text-muted border border-transparent hover:text-body' }}">
                                <span class="w-5 h-5 rounded flex items-center justify-center shrink-0
                                    {{ $isChecked ? 'bg-accent text-white' : 'bg-white dark:bg-slate-600 border border-slate-300 dark:border-slate-500' }}">
                                    @if($isChecked)
                                        <x-lucide-check class="w-3 h-3" />
                                    @endif
                                </span>
                                {{ $event }}
                            </button>
                        @endforeach
                    </div>
                    @error('webhookEvents') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex gap-2 pt-2">
                    <x-ui.button wire:click="saveWebhook" variant="secondary" icon="plus" size="sm">
                        {{ $editingWebhookId ? __('common.save') : __('settings.webhooks.add') }}
                    </x-ui.button>
                    @if($editingWebhookId)
                        <x-ui.button wire:click="resetWebhookForm" variant="ghost" size="sm">{{ __('common.cancel') }}</x-ui.button>
                    @endif
                </div>
            </div>

            {{-- List --}}
            @php $webhooks = \App\Models\Webhook::where('organization_id', auth()->user()->organization_id)->latest()->get(); @endphp
            @forelse($webhooks as $wh)
                <div class="flex items-center justify-between p-3 rounded-xl {{ $wh->is_active ? 'bg-card border border-border-light' : 'bg-surface/50 opacity-60' }} mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-heading truncate">{{ $wh->label ?: $wh->url }}</p>
                        <p class="text-[10px] text-muted truncate">{{ $wh->url }}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($wh->events as $evt)
                                <span class="px-1 py-0.5 bg-accent/10 text-accent text-[8px] font-bold rounded">{{ $evt }}</span>
                            @endforeach
                        </div>
                        @if($wh->failure_count > 0)
                            <p class="text-[9px] text-error mt-1">{{ $wh->failure_count }} {{ __('settings.webhooks.failures') }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 ml-3">
                        <x-ui.button wire:click="toggleWebhook('{{ $wh->id }}')" variant="{{ $wh->is_active ? 'outline' : 'accent' }}" size="sm" icon="{{ $wh->is_active ? 'pause' : 'play' }}">
                            {{ $wh->is_active ? __('common.deactivate') : __('common.activate') }}
                        </x-ui.button>
                        <x-ui.button wire:click="editWebhook('{{ $wh->id }}')" variant="ghost" size="sm" icon="pencil">
                            {{ __('common.edit') }}
                        </x-ui.button>
                        <x-ui.button wire:click="deleteWebhook('{{ $wh->id }}')" wire:confirm="{{ __('settings.webhooks.confirm_delete') }}" variant="danger" size="sm" icon="trash-2">
                            {{ __('common.delete') }}
                        </x-ui.button>
                    </div>
                </div>
            @empty
                <p class="text-xs text-muted text-center py-4">{{ __('settings.webhooks.no_webhooks') }}</p>
            @endforelse
        </x-ui.section>
    </div>
    @endif

    {{-- TAB API --}}
    @if($activeTab === 'api')
    <div class="space-y-6">
        <x-ui.section :title="__('settings.api.title')" icon="plug" :noPadding="false">
            <p class="text-xs text-muted mb-4">{{ __('settings.api.desc') }}</p>

            {{-- Create token --}}
            <div class="flex gap-2 mb-6 items-end">
                <div class="flex-1">
                    <x-ui.input wire:model="newTokenName" icon="key" :placeholder="__('settings.api.token_name_placeholder')" />
                </div>
                <x-ui.button wire:click="createApiToken" variant="secondary" icon="plus" size="sm">
                    {{ __('settings.api.create_token') }}
                </x-ui.button>
            </div>
            @error('newTokenName') <p class="text-xs text-error -mt-4 mb-4">{{ $message }}</p> @enderror

            {{-- Show new token (once) --}}
            @if($plainTextToken)
                <div class="p-4 bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 mb-4" x-data="{ copied: false }">
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-300 mb-2">
                        <x-lucide-alert-triangle class="w-3.5 h-3.5 inline" />
                        {{ __('settings.api.token_warning') }}
                    </p>
                    <div class="flex gap-2">
                        <code class="flex-1 bg-white dark:bg-black/20 p-2 rounded text-[10px] font-mono text-heading break-all" id="api-token">{{ $plainTextToken }}</code>
                        <x-ui.button @click="navigator.clipboard.writeText(document.getElementById('api-token').textContent); copied = true; setTimeout(() => copied = false, 2000)"
                                variant="accent" size="sm">
                            <span x-show="!copied"><x-lucide-copy class="w-3.5 h-3.5" /></span>
                            <span x-show="copied"><x-lucide-check class="w-3.5 h-3.5" /></span>
                        </x-ui.button>
                    </div>
                </div>
            @endif

            {{-- Existing tokens --}}
            @php $tokens = auth()->user()->tokens; @endphp
            @if($tokens->count() > 0)
                <div class="space-y-2">
                    @foreach($tokens as $token)
                        <div class="flex items-center justify-between p-3 bg-surface rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-heading">{{ $token->name }}</p>
                                <p class="text-[10px] text-muted">{{ __('settings.api.created') }} {{ $token->created_at->diffForHumans() }}
                                    @if($token->last_used_at) · {{ __('settings.api.last_used') }} {{ $token->last_used_at->diffForHumans() }} @endif
                                </p>
                            </div>
                            <x-ui.button wire:click="revokeApiToken('{{ $token->id }}')"
                                    wire:confirm="{{ __('settings.api.confirm_revoke') }}"
                                    variant="danger" size="sm" icon="trash-2">
                                {{ __('settings.api.revoke') }}
                            </x-ui.button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-muted text-center py-4">{{ __('settings.api.no_tokens') }}</p>
            @endif
        </x-ui.section>

        {{-- API docs hint --}}
        <x-ui.section title="Endpoints" icon="book-open" :noPadding="false">
            <div class="text-xs text-body space-y-1.5 font-mono">
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/me</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/projects</p>
                <p><span class="text-blue-500 font-bold">POST</span> /api/v1/projects</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/projects/{id}</p>
                <p><span class="text-amber-500 font-bold">PUT</span> /api/v1/projects/{id}</p>
                <p><span class="text-error font-bold">DEL</span> /api/v1/projects/{id}</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/projects/{id}/activities</p>
                <p><span class="text-blue-500 font-bold">POST</span> /api/v1/projects/{id}/activities</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/activities</p>
                <p><span class="text-amber-500 font-bold">PUT</span> /api/v1/activities/{id}</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/members</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/stats</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/notifications</p>
                <p><span class="text-blue-500 font-bold">POST</span> /api/v1/notifications/{id}/read</p>
                <p><span class="text-emerald-500 font-bold">GET</span> /api/v1/audit-logs</p>
            </div>
            <p class="text-[10px] text-muted mt-3">Header: <code class="bg-surface px-1 rounded">Authorization: Bearer YOUR_TOKEN</code></p>
        </x-ui.section>
    </div>
    @endif

</x-ui.page-layout>
