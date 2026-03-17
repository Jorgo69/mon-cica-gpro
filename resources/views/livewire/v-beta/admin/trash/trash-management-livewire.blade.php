<x-ui.page-layout>

    <x-ui.page-header title="Corbeille" subtitle="Gérez les éléments supprimés — restaurez ou supprimez définitivement" />

    <div class="space-y-6">

        {{-- Membres supprimés --}}
        <x-ui.section title="Membres supprimés" icon="user-x" :noPadding="false">
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-3 text-left w-8"><input type="checkbox" wire:model="selectAllUsers" class="rounded border-slate-300 text-accent focus:ring-accent"></th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nom</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Email</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Rôle</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Supprimé le</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse ($trashedUsers as $user)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-3"><input type="checkbox" wire:model="selectedIds" value="{{ $user->id }}" class="rounded border-slate-300 text-accent focus:ring-accent"></td>
                                <td class="px-6 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-6 py-3 hidden lg:table-cell"><x-ui.badge variant="slate" size="sm">{{ $user->role }}</x-ui.badge></td>
                                <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $user->deleted_at }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view','user','{{ $user->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="restore('{{ $user->id }}','user')" variant="ghost" icon="rotate-ccw" size="sm" class="text-success hover:bg-success/5" />
                                        <x-ui.button wire:click="openModal('delete','user','{{ $user->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><x-ui.empty-state icon="user-check" title="Aucun membre supprimé" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-slot:footer>{{ $trashedUsers->links() }}</x-slot:footer>
        </x-ui.section>

        {{-- Types de projet supprimés --}}
        <x-ui.section title="Types de projet supprimés" icon="layout-grid" :noPadding="false">
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-3 text-left w-8"><input type="checkbox" wire:model="selectAllTypes" class="rounded border-slate-300 text-accent focus:ring-accent"></th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Nom</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Catégorie</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Supprimé le</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse ($trashedProjectTypes as $type)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-3"><input type="checkbox" wire:model="selectedIds" value="{{ $type->id }}" class="rounded border-slate-300 text-accent focus:ring-accent"></td>
                                <td class="px-6 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $type->name }}</td>
                                <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400 hidden md:table-cell">{{ $type->category }}</td>
                                <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $type->deleted_at }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view','project_type','{{ $type->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="restore('{{ $type->id }}','project_type')" variant="ghost" icon="rotate-ccw" size="sm" class="text-success hover:bg-success/5" />
                                        <x-ui.button wire:click="openModal('delete','project_type','{{ $type->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><x-ui.empty-state icon="check-circle" title="Aucun type supprimé" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-slot:footer>{{ $trashedProjectTypes->links() }}</x-slot:footer>
        </x-ui.section>

        {{-- Projets supprimés --}}
        <x-ui.section title="Projets supprimés" icon="folder-minus" :noPadding="false">
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800">
                            <th class="px-6 py-3 text-left w-8"><input type="checkbox" wire:model="selectAllProjects" class="rounded border-slate-300 text-accent focus:ring-accent"></th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Titre</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden md:table-cell">Code</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest hidden lg:table-cell">Statut</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Supprimé le</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                        @forelse ($trashedProjects as $project)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-3"><input type="checkbox" wire:model="selectedIds" value="{{ $project->id }}" class="rounded border-slate-300 text-accent focus:ring-accent"></td>
                                <td class="px-6 py-3 text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $project->title }}</td>
                                <td class="px-6 py-3 hidden md:table-cell"><span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-lg">{{ $project->project_code }}</span></td>
                                <td class="px-6 py-3 hidden lg:table-cell">
                                    @php
                                        $statusEnum = $project->status instanceof \App\Enums\ProjectStatus ? $project->status : \App\Enums\ProjectStatus::tryFrom($project->status);
                                        $variant = $statusEnum ? $statusEnum->color() : 'slate';
                                    @endphp
                                    <x-ui.badge :variant="$variant" size="sm">{{ $statusEnum ? $statusEnum->label() : $project->status }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $project->deleted_at }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view','project','{{ $project->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="restore('{{ $project->id }}','project')" variant="ghost" icon="rotate-ccw" size="sm" class="text-success hover:bg-success/5" />
                                        <x-ui.button wire:click="openModal('delete','project','{{ $project->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><x-ui.empty-state icon="check-circle" title="Aucun projet supprimé" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-slot:footer>{{ $trashedProjects->links() }}</x-slot:footer>
        </x-ui.section>

    </div>

    {{-- Modal --}}
    @if ($showModal)
        <x-ui.modal :show="$showModal"
                     :title="$modalType === 'view' ? 'Détails de l\'élément' : 'Suppression définitive'"
                     wire:close="$set('showModal', false)">
            @if ($modalType === 'view' && $selectedItem)
                <div class="space-y-2">
                    @foreach ((array) $selectedItem->toArray() as $key => $val)
                        @if(!is_array($val) && $val)
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider w-36 shrink-0">{{ str_replace('_', ' ', ucfirst($key)) }}</span>
                            <span class="text-slate-600 dark:text-slate-300 break-all">{{ $val }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>

            @elseif ($modalType === 'delete' && $selectedItem)
                <div class="text-center py-4">
                    <div class="w-12 h-12 rounded-xl bg-error/10 flex items-center justify-center mx-auto mb-4">
                        <x-lucide-alert-triangle class="w-6 h-6 text-error" />
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">Cette action est <strong class="text-error">irréversible</strong>.</p>
                    <p class="text-sm text-slate-500">Voulez-vous supprimer définitivement cet élément ?</p>
                </div>
                <x-slot:footer>
                    <x-ui.button wire:click="$set('showModal', false)" variant="outline" size="sm">Annuler</x-ui.button>
                    <x-ui.button wire:click="forceDelete('{{ $selectedItem->id }}','{{ $selectedModel }}')" variant="danger" icon="trash-2" size="sm">Supprimer définitivement</x-ui.button>
                </x-slot:footer>
            @endif
        </x-ui.modal>
    @endif

</x-ui.page-layout>