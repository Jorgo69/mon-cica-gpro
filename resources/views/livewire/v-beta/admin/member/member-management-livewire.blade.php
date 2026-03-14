<x-ui.page-layout>

    <x-ui.page-header title="Gestion des Membres" subtitle="Ajoutez, modifiez et gérez les membres de votre équipe">
        <x-slot:actions>
            <x-ui.button wire:click="openModal('create')" variant="accent" icon="user-plus" size="md">
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
    <x-ui.section title="Membres" icon="users" :noPadding="false">
        <div class="overflow-x-auto -mx-6">
            @if ($members->isEmpty())
                <x-ui.empty-state icon="users" title="Aucun membre trouvé" description="Ajoutez votre premier membre d'équipe." />
            @else
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer group" wire:click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    Nom
                                    @if ($sortField === 'name')
                                        <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Téléphone</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Rôle</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Département</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden xl:table-cell">Pays</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden xl:table-cell">Ville</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @foreach ($members as $member)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center text-accent font-bold text-[10px]">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $member->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">{{ $member->email }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 hidden lg:table-cell">{{ $member->telephone ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :variant="$member->role?->color() ?? 'slate'" size="sm">
                                        {{ $member->role?->label() ?? $member->role }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 hidden md:table-cell">{{ $member->department }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 hidden xl:table-cell">{{ $member->pays ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 hidden xl:table-cell">{{ $member->ville ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view', '{{ $member->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="openModal('edit', '{{ $member->id }}')" variant="ghost" icon="pencil" size="sm" />
                                        <x-ui.button wire:click="openModal('delete', '{{ $member->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if($members->isNotEmpty())
            <x-slot:footer>
                {{ $members->links() }}
            </x-slot:footer>
        @endif
    </x-ui.section>

    {{-- Modal --}}
    <x-ui.modal wire:show="showModal" 
                 :title="match($modalType) { 'create' => 'Ajouter un membre', 'edit' => 'Modifier le membre', 'view' => 'Détails du membre', 'delete' => 'Confirmer la suppression', default => 'Membre' }"
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
                <x-ui.button @click="show = false" variant="ghost" size="md">Annuler</x-ui.button>
                <x-ui.button wire:click="delete" variant="danger" icon="trash-2" size="md">Supprimer définitivement</x-ui.button>
            </x-slot:footer>
        @endif
    </x-ui.modal>

</x-ui.page-layout>
