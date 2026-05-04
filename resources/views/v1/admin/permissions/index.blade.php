<x-app-layout :title="__('admin.permissions.title')">
    <x-ui.page-header :title="__('admin.permissions.title')" :subtitle="__('admin.permissions.subtitle')" />

    <x-ui.page-layout>
        @livewire('v1.admin.org-permissions-livewire')
    </x-ui.page-layout>
</x-app-layout>
