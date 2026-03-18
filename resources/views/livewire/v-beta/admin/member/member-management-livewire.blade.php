<x-ui.page-layout>

    <x-ui.page-header title="Gestion des Membres" subtitle="Ajoutez, modifiez et gérez les membres de votre équipe">
        <x-slot:actions>
            <x-ui.button wire:click="openModal('create')" variant="accent" icon="user-plus" loadingText="Chargement...">
                Ajouter un membre
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Search --}}
    <div class="mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher un membre..." icon="search" />
    </div>

    @include('messages.index')

    {{-- Table --}}
    <x-ui.section title="Membres" icon="users" :noPadding="true">
        @if ($members->isEmpty())
            <x-ui.empty-state icon="users" title="Aucun membre trouvé" description="Ajoutez votre premier membre d'équipe." />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th class="cursor-pointer group" wire:click="sortBy('name')">
                        <div class="flex items-center gap-1">
                            Nom
                            @if ($sortField === 'name')
                                <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                            @endif
                        </div>
                    </x-ui.table.th>
                    <x-ui.table.th>Email</x-ui.table.th>
                    <x-ui.table.th class="hidden lg:table-cell">Téléphone</x-ui.table.th>
                    <x-ui.table.th>Rôle</x-ui.table.th>
                    <x-ui.table.th class="hidden md:table-cell">Département</x-ui.table.th>
                    <x-ui.table.th class="hidden xl:table-cell">Pays</x-ui.table.th>
                    <x-ui.table.th class="hidden xl:table-cell">Ville</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach ($members as $member)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center text-accent font-bold text-[10px]">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $member->name }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>{{ $member->email }}</x-ui.table.td>
                        <x-ui.table.td class="hidden lg:table-cell">{{ $member->telephone ?? 'N/A' }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$member->role?->color() ?? 'slate'" size="sm">
                                {{ $member->role?->label() ?? $member->role }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="hidden md:table-cell">{{ $member->department }}</x-ui.table.td>
                        <x-ui.table.td class="hidden xl:table-cell">{{ $member->pays ?? 'N/A' }}</x-ui.table.td>
                        <x-ui.table.td class="hidden xl:table-cell">{{ $member->ville ?? 'N/A' }}</x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button wire:click="openModal('view', '{{ $member->id }}')" variant="ghost" icon="eye" size="sm" />
                                <x-ui.button wire:click="openModal('edit', '{{ $member->id }}')" variant="ghost" icon="pencil" size="sm" />
                                <x-ui.button wire:click="openModal('delete', '{{ $member->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>
        @endif

        @if($members->isNotEmpty())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
                {{ $members->links() }}
            </div>
        @endif
    </x-ui.section>

    {{-- Modal : Livewire contrôle la présence via @if --}}
    @if($showModal)
        <x-ui.modal :show="true" 
                    :title="match($modalType) { 'create' => 'Ajouter un membre', 'edit' => 'Modifier le membre', 'view' => 'Détails du membre', 'delete' => 'Confirmer la suppression', default => 'Membre' }"
                    :dismissable="$modalType !== 'delete'"
                    id="member-management">

            @if ($modalType === 'create')
                @include('livewire.v-beta.admin.member.partials.form', ['action' => 'store'])

            @elseif ($modalType === 'edit')
                @include('livewire.v-beta.admin.member.partials.form', ['action' => 'update'])

            @elseif ($modalType === 'view')
                <div class="space-y-4 text-sm">
                    @foreach([
                        ['Nom', $name, 'user'], 
                        ['Email', $email, 'mail'], 
                        ['Téléphone', $telephone, 'phone'], 
                        ['Rôle', $role instanceof \App\Enums\AccountType ? $role->label() : $role, 'shield'], 
                        ['Département', $department, 'building'], 
                        ['Pays', $pays, 'globe'], 
                        ['Ville', $ville, 'map-pin']
                    ] as [$label, $val, $icon])
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 p-1.5 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-400">
                                <x-dynamic-component :component="'lucide-' . $icon" class="w-3.5 h-3.5" />
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                                <span class="text-[13px] font-semibold text-slate-700 dark:text-slate-200">{{ $val ?? 'Non renseigné' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            @elseif ($modalType === 'delete')
                <div class="text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-error/10 text-error flex items-center justify-center mx-auto mb-4">
                        <x-lucide-alert-triangle class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Supprimer le membre ?</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Voulez-vous vraiment supprimer <strong class="text-error">{{ $name }}</strong> ? Cette action est irréversible.</p>
                </div>
                <x-slot:footer>
                    <x-ui.button wire:click="closeModal" variant="ghost">Annuler</x-ui.button>
                    <x-ui.button wire:click="delete" variant="danger" icon="trash-2" loadingText="Suppression...">Supprimer définitivement</x-ui.button>
                </x-slot:footer>
            @endif
        </x-ui.modal>
    @endif

</x-ui.page-layout>
