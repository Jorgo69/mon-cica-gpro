<x-app-layout title="Utilisateurs">
    <x-ui.page-header title="Utilisateurs globaux" :breadcrumbs="[
        ['label' => 'Systeme', 'url' => '#'],
        ['label' => 'Utilisateurs', 'url' => route('system.users')]
    ]">
    </x-ui.page-header>

    <x-ui.page-layout>
        @livewire('v-beta.system.root-user-list-livewire')
    </x-ui.page-layout>
</x-app-layout>
