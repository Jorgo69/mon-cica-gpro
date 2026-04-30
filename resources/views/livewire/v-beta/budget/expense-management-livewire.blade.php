<div class="space-y-6">
    {{-- Summary cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="p-4 bg-card border border-border-light rounded-2xl">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Budget planifie</span>
            <span class="text-lg font-bold text-heading"><x-currency :amount="$summary['planned']" :currency="$project->currency" /></span>
        </div>
        <div class="p-4 bg-card border border-border-light rounded-2xl">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Depense</span>
            <span class="text-lg font-bold {{ $summary['is_over_budget'] ? 'text-error' : 'text-heading' }}"><x-currency :amount="$summary['spent']" :currency="$project->currency" /></span>
        </div>
        <div class="p-4 bg-card border border-border-light rounded-2xl">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Restant</span>
            <span class="text-lg font-bold {{ $summary['remaining'] < 0 ? 'text-error' : 'text-success' }}"><x-currency :amount="$summary['remaining']" :currency="$project->currency" /></span>
        </div>
        <div class="p-4 bg-card border border-border-light rounded-2xl">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Consommation</span>
            <div class="flex items-end gap-2">
                <span class="text-lg font-bold {{ $summary['used_percent'] >= 100 ? 'text-error' : ($summary['used_percent'] >= 80 ? 'text-warning' : 'text-heading') }}">{{ $summary['used_percent'] }}%</span>
            </div>
            <div class="w-full bg-surface-alt rounded-full h-1.5 mt-2">
                <div class="h-1.5 rounded-full transition-all {{ $summary['used_percent'] >= 100 ? 'bg-error' : ($summary['used_percent'] >= 80 ? 'bg-warning' : 'bg-accent') }}"
                    style="width: {{ min($summary['used_percent'], 100) }}%"></div>
            </div>
        </div>
    </div>

    {{-- Burn rate --}}
    @if($burnRate['daily'] > 0)
    <div class="grid grid-cols-3 gap-4">
        <div class="p-3 bg-surface rounded-xl border border-border-light text-center">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Taux journalier</span>
            <span class="text-sm font-bold text-heading"><x-currency :amount="$burnRate['daily']" :currency="$project->currency" /></span>
        </div>
        <div class="p-3 bg-surface rounded-xl border border-border-light text-center">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Taux mensuel</span>
            <span class="text-sm font-bold text-heading"><x-currency :amount="$burnRate['monthly']" :currency="$project->currency" /></span>
        </div>
        <div class="p-3 bg-surface rounded-xl border border-border-light text-center">
            <span class="text-[9px] font-black text-muted uppercase tracking-widest block mb-1">Projection fin projet</span>
            <span class="text-sm font-bold {{ $burnRate['projected_total'] > $summary['planned'] ? 'text-error' : 'text-heading' }}"><x-currency :amount="$burnRate['projected_total']" :currency="$project->currency" /></span>
        </div>
    </div>
    @endif

    {{-- Budget lines status --}}
    @if(count($lineSummaries) > 0)
    <x-ui.section title="Lignes budgetaires" icon="layers" :noPadding="true">
        <div class="divide-y divide-border-light">
            @foreach($lineSummaries as $line)
                <div class="px-6 py-3 flex items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-body truncate">{{ $line['budget']->description }}</p>
                        <div class="w-full bg-surface-alt rounded-full h-1 mt-1.5">
                            <div class="h-1 rounded-full {{ $line['status'] === 'over' ? 'bg-error' : ($line['status'] === 'warning' ? 'bg-warning' : 'bg-accent') }}"
                                style="width: {{ min($line['used_percent'], 100) }}%"></div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-xs font-bold {{ $line['status'] === 'over' ? 'text-error' : 'text-heading' }}">{{ $line['used_percent'] }}%</span>
                        <p class="text-[10px] text-muted"><x-currency :amount="$line['spent']" :currency="$project->currency" /> / <x-currency :amount="$line['planned']" :currency="$project->currency" /></p>
                    </div>
                </div>
            @endforeach
        </div>
    </x-ui.section>
    @endif

    {{-- Expenses list --}}
    <x-ui.section title="Depenses" icon="receipt" :noPadding="true">
        <x-slot:actions>
            <x-ui.button wire:click="openModal" variant="accent" icon="plus" size="sm">
                Nouvelle depense
            </x-ui.button>
        </x-slot:actions>

        @if($expenses->isEmpty())
            <x-ui.empty-state icon="receipt" title="Aucune depense" description="Enregistrez votre premiere depense." />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Date</x-ui.table.th>
                    <x-ui.table.th>Description</x-ui.table.th>
                    <x-ui.table.th>Ligne budget</x-ui.table.th>
                    <x-ui.table.th>Montant</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach($expenses as $expense)
                    <x-ui.table.row>
                        <x-ui.table.td class="text-subtle text-xs">{{ $expense->expense_date->format('d/m/Y') }}</x-ui.table.td>
                        <x-ui.table.td>
                            <p class="text-xs font-bold text-body">{{ $expense->description }}</p>
                            @if($expense->reference)
                                <p class="text-[10px] text-muted">Ref: {{ $expense->reference }}</p>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td class="text-xs text-subtle">{{ $expense->budget?->description ?? '—' }}</x-ui.table.td>
                        <x-ui.table.td class="font-bold text-heading"><x-currency :amount="$expense->amount" :currency="$project->currency" /></x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button wire:click="openModal('{{ $expense->id }}')" variant="ghost" icon="pencil" size="sm" />
                                <x-ui.button wire:click="delete('{{ $expense->id }}')" wire:confirm="Supprimer cette depense ?" variant="ghost" icon="trash-2" size="sm" class="text-error" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>
        @endif

        @if($expenses->isNotEmpty())
            <div class="px-6 py-4 border-t border-border-light">
                {{ $expenses->links() }}
            </div>
        @endif
    </x-ui.section>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal :show="true" :title="$editingId ? 'Modifier la depense' : 'Nouvelle depense'" id="expense-modal">
            <form wire:submit="save" class="space-y-4">
                <x-ui.input label="Description" wire:model="description" icon="file-text" :error="$errors->first('description')" required />

                <div class="grid grid-cols-2 gap-4">
                    <x-ui.input label="Montant" wire:model="amount" type="number" step="0.01" icon="wallet" :error="$errors->first('amount')" required />
                    <x-ui.input label="Date" wire:model="expense_date" type="date" icon="calendar" :error="$errors->first('expense_date')" required />
                </div>

                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Ligne budgetaire</label>
                    <select wire:model="budget_id"
                        class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all">
                        <option value="">— Non rattachee —</option>
                        @foreach($budgets as $b)
                            <option value="{{ $b->id }}">{{ $b->description }} (<x-currency :amount="$b->total_cost" :currency="$project->currency" />)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-ui.input label="Categorie" wire:model="category" icon="tag" :error="$errors->first('category')" />
                    <x-ui.input label="Reference" wire:model="reference" icon="hash" placeholder="N° facture..." :error="$errors->first('reference')" />
                </div>

                <div>
                    <label class="text-xs font-bold text-heading uppercase tracking-wider block mb-2">Notes</label>
                    <textarea wire:model="notes" rows="2" class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all resize-none"></textarea>
                </div>

                <x-slot:footer>
                    <x-ui.button wire:click="$set('showModal', false)" variant="outline" size="sm">Annuler</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="check" size="sm">
                        {{ $editingId ? 'Mettre a jour' : 'Enregistrer' }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif
</div>
