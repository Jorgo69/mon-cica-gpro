<x-app-layout>
    
    <main x-data="settings" class="lg:ml-64 px-16 py-24 min-h-screen bg-surface">
        <!-- En-tête de la page -->
        <div class="mb-6 animate-fade-in">
            <h1 class="text-2xl font-bold text-heading flex items-center">
                <x-dynamic-component component="lucide-settings" class="w-6 h-6 mr-3 text-primary-500" />
                {{ __('Paramètres') }}
            </h1>
            <p class="text-subtle mt-2">
                {{ __('Personnalisez votre expérience selon vos préférences') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <!-- Navigation latérale -->
            <div class="lg:col-span-1">
                <div class="bg-card rounded-xl shadow-sm p-4 sticky top-6">
                    <h2 class="text-lg font-semibold text-heading dark:text-white mb-4">
                        {{ __('Catégories') }}
                    </h2>
                    <nav class="space-y-1">
                        <button 
                            @click="activeTab = 'appearance'" 
                            :class="{
                                'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300': activeTab === 'appearance',
                                'text-body hover:bg-surface dark:hover:bg-surface-alt': activeTab !== 'appearance'
                            }"
                            class="w-full text-left px-4 py-3 rounded-lg transition-colors flex items-center"
                        >
                            <x-dynamic-component component="lucide-palette" class="w-5 h-5 mr-3" />
                            {{ __('Apparence') }}
                        </button>
                        <button 
                            @click="activeTab = 'language'" 
                            :class="{
                                'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300': activeTab === 'language',
                                'text-body hover:bg-surface dark:hover:bg-surface-alt': activeTab !== 'language'
                            }"
                            class="w-full text-left px-4 py-3 rounded-lg transition-colors flex items-center"
                        >
                            <x-dynamic-component component="lucide-languages" class="w-5 h-5 mr-3" />
                            {{ __('Langue') }}
                        </button>
                        <button 
                            @click="activeTab = 'notifications'" 
                            :class="{
                                'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300': activeTab === 'notifications',
                                'text-body hover:bg-surface dark:hover:bg-surface-alt': activeTab !== 'notifications'
                            }"
                            class="w-full text-left px-4 py-3 rounded-lg transition-colors flex items-center"
                        >
                            <x-dynamic-component component="lucide-bell" class="w-5 h-5 mr-3" />
                            {{ __('Notifications') }}
                        </button>
                        <button 
                            @click="activeTab = 'privacy'" 
                            :class="{
                                'bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300': activeTab === 'privacy',
                                'text-body hover:bg-surface dark:hover:bg-surface-alt': activeTab !== 'privacy'
                            }"
                            class="w-full text-left px-4 py-3 rounded-lg transition-colors flex items-center"
                        >
                            <x-dynamic-component component="lucide-shield" class="w-5 h-5 mr-3" />
                            {{ __('Confidentialité') }}
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class=" space-y-6">
                <!-- Apparence -->
                <div x-show="activeTab === 'appearance'" class="animate-fade-in">
                    <div class="bg-card rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-border-light">
                            <h2 class="text-lg font-semibold text-heading dark:text-white flex items-center">
                                <x-dynamic-component component="lucide-palette" class="w-5 h-5 mr-3 text-primary-500" />
                                {{ __('Apparence') }}
                            </h2>
                            <p class="text-sm text-subtle mt-1">
                                {{ __('Personnalisez l\'apparence de votre application') }}
                            </p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Mode sombre/clair -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-heading dark:text-white">
                                        {{ __('Mode sombre') }}
                                    </h3>
                                    <p class="text-sm text-subtle mt-1">
                                        {{ __('Activez le mode sombre pour un confort visuel nocturne') }}
                                    </p>
                                </div>
                                <button 
                                    @click="toggleTheme(); darkMode = !darkMode" 
                                    :class="{
                                        'bg-primary-600': darkMode,
                                        'bg-border dark:bg-surface-alt': !darkMode
                                    }"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                                    role="switch"
                                    :aria-checked="darkMode"
                                >
                                    <span 
                                        :class="{
                                            'translate-x-5': darkMode,
                                            'translate-x-0': !darkMode
                                        }"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                    />
                                </button>
                            </div>

                            <!-- Thème de couleur -->
                            <div>
                                <h3 class="font-medium text-heading dark:text-white mb-3">
                                    {{ __('Couleur du thème') }}
                                </h3>
                                <div class="flex space-x-3">
                                    <button class="w-10 h-10 rounded-full bg-primary-600 ring-2 ring-offset-2 ring-primary-600"></button>
                                    <button class="w-10 h-10 rounded-full bg-purple-600 ring-2 ring-offset-2 ring-transparent hover:ring-purple-600"></button>
                                    <button class="w-10 h-10 rounded-full bg-green-600 ring-2 ring-offset-2 ring-transparent hover:ring-green-600"></button>
                                    <button class="w-10 h-10 rounded-full bg-error ring-2 ring-offset-2 ring-transparent hover:ring-red-600"></button>
                                    <button class="w-10 h-10 rounded-full bg-yellow-500 ring-2 ring-offset-2 ring-transparent hover:ring-yellow-500"></button>
                                </div>
                            </div>

                            <!-- Densité d'affichage -->
                            <div>
                                <h3 class="font-medium text-heading dark:text-white mb-3">
                                    {{ __('Densité d\'affichage') }}
                                </h3>
                                <div class="grid grid-cols-3 gap-3">
                                    <button class="px-4 py-2 border border-border rounded-lg text-sm font-medium text-body hover:bg-surface dark:hover:bg-surface-alt transition-colors">
                                        {{ __('Compact') }}
                                    </button>
                                    <button class="px-4 py-2 border border-border rounded-lg text-sm font-medium text-body hover:bg-surface dark:hover:bg-surface-alt transition-colors">
                                        {{ __('Confortable') }}
                                    </button>
                                    <button class="px-4 py-2 border border-border rounded-lg text-sm font-medium text-body hover:bg-surface dark:hover:bg-surface-alt transition-colors">
                                        {{ __('Espacé') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Langue -->
                <div x-show="activeTab === 'language'" class="animate-fade-in">
                    <div class="bg-card rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-border-light">
                            <h2 class="text-lg font-semibold text-heading dark:text-white flex items-center">
                                <x-dynamic-component component="lucide-languages" class="w-5 h-5 mr-3 text-primary-500" />
                                {{ __('Langue et région') }}
                            </h2>
                            <p class="text-sm text-subtle mt-1">
                                {{ __('Choisissez votre langue et format régionaux') }}
                            </p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Sélection de la langue -->
                            <!-- Sélection de la langue -->
                        <div>
                            <label class="block text-sm font-medium text-body mb-2">
                                {{ __('Langue') }}
                            </label>
                            <select 
                                x-model="currentLocale" 
                                @change="changeLanguage($event.target.value)"
                                class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors"
                            >
                                <option value="fr" :selected="currentLocale === 'fr'">Français</option>
                                <option value="en" :selected="currentLocale === 'en'">English</option>
                                {{-- <option value="es" :selected="currentLocale === 'es'">Español</option>
                                <option value="de" :selected="currentLocale === 'de'">Deutsch</option> --}}
                            </select>
                        </div>

                            <!-- Format de date -->
                            <div>
                                <label class="block text-sm font-medium text-body mb-2">
                                    {{ __('Format de date') }}
                                </label>
                                <select class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                                    <option value="fr" selected>JJ/MM/AAAA (français)</option>
                                    <option value="en">MM/DD/YYYY (anglais)</option>
                                    <option value="iso">YYYY-MM-DD (ISO)</option>
                                </select>
                            </div>

                            <!-- Fuseau horaire -->
                            <div>
                                <label class="block text-sm font-medium text-body mb-2">
                                    {{ __('Fuseau horaire') }}
                                </label>
                                <select class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                                    <option value="paris" selected>Europe/Paris (UTC+1)</option>
                                    <option value="london">Europe/London (UTC+0)</option>
                                    <option value="newyork">America/New_York (UTC-5)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div x-show="activeTab === 'notifications'" class="animate-fade-in">
                    <div class="bg-card rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-border-light">
                            <h2 class="text-lg font-semibold text-heading dark:text-white flex items-center">
                                <x-dynamic-component component="lucide-bell" class="w-5 h-5 mr-3 text-primary-500" />
                                {{ __('Notifications') }}
                            </h2>
                            <p class="text-sm text-subtle mt-1">
                                {{ __('Contrôlez comment vous recevez les notifications') }}
                            </p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Notifications par email -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-heading dark:text-white">
                                        {{ __('Notifications par email') }}
                                    </h3>
                                    <p class="text-sm text-subtle mt-1">
                                        {{ __('Recevoir des notifications importantes par email') }}
                                    </p>
                                </div>
                                <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-primary-600 transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" role="switch" aria-checked="true">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-5" />
                                </button>
                            </div>

                            <!-- Notifications push -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-heading dark:text-white">
                                        {{ __('Notifications push') }}
                                    </h3>
                                    <p class="text-sm text-subtle mt-1">
                                        {{ __('Recevoir des notifications sur votre appareil') }}
                                    </p>
                                </div>
                                <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-border dark:bg-surface-alt transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2" role="switch" aria-checked="false">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0" />
                                </button>
                            </div>

                            <!-- Fréquence des résumés -->
                            <div>
                                <label class="block text-sm font-medium text-body mb-2">
                                    {{ __('Résumé hebdomadaire') }}
                                </label>
                                <select class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                                    <option value="never">{{ __('Jamais') }}</option>
                                    <option value="weekly" selected>{{ __('Chaque semaine') }}</option>
                                    <option value="monthly">{{ __('Chaque mois') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confidentialité -->
                <div x-show="activeTab === 'privacy'" class="animate-fade-in">
                    <div class="bg-card rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-border-light">
                            <h2 class="text-lg font-semibold text-heading dark:text-white flex items-center">
                                <x-dynamic-component component="lucide-shield" class="w-5 h-5 mr-3 text-primary-500" />
                                {{ __('Confidentialité et sécurité') }}
                            </h2>
                            <p class="text-sm text-subtle mt-1">
                                {{ __('Gérez vos paramètres de confidentialité et de sécurité') }}
                            </p>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Visibilité du profil -->
                            <div>
                                <label class="block text-sm font-medium text-body mb-2">
                                    {{ __('Visibilité du profil') }}
                                </label>
                                <select class="w-full px-4 py-2.5 rounded-lg border border-border focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-card text-heading transition-colors">
                                    <option value="public">{{ __('Public') }}</option>
                                    <option value="private" selected>{{ __('Privé') }}</option>
                                    <option value="friends">{{ __('Amis uniquement') }}</option>
                                </select>
                            </div>

                            <!-- Authentification à deux facteurs -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-medium text-heading dark:text-white">
                                        {{ __('Authentification à deux facteurs') }}
                                    </h3>
                                    <p class="text-sm text-subtle mt-1">
                                        {{ __('Ajoutez une couche de sécurité supplémentaire à votre compte') }}
                                    </p>
                                </div>
                                <button class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    {{ __('Activer') }}
                                </button>
                            </div>

                            <!-- Données personnelles -->
                            <div class="pt-4 border-t border-border-light">
                                <h3 class="font-medium text-heading dark:text-white mb-3">
                                    {{ __('Données personnelles') }}
                                </h3>
                                <div class="space-y-3">
                                    <button class="w-full text-left px-4 py-3 border border-border dark:border-border rounded-lg text-sm font-medium text-body hover:bg-surface dark:hover:bg-surface-alt transition-colors flex items-center justify-between">
                                        <span>{{ __('Télécharger mes données') }}</span>
                                        <x-dynamic-component component="lucide-download" class="w-4 h-4" />
                                    </button>
                                    <button class="w-full text-left px-4 py-3 border border-border dark:border-border rounded-lg text-sm font-medium text-error dark:text-error/70 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors flex items-center justify-between">
                                        <span>{{ __('Supprimer mon compte') }}</span>
                                        <x-dynamic-component component="lucide-trash-2" class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @push('alpine-js')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('settings', () => ({
                activeTab: 'appearance',
                darkMode: localStorage.getItem('darkMode') === 'true',
                currentLocale: '{{ app()->getLocale() }}',
                
                init() {
                    // Synchroniser avec le thème existant
                    this.darkMode = document.documentElement.classList.contains('dark');
                    
                    // Récupérer la locale actuelle
                    this.currentLocale = '{{ app()->getLocale() }}';
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
                },
                
                changeLanguage(locale) {
                    // Changement de langue via AJAX
                    fetch(`/lang/${locale}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Language changed:', data);
                        // Recharger la page pour appliquer les traductions
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error changing language:', error);
                        // Fallback: redirection standard
                        window.location.href = `/lang/${locale}`;
                    });
                }
            }));
        });
    </script>
    @endpush

</x-app-layout>