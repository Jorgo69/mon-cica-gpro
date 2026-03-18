<div>
    <div class="flex flex-col gap-6">
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Rechercher une organisation..." 
                    icon="search" 
                />
            </div>
        </div>

        {{-- Table --}}
        <x-ui.section title="Unités & Organisations" icon="building" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Nom & Identifiant</x-ui.table.th>
                    <x-ui.table.th>Contact</x-ui.table.th>
                    <x-ui.table.th>Statistiques</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @forelse($organizations as $org)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <div class="flex flex-col">
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $org->name }}</span>
                                <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500 uppercase tracking-tight">Slug: {{ $org->slug }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $org->email ?? 'Aucun email' }}</span>
                                <span class="text-[10px] text-slate-400 font-medium italic">{{ $org->phone ?? 'Sans contact' }}</span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex items-center gap-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase tracking-tighter">
                                    {{ $org->users()->count() }} Membres
                                </span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button variant="ghost" size="sm" icon="edit" wire:click="openOrganizationModal('{{ $org->id }}')" />
                                @if($org->users()->count() === 0)
                                    <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-rose-500" 
                                        wire:click="deleteOrg('{{ $org->id }}')"
                                        wire:confirm="ACTION CRITIQUE : Souhaitez-vous vraiment supprimer définitivement cette organisation ? Toutes les données liées seront perdues." />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="4" class="py-16 text-center">
                            <x-ui.empty-state icon="building-2" title="Aucune organisation" description="Il n'y a pas encore d'organisations enregistrées dans le système." />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>
            
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/20 dark:bg-slate-900/20">
                {{ $organizations->links() }}
            </div>
        </x-ui.section>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal :show="true" :title="$selectedOrg ? 'Configuration de l\'Organisation' : 'Nouvelle Entité'" id="org-modal">
            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input 
                        wire:model.live="name" 
                        label="Désignation" 
                        placeholder="Ex: Direction Générale" 
                        icon="building" 
                        required
                        :error="$errors->first('name')"
                    />
                    <x-ui.input 
                        wire:model="slug" 
                        label="Identifiant unique (Slug)" 
                        placeholder="ex: direction-generale" 
                        icon="link" 
                        required
                        :error="$errors->first('slug')"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input 
                        wire:model="email" 
                        type="email"
                        label="Email professionnel" 
                        placeholder="admin@organisation.org" 
                        icon="mail" 
                        :error="$errors->first('email')"
                    />
                    <x-ui.input 
                        wire:model="phone" 
                        label="Ligne téléphonique" 
                        placeholder="+229 00 00 00 00" 
                        icon="phone" 
                        :error="$errors->first('phone')"
                    />
                </div>

                <x-ui.input 
                    wire:model="address" 
                    label="Siège social / Adresse" 
                    placeholder="Bénin, Cotonou..." 
                    icon="map-pin" 
                    :error="$errors->first('address')"
                />

                <div class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">Présentation</label>
                    <textarea wire:model="description" rows="3" class="block w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 rounded-2xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3.5 px-4 transition-all resize-none" placeholder="Brève description de l'organisation..."></textarea>
                </div>

                <x-slot:footer>
                    <x-ui.button variant="ghost" wire:click="closeModal">Annuler</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="check" loadingText="Enregistrement...">
                        {{ $selectedOrg ? 'Enregistrer les modifications' : 'Créer l\'organisation' }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif
</div>
