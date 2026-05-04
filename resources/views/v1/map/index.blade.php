<x-app-layout :title="__('map.title')">
    <x-ui.page-header :title="__('map.title')" :subtitle="__('map.subtitle')" />

    <x-ui.page-layout>
        @livewire('v1.map.map-livewire')
    </x-ui.page-layout>
</x-app-layout>
