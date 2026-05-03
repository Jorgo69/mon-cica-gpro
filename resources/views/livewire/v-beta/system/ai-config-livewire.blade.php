<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <x-ui.page-header :title="__('ai.config.title')" :subtitle="__('ai.config.subtitle')">
        <x-slot:actions>
            <x-ui.badge variant="info" icon="info">
                {{ __('ai.config.env_fallback_info') }}
            </x-ui.badge>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- GUIDE SECTION (collapsible) --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <x-ui.card x-data="{ guideOpen: false }">
        <button @click="guideOpen = !guideOpen" class="w-full p-4 flex items-center justify-between hover:bg-surface/50 transition-colors rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <x-lucide-book-open class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                </div>
                <div class="text-left">
                    <p class="text-sm font-bold text-heading">{{ __('ai.guide.title') }}</p>
                    <p class="text-xs text-muted">{{ __('ai.guide.what_is_ai') }} — {{ __('ai.guide.who_can_configure') }}</p>
                </div>
            </div>
            <x-lucide-chevron-down class="w-5 h-5 text-muted transition-transform" x-bind:class="guideOpen && 'rotate-180'" />
        </button>

        <div x-show="guideOpen" x-collapse class="px-4 pb-4 space-y-5">
            {{-- What is AI in GPRO --}}
            <div class="p-4 bg-purple-50 dark:bg-purple-900/10 rounded-xl border border-purple-100 dark:border-purple-800/30">
                <p class="text-sm font-bold text-heading mb-2"><x-lucide-sparkles class="w-4 h-4 inline text-purple-500" /> {{ __('ai.guide.what_is_ai') }}</p>
                <p class="text-xs text-body leading-relaxed">{!! __('ai.guide.what_is_ai_desc') !!}</p>
            </div>

            {{-- Where to find AI --}}
            <div>
                <p class="text-sm font-bold text-heading mb-2"><x-lucide-map-pin class="w-4 h-4 inline text-accent" /> {{ __('ai.guide.where_is_ai') }}</p>
                <ul class="space-y-1.5">
                    @foreach(__('ai.guide.where_is_ai_items') as $item)
                        <li class="flex items-start gap-2 text-xs text-body">
                            <x-lucide-check class="w-3.5 h-3.5 text-green-500 mt-0.5 shrink-0" />
                            <span>{!! $item !!}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Who can configure --}}
            <div>
                <p class="text-sm font-bold text-heading mb-2"><x-lucide-users class="w-4 h-4 inline text-accent" /> {{ __('ai.guide.who_can_configure') }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach(__('ai.guide.who_roles') as $role => $desc)
                        <div class="p-3 bg-surface rounded-lg border border-border-light">
                            <p class="text-xs text-body leading-relaxed">{!! $desc !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- How to get a key --}}
            <div>
                <p class="text-sm font-bold text-heading mb-2"><x-lucide-key class="w-4 h-4 inline text-accent" /> {{ __('ai.guide.how_to_get_key') }}</p>
                <ol class="space-y-1.5">
                    @foreach(__('ai.guide.how_to_get_key_steps') as $i => $step)
                        <li class="flex items-start gap-2 text-xs text-body">
                            <span class="w-5 h-5 rounded-full bg-accent/10 text-accent text-[10px] font-bold flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                            <span>{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>

            {{-- Recommendation --}}
            <div class="p-4 bg-green-50 dark:bg-green-900/10 rounded-xl border border-green-100 dark:border-green-800/30">
                <p class="text-sm font-bold text-heading mb-1"><x-lucide-star class="w-4 h-4 inline text-green-500" /> {{ __('ai.guide.recommended') }}</p>
                <p class="text-xs text-body leading-relaxed">{!! __('ai.guide.recommended_desc') !!}</p>
            </div>

            {{-- Provider catalog --}}
            <div>
                <p class="text-sm font-bold text-heading mb-3"><x-lucide-layout-grid class="w-4 h-4 inline text-accent" /> {{ __('ai.guide.choose_provider') }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($providers as $p)
                        <div class="p-3 bg-card rounded-xl border border-border-light hover:border-accent/30 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <x-dynamic-component :component="'lucide-' . $p->icon()" class="w-4 h-4 text-accent" />
                                <span class="text-xs font-bold text-heading">{{ $p->label() }}</span>
                                @if($p->hasFreeTier())
                                    <span class="ml-auto text-[9px] font-bold text-green-600 bg-green-100 dark:bg-green-900/30 dark:text-green-400 px-1.5 py-0.5 rounded-full">{{ __('ai.guide.free') }}</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-muted leading-relaxed mb-2">{{ $p->description() }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-subtle">{{ $p->pricing() }}</span>
                                @if($p->signupUrl())
                                    <a href="{{ $p->signupUrl() }}" target="_blank" rel="noopener" class="text-[9px] font-bold text-accent hover:underline">
                                        {{ __('ai.guide.get_key_at') }} &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Security --}}
            <div class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800/30">
                <p class="text-sm font-bold text-heading mb-1"><x-lucide-shield-check class="w-4 h-4 inline text-blue-500" /> {{ __('ai.guide.security') }}</p>
                <p class="text-xs text-body leading-relaxed">{!! __('ai.guide.security_desc') !!}</p>
            </div>
        </div>
    </x-ui.card>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- .env Status --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <x-ui.card>
        <x-ui.card-header :title="__('ai.config.env_status')" icon="hard-drive" />
        <div class="p-4 space-y-2">
            <div class="flex items-center gap-2 text-sm">
                @if($hasEnvGroq)
                    <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                    <span class="text-body">GROQ_API_KEY {{ __('ai.config.configured_env') }}</span>
                @else
                    <x-lucide-x-circle class="w-4 h-4 text-muted" />
                    <span class="text-muted">GROQ_API_KEY {{ __('ai.config.not_configured') }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2 text-sm">
                @if($hasEnvGemini)
                    <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                    <span class="text-body">GEMINI_API_KEY {{ __('ai.config.configured_env') }}</span>
                @else
                    <x-lucide-x-circle class="w-4 h-4 text-muted" />
                    <span class="text-muted">GEMINI_API_KEY {{ __('ai.config.not_configured') }}</span>
                @endif
            </div>
            <p class="text-xs text-muted mt-2">{{ __('ai.config.env_note') }}</p>
        </div>
    </x-ui.card>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- Configuration Form --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <x-ui.card>
        <x-ui.card-header :title="__('ai.config.global_config')" icon="settings" />
        <form wire:submit="save" class="p-4 space-y-5">

            {{-- Enable toggle --}}
            <div class="flex items-center justify-between">
                <div>
                    <label class="text-sm font-medium text-heading">{{ __('ai.config.enable_ai') }}</label>
                    <p class="text-[10px] text-muted">Si desactive, l'IA sera indisponible pour tous sauf les organisations avec leur propre cle.</p>
                </div>
                <button type="button" wire:click="$toggle('enabled')"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $enabled ? 'bg-primary-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $enabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>

            {{-- Provider select with description --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">{{ __('ai.config.provider') }}</label>
                <select wire:model.live="provider" class="w-full rounded-lg border-border bg-surface text-body text-sm px-3 py-2">
                    @foreach($providers as $p)
                        <option value="{{ $p->value }}">{{ $p->label() }} — {{ $p->pricing() }}</option>
                    @endforeach
                </select>
                @php $selectedProvider = \App\Enums\AiProvider::tryFrom($provider); @endphp
                @if($selectedProvider)
                    <p class="text-[10px] text-muted mt-1.5">{{ $selectedProvider->description() }}</p>
                    @if($selectedProvider->signupUrl())
                        <a href="{{ $selectedProvider->signupUrl() }}" target="_blank" rel="noopener" class="text-[10px] text-accent hover:underline">
                            {{ __('ai.guide.get_key_at') }} {{ $selectedProvider->label() }} &rarr;
                        </a>
                    @endif
                @endif
            </div>

            {{-- API Key --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">{{ __('ai.config.api_key') }}</label>
                @if($maskedKey)
                    <p class="text-xs text-muted mb-1">{{ __('ai.config.current_key') }}: <code class="bg-surface-alt px-1 rounded">{{ $maskedKey }}</code></p>
                @endif
                <input type="password" wire:model="apiKey"
                    placeholder="{{ $maskedKey ? __('ai.config.leave_empty') : __('ai.config.enter_key') }}"
                    class="w-full rounded-lg border-border bg-surface text-body text-sm px-3 py-2" />
                <p class="text-xs text-muted mt-1"><x-lucide-lock class="w-3 h-3 inline" /> {{ __('ai.config.key_encrypted') }}</p>
            </div>

            {{-- Model --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">{{ __('ai.config.model') }}</label>
                <input type="text" wire:model="model"
                    placeholder="{{ \App\Enums\AiProvider::tryFrom($provider)?->defaultModel() }}"
                    class="w-full rounded-lg border-border bg-surface text-body text-sm px-3 py-2" />
                <p class="text-[10px] text-muted mt-1">Laissez vide pour utiliser le modele par defaut : <code>{{ \App\Enums\AiProvider::tryFrom($provider)?->defaultModel() }}</code></p>
            </div>

            {{-- Custom URL (only for custom provider) --}}
            @if($provider === 'custom')
            <div>
                <label class="block text-sm font-medium text-heading mb-1">{{ __('ai.config.base_url') }}</label>
                <input type="url" wire:model="baseUrl"
                    placeholder="https://your-ai-server.com/v1"
                    class="w-full rounded-lg border-border bg-surface text-body text-sm px-3 py-2" />
                <p class="text-xs text-muted mt-1">{{ __('ai.config.base_url_note') }}</p>
            </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit" icon="save" wire:loading.attr="disabled">
                    {{ __('common.save') }}
                </x-ui.button>

                @if($configId)
                <x-ui.button type="button" variant="outline" icon="activity" wire:click="testConnection" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="testConnection">{{ __('ai.config.test') }}</span>
                    <span wire:loading wire:target="testConnection">{{ __('ai.config.testing') }}</span>
                </x-ui.button>

                <x-ui.button type="button" variant="danger" icon="trash-2" wire:click="deleteConfig"
                    wire:confirm="{{ __('ai.config.confirm_delete') }}">
                    {{ __('common.delete') }}
                </x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- How it works --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <x-ui.card>
        <div class="p-4 space-y-3 text-sm text-muted">
            <p class="font-medium text-heading">{{ __('ai.config.how_it_works') }}</p>
            <div class="space-y-2">
                <div class="flex items-start gap-3 p-3 bg-surface rounded-lg">
                    <span class="w-6 h-6 rounded-full bg-accent text-white text-xs font-bold flex items-center justify-center shrink-0">1</span>
                    <p class="text-xs text-body">{{ __('ai.config.priority_1') }}</p>
                </div>
                <div class="flex items-start gap-3 p-3 bg-surface rounded-lg">
                    <span class="w-6 h-6 rounded-full bg-accent/70 text-white text-xs font-bold flex items-center justify-center shrink-0">2</span>
                    <p class="text-xs text-body">{{ __('ai.config.priority_2') }}</p>
                </div>
                <div class="flex items-start gap-3 p-3 bg-surface rounded-lg">
                    <span class="w-6 h-6 rounded-full bg-gray-400 text-white text-xs font-bold flex items-center justify-center shrink-0">3</span>
                    <p class="text-xs text-body">{{ __('ai.config.priority_3') }}</p>
                </div>
            </div>
        </div>
    </x-ui.card>
</div>
