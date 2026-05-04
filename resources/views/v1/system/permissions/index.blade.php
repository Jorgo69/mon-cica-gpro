<x-app-layout title="Gestion des Permissions Système">
    <x-ui.page-header title="Permissions Système" :breadcrumbs="[
        ['label' => 'Système', 'url' => '#'],
        ['label' => 'Permissions', 'url' => route('system.permissions')]
    ]">
        <x-slot:actions>
            <x-ui.button variant="accent" icon="plus" wire:click="$dispatch('openPermissionModal')">
                Nouvelle Permission
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.page-layout>
        @livewire('v1.system.permission-management-livewire')
    </x-ui.page-layout>
</x-app-layout>
