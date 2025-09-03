@if (session('error'))
<div class="alert-container" data-duration="5000">
    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 my-2 shadow-md relative" role="alert">
        <div class="flex">
            <div class="py-1">
                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 1 0 0 16 8 8 0 0 0 0-16zm0 2a6 6 0 1 1 0 12 6 6 0 0 1 0-12zm-1 8l1 1 1-1 3-3-1-1-1 1-3 3z"/>
                </svg>
            </div>
            <div>
                <p class="font-bold">Erreur</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Afficher les alertes de succès --}}
{{-- <x-alert 
    sessionKey="danger-project"
    title="Succès"
    bgColor="teal"
    borderColor="teal"
    textColor="teal"
    iconColor="text-green-500"
    :duration="5000">
    <x-slot name="icon">
        <x-icons.check />
    </x-slot>
</x-alert>

<x-alert 
    sessionKey="danger-activity"
    title="Succès"
    bgColor="teal"
    borderColor="teal"
    textColor="teal"
    iconColor="text-green-500"
    :duration="5000">
    <x-slot name="icon">
        <x-icons.check />
    </x-slot>
</x-alert>

<x-alert 
    sessionKey="danger-sub-activity"
    title="Succès"
    bgColor="teal"
    borderColor="teal"
    textColor="teal"
    iconColor="text-green-500"
    :duration="5000">
    <x-slot name="icon">
        <x-icons.check />
    </x-slot>
</x-alert> --}}