<x-ui.page-layout>
    <x-ui.page-header :title="__('admin.invitations.title')" :subtitle="__('admin.invitations.subtitle')">
        <x-slot:actions>
            <x-ui.button wire:click="openModal" variant="accent" icon="send" size="lg">
                {{ __('admin.invitations.invite_member') }}
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filtres --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1">
            <x-ui.input wire:model.live.debounce.300ms="search" :placeholder="__('admin.invitations.search_by_email')" icon="search" />
        </div>
        <x-ui.select wire:model.live="statusFilter">
            <option value="">{{ __('admin.invitations.all_statuses') }}</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </x-ui.select>
    </div>

    @php $isRoot = \App\Services\OrgContext::isRoot() && !session('acting_as_organization_id'); @endphp

    {{-- Table --}}
    <x-ui.section :title="__('admin.invitations.title')" icon="mail" :noPadding="true">
        @if($invitations->isEmpty())
            <x-ui.empty-state icon="mail" :title="__('admin.invitations.no_invitations')" :description="__('admin.invitations.no_invitations_detail')" />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>{{ __('admin.invitations.email') }}</x-ui.table.th>
                    @if($isRoot)
                        <x-ui.table.th>{{ __('admin.invitations.organization') }}</x-ui.table.th>
                    @endif
                    <x-ui.table.th>{{ __('admin.invitations.role') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('admin.invitations.status') }}</x-ui.table.th>
                    <x-ui.table.th>{{ __('admin.invitations.expires_at') }}</x-ui.table.th>
                    <x-ui.table.th align="right">{{ __('admin.invitations.actions') }}</x-ui.table.th>
                </x-slot:headers>

                @php
                    $roleLabels = ['ORG_ADMIN' => __('admin.invitations.roles.org_admin'), 'MANAGER' => __('admin.invitations.roles.manager'), 'MEMBER' => __('admin.invitations.roles.member'), 'SUPERVISOR' => __('admin.invitations.roles.supervisor')];
                    $roleColors = ['ORG_ADMIN' => 'indigo', 'MANAGER' => 'blue', 'MEMBER' => 'gray', 'SUPERVISOR' => 'emerald'];
                @endphp

                @foreach($invitations as $invitation)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <span class="font-medium text-heading text-sm">{{ $invitation->email }}</span>
                        </x-ui.table.td>
                        @if($isRoot)
                            <x-ui.table.td>{{ $invitation->organization?->name ?? '—' }}</x-ui.table.td>
                        @endif
                        <x-ui.table.td>
                            <x-ui.badge :color="$roleColors[$invitation->spatie_role] ?? 'gray'" size="xs">
                                {{ $roleLabels[$invitation->spatie_role] ?? $invitation->spatie_role }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :color="$invitation->status->color()" size="xs">
                                {{ $invitation->status->label() }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <span class="text-xs {{ $invitation->isExpired() ? 'text-error' : 'text-subtle' }}">
                                {{ $invitation->expires_at->format('d/m/Y H:i') }}
                            </span>
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            @if($invitation->status === \App\Enums\InvitationStatus::PENDING)
                                @php $cooldown = $this->getResendCooldown($invitation->id); @endphp
                                <div class="flex items-center justify-end gap-1">
                                    @if($cooldown > 0)
                                        <span class="text-[10px] text-muted font-medium px-2" title="{{ __('admin.invitations.cooldown_hint') }}">
                                            {{ ceil($cooldown / 60) }}min
                                        </span>
                                    @else
                                        <button wire:click="openConfirm('resend', '{{ $invitation->id }}')"
                                                class="p-1.5 rounded-lg text-accent hover:bg-accent/10 transition-colors" title="{{ __('admin.invitations.resend') }}">
                                            <x-lucide-repeat class="w-4 h-4" />
                                        </button>
                                    @endif
                                    <button wire:click="openConfirm('revoke', '{{ $invitation->id }}')"
                                            class="p-1.5 rounded-lg text-error hover:bg-error/10 transition-colors" title="{{ __('admin.invitations.revoke') }}">
                                        <x-lucide-x-circle class="w-4 h-4" />
                                    </button>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>
        @endif

        @if($invitations->isNotEmpty())
            <div class="px-6 py-4 border-t border-border-light">
                {{ $invitations->links() }}
            </div>
        @endif
    </x-ui.section>

    {{-- Modal d'invitation --}}
    @if($showModal)
    <x-ui.modal :title="__('admin.invitations.invite_member')" closeAction="closeModal">
        <form wire:submit="sendInvitation" class="space-y-4">
            <x-ui.input wire:model="email" :label="__('admin.invitations.email')" type="email" required :placeholder="__('admin.invitations.email_placeholder')" />

            @if($isRoot)
                <x-ui.select wire:model="organizationId" :label="__('admin.invitations.target_organization')" required>
                    <option value="">{{ __('admin.invitations.select_organization') }}</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </x-ui.select>
            @endif

            <x-ui.select wire:model.live="selectedRole" :label="__('admin.invitations.role')" icon="shield-check">
                @foreach($availableRoles as $r)
                    <option value="{{ $r }}">{{ __('admin.invitations.roles.' . $r) }}</option>
                @endforeach
            </x-ui.select>
            <p class="text-[10px] text-muted -mt-2 ml-1">
                {{ __('admin.invitations.role_desc.' . $selectedRole) }}
            </p>

            <x-slot:footer>
                <x-ui.button type="button" wire:click="closeModal" variant="outline" size="sm">
                    {{ __('common.cancel') }}
                </x-ui.button>
                <x-ui.button type="submit" variant="accent" icon="send" size="sm">
                    {{ __('admin.invitations.send') }}
                </x-ui.button>
            </x-slot:footer>
        </form>
    </x-ui.modal>
    @endif

    {{-- Modal de confirmation (resend / revoke) --}}
    @if($showConfirmModal)
    <x-ui.modal title="" closeAction="closeConfirmModal" maxWidth="max-w-sm">
        <div class="text-center space-y-4">
            {{-- Icon --}}
            <div class="flex justify-center">
                @if($confirmType === 'resend')
                    <div class="w-12 h-12 rounded-full bg-accent/10 flex items-center justify-center">
                        <x-lucide-repeat class="w-6 h-6 text-accent" />
                    </div>
                @else
                    <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center">
                        <x-lucide-x-circle class="w-6 h-6 text-error" />
                    </div>
                @endif
            </div>

            {{-- Title --}}
            <h3 class="text-base font-bold text-heading">
                {{ $confirmType === 'resend' ? __('admin.invitations.confirm_resend') : __('admin.invitations.confirm_revoke') }}
            </h3>

            {{-- Email --}}
            <p class="text-sm text-subtle">
                <span class="font-medium text-heading">{{ $confirmEmail }}</span>
            </p>
        </div>

        <x-slot:footer>
            <x-ui.button wire:click="closeConfirmModal" variant="outline" size="sm">
                {{ __('common.cancel') }}
            </x-ui.button>
            <x-ui.button wire:click="executeConfirmedAction" variant="{{ $confirmType === 'revoke' ? 'danger' : 'accent' }}" size="sm">
                {{ $confirmType === 'resend' ? __('admin.invitations.resend') : __('admin.invitations.revoke') }}
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
    @endif
</x-ui.page-layout>
