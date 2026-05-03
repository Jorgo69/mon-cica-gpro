<div>
    <div class="flex flex-col gap-6">
        {{-- Toolbar --}}
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-3 w-full md:w-96">
                <x-ui.input 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="{{ __('system.role_management.search_placeholder') }}"
                    icon="search" 
                />
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto">
                <x-ui.select wire:model.live="organizationId" icon="building">
                    <option value="">{{ __('system.role_management.all_roles') }}</option>
                    <option value="global">{{ __('system.role_management.global_roles') }}</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </x-ui.select>
            </div>
        </div>

        {{-- Table --}}
        <x-ui.section :title="__('system.role_management.section_title')" icon="shield" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>{{ __('system.role_management.role') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.role_management.scope') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('system.role_management.permissions') }}</x-ui.table.th>
                    <x-ui.table.th align="right">{{ __('common.actions') }}</x-ui.table.th>
                </x-slot:headers>

                @forelse($roles as $role)
                    <x-ui.table.row>
                        <x-ui.table.td font="bold">
                            <div class="flex items-center gap-2">
                                <span class="text-heading">{{ $role->name }}</span>
                                @if(in_array($role->name, ['IT_ADMIN', 'ORG_ADMIN']))
                                    <x-lucide-award class="w-3 h-3 text-warning" />
                                @endif
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @if($role->organization_id)
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                    <span class="text-xs font-bold text-body">{{ $role->organization?->name ?? __('system.role_management.org_unknown') }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">{{ __('system.role_management.global_system') }}</span>
                                </div>
                            @endif
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <div class="flex items-center gap-1.5">
                                <span class="text-[10px] font-black bg-surface-alt px-2 py-0.5 rounded text-subtle uppercase tracking-tighter">
                                    {{ __('system.role_management.perms_count', ['count' => $role->permissions->count()]) }}
                                </span>
                            </div>
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button variant="ghost" size="sm" icon="edit-3" wire:click="openRoleModal('{{ $role->id }}')" />
                                @if(!in_array($role->name, ['IT_ADMIN', 'ORG_ADMIN']))
                                    <x-ui.button variant="ghost" size="sm" icon="trash-2" class="text-rose-500" 
                                    wire:click="deleteRole('{{ $role->id }}')" 
                                    wire:confirm="{{ __('system.role_management.confirm_delete_role') }}" />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="4" class="py-16 text-center">
                            <x-ui.empty-state icon="shield-off" :title="__('system.role_management.no_role')" :description="__('system.role_management.no_role_desc')" />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>
            
            <div class="p-4 border-t border-border-light bg-surface/30 dark:bg-surface/30">
                {{ $roles->links() }}
            </div>
        </x-ui.section>
    </div>

    {{-- Modal Create/Edit --}}
    @if($showModal)
        <x-ui.modal 
            :show="true" 
            :title="$modalType === 'create' ? __('system.role_management.modal_create_title') : __('system.role_management.modal_edit_title')"
            id="role-config-modal"
            maxWidth="max-w-4xl"
        >
            <form wire:submit.prevent="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input 
                        wire:model="name" 
                        :label="__('system.role_management.label_name')"
                        :placeholder="__('system.role_management.placeholder_name')"
                        icon="shield" 
                        required
                        :error="$errors->first('name')"
                    />

                    <x-ui.select 
                        wire:model="org_id" 
                        :label="__('system.role_management.label_org')"
                        icon="building"
                        :error="$errors->first('org_id')"
                    >
                        <option value="">{{ __('system.role_management.option_global') }}</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-subtle uppercase tracking-wider ml-1">
                        {{ __('system.role_management.label_permissions') }}
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 p-4 rounded-2xl bg-surface dark:bg-primary-dark border border-border-light max-h-80 overflow-y-auto custom-scrollbar">
                        @foreach($permissions as $permission)
                            <label class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white dark:hover:bg-surface transition-all cursor-pointer border border-transparent hover:border-border shadow-sm hover:shadow-md group">
                                <div class="relative flex items-center justify-center">
                                    <input type="checkbox" wire:model="rolePermissions" value="{{ $permission->id }}" 
                                           class="w-4 h-4 rounded border-border text-accent focus:ring-accent/20 transition-all">
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-body capitalize group-hover:text-accent transition-colors">
                                        {{ str_replace(['-', '_'], ' ', $permission->name) }}
                                    </span>
                                    <span class="text-[9px] text-muted font-medium uppercase tracking-tighter">
                                        {{ $permission->guard_name }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <x-slot:footer>
                    <x-ui.button variant="ghost" wire:click="closeModal">{{ __('common.cancel') }}</x-ui.button>
                    <x-ui.button type="submit" variant="accent" icon="save" :loadingText="__('system.role_management.saving')">
                        {{ $modalType === 'create' ? __('system.role_management.create_role') : __('system.role_management.save_changes') }}
                    </x-ui.button>
                </x-slot:footer>
            </form>
        </x-ui.modal>
    @endif
</div>
