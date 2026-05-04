<x-app-layout title="{{ __('plans.plan_management') }}">
    <x-ui.page-header :title="__('plans.plan_management')" :breadcrumbs="[
        ['label' => __('navigation.system'), 'url' => '#'],
        ['label' => __('plans.plan'), 'url' => route('system.plans')]
    ]" />

    <x-ui.page-layout>
        @livewire('v-beta.system.plan-management-livewire')
    </x-ui.page-layout>
</x-app-layout>
