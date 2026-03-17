<x-ui.page-layout>
    <x-ui.page-header title="Audit & Traçabilité" subtitle="Historique global des actions de l'organisation">
        <x-slot:actions>
            <x-ui.button tag="a" :href="route('project.list')" variant="ghost" icon="arrow-left" size="sm" wire:navigate>
                Retour
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="space-y-6">
        <x-ui.section title="Flux d'activité en temps réel" icon="activity">
            @livewire('v-beta.audit.activity-history-livewire')
        </x-ui.section>
    </div>
</x-ui.page-layout>
