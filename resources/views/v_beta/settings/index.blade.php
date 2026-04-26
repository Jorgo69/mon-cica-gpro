<x-app-layout>
    <x-ui.page-layout>
        <div class="mb-6 animate-fade-in">
            <h1 class="text-2xl font-bold text-heading flex items-center">
                <x-lucide-settings class="w-6 h-6 mr-3 text-primary-500" />
                {{ __('Parametres') }}
            </h1>
            <p class="text-subtle mt-2">
                {{ __('Personnalisez votre experience selon vos preferences') }}
            </p>
        </div>

        @livewire('v-beta.settings.settings-livewire')
    </x-ui.page-layout>
</x-app-layout>
