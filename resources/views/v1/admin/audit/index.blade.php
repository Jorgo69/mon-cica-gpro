<x-app-layout title="Audit & Traçabilité">
    <x-ui.page-layout>
        <x-ui.page-header title="Audit & Traçabilité" subtitle="Historique global des actions de l'organisation">
        </x-ui.page-header>

        <x-ui.section title="Flux d'activité" icon="activity">
            @livewire('v1.audit.activity-history-livewire')
        </x-ui.section>
    </x-ui.page-layout>
</x-app-layout>
