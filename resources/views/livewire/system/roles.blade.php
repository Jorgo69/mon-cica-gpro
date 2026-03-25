<div>
    <div class="flex flex-col gap-6">
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Rechercher un rôle..." 
                    icon="search" 
                />
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <x-ui.select wire:model.live="organizationId" icon="building">
                    <option value="">-- Tous les rôles --</option>
                    <option value="null">Rôles Système (Globaux)</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>

        {{-- Table --}}
        <x-ui.section title="Rôles & Accès" icon="shield" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Rôle</x-ui.table.th>
                    <x-ui.table.th>Domaine / Organisation</x-ui.table.th>
                    <x-ui.table.th>Permissions</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @forelse($roles as $role)
                    <x-ui.table.row>
                        <x-ui.table.td font="bold">
                            <div class="flex items-center gap-2">
                                <span class="text-slate-800 dark:text-slate-100">{{ $role->name }}</span>
                                @if(in_array($role->name, ['IT_ADMIN', 'ORG_ADMIN']))
                                    <x-lucide-award class="w-3 h-3 text-amber-500" />
                                @endif
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @if($role->organization_id)
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $role->organization?->name ?? 'Org Inconnue' }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Global Système</span>
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-black bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded text-slate-500 uppercase tracking-tighter">
                                    {{ $role->permissions->count() }} Perms
                                </span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button variant="ghost" size="sm" icon="edit-3" wire:click="openRoleModal('{{ $role->id }}')" />
                                @if(!in_array($role->name, ['IT_ADMIN', 'ORG_ADMIN']))
                                    <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-rose-500" 
                                    wire:click="deleteRole('{{ $role->id }}')" 
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer ce rôle ? Cette action est irréversible et retirera ce rôle à tous les utilisateurs concernés." />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="4" class="py-16 text-center">
                            <x-ui.empty-state icon="shield-off" title="Aucun rôle" description="Aucun rôle ne correspond à vos filtres." />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>
            
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/30">
                {{ $roles->links() }}
            </div>
        </x-ui.section>
    </div>

    {{-- Modal Create/Edit --}}
    @if($showModal)
        <x-ui.modal 
            :show="true" 
            :title="$modalType === 'create' ? 'Nouveau Rôle Système' : 'Configuration du Rôle'"
            id="role-config-modal"
            maxWidth="max-w-4xl"
        >
            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input 
                        wire:model="name" 
                        label="Nom Identifiant du Rôle" 
                        placeholder="Ex: MANAGER_PROJET" 
                        icon="shield" 
                        required
                        :error="$errors->first('name')"
                    />

                    <x-ui.select 
                        wire:model="org_id" 
                        label="Organisation de Rattachement" 
                        icon="building"
                        :error="$errors->first('org_id')"
                    >
                        <option value="">-- Système Global (Cross-org) --</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-wider ml-1">
                        Attribution des Permissions
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 max-h-80 overflow-y-auto custom-scrollbar">
                        @foreach($permissions as $permission)
                            <label class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white dark:hover:bg-slate-900 transition-all cursor-pointer border border-transparent hover:border-slate-200 dark:hover:border-slate-800 shadow-sm hover:shadow-md group">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" wire:model="rolePermissions" value="{{ $permission->id }}" 
                                           class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent/20 transition-all">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 capitalize group-hover:text-accent transition-colors">
                                        {{ str_replace(['-', '_'], ' ', $permission->name) }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-medium uppercase tracking-tighter">
                                        {{ $permission->guard_name }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <x-slot:footer>
                    <x-ui.button variant="ghost" wire:click="closeModal">Annuler</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="save" loadingText="Enregistrement...">
                        {{ $modalType === 'create' ? 'Créer le rôle' : 'Enregistrer les modifications' }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif
</div>
