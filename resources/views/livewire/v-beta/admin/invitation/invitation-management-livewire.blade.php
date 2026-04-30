<div>
    <x-ui.page-header title="Invitations" subtitle="Gérez les invitations à rejoindre votre organisation">
        <x-ui.button wire:click="openModal" variant="accent" size="md">
            <x-lucide-send class="w-4 h-4 mr-1.5" />
            Inviter un membre
        </x-ui.button>
    </x-ui.page-header>

    {{-- Filtres --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1">
            <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Rechercher par email..." />
        </div>
        <x-ui.select wire:model.live="statusFilter">
            <option value="">Tous les statuts</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </x-ui.select>
    </div>

    {{-- Table --}}
    <x-ui.card>
        <x-ui.table>
            <x-slot name="head">
                <x-ui.table.th>Email</x-ui.table.th>
                <x-ui.table.th>Organisation</x-ui.table.th>
                <x-ui.table.th>Rôle</x-ui.table.th>
                <x-ui.table.th>Statut</x-ui.table.th>
                <x-ui.table.th>Invité par</x-ui.table.th>
                <x-ui.table.th>Expire le</x-ui.table.th>
                <x-ui.table.th>Code</x-ui.table.th>
                <x-ui.table.th>Actions</x-ui.table.th>
            </x-slot>

            <x-slot name="body">
                @forelse($invitations as $invitation)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <span class="font-medium text-heading">{{ $invitation->email }}</span>
                        </x-ui.table.td>
                        <x-ui.table.td>{{ $invitation->organization?->name ?? '—' }}</x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :color="$invitation->spatie_role === 'ORG_ADMIN' ? 'indigo' : ($invitation->spatie_role === 'MANAGER' ? 'blue' : 'gray')">
                                {{ $invitation->spatie_role }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :color="$invitation->status->color()">
                                {{ $invitation->status->label() }}
                            </x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>{{ $invitation->invitedBy?->name ?? '—' }}</x-ui.table.td>
                        <x-ui.table.td>
                            <span class="{{ $invitation->isExpired() ? 'text-error' : 'text-subtle' }}">
                                {{ $invitation->expires_at->format('d/m/Y H:i') }}
                            </span>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <code class="text-xs bg-surface-alt px-2 py-1 rounded font-mono">{{ $invitation->code }}</code>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            @if($invitation->status === \App\Enums\InvitationStatus::PENDING)
                                <div class="flex gap-2">
                                    <button wire:click="resend('{{ $invitation->id }}')"
                                            wire:confirm="Renvoyer une nouvelle invitation ?"
                                            class="text-accent hover:text-accent-dark text-sm font-medium">
                                        Renvoyer
                                    </button>
                                    <button wire:click="revoke('{{ $invitation->id }}')"
                                            wire:confirm="Révoquer cette invitation ?"
                                            class="text-error hover:text-error-dark text-sm font-medium">
                                        Révoquer
                                    </button>
                                </div>
                            @else
                                <span class="text-muted text-sm">—</span>
                            @endif
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="8">
                            <x-ui.empty-state
                                icon="mail"
                                title="Aucune invitation"
                                description="Invitez des membres à rejoindre votre organisation." />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-slot>
        </x-ui.table>

        <div class="mt-4">
            {{ $invitations->links() }}
        </div>
    </x-ui.card>

    {{-- Modal d'invitation --}}
    @if($showModal)
    <x-ui.modal title="Inviter un membre">
        <form wire:submit="sendInvitation" class="space-y-4">
            <x-ui.input wire:model="email" label="Email" type="email" required placeholder="collaborateur@exemple.com" />

            @if(auth()->user()->role === \App\Enums\AccountType::ROOT && !session('acting_as_organization_id'))
                <x-ui.select wire:model="organizationId" label="Organisation cible" required>
                    <option value="">Sélectionner une organisation</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </x-ui.select>
            @endif

            <x-ui.select wire:model="role" label="Type de compte">
                @foreach($accountTypes as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </x-ui.select>

            <x-ui.select wire:model="spatieRole" label="Rôle">
                @foreach($spatieRoles as $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                @endforeach
            </x-ui.select>

            <div class="flex justify-end gap-3 pt-4">
                <x-ui.button type="button" wire:click="$set('showModal', false)" variant="secondary">
                    Annuler
                </x-ui.button>
                <x-ui.button type="submit" variant="accent">
                    <x-lucide-send class="w-4 h-4 mr-1.5" />
                    Envoyer l'invitation
                </x-ui.button>
            </div>
        </form>
    </x-ui.modal>
    @endif
</div>
