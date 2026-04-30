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
                    <x-ui.table.th class="hidden md:table-cell">Localisation</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach ($members as $member)
                    @php
                        $pivotRole = $member->organizations()
                            ->where('organizations.id', session('current_organization_id'))
                            ->first()?->pivot?->role;
                        $roleEnum = $pivotRole ? \App\Enums\OrgMemberRole::tryFrom($pivotRole) : null;
                    @endphp
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center text-accent font-bold text-[10px]">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-heading">{{ $member->name }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>{{ $member->email }}</x-ui.table.td>
                        <x-ui.table.td class="hidden lg:table-cell">{{ $member->telephone ?? 'N/A' }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$roleEnum?->color() ?? 'slate'" size="sm">
                                {{ $roleEnum?->label() ?? 'Membre' }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="hidden md:table-cell">
                            {{ $member->location['ville'] ?? $member->country ?? 'N/A' }}
                        </x-ui.table.td>
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
            <div class="px-6 py-4 border-t border-border-light dark:border-surface-alt">
                {{ $members->links() }}
            </div>
        @endif
    </x-ui.section>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal :show="true"
                    :title="match($modalType) { 'create' => 'Ajouter un membre', 'edit' => 'Modifier le membre', 'view' => 'Détails du membre', 'delete' => 'Confirmer la suppression', default => 'Membre' }"
                    :dismissable="$modalType !== 'delete'"
                    id="member-management">

            @if ($modalType === 'create')
                @include('livewire.admin.member.partials.form', ['action' => 'store'])

            @elseif ($modalType === 'edit')
                @include('livewire.admin.member.partials.form', ['action' => 'update'])

            @elseif ($modalType === 'view')
                <div class="space-y-4 text-sm">
                    @php
                        $viewRole = $org_role ? \App\Enums\OrgMemberRole::tryFrom($org_role)?->label() : null;
                    @endphp
                    @foreach([
                        ['Nom', $name, 'user'],
                        ['Email', $email, 'mail'],
                        ['Téléphone', $telephone, 'phone'],
                        ['Rôle dans l\'org', $viewRole ?? 'Membre', 'shield'],
                        ['Rôle Spatie', $spatie_role, 'key'],
                        ['N° Identification', $numero_identification, 'id-card'],
                        ['Pays', $country, 'globe'],
                        ['Ville', $ville, 'map-pin'],
                    ] as [$label, $val, $icon])
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 p-1.5 rounded-lg bg-surface text-muted">
                                <x-dynamic-component :component="'lucide-' . $icon" class="w-3.5 h-3.5" />
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ $label }}</span>
                                <span class="text-[13px] font-semibold text-body">{{ $val ?? 'Non renseigné' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            @elseif ($modalType === 'delete')
                <div class="text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-error/10 text-error flex items-center justify-center mx-auto mb-4">
                        <x-lucide-alert-triangle class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-bold text-heading mb-2">Supprimer le membre ?</h3>
                    <p class="text-sm text-subtle">Voulez-vous vraiment supprimer <strong class="text-error">{{ $name }}</strong> ? Cette action est irréversible.</p>
                </div>
                <x-slot:footer>
                    <x-ui.button wire:click="closeModal" variant="ghost">Annuler</x-ui.button>
                    <x-ui.button wire:click="delete" variant="danger" icon="trash-2" loadingText="Suppression...">Supprimer définitivement</x-ui.button>
                </x-slot:footer>
            @endif
        </x-ui.modal>
    @endif

</x-ui.page-layout>
