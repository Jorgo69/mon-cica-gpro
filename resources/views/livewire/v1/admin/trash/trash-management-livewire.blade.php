<x-ui.page-layout>

    <x-ui.page-header :title="__('admin.trash.title')" :subtitle="__('admin.trash.subtitle')" />

    <div class="space-y-6">

        {{-- Membres supprimés --}}
        <x-ui.section :title="__('admin.trash.members_section')" icon="user-x" :noPadding="false">
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead class="bg-surface-alt/50 bg-surface-alt/50">
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left w-8"><input type="checkbox" wire:model="selectAllUsers" class="rounded border-border text-accent focus:ring-accent"></th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.name') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest hidden md:table-cell">{{ __('admin.trash.email') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest hidden lg:table-cell">{{ __('admin.trash.role') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.deleted_at') }}</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @forelse ($trashedUsers as $user)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors">
                                <td class="px-6 py-3"><input type="checkbox" wire:model="selectedIds" value="{{ $user->id }}" class="rounded border-border text-accent focus:ring-accent"></td>
                                <td class="px-6 py-3 text-sm font-semibold text-heading">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-sm text-subtle hidden md:table-cell">{{ $user->email }}</td>
                                <td class="px-6 py-3 hidden lg:table-cell"><x-ui.badge variant="slate" size="sm">{{ $user->role }}</x-ui.badge></td>
                                <td class="px-6 py-3 text-sm text-subtle">{{ $user->deleted_at }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view','user','{{ $user->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="restore('{{ $user->id }}','user')" variant="ghost" icon="rotate-ccw" size="sm" class="text-success hover:bg-success/5" />
                                        <x-ui.button wire:click="openModal('delete','user','{{ $user->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><x-ui.empty-state icon="user-check" :title="__('admin.trash.no_members')" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-slot:footer>{{ $trashedUsers->links() }}</x-slot:footer>
        </x-ui.section>

        {{-- Types de projet supprimés --}}
        <x-ui.section :title="__('admin.trash.project_types_section')" icon="layout-grid" :noPadding="false">
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead class="bg-surface-alt/50 bg-surface-alt/50">
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left w-8"><input type="checkbox" wire:model="selectAllTypes" class="rounded border-border text-accent focus:ring-accent"></th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.name') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest hidden md:table-cell">{{ __('admin.trash.category') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.deleted_at') }}</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @forelse ($trashedProjectTypes as $type)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors">
                                <td class="px-6 py-3"><input type="checkbox" wire:model="selectedIds" value="{{ $type->id }}" class="rounded border-border text-accent focus:ring-accent"></td>
                                <td class="px-6 py-3 text-sm font-semibold text-heading">{{ $type->name }}</td>
                                <td class="px-6 py-3 text-sm text-subtle hidden md:table-cell">{{ $type->category }}</td>
                                <td class="px-6 py-3 text-sm text-subtle">{{ $type->deleted_at }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view','project_type','{{ $type->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="restore('{{ $type->id }}','project_type')" variant="ghost" icon="rotate-ccw" size="sm" class="text-success hover:bg-success/5" />
                                        <x-ui.button wire:click="openModal('delete','project_type','{{ $type->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><x-ui.empty-state icon="check-circle" :title="__('admin.trash.no_types')" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-slot:footer>{{ $trashedProjectTypes->links() }}</x-slot:footer>
        </x-ui.section>

        {{-- Projets supprimés --}}
        <x-ui.section :title="__('admin.trash.projects_section')" icon="folder-minus" :noPadding="false">
            <div class="overflow-x-auto -mx-6">
                <table class="w-full">
                    <thead class="bg-surface-alt/50 bg-surface-alt/50">
                        <tr class="border-b border-border-light dark:border-surface-alt">
                            <th class="px-6 py-3 text-left w-8"><input type="checkbox" wire:model="selectAllProjects" class="rounded border-border text-accent focus:ring-accent"></th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.title_col') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest hidden md:table-cell">{{ __('admin.trash.code') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest hidden lg:table-cell">{{ __('admin.trash.status') }}</th>
                            <th class="px-6 py-3 text-left text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.deleted_at') }}</th>
                            <th class="px-6 py-3 text-right text-[10px] font-black text-muted uppercase tracking-widest">{{ __('admin.trash.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light dark:divide-surface-alt/50">
                        @forelse ($trashedProjects as $project)
                            <tr class="hover:bg-surface/50 dark:hover:bg-surface-alt/30 transition-colors">
                                <td class="px-6 py-3"><input type="checkbox" wire:model="selectedIds" value="{{ $project->id }}" class="rounded border-border text-accent focus:ring-accent"></td>
                                <td class="px-6 py-3 text-sm font-semibold text-heading">{{ $project->title }}</td>
                                <td class="px-6 py-3 hidden md:table-cell"><span class="text-xs font-mono font-bold text-subtle bg-surface-alt px-2 py-0.5 rounded-lg">{{ $project->project_code }}</span></td>
                                <td class="px-6 py-3 hidden lg:table-cell">
                                    @php
                                        $statusEnum = $project->status instanceof \App\Enums\ProjectStatus ? $project->status : \App\Enums\ProjectStatus::tryFrom($project->status);
                                        $variant = $statusEnum ? $statusEnum->color() : 'slate';
                                    @endphp
                                    <x-ui.badge :variant="$variant" size="sm">{{ $statusEnum ? $statusEnum->label() : $project->status }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-3 text-sm text-subtle">{{ $project->deleted_at }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <x-ui.button wire:click="openModal('view','project','{{ $project->id }}')" variant="ghost" icon="eye" size="sm" />
                                        <x-ui.button wire:click="restore('{{ $project->id }}','project')" variant="ghost" icon="rotate-ccw" size="sm" class="text-success hover:bg-success/5" />
                                        <x-ui.button wire:click="openModal('delete','project','{{ $project->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><x-ui.empty-state icon="check-circle" :title="__('admin.trash.no_projects')" /></td></tr>
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
                     :title="$modalType === 'view' ? __('admin.trash.view_details') : __('admin.trash.permanent_delete')"
                     wire:close="$set('showModal', false)">
            @if ($modalType === 'view' && $selectedItem)
                <div class="space-y-2">
                    @foreach ((array) $selectedItem->toArray() as $key => $val)
                        @if(!is_array($val) && $val)
                        <div class="flex items-start gap-2 text-sm">
                            <span class="text-[11px] font-bold text-muted uppercase tracking-wider w-36 shrink-0">{{ str_replace('_', ' ', ucfirst($key)) }}</span>
                            <span class="text-body break-all">{{ $val }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>

            @elseif ($modalType === 'delete' && $selectedItem)
                <div class="text-center py-4">
                    <div class="w-12 h-12 rounded-xl bg-error/10 flex items-center justify-center mx-auto mb-4">
                        <x-lucide-alert-triangle class="w-6 h-6 text-error" />
                    </div>
                    <p class="text-sm text-body mb-2">{!! __('admin.trash.irreversible') !!}</p>
                    <p class="text-sm text-subtle">{{ __('admin.trash.confirm_permanent_delete') }}</p>
                </div>
                <x-slot:footer>
                    <x-ui.button wire:click="$set('showModal', false)" variant="outline" size="sm">{{ __('common.cancel') }}</x-ui.button>
                    <x-ui.button wire:click="forceDelete('{{ $selectedItem->id }}','{{ $selectedModel }}')" variant="danger" icon="trash-2" size="sm">{{ __('common.delete_permanently') }}</x-ui.button>
                </x-slot:footer>
            @endif
        </x-ui.modal>
    @endif

</x-ui.page-layout>