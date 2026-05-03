<x-ui.page-layout>
    <x-ui.page-header :title="__('system.root_org_list.title')" :subtitle="__('system.root_org_list.subtitle')" icon="building-2" />

    {{-- Toolbar --}}
    <div class="mb-4">
        <x-ui.input
            wire:model.live.debounce.300ms="search"
            placeholder="{{ __('system.root_org_list.search_placeholder') }}"
            icon="search"
        />
    </div>

    {{-- Table --}}
    <x-ui.section>
        @if($organizations->isEmpty())
            <x-ui.empty-state icon="building-2" :message="__('system.root_org_list.no_org')" />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>{{ __('system.root_org_list.organization') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.root_org_list.members') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.root_org_list.projects') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('plans.plan') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('common.status') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('common.created_at') }}</x-ui.table.th>
                    <x-ui.table.th align="right">{{ __('common.actions') }}</x-ui.table.th>
                </x-slot:headers>

                @foreach($organizations as $org)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <div class="font-bold text-heading">{{ $org->name }}</div>
                            <div class="text-[10px] font-mono text-muted">{{ $org->slug }}</div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="info" size="sm">{{ $org->users_count }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="accent" size="sm">{{ $org->projects_count }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @php $plan = $org->currentPlan(); @endphp
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-1.5">
                                    <x-ui.badge :variant="$plan->badgeColor()" size="sm">{{ $plan->label() }}</x-ui.badge>
                                    <x-lucide-chevron-down class="w-3 h-3 text-muted" />
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute z-30 top-full mt-1 left-0 bg-card border border-border-light rounded-xl shadow-xl p-3 w-56 space-y-2">
                                    <p class="text-[9px] font-black text-muted uppercase tracking-widest mb-2">{{ __('plans.change_plan') }}</p>
                                    @foreach(\App\Enums\Plan::cases() as $p)
                                        <button wire:click="changePlan('{{ $org->id }}', '{{ $p->value }}', 12)"
                                                @click="open = false"
                                                class="w-full text-left px-2 py-1.5 rounded-lg text-xs hover:bg-surface transition-colors flex items-center justify-between {{ $plan === $p ? 'bg-accent/10 text-accent font-bold' : 'text-body' }}">
                                            <span>{{ $p->label() }}</span>
                                            <span class="text-[9px] text-muted">{{ $p->price() }}</span>
                                        </button>
                                    @endforeach
                                    @if($org->plan_expires_at)
                                        <p class="text-[9px] text-muted pt-1 border-t border-border-light">
                                            {{ __('plans.expires') }}: {{ $org->plan_expires_at->format('d/m/Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$org->status->color()" size="sm">{{ $org->status->label() }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="text-xs text-subtle">
                            {{ $org->created_at->format('d/m/Y') }}
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button tag="a" href="{{ route('system.org.enter', $org->id) }}" variant="accent" icon="log-in" size="sm">{{ __('system.root_org_list.enter') }}</x-ui.button>

                                @if($org->status === \App\Enums\OrganizationStatus::ACTIVE)
                                    <x-ui.button variant="ghost" size="sm" icon="pause-circle"
                                        wire:click="toggleStatus('{{ $org->id }}')"
                                        wire:confirm="{{ __('system.root_org_list.confirm_suspend', ['name' => $org->name]) }}"
                                        title="{{ __('system.root_org_list.suspend') }}" />
                                @else
                                    <x-ui.button variant="ghost" size="sm" icon="play-circle"
                                        wire:click="toggleStatus('{{ $org->id }}')"
                                        title="{{ __('system.root_org_list.activate') }}" />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>

            <div class="px-6 py-4 border-t border-border-light">
                {{ $organizations->links() }}
            </div>
        @endif
    </x-ui.section>
</x-ui.page-layout>
