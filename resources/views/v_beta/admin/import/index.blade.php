<x-app-layout :title="__('import.title')">
    <x-ui.page-header :title="__('import.title')" :subtitle="__('import.subtitle')" />

    <x-ui.page-layout>
        @livewire('v-beta.admin.import-livewire')
    </x-ui.page-layout>
</x-app-layout>
