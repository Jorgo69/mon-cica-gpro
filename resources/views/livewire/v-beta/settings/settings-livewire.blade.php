<div x-data="{
    activeTab: 'appearance',
    darkMode: @js($theme === 'dark'),
    currentDensity: @js($density),
}" class="space-y-6">

    <!-- Navigation par onglets -->
    <div class="bg-card rounded-xl shadow-sm p-4">
        <h2 class="text-lg font-semibold text-heading mb-4">{{ __('Categories') }}</h2>
        <nav class="flex flex-wrap gap-2">
            <button @click="activeTab = 'appearance'"
                :class="activeTab === 'appearance' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-body hover:bg-surface dark:hover:bg-surface-alt'"
                class="px-4 py-3 rounded-lg transition-colors flex items-center">
                <x-lucide-palette class="w-5 h-5 mr-2" />
                {{ __('Apparence') }}
            </button>
            <button @click="activeTab = 'language'"
                :class="activeTab === 'language' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-body hover:bg-surface dark:hover:bg-surface-alt'"
                class="px-4 py-3 rounded-lg transition-colors flex items-center">
                <x-lucide-languages class="w-5 h-5 mr-2" />
                {{ __('Langue') }}
            </button>
            <button @click="activeTab = 'notifications'"
                :class="activeTab === 'notifications' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-body hover:bg-surface dark:hover:bg-surface-alt'"
                class="px-4 py-3 rounded-lg transition-colors flex items-center">
                <x-lucide-bell class="w-5 h-5 mr-2" />
                {{ __('Notifications') }}
            </button>
            <button @click="activeTab = 'privacy'"
                :class="activeTab === 'privacy' ? 'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : 'text-body hover:bg-surface dark:hover:bg-surface-alt'"
                class="px-4 py-3 rounded-lg transition-colors flex items-center">
                <x-lucide-shield class="w-5 h-5 mr-2" />
                {{ __('Confidentialite') }}
            </button>
        </nav>
    </div>

    <!-- Apparence -->
    <div x-show="activeTab === 'appearance'" x-cloak class="bg-card rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-border-light">
            <h2 class="text-lg font-semibold text-heading flex items-center">
                <x-lucide-palette class="w-5 h-5 mr-3 text-primary-500" />
                {{ __('Apparence') }}
            </h2>
            <p class="text-sm text-subtle mt-1">{{ __('Personnalisez l\'apparence de votre application') }}</p>
        </div>
        <div class="p-6 space-y-6">
            <!-- Mode sombre -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-heading">{{ __('Mode sombre') }}</h3>
                    <p class="text-sm text-subtle mt-1">{{ __('Activez le mode sombre pour un confort visuel nocturne') }}</p>
                </div>
                <button
                    @click="darkMode = !darkMode;
                        document.documentElement.classList.toggle('dark', darkMode);
                        localStorage.setItem('darkMode', darkMode ? 'true' : 'false');
                        $wire.saveTheme(darkMode ? 'dark' : 'light')"
                    :class="darkMode ? 'bg-primary-600' : 'bg-border dark:bg-surface-alt'"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    role="switch" :aria-checked="darkMode">
                    <span :class="darkMode ? 'translate-x-5' : 'translate-x-0'"
                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" />
                </button>
            </div>

            <!-- Densite -->
            <div>
                <h3 class="font-medium text-heading mb-3">{{ __('Densite d\'affichage') }}</h3>
                <div class="grid grid-cols-3 gap-3">
                    @foreach (['compact' => 'Compact', 'comfortable' => 'Confortable', 'spacious' => 'Espace'] as $val => $label)
                        <button
                            @click="currentDensity = '{{ $val }}'; $wire.saveDensity('{{ $val }}')"
                            :class="currentDensity === '{{ $val }}'
                                ? 'border-primary-500 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300'
                                : 'border-border text-body hover:bg-surface dark:hover:bg-surface-alt'"
                            class="px-4 py-2 border rounded-lg text-sm font-medium transition-colors">
                            {{ __($label) }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Langue -->
    <div x-show="activeTab === 'language'" x-cloak class="bg-card rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-border-light">
            <h2 class="text-lg font-semibold text-heading flex items-center">
                <x-lucide-languages class="w-5 h-5 mr-3 text-primary-500" />
                {{ __('Langue et region') }}
            </h2>
            <p class="text-sm text-subtle mt-1">{{ __('Choisissez votre langue et format regionaux') }}</p>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-body mb-2">{{ __('Langue') }}</label>
                <select wire:change="saveLocale($event.target.value)"
                    class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                    <option value="fr" @selected($locale === 'fr')>Francais</option>
                    <option value="en" @selected($locale === 'en')>English</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-body mb-2">{{ __('Format de date') }}</label>
                <select wire:change="saveDateFormat($event.target.value)"
                    class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                    <option value="fr" @selected($dateFormat === 'fr')>JJ/MM/AAAA (francais)</option>
                    <option value="en" @selected($dateFormat === 'en')>MM/DD/YYYY (anglais)</option>
                    <option value="iso" @selected($dateFormat === 'iso')>YYYY-MM-DD (ISO)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-body mb-2">{{ __('Fuseau horaire') }}</label>
                <select wire:change="saveTimezone($event.target.value)"
                    class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                    <option value="Europe/Paris" @selected($timezone === 'Europe/Paris')>Europe/Paris (UTC+1)</option>
                    <option value="Europe/London" @selected($timezone === 'Europe/London')>Europe/London (UTC+0)</option>
                    <option value="America/New_York" @selected($timezone === 'America/New_York')>America/New_York (UTC-5)</option>
                    <option value="Africa/Douala" @selected($timezone === 'Africa/Douala')>Africa/Douala (UTC+1)</option>
                    <option value="Africa/Abidjan" @selected($timezone === 'Africa/Abidjan')>Africa/Abidjan (UTC+0)</option>
                    <option value="Africa/Dakar" @selected($timezone === 'Africa/Dakar')>Africa/Dakar (UTC+0)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div x-show="activeTab === 'notifications'" x-cloak class="bg-card rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-border-light">
            <h2 class="text-lg font-semibold text-heading flex items-center">
                <x-lucide-bell class="w-5 h-5 mr-3 text-primary-500" />
                {{ __('Notifications') }}
            </h2>
            <p class="text-sm text-subtle mt-1">{{ __('Controlez comment vous recevez les notifications') }}</p>
        </div>
        <div class="p-6 space-y-6">
            <!-- Email -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-heading">{{ __('Notifications par email') }}</h3>
                    <p class="text-sm text-subtle mt-1">{{ __('Recevoir des notifications importantes par email') }}</p>
                </div>
                <button
                    wire:click="saveEmailNotifications({{ $emailNotifications ? 'false' : 'true' }})"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 {{ $emailNotifications ? 'bg-primary-600' : 'bg-border dark:bg-surface-alt' }}"
                    role="switch" aria-checked="{{ $emailNotifications ? 'true' : 'false' }}">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $emailNotifications ? 'translate-x-5' : 'translate-x-0' }}" />
                </button>
            </div>

            <!-- Push -->
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-heading">{{ __('Notifications push') }}</h3>
                    <p class="text-sm text-subtle mt-1">{{ __('Recevoir des notifications sur votre appareil') }}</p>
                </div>
                <button
                    wire:click="savePushNotifications({{ $pushNotifications ? 'false' : 'true' }})"
                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 {{ $pushNotifications ? 'bg-primary-600' : 'bg-border dark:bg-surface-alt' }}"
                    role="switch" aria-checked="{{ $pushNotifications ? 'true' : 'false' }}">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $pushNotifications ? 'translate-x-5' : 'translate-x-0' }}" />
                </button>
            </div>

            <!-- Digest -->
            <div>
                <label class="block text-sm font-medium text-body mb-2">{{ __('Resume hebdomadaire') }}</label>
                <select wire:change="saveDigestFrequency($event.target.value)"
                    class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                    <option value="never" @selected($digestFrequency === 'never')>{{ __('Jamais') }}</option>
                    <option value="weekly" @selected($digestFrequency === 'weekly')>{{ __('Chaque semaine') }}</option>
                    <option value="monthly" @selected($digestFrequency === 'monthly')>{{ __('Chaque mois') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Confidentialite -->
    <div x-show="activeTab === 'privacy'" x-cloak class="bg-card rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-border-light">
            <h2 class="text-lg font-semibold text-heading flex items-center">
                <x-lucide-shield class="w-5 h-5 mr-3 text-primary-500" />
                {{ __('Confidentialite et securite') }}
            </h2>
            <p class="text-sm text-subtle mt-1">{{ __('Gerez vos parametres de confidentialite et de securite') }}</p>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-body mb-2">{{ __('Visibilite du profil') }}</label>
                <select wire:change="saveProfileVisibility($event.target.value)"
                    class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                    <option value="public" @selected($profileVisibility === 'public')>{{ __('Public') }}</option>
                    <option value="private" @selected($profileVisibility === 'private')>{{ __('Prive') }}</option>
                    <option value="org_only" @selected($profileVisibility === 'org_only')>{{ __('Organisation uniquement') }}</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-medium text-heading">{{ __('Authentification a deux facteurs') }}</h3>
                    <p class="text-sm text-subtle mt-1">{{ __('Ajoutez une couche de securite supplementaire a votre compte') }}</p>
                </div>
                <button class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                    {{ __('Activer') }}
                </button>
            </div>

            <div class="pt-4 border-t border-border-light">
                <h3 class="font-medium text-heading mb-3">{{ __('Donnees personnelles') }}</h3>
                <div class="space-y-3">
                    <button class="w-full text-left px-4 py-3 border border-border rounded-lg text-sm font-medium text-body hover:bg-surface dark:hover:bg-surface-alt transition-colors flex items-center justify-between">
                        <span>{{ __('Telecharger mes donnees') }}</span>
                        <x-lucide-download class="w-4 h-4" />
                    </button>
                    <button class="w-full text-left px-4 py-3 border border-border rounded-lg text-sm font-medium text-error hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center justify-between">
                        <span>{{ __('Supprimer mon compte') }}</span>
                        <x-lucide-trash-2 class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
