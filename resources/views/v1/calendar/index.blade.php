<x-app-layout :title="__('calendar.title')">
    <x-ui.page-header :title="__('calendar.title')" :subtitle="__('calendar.subtitle')" />

    <x-ui.page-layout>
        @livewire('v1.calendar.calendar-livewire')
    </x-ui.page-layout>
</x-app-layout>
