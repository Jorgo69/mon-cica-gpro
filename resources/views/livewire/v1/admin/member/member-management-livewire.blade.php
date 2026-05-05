<x-ui.page-layout>

    <x-ui.page-header :title="__('admin.members.title')" :subtitle="__('admin.members.subtitle')">
        <x-slot:actions>
            <x-ui.button wire:click="openModal('create')" variant="accent" icon="user-plus" :loadingText="__('common.loading')">
                {{ __('admin.members.add') }}
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Search --}}
    <div class="mb-6">
        <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('admin.members.search_placeholder')" icon="search" />
    </div>

    @include('messages.index')

    {{-- Table --}}
    <x-ui.section :title="__('admin.members.section')" icon="users" :noPadding="true">
        @if ($members->isEmpty())
            <x-ui.empty-state icon="users" :title="__('admin.members.no_members')" :description="__('admin.members.no_members_desc')" />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th class="cursor-pointer group" wire:click="sortBy('name')">
                        <div class="flex items-center gap-1">
                            {{ __('admin.members.name') }}
                            @if ($sortField === 'name')
                                <x-dynamic-component :component="'lucide-chevron-' . ($sortDirection === 'asc' ? 'up' : 'down')" class="w-3 h-3 text-accent" />
                            @endif
                        </div>
                    </x-ui.table.th>
                    <x-ui.table.th>{{ __('admin.members.email') }}</x-ui.table.th>
                    <x-ui.table.th class="hidden lg:table-cell">{{ __('admin.members.phone') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('admin.members.role') }}</x-ui.table.th>
                    <x-ui.table.th class="hidden md:table-cell">{{ __('admin.members.department') }}</x-ui.table.th>
                    <x-ui.table.th align="right">{{ __('admin.members.actions') }}</x-ui.table.th>
                </x-slot:headers>

                @foreach ($members as $member)
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
                        <x-ui.table.td class="hidden lg:table-cell">{{ $member->telephone ?? '—' }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$member->role?->color() ?? 'slate'" size="sm">
                                {{ $member->role?->label() ?? $member->role }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="hidden md:table-cell">{{ $member->department ?? '—' }}</x-ui.table.td>
                        <x-ui.table.td align="right">
                            @php $isSelf = $member->id === auth()->id(); @endphp
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button wire:click="openModal('view', '{{ $member->id }}')" variant="ghost" icon="eye" size="sm" />
                                @unless($isSelf)
                                    <x-ui.button wire:click="openModal('edit', '{{ $member->id }}')" variant="ghost" icon="pencil" size="sm" />
                                    <x-ui.button wire:click="openModal('delete', '{{ $member->id }}')" variant="ghost" icon="trash-2" size="sm" class="text-error hover:bg-error/5" />
                                @endunless
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

    {{-- Modal : Livewire contrôle la présence via @if --}}
    @if($showModal)
        <x-ui.modal :show="true" 
                    :title="match($modalType) { 'create' => __('admin.members.modal_add'), 'edit' => __('admin.members.modal_edit'), 'view' => __('admin.members.modal_details'), 'delete' => __('admin.members.modal_confirm_delete'), default => __('admin.members.section') }"
                    :dismissable="$modalType !== 'delete'"
                    id="member-management">

            @if ($modalType === 'create')
                @include('livewire.v1.admin.member.partials.form', ['action' => 'store'])

            @elseif ($modalType === 'edit')
                @include('livewire.v1.admin.member.partials.form', ['action' => 'update'])

                {{-- Permission customization --}}
                <div class="mt-6 pt-4 border-t border-border-light">
                    <button type="button" wire:click="$toggle('showPermissions')"
                            class="flex items-center gap-2 text-xs font-bold text-accent hover:underline">
                        <x-lucide-shield class="w-3.5 h-3.5" />
                        {{ __('admin.members.customize_permissions') }}
                        <x-lucide-chevron-down class="w-3 h-3 transition-transform" x-bind:class="{ 'rotate-180': $wire.showPermissions }" />
                    </button>

                    @if($showPermissions)
                    <div class="mt-4 space-y-4">
                        {{-- Level picker --}}
                        <div>
                            <label class="text-[10px] font-black text-muted uppercase tracking-widest block mb-2">{{ __('admin.members.permission_level') }}</label>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach($permissionLevels as $level)
                                    <button type="button" wire:click="$set('selectedPermissionLevel', {{ $level->value }})"
                                            class="p-2 rounded-xl border-2 text-center transition-all text-[10px] font-bold
                                                {{ $selectedPermissionLevel === $level->value
                                                    ? 'border-accent bg-accent/5 text-accent'
                                                    : 'border-border-light bg-card text-body hover:border-accent/30' }}">
                                        {{ $level->label() }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Permission checkboxes --}}
                        <div>
                            <label class="text-[10px] font-black text-muted uppercase tracking-widest block mb-2">{{ __('admin.members.permissions_list') }}</label>
                            <div class="grid grid-cols-2 gap-1.5">
                                @foreach($allPermissions as $perm)
                                    <label class="flex items-center gap-2 text-[11px] text-body cursor-pointer p-1.5 rounded-lg hover:bg-surface transition-colors">
                                        <input type="checkbox" wire:model="customPermissions" value="{{ $perm }}"
                                               class="rounded border-gray-300 text-accent focus:ring-accent w-3.5 h-3.5">
                                        {{ $perm }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <x-ui.button wire:click="savePermissions" variant="accent" icon="shield-check" size="sm">
                            {{ __('admin.members.save_permissions') }}
                        </x-ui.button>
                    </div>
                    @endif
                </div>

            @elseif ($modalType === 'view')
                <div class="space-y-4 text-sm">
                    @foreach([
                        [__('admin.members.name'), $name, 'user'],
                        [__('admin.members.email'), $email, 'mail'],
                        [__('admin.members.phone'), $telephone, 'phone'],
                        [__('admin.members.role'), $role instanceof \App\Enums\AccountType ? $role->label() : $role, 'shield'],
                        [__('admin.members.department'), $department, 'building'],
                        [__('admin.members.country'), $pays, 'globe'],
                        [__('admin.members.city'), $ville, 'map-pin']
                    ] as [$label, $val, $icon])
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 p-1.5 rounded-lg bg-surface text-muted">
                                <x-dynamic-component :component="'lucide-' . $icon" class="w-3.5 h-3.5" />
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[10px] font-black text-muted uppercase tracking-widest">{{ $label }}</span>
                                <span class="text-[13px] font-semibold text-body">{{ $val ?? __('admin.members.not_specified') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

            @elseif ($modalType === 'delete')
                <div class="text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-error/10 text-error flex items-center justify-center mx-auto mb-4">
                        <x-lucide-alert-triangle class="w-8 h-8" />
                    </div>
                    <h3 class="text-lg font-bold text-heading mb-2">{{ __('admin.members.delete_confirm_title') }}</h3>
                    <p class="text-sm text-subtle">{!! __('admin.members.confirm_delete_text', ['name' => '<strong class="text-error">' . e($name) . '</strong>']) !!}</p>
                </div>
                <x-slot:footer>
                    <x-ui.button wire:click="closeModal" variant="ghost">{{ __('common.cancel') }}</x-ui.button>
                    <x-ui.button wire:click="delete" variant="danger" icon="trash-2" loadingText="Suppression...">{{ __('admin.members.delete_permanently') }}</x-ui.button>
                </x-slot:footer>
            @endif
        </x-ui.modal>
    @endif

</x-ui.page-layout>
