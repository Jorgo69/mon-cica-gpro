<div>
    <div class="flex flex-col gap-6">
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Filtrer les permissions..." 
                    icon="search" 
                />
            </div>
        </div>

        {{-- Table --}}
        <x-ui.section title="Clés de Droits d'Accès" icon="key" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Permission</x-ui.table.th>
                    <x-ui.table.th>Guard</x-ui.table.th>
                    <x-ui.table.th>Protection</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @forelse($permissions as $permission)
                    <x-ui.table.row>
                        <x-ui.table.td font="bold">
                            <code class="px-2.5 py-1 rounded-lg bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 font-mono text-[11px] tracking-tight">
                                {{ $permission->name }}
                            </code>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $permission->guard_name }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @php
                                $isProtected = in_array($permission->name, ['manage-users', 'manage-organization', 'access-admin-panel']);
                            @endphp
                            @if($isProtected)
                                <div class="flex items-center gap-1.5 text-rose-500">
                                    <x-lucide-lock class="w-3 h-3" />
                                    <span class="text-[10px] font-bold uppercase tracking-wider italic">Critique Système</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-emerald-500">
                                    <x-lucide-unlock class="w-3 h-3" />
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Libre</span>
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button variant="ghost" size="sm" icon="edit-2" wire:click="openPermissionModal('{{ $permission->id }}')" />
                                @if(!$isProtected)
                                    <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-rose-500/70 hover:text-rose-500" 
                                        wire:click="deletePermission('{{ $permission->id }}')"
                                        wire:confirm="Attention : la suppression d'une permission peut impacter les accès aux fonctionnalités. Confirmer la suppression ?" />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="4" class="py-16 text-center">
                            <x-ui.empty-state icon="key" title="Aucune permission" description="Aucune règle d'accès personnalisée n'est encore définie." />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>
            
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/30 dark:bg-slate-900/30 font-semibold italic text-[10px] text-slate-400">
                {{ $permissions->links() }}
            </div>
        </x-ui.section>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal 
            :show="true" 
            :title="$selectedPermission ? 'Éditer la Permission' : 'Nouvelle Règle d\'Accès'" 
            id="perm-modal"
        >
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <x-ui.input 
                        wire:model="name" 
                        label="Nom Identifiant (slug)" 
                        placeholder="Ex: rapport.valider" 
                        icon="key" 
                        required
                        :error="$errors->first('name')"
                    />
                    <p class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">
                        <x-lucide-info class="inline w-3 h-3 mr-1" />
                        Utilisez des points (.) ou des tirets (-) pour structurer vos noms technique de permission.
                    </p>
                </div>

                <x-slot:footer>
                    <x-ui.button variant="ghost" wire:click="closeModal">Annuler</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="check" loadingText="Sauvegarde...">
                        {{ $selectedPermission ? 'Mettre à jour' : 'Ajouter au système' }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif
</div>
