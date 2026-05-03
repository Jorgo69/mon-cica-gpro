<div>
    <x-ui.page-layout>
        <x-ui.page-header :title="__('system.email_suppression.title')" icon="mail-x" :description="__('system.email_suppression.description')" />

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <x-ui.stat-card :value="$totalBounced" :label="__('system.email_suppression.bounced')" icon="alert-triangle" variant="error" />
            <x-ui.stat-card :value="$totalUnsubscribed" :label="__('system.email_suppression.unsubscribed')" icon="user-minus" variant="warning" />
            <x-ui.stat-card :value="$totalComplained" :label="__('system.email_suppression.complained')" icon="flag" variant="error" />
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between mb-6">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input
                    wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('system.email_suppression.search_placeholder') }}"
                    icon="search"
                />
            </div>
            <div class="flex items-center gap-3">
                <select wire:model.live="reasonFilter"
                    class="block border-border bg-card text-heading rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-xs py-2.5 px-3 transition-all">
                    <option value="">{{ __('system.email_suppression.all_reasons') }}</option>
                    <option value="bounced">{{ __('system.email_suppression.bounced') }}</option>
                    <option value="unsubscribed">{{ __('system.email_suppression.unsubscribed') }}</option>
                    <option value="complained">{{ __('system.email_suppression.complained') }}</option>
                </select>
            </div>
        </div>

        {{-- Formulaire inline d'ajout --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-end gap-3 mb-6 p-4 rounded-xl bg-surface-alt/30 border border-border-light">
            <div class="flex-1 w-full sm:w-auto">
                <x-ui.input
                    wire:model="newEmail"
                    placeholder="email@example.com"
                    icon="mail"
                    :error="$errors->first('newEmail')"
                />
            </div>
            <div>
                <select wire:model="newReason"
                    class="block border-border bg-card text-heading rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-xs py-2.5 px-3 transition-all">
                    <option value="bounced">{{ __('system.email_suppression.bounced') }}</option>
                    <option value="unsubscribed">{{ __('system.email_suppression.unsubscribed') }}</option>
                    <option value="complained">{{ __('system.email_suppression.complained') }}</option>
                </select>
            </div>
            <x-ui.button wire:click="addSuppression" icon="plus" size="sm" variant="accent">
                {{ __('common.add') }}
            </x-ui.button>
        </div>

        {{-- Table --}}
        <x-ui.section :title="__('system.email_suppression.section_title')" icon="list" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>{{ __('system.email_suppression.email') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.email_suppression.reason') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.email_suppression.details') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('common.date') }}</x-ui.table.th>
                    <x-ui.table.th align="right">{{ __('common.actions') }}</x-ui.table.th>
                </x-slot:headers>

                @forelse($suppressions as $suppression)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <span class="font-bold text-heading">{{ $suppression->email }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @switch($suppression->reason)
                                @case('bounced')
                                    <x-ui.badge variant="error">{{ __('system.email_suppression.bounced') }}</x-ui.badge>
                                    @break
                                @case('unsubscribed')
                                    <x-ui.badge variant="warning">{{ __('system.email_suppression.unsubscribed') }}</x-ui.badge>
                                    @break
                                @case('complained')
                                    <x-ui.badge variant="error">{{ __('system.email_suppression.complained') }}</x-ui.badge>
                                    @break
                                @default
                                    <x-ui.badge variant="slate">{{ $suppression->reason }}</x-ui.badge>
                            @endswitch
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-xs text-muted">{{ $suppression->details ?? '-' }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-xs text-subtle">{{ $suppression->created_at->format('d/m/Y H:i') }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <x-ui.button
                                variant="ghost"
                                size="sm"
                                icon="x"
                                wire:click="unsuppress('{{ $suppression->id }}')"
                                wire:confirm="{{ __('system.email_suppression.confirm_remove') }}"
                                class="text-rose-500 hover:bg-rose-50"
                            >
                                {{ __('system.email_suppression.remove') }}
                            </x-ui.button>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="5" class="py-16 text-center">
                            <x-ui.empty-state icon="mail-check" :title="__('system.email_suppression.no_email')" :description="__('system.email_suppression.no_email_desc')" />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>

            <div class="p-4 border-t border-border-light dark:border-surface-alt bg-surface/20 dark:bg-surface/20">
                {{ $suppressions->links() }}
            </div>
        </x-ui.section>
    </x-ui.page-layout>
</div>
