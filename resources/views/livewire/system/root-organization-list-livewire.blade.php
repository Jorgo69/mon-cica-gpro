<x-ui.page-layout>
    <x-ui.page-header title="Organisations" subtitle="Gestion de toutes les organisations de la plateforme" icon="building-2" />

    {{-- Toolbar --}}
    <div class="mb-4">
        <x-ui.input
            wire:model.live.debounce.300ms="search"
            placeholder="Rechercher une organisation..."
            icon="search"
        />
    </div>

    {{-- Table --}}
    <x-ui.section>
        @if($organizations->isEmpty())
            <x-ui.empty-state icon="building-2" message="Aucune organisation trouvée" />
        @else
            <x-ui.table>
                <x-slot:headers>
                    <x-ui.table.th>Organisation</x-ui.table.th>
                    <x-ui.table.th>Membres</x-ui.table.th>
                    <x-ui.table.th>Projets</x-ui.table.th>
                    <x-ui.table.th>Statut</x-ui.table.th>
                    <x-ui.table.th>Créée le</x-ui.table.th>
                    <x-ui.table.th align="right">Actions</x-ui.table.th>
                </x-slot:headers>

                @foreach($organizations as $org)
                    <x-ui.table.row>
                        <x-ui.table.td>
                            <div class="font-bold text-heading">{{ $org->name }}</div>
                            <div class="text-[10px] font-mono text-muted">{{ $org->slug }}</div>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="info" size="sm">{{ $org->users_count }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge variant="accent" size="sm">{{ $org->projects_count }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td>
                            <x-ui.badge :variant="$org->status->color()" size="sm">{{ $org->status->label() }}</x-ui.badge>
                        </x-ui.table.td>
                        <x-ui.table.td class="text-xs text-subtle">
                            {{ $org->created_at->format('d/m/Y') }}
                        </x-ui.table.td>
                        <x-ui.table.td align="right">
                            <div class="flex items-center justify-end gap-1">
                                <x-ui.button tag="a" href="{{ route('system.org.enter', $org->id) }}" variant="accent" icon="log-in" size="sm">Entrer</x-ui.button>

                                @if($org->status === \App\Enums\OrganizationStatus::ACTIVE)
                                    <x-ui.button variant="ghost" size="sm" icon="pause-circle"
                                        wire:click="toggleStatus('{{ $org->id }}')"
                                        wire:confirm="Suspendre {{ $org->name }} ?"
                                        title="Suspendre" />
                                @else
                                    <x-ui.button variant="ghost" size="sm" icon="play-circle"
                                        wire:click="toggleStatus('{{ $org->id }}')"
                                        title="Activer" />
                                @endif
                            </div>
                        </x-ui.table.td>
                    </x-ui.table.row>
                @endforeach
            </x-ui.table>

            <div class="px-6 py-4 border-t border-border-light">
                {{ $organizations->links() }}
            </div>
        @endif
    </x-ui.section>
</x-ui.page-layout>
