<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    {{-- Bouton cloche --}}
    <button @click="open = !open" class="relative p-2 rounded-xl text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
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
         class="absolute right-0 mt-2 w-96 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden"
         style="display: none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-black text-slate-700 dark:text-slate-200">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-bold text-accent hover:text-accent/80 uppercase tracking-widest transition-colors">
                    Tout marquer lu
                </button>
            @endif
        </div>

        {{-- Liste --}}
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50 dark:divide-slate-800/50">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <a href="{{ $data['action_url'] ?? '#' }}"
                   wire:click="markAsRead('{{ $notification->id }}')"
                   class="block px-5 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ $isUnread ? 'bg-accent/5' : '' }}">
                    <div class="flex items-start gap-3">
                        {{-- Icone selon le type --}}
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $isUnread ? 'bg-accent/10 text-accent' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
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
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 {{ $isUnread ? '' : 'font-medium' }}">
                                {{ $data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">
                                {{ $data['message'] ?? '' }}
                            </p>
                            <p class="text-[10px] text-slate-400 mt-1">
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
                    <x-lucide-bell-off class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600 mb-2" />
                    <p class="text-sm text-slate-400 dark:text-slate-500">Aucune notification</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
