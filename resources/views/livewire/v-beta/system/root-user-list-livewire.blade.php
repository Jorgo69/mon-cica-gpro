<div>
    <div class="flex flex-col gap-6">
        {{-- Toolbar : Recherche + Filtres --}}
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="w-full md:w-80">
                <x-ui.input
                    wire:model.live.debounce.300ms="search"
                    placeholder="Rechercher par nom ou email..."
                    icon="search"
                />
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <select wire:model.live="organizationFilter"
                    class="block w-full sm:w-52 border-border bg-card text-heading rounded-2xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 px-4 transition-all">
                    <option value="">Toutes les organisations</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="roleFilter"
                    class="block w-full sm:w-52 border-border bg-card text-heading rounded-2xl shadow-sm focus:ring-2 focus:ring-accent/20 focus:border-accent sm:text-sm py-3 px-4 transition-all">
                    <option value="">Tous les roles</option>
                    @foreach($accountTypes as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Table --}}
        <x-ui.section title="Utilisateurs globaux" icon="users" :noPadding="true">
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Utilisateur</x-ui.table.th>
                    <x-ui.table.th>Role</x-ui.table.th>
                    <x-ui.table.th class="hidden md:table-cell">Organisation</x-ui.table.th>
                    <x-ui.table.th>Statut</x-ui.table.th>
                    <x-ui.table.th class="hidden lg:table-cell">Date</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @forelse($users as $user)
                    <x-ui.table.row>
                        {{-- Utilisateur (nom + email) --}}
                        <x-ui.table.td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-accent/10 flex items-center justify-center text-accent font-bold text-[10px]">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-heading">{{ $user->name }}</span>
                                    <span class="text-[10px] text-muted">{{ $user->email }}</span>
                                </div>
                            </div>
                        </x-ui.table.td>

                        {{-- Role --}}
                        <x-ui.table.td>
                            <x-ui.badge :variant="$user->role?->color() ?? 'slate'" size="sm">
                                {{ $user->role?->label() ?? 'Inconnu' }}
                            </x-ui.badge>
                        </x-ui.table.td>

                        {{-- Organisation --}}
                        <x-ui.table.td class="hidden md:table-cell">
                            <span class="text-subtle text-sm">{{ $user->organization?->name ?? 'Aucune' }}</span>
                        </x-ui.table.td>

                        {{-- Statut (verifie / bloque) --}}
                        <x-ui.table.td>
                            @if($user->email_verified_at)
                                <x-ui.badge variant="emerald" size="sm">Verifie</x-ui.badge>
                            @else
                                <x-ui.badge variant="rose" size="sm">Bloque</x-ui.badge>
                            @endif
                        </x-ui.table.td>

                        {{-- Date --}}
                        <x-ui.table.td class="hidden lg:table-cell">
                            <span class="text-muted text-xs">{{ $user->created_at?->format('d/m/Y') }}</span>
                        </x-ui.table.td>

                        {{-- Actions --}}
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                @if($user->id !== auth()->id())
                                    <x-ui.button
                                        wire:click="toggleBlock('{{ $user->id }}')"
                                        variant="ghost"
                                        size="sm"
                                        :icon="$user->email_verified_at ? 'lock' : 'unlock'"
                                        wire:confirm="{{ $user->email_verified_at ? 'Bloquer cet utilisateur ?' : 'Debloquer cet utilisateur ?' }}"
                                    />
                                @endif
                                <x-ui.button
                                    wire:click="sendPasswordReset('{{ $user->id }}')"
                                    variant="ghost"
                                    size="sm"
                                    icon="key"
                                    wire:confirm="Envoyer un lien de reinitialisation de mot de passe a {{ $user->email }} ?"
                                />
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.td colspan="6" class="py-16 text-center">
                            <x-ui.empty-state icon="users" title="Aucun utilisateur" description="Aucun utilisateur ne correspond aux criteres de recherche." />
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforelse
            </x-ui.table>

            <div class="p-4 border-t border-border-light dark:border-surface-alt bg-surface/20 dark:bg-surface/20">
                {{ $users->links() }}
            </div>
        </x-ui.section>
    </div>
</div>
