<div>
    <div class="flex flex-col gap-6">
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="{{ __('system.perm_management.search_placeholder') }}"
                    icon="search" 
                />
            </div>
        </div>

        {{-- Table --}}
        <x-ui.section :title="__('system.perm_management.section_title')" icon="key" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>{{ __('system.perm_management.permission') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.perm_management.guard') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.perm_management.protection') }}</x-ui.table.th>
                    <x-ui.table.th align="right">{{ __('common.actions') }}</x-ui.table.th>
                </x-slot:headers>

                @forelse($permissions as $permission)
                    <x-ui.table.row>
                        <x-ui.table.td font="bold">
                            <code class="px-2.5 py-1 rounded-lg bg-surface dark:bg-primary-dark border border-border-light dark:border-surface-alt text-body font-mono text-[11px] tracking-tight">
                                {{ $permission->name }}
                            </code>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ $permission->guard_name }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @php
                                $isProtected = in_array($permission->name, ['manage-users', 'manage-organization', 'access-admin-panel']);
                            @endphp
                            @if($isProtected)
                                <div class="flex items-center gap-1.5 text-rose-500">
                                    <x-lucide-lock class="w-3 h-3" />
                                    <span class="text-[10px] font-bold uppercase tracking-wider italic">{{ __('system.perm_management.critical') }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-success">
                                    <x-lucide-unlock class="w-3 h-3" />
                                    <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('system.perm_management.free') }}</span>
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button variant="ghost" size="sm" icon="edit-2" wire:click="openPermissionModal('{{ $permission->id }}')" />
                                @if(!$isProtected)
                                    <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-rose-500/70 hover:text-rose-500" 
                                        wire:click="deletePermission('{{ $permission->id }}')"
                                        wire:confirm="{{ __('system.perm_management.confirm_delete_perm') }}" />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="4" class="py-16 text-center">
                            <x-ui.empty-state icon="key" :title="__('system.perm_management.no_perm')" :description="__('system.perm_management.no_perm_desc')" />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>
            
            <div class="p-4 border-t border-border-light dark:border-surface-alt bg-surface/30 dark:bg-surface/30 font-semibold italic text-[10px] text-muted">
                {{ $permissions->links() }}
            </div>
        </x-ui.section>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <x-ui.modal 
            :show="true" 
            :title="$selectedPermission ? __('system.perm_management.modal_edit_title') : __('system.perm_management.modal_create_title')"
            id="perm-modal"
        >
            <form wire:submit.prevent="save" class="space-y-6">
                <div>
                    <x-ui.input 
                        wire:model="name" 
                        :label="__('system.perm_management.label_name')"
                        :placeholder="__('system.perm_management.placeholder_name')"
                        icon="key" 
                        required
                        :error="$errors->first('name')"
                    />
                    <p class="mt-2 text-[10px] text-subtle font-medium leading-relaxed">
                        <x-lucide-info class="inline w-3 h-3 mr-1" />
                        {{ __('system.perm_management.hint') }}
                    </p>
                </div>

                <x-slot:footer>
                    <x-ui.button variant="ghost" wire:click="closeModal">{{ __('common.cancel') }}</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="check" :loadingText="__('system.perm_management.saving')">
                        {{ $selectedPermission ? __('system.perm_management.update') : __('system.perm_management.add_to_system') }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif
</div>
