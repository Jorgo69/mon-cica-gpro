<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-heading">{{ __('Marketplace — Plugins') }}</h2>
            <p class="text-sm text-muted mt-1">{{ __('Gerez les extensions installees et decouvrez-en de nouvelles.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('Rechercher...') }}"
                class="px-3 py-2 rounded-lg border border-border bg-surface text-sm text-body focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            <button wire:click="discover" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                <x-lucide-scan class="w-4 h-4" />
                {{ __('Scanner') }}
            </button>
        </div>
    </div>

    {{-- Warning banner --}}
    <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
        <div class="flex items-start gap-3">
            <x-lucide-triangle-alert class="w-5 h-5 text-yellow-600 dark:text-yellow-400 shrink-0 mt-0.5" />
            <div class="text-sm text-yellow-800 dark:text-yellow-200">
                <strong>{{ __('Securite') }} :</strong>
                {{ __('Les plugins ont acces complet a l\'application. N\'installez que des plugins de sources fiables.') }}
            </div>
        </div>
    </div>

    {{-- Installed plugins --}}
    @if($plugins->isEmpty())
        <x-ui.empty-state
            icon="puzzle"
            :title="__('Aucun plugin installe')"
            :description="__('Placez vos plugins dans le dossier plugins/ puis cliquez Scanner.')"
        />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-8">
            @foreach($plugins as $plugin)
                <div class="bg-card border border-border rounded-xl p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-heading">{{ $plugin->name }}</h3>
                            <p class="text-xs text-muted">v{{ $plugin->version }} · {{ $plugin->author ?? 'Unknown' }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $plugin->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200' }}">
                            {{ $plugin->is_active ? __('Actif') : __('Inactif') }}
                        </span>
                    </div>

                    <p class="text-sm text-body mb-3">{{ $plugin->description ?? __('Pas de description.') }}</p>

                    {{-- Hooks --}}
                    @if($plugin->hooks)
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($plugin->hooks as $hook)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                    {{ $hook }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Permissions --}}
                    @if($plugin->permissions)
                        <div class="flex flex-wrap gap-1 mb-4">
                            @foreach($plugin->permissions as $perm)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                    {{ $perm }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <button wire:click="toggleActive('{{ $plugin->id }}')" wire:confirm="{{ $plugin->is_active ? __('Desactiver ce plugin ?') : __('Activer ce plugin ?') }}"
                            class="flex-1 px-3 py-1.5 text-sm font-medium rounded-lg border transition-colors {{ $plugin->is_active ? 'border-red-300 text-red-700 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20' : 'border-green-300 text-green-700 hover:bg-green-50 dark:border-green-700 dark:text-green-400 dark:hover:bg-green-900/20' }}">
                            {{ $plugin->is_active ? __('Desactiver') : __('Activer') }}
                        </button>
                        <button wire:click="uninstall('{{ $plugin->id }}')" wire:confirm="{{ __('Desinstaller ce plugin ? Cette action est irreversible.') }}"
                            class="px-3 py-1.5 text-sm font-medium rounded-lg border border-red-300 text-red-700 hover:bg-red-50 dark:border-red-700 dark:text-red-400 dark:hover:bg-red-900/20 transition-colors">
                            <x-lucide-trash-2 class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Remote catalog --}}
    @if(!empty($catalog))
        <h3 class="text-lg font-semibold text-heading mb-4">{{ __('Catalogue en ligne') }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($catalog as $item)
                <div class="bg-card border border-border rounded-xl p-5 opacity-80">
                    <h4 class="font-semibold text-heading">{{ $item['name'] ?? 'Unknown' }}</h4>
                    <p class="text-xs text-muted mb-2">{{ $item['author'] ?? '' }} · v{{ $item['version'] ?? '?' }}</p>
                    <p class="text-sm text-body mb-3">{{ $item['description'] ?? '' }}</p>
                    <a href="{{ $item['url'] ?? '#' }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                        {{ __('Voir les instructions d\'installation') }} →
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
