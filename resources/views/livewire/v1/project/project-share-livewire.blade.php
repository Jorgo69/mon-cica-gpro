<div x-data @copy-to-clipboard.window="navigator.clipboard.writeText($event.detail.url)">
    {{-- Toggle Button --}}
    <x-ui.button wire:click="$toggle('showModal')" variant="outline" icon="share-2" size="sm">
        {{ __('shared.share') }}
        @if($tokens->where('is_active', true)->count())
            <span class="ml-1 w-4 h-4 rounded-full bg-success text-[9px] font-bold text-white inline-flex items-center justify-center">{{ $tokens->where('is_active', true)->count() }}</span>
        @endif
    </x-ui.button>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-data @click.self="$wire.set('showModal', false)">
        <div class="bg-card rounded-2xl border border-border-light shadow-xl w-full max-w-lg mx-4 max-h-[80vh] overflow-y-auto">
            <div class="p-6 border-b border-border-light flex items-center justify-between">
                <h3 class="text-sm font-black text-heading uppercase tracking-wider">{{ __('shared.manage_links') }}</h3>
                <button wire:click="$set('showModal', false)" class="text-muted hover:text-heading">
                    <x-lucide-x class="w-4 h-4" />
                </button>
            </div>

            <div class="p-6 space-y-6">
                {{-- Create New Link --}}
                <div class="p-4 bg-surface rounded-xl space-y-3">
                    <h4 class="text-xs font-bold text-heading">{{ __('shared.new_link') }}</h4>
                    <x-ui.input wire:model="label" :placeholder="__('shared.label_placeholder')" size="sm" />
                    <div class="flex items-center gap-3">
                        <x-ui.select wire:model="expiresIn" size="sm" class="flex-1">
                            <option value="7">7 {{ __('common.days') }}</option>
                            <option value="30">30 {{ __('common.days') }}</option>
                            <option value="90">90 {{ __('common.days') }}</option>
                            <option value="365">1 {{ __('common.year') }}</option>
                            <option value="0">{{ __('shared.no_expiry') }}</option>
                        </x-ui.select>
                        <x-ui.button wire:click="createLink" variant="accent" icon="plus" size="sm">
                            {{ __('common.create') }}
                        </x-ui.button>
                    </div>
                </div>

                {{-- Existing Links --}}
                @forelse($tokens as $token)
                    <div class="p-4 bg-surface rounded-xl space-y-2 {{ !$token->is_active ? 'opacity-50' : '' }}">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-heading">{{ $token->label ?? __('shared.default_label') }}</span>
                                @if(!$token->isValid())
                                    <span class="ml-2 text-[9px] font-bold text-error uppercase">{{ __('shared.expired') }}</span>
                                @elseif($token->is_active)
                                    <span class="ml-2 text-[9px] font-bold text-success uppercase">{{ __('common.active') }}</span>
                                @else
                                    <span class="ml-2 text-[9px] font-bold text-muted uppercase">{{ __('common.inactive') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="toggleLink('{{ $token->id }}')" class="p-1 rounded text-muted hover:text-heading" title="{{ $token->is_active ? __('shared.deactivate') : __('shared.activate') }}">
                                    <x-lucide-toggle-right class="w-4 h-4" />
                                </button>
                                <button wire:click="deleteLink('{{ $token->id }}')" wire:confirm="{{ __('common.confirm_delete') }}" class="p-1 rounded text-muted hover:text-error" title="{{ __('common.delete') }}">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        {{-- URL --}}
                        <div x-data="{ copied: false }" class="flex items-center gap-2">
                            <input type="text" value="{{ $token->getUrl() }}" readonly class="flex-1 text-[10px] bg-card border border-border-light rounded-md px-2 py-1 text-muted font-mono" />
                            <button @click="navigator.clipboard.writeText('{{ $token->getUrl() }}'); copied = true; setTimeout(() => copied = false, 2000)" class="text-muted hover:text-accent p-1">
                                <x-lucide-copy class="w-3.5 h-3.5" x-show="!copied" />
                                <x-lucide-check class="w-3.5 h-3.5 text-success" x-show="copied" x-cloak />
                            </button>
                        </div>

                        {{-- Stats --}}
                        <div class="flex items-center gap-4 text-[10px] text-muted">
                            <span>{{ __('shared.views') }}: {{ $token->view_count }}</span>
                            @if($token->expires_at)
                                <span>{{ __('shared.expires') }}: {{ $token->expires_at->format('d/m/Y') }}</span>
                            @else
                                <span>{{ __('shared.no_expiry') }}</span>
                            @endif
                            @if($token->last_viewed_at)
                                <span>{{ __('shared.last_view') }}: {{ $token->last_viewed_at->diffForHumans() }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-muted text-center py-4">{{ __('shared.no_links') }}</p>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>
