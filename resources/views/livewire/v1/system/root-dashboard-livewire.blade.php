<x-ui.page-layout>
    <x-ui.page-header
        :title="__('system.root_dashboard.title')"
        :subtitle="__('system.root_dashboard.subtitle')"
        icon="shield-check"
    />

    {{-- Stats globales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.stat-card
            :label="__('system.root_dashboard.organizations')"
            :value="$totalOrganizations"
            icon="building-2"
            color="accent"
        />
        <x-ui.stat-card
            :label="__('system.root_dashboard.users')"
            :value="$totalUsers"
            icon="users"
            color="info"
        />
        <x-ui.stat-card
            :label="__('system.root_dashboard.projects')"
            :value="$totalProjects"
            icon="folder-kanban"
            color="success"
        />
        <x-ui.stat-card
            :label="__('system.root_dashboard.pending_invitations')"
            :value="$pendingInvitations"
            icon="mail"
            color="warning"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top organisations --}}
        <x-ui.section :title="__('system.root_dashboard.top_orgs.organizations')" icon="building-2">
            @if($topOrganizations->isEmpty())
                <x-ui.empty-state icon="building-2" :message="__('system.root_dashboard.no_orgs')" />
            @else
                <x-ui.table>
                    <x-slot:headers>
                        <x-ui.table.th>{{ __('system.root_dashboard.top_orgs.organizations') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.top_orgs.members') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.top_orgs.projects') }}</x-ui.table.th>
                    </x-slot:headers>
                    @foreach($topOrganizations as $org)
                        <x-ui.table.row>
                            <x-ui.table.td>
                                <div class="font-semibold text-heading">{{ $org->name }}</div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge variant="info" size="sm">{{ $org->users_count }}</x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge variant="success" size="sm">{{ $org->projects_count }}</x-ui.badge>
                            </x-ui.table.td>
                        </x-ui.table.row>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.section>

        {{-- Derniers utilisateurs --}}
        <x-ui.section :title="__('system.root_dashboard.recent_users.title')" icon="user-plus">
            @if($recentUsers->isEmpty())
                <x-ui.empty-state icon="users" :message="__('system.root_dashboard.no_users')" />
            @else
                <x-ui.table>
                    <x-slot:headers>
                        <x-ui.table.th>{{ __('system.root_dashboard.recent_users.user') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.recent_users.role') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.recent_users.organization') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.recent_users.date') }}</x-ui.table.th>
                    </x-slot:headers>
                    @foreach($recentUsers as $user)
                        <x-ui.table.row>
                            <x-ui.table.td>
                                <div class="font-semibold text-heading">{{ $user->name }}</div>
                                <div class="text-xs text-muted">{{ $user->email }}</div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <x-ui.badge :variant="$user->role?->color() ?? 'slate'" size="sm">
                                    {{ $user->role?->label() ?? 'N/A' }}
                                </x-ui.badge>
                            </x-ui.table.td>
                            <x-ui.table.td class="text-sm text-subtle">
                                {{ $user->organization?->name ?? __('common.none') }}
                            </x-ui.table.td>
                            <x-ui.table.td class="text-xs text-muted">
                                {{ $user->created_at->diffForHumans() }}
                            </x-ui.table.td>
                        </x-ui.table.row>
                    @endforeach
                </x-ui.table>
            @endif
        </x-ui.section>
    </div>

    {{-- Invitations en attente --}}
    @if($recentInvitations->isNotEmpty())
        <div class="mt-6">
            <x-ui.section :title="__('system.root_dashboard.pending_invitations_table.title')" icon="mail">
                <x-ui.table>
                    <x-slot:headers>
                        <x-ui.table.th>{{ __('system.root_dashboard.pending_invitations_table.email') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.pending_invitations_table.organization') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.pending_invitations_table.invited_by') }}</x-ui.table.th>
                        <x-ui.table.th>{{ __('system.root_dashboard.pending_invitations_table.date') }}</x-ui.table.th>
                    </x-slot:headers>
                    @foreach($recentInvitations as $invitation)
                        <x-ui.table.row>
                            <x-ui.table.td class="font-semibold text-heading">{{ $invitation->email }}</x-ui.table.td>
                            <x-ui.table.td class="text-sm text-subtle">{{ $invitation->organization?->name ?? 'N/A' }}</x-ui.table.td>
                            <x-ui.table.td class="text-sm text-subtle">{{ $invitation->invitedBy?->name ?? 'N/A' }}</x-ui.table.td>
                            <x-ui.table.td class="text-xs text-muted">{{ $invitation->created_at->diffForHumans() }}</x-ui.table.td>
                        </x-ui.table.row>
                    @endforeach
                </x-ui.table>
            </x-ui.section>
        </div>
    @endif
</x-ui.page-layout>
