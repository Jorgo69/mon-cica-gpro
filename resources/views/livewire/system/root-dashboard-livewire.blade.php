<x-ui.page-layout>
    <x-ui.page-header
        title="Supervision Plateforme"
        subtitle="Vue globale de toutes les organisations et utilisateurs"
        icon="shield-check"
    />

    {{-- Stats globales --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.stat-card
            label="Organisations"
            :value="$totalOrganizations"
            icon="building-2"
            color="accent"
        />
        <x-ui.stat-card
            label="Utilisateurs"
            :value="$totalUsers"
            icon="users"
            color="info"
        />
        <x-ui.stat-card
            label="Projets"
            :value="$totalProjects"
            icon="folder-kanban"
            color="success"
        />
        <x-ui.stat-card
            label="Invitations en attente"
            :value="$pendingInvitations"
            icon="mail"
            color="warning"
        />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top organisations --}}
        <x-ui.section title="Organisations" icon="building-2">
            @if($topOrganizations->isEmpty())
                <x-ui.empty-state icon="building-2" message="Aucune organisation" />
            @else
                <x-ui.table>
                    <x-slot:headers>
                        <x-ui.table.th>Organisation</x-ui.table.th>
                        <x-ui.table.th>Membres</x-ui.table.th>
                        <x-ui.table.th>Projets</x-ui.table.th>
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
        <x-ui.section title="Derniers inscrits" icon="user-plus">
            @if($recentUsers->isEmpty())
                <x-ui.empty-state icon="users" message="Aucun utilisateur" />
            @else
                <x-ui.table>
                    <x-slot:headers>
                        <x-ui.table.th>Utilisateur</x-ui.table.th>
                        <x-ui.table.th>Rôle</x-ui.table.th>
                        <x-ui.table.th>Organisation</x-ui.table.th>
                        <x-ui.table.th>Date</x-ui.table.th>
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
                                {{ $user->organization?->name ?? 'Aucune' }}
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
            <x-ui.section title="Invitations en attente" icon="mail">
                <x-ui.table>
                    <x-slot:headers>
                        <x-ui.table.th>Email</x-ui.table.th>
                        <x-ui.table.th>Organisation</x-ui.table.th>
                        <x-ui.table.th>Invité par</x-ui.table.th>
                        <x-ui.table.th>Date</x-ui.table.th>
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
