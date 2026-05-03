<div wire:ignore.self x-data="{ open: false }" @click.outside="open = false" class="relative">
    {{-- Bouton cloche --}}
    <button @click="open = !open" class="relative p-2 rounded-xl text-muted hover:text-subtle dark:hover:text-heading hover:bg-surface-alt transition-all">
        <x-lucide-bell class="w-5 h-5" />
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 w-5 h-5 bg-error text-white text-[10px] font-black rounded-full flex items-center justify-center shadow-sm">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="absolute right-0 mt-2 w-96 bg-card rounded-2xl shadow-xl border border-border z-50 overflow-hidden"
         style="display: none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-border-light dark:border-surface-alt">
            <h3 class="text-sm font-black text-body">{{ __('notifications.title') }}</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-bold text-accent hover:text-accent/80 uppercase tracking-widest transition-colors">
                    {{ __('notifications.mark_all_read') }}
                </button>
            @endif
        </div>

        {{-- Liste --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-border-light dark:divide-surface-alt/50">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <a href="{{ $data['action_url'] ?? '#' }}"
                   wire:click="markAsRead('{{ $notification->id }}')"
                   class="block px-5 py-3 hover:bg-surface dark:hover:bg-surface-alt/50 transition-colors {{ $isUnread ? 'bg-accent/5' : '' }}">
                    <div class="flex items-start gap-3">
                        {{-- Icone selon le type --}}
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $isUnread ? 'bg-accent/10 text-accent' : 'bg-surface-alt text-muted' }}">
                            @if(str_contains($notification->type, 'ProjectStatus'))
                                <x-lucide-refresh-cw class="w-4 h-4" />
                            @elseif(str_contains($notification->type, 'ActivityProgress'))
                                <x-lucide-trending-up class="w-4 h-4" />
                            @elseif(str_contains($notification->type, 'ProjectSubmitted'))
                                <x-lucide-send class="w-4 h-4" />
                            @elseif(str_contains($notification->type, 'ActivityAssigned'))
                                <x-lucide-user-check class="w-4 h-4" />
                            @else
                                <x-lucide-bell class="w-4 h-4" />
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-body {{ $isUnread ? '' : 'font-medium' }}">
                                {{ $data['title'] ?? __('notifications.title') }}
                            </p>
                            <p class="text-[11px] text-subtle mt-0.5 line-clamp-2">
                                {{ $data['message'] ?? '' }}
                            </p>
                            <p class="text-[10px] text-muted mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        @if($isUnread)
                            <div class="w-2 h-2 rounded-full bg-accent shrink-0 mt-1.5"></div>
                        @endif
                    </div>
                </a>
            @empty
                <div class="px-5 py-10 text-center">
                    <x-lucide-bell-off class="w-8 h-8 mx-auto text-body dark:text-subtle mb-2" />
                    <p class="text-sm text-muted">{{ __('notifications.no_notifications') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
