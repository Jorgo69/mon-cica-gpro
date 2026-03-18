<x-app-layout title="Gestion des Organisations">
    <x-ui.page-header title="Liste des Organisations" :breadcrumbs="[
        ['label' => 'Système', 'url' => '#'],
        ['label' => 'Organisations', 'url' => route('system.organizations')]
    ]">
        <x-slot:actions>
            <x-ui.button variant="accent" icon="plus" wire:click="$dispatch('openOrganizationModal')">
                Nouvelle Organisation
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.page-layout>
        @livewire('v-beta.system.organization-management-livewire')
    </x-ui.page-layout>
</x-app-layout>
