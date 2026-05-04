<x-app-layout title="Gestion des Rôles Système">
    <x-ui.page-header title="Rôles du Système" :breadcrumbs="[
        ['label' => 'Système', 'url' => '#'],
        ['label' => 'Rôles', 'url' => route('system.roles')]
    ]">
        <x-slot:actions>
            {{-- Action pour ajouter un rôle global --}}
            <x-ui.button variant="accent" icon="plus" wire:click="$dispatch('openRoleModal')">
                Nouveau Rôle Système
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.page-layout>
        @livewire('v1.system.role-management-livewire')
    </x-ui.page-layout>
</x-app-layout>
