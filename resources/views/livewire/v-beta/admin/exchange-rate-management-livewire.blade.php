<x-ui.page-layout>

    <x-ui.page-header title="Taux de change" subtitle="Gerez les taux de conversion entre devises">
        <x-slot:actions>
            <x-ui.button wire:click="openModal" variant="accent" icon="plus" size="lg">
                Nouveau taux
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Recherche --}}
    <x-ui.card class="mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher par devise..." icon="search" />
    </x-ui.card>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal :show="true" :title="$editingId ? 'Modifier le taux' : 'Nouveau taux de change'" id="exchange-rate-modal">
            <form wire:submit="save" class="space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Devise source</label>
                        <select wire:model="base_currency"
                            class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                            @foreach($currencies as $c)
                                <option value="{{ $c->value }}">{{ $c->value }} - {{ $c->label() }}</option>
                            @endforeach
                        </select>
                        @error('base_currency') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Devise cible</label>
                        <select wire:model="target_currency"
                            class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                            @foreach($currencies as $c)
                                <option value="{{ $c->value }}">{{ $c->value }} - {{ $c->label() }}</option>
                            @endforeach
                        </select>
                        @error('target_currency') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-ui.input label="Taux" wire:model="rate" type="number" step="0.000001" placeholder="655.957" icon="trending-up" :error="$errors->first('rate')" required />
                    <x-ui.input label="Date d'effet" wire:model="effective_date" type="date" icon="calendar" :error="$errors->first('effective_date')" required />
                </div>

                <x-slot:footer>
                    <x-ui.button wire:click="$set('showModal', false)" variant="outline" size="sm">Annuler</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="check" size="sm">
                        {{ $editingId ? 'Mettre a jour' : 'Ajouter' }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif

    {{-- Table --}}
    <x-ui.section title="Taux enregistres" icon="bar-chart-3" :noPadding="true">
        @if($rates->isEmpty())
            <x-ui.empty-state icon="bar-chart-3" title="Aucun taux de change" description="Ajoutez votre premier taux de conversion." />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Source</x-ui.table.th>
                    <x-ui.table.th>Cible</x-ui.table.th>
                    <x-ui.table.th>Taux</x-ui.table.th>
                    <x-ui.table.th>Date d'effet</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach($rates as $rate)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <x-ui.badge variant="accent" size="sm">{{ $rate->base_currency->value }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="slate" size="sm">{{ $rate->target_currency->value }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="font-mono font-bold text-heading">{{ number_format((float) $rate->rate, 6, '.', ' ') }}</x-ui.table.td>
                        <x-ui.table.td class="text-subtle">{{ $rate->effective_date->format('d/m/Y') }}</x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button wire:click="openModal('{{ $rate->id }}')" variant="ghost" icon="pencil" size="sm" />
                                <x-ui.button wire:click="delete('{{ $rate->id }}')" wire:confirm="Supprimer ce taux ?" variant="ghost" icon="trash-2" size="sm" class="text-error hover:text-error" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>
        @endif

        @if($rates->isNotEmpty())
            <div class="px-6 py-4 border-t border-border-light">
                {{ $rates->links() }}
            </div>
        @endif
    </x-ui.section>

</x-ui.page-layout>
