<div>
    <x-ui.page-layout>
        <x-ui.page-header title="Emails - Liste de suppression" icon="mail-x" description="Gestion des emails bloques (bounce, desabonnement, plainte)." />

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <x-ui.stat-card :value="$totalBounced" label="Bounced" icon="alert-triangle" variant="error" />
            <x-ui.stat-card :value="$totalUnsubscribed" label="Desabonnes" icon="user-minus" variant="warning" />
            <x-ui.stat-card :value="$totalComplained" label="Plaintes" icon="flag" variant="error" />
        </div>

        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between mb-6">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher un email..."
                    icon="search"
                />
            </div>
            <div class="flex items-center gap-3">
                <select wire:model.live="reasonFilter"
                    class="block border-border bg-card text-heading rounded-xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent text-xs py-2.5 px-3 transition-all">
                    <option value="">Toutes les raisons</option>
                    <option value="bounced">Bounced</option>
                    <option value="unsubscribed">Desabonne</option>
                    <option value="complained">Plainte</option>
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
                    <option value="bounced">Bounced</option>
                    <option value="unsubscribed">Desabonne</option>
                    <option value="complained">Plainte</option>
                </select>
            </div>
            <x-ui.button wire:click="addSuppression" icon="plus" size="sm" variant="accent">
                Ajouter
            </x-ui.button>
        </div>

        {{-- Table --}}
        <x-ui.section title="Emails supprimes" icon="list" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Email</x-ui.table.th>
                    <x-ui.table.th>Raison</x-ui.table.th>
                    <x-ui.table.th>Details</x-ui.table.th>
                    <x-ui.table.th>Date</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @forelse($suppressions as $suppression)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <span class="font-bold text-heading">{{ $suppression->email }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @switch($suppression->reason)
                                @case('bounced')
                                    <x-ui.badge variant="error">Bounced</x-ui.badge>
                                    @break
                                @case('unsubscribed')
                                    <x-ui.badge variant="warning">Desabonne</x-ui.badge>
                                    @break
                                @case('complained')
                                    <x-ui.badge variant="error">Plainte</x-ui.badge>
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
                                wire:confirm="Retirer cet email de la liste ?"
                                class="text-rose-500 hover:bg-rose-50"
                            >
                                Retirer
                            </x-ui.button>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="5" class="py-16 text-center">
                            <x-ui.empty-state icon="mail-check" title="Aucun email supprime" description="La liste de suppression est vide." />
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
