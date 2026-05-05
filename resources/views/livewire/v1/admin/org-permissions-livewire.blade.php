<x-ui.page-layout>
    <x-ui.page-header :title="__('admin.permissions.title')" :subtitle="__('admin.permissions.subtitle', ['default' => 'Permissions detaillees des membres de l\'organisation'])">
    </x-ui.page-header>

    {{-- Search --}}
    <div class="mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('admin.members.search_placeholder')" icon="search" />
    </div>

    {{-- Members permissions --}}
    <x-ui.section :title="__('admin.permissions.members')" icon="shield-check" :noPadding="true">
        @forelse($members as $member)
            <div class="border-b border-border last:border-b-0" x-data="{ open: false }">
                {{-- Header --}}
                <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 hover:bg-surface-alt transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center">
                            <span class="text-xs font-black text-accent">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-bold text-heading">{{ $member->name }}</p>
                            <p class="text-[10px] text-muted">{{ $member->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-ui.badge :color="$member->level->color()" size="xs">
                            {{ $member->level->label() }}
                        </x-ui.badge>
                        <span class="text-[9px] text-muted">{{ count($member->permissions) }} perms</span>
                        <x-lucide-chevron-down class="w-4 h-4 text-muted transition-transform" ::class="{ 'rotate-180': open }" />
                    </div>
                </button>

                {{-- Permissions detail --}}
                <div x-show="open" x-collapse class="px-6 pb-4 border-t border-border-light">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-3">
                        @foreach($allPermissions as $perm)
                            <div class="flex items-center gap-1.5 text-[11px] {{ in_array($perm, $member->permissions) ? 'text-body' : 'text-muted/40' }}">
                                @if(in_array($perm, $member->permissions))
                                    <x-lucide-check-circle class="w-3.5 h-3.5 text-success shrink-0" />
                                @else
                                    <x-lucide-x-circle class="w-3.5 h-3.5 text-muted/30 shrink-0" />
                                @endif
                                {{ $perm }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <x-ui.empty-state icon="shield-check" :title="__('admin.members.no_members')" />
        @endforelse
    </x-ui.section>
</x-ui.page-layout>
