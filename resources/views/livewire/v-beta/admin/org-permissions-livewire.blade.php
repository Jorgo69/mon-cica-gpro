<div>
    {{-- Search --}}
    <div class="mb-6">
        <input type="text" wire:model.live.debounce.300ms="search"
               class="input-field w-full max-w-sm text-xs"
               placeholder="{{ __('admin.members.search_placeholder') }}">
    </div>

    {{-- Members permissions table --}}
    <div class="space-y-3">
        @forelse($members as $member)
            <div class="bg-card rounded-xl border border-border-light p-4" x-data="{ open: false }">
                {{-- Header --}}
                <button @click="open = !open" class="w-full flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-accent/10 flex items-center justify-center">
                            <span class="text-xs font-black text-accent">{{ strtoupper(substr($member->name, 0, 2)) }}</span>
                        </div>
                        <div class="text-left">
                            <p class="text-sm font-bold text-heading">{{ $member->name }}</p>
                            <p class="text-[10px] text-muted">{{ $member->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-{{ $member->level->color() }}-100 text-{{ $member->level->color() }}-700 dark:bg-{{ $member->level->color() }}-900/30 dark:text-{{ $member->level->color() }}-300">
                            {{ $member->level->label() }}
                        </span>
                        <span class="text-[9px] text-muted">{{ count($member->permissions) }} perms</span>
                        <x-lucide-chevron-down class="w-4 h-4 text-muted transition-transform" x-bind:class="{ 'rotate-180': open }" />
                    </div>
                </button>

                {{-- Permissions detail --}}
                <div x-show="open" x-collapse class="mt-3 pt-3 border-t border-border-light">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                        @foreach($allPermissions as $perm)
                            <div class="flex items-center gap-1.5 text-[11px] {{ in_array($perm, $member->permissions) ? 'text-body' : 'text-muted/40' }}">
                                @if(in_array($perm, $member->permissions))
                                    <x-lucide-check-circle class="w-3.5 h-3.5 text-success flex-shrink-0" />
                                @else
                                    <x-lucide-x-circle class="w-3.5 h-3.5 text-muted/30 flex-shrink-0" />
                                @endif
                                {{ $perm }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-muted text-center py-8">{{ __('admin.members.no_members') }}</p>
        @endforelse
    </div>
</div>
