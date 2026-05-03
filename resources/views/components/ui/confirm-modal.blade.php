{{--
    Composant de confirmation modale reutilisable.

    Usage:
    <x-ui.confirm-modal
        event="confirm-delete"
        title="Supprimer cet element ?"
        description="Cette action est irreversible."
        confirm-label="Supprimer"
        confirm-variant="danger"
        icon="trash-2"
        icon-color="error"
    />

    Declenchement depuis un bouton:
    <button @click="$dispatch('confirm-delete', { id: '123' })">Supprimer</button>

    Ecoute Livewire (dans le composant parent):
    Livewire.on('confirm-delete-confirmed', ({ id }) => { $wire.delete(id) })
    OU utiliser wire: directement via Alpine $wire dans le parent.
--}}

@props([
    'event' => 'confirm-action',
    'title' => 'Confirmer cette action ?',
    'description' => '',
    'confirmLabel' => __('common.confirm'),
    'cancelLabel' => __('common.cancel'),
    'confirmVariant' => 'accent',
    'icon' => 'alert-circle',
    'iconColor' => 'accent',
])

@php
    $bgColors = [
        'accent' => 'bg-accent/10',
        'error' => 'bg-error/10',
        'danger' => 'bg-error/10',
        'warning' => 'bg-amber-100 dark:bg-amber-900/20',
        'info' => 'bg-blue-100 dark:bg-blue-900/20',
    ];
    $textColors = [
        'accent' => 'text-accent',
        'error' => 'text-error',
        'danger' => 'text-error',
        'warning' => 'text-amber-600',
        'info' => 'text-blue-600',
    ];
    $btnColors = [
        'accent' => 'bg-accent hover:bg-accent/90',
        'error' => 'bg-error hover:bg-error/90',
        'danger' => 'bg-error hover:bg-error/90',
        'warning' => 'bg-amber-500 hover:bg-amber-600',
    ];
@endphp

<template x-teleport="body">
<div x-data="{ open: false, payload: {} }"
     @{{ $event }}.window="open = true; payload = $event.detail"
     x-show="open" x-cloak
     style="display: none"
     class="fixed inset-0 z-50 flex items-center justify-center"
     role="dialog" aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="open = false"
         class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    {{-- Content --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-card rounded-2xl shadow-2xl border border-border p-6 w-full max-w-sm mx-4">

        {{-- Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full {{ $bgColors[$iconColor] ?? $bgColors['accent'] }} flex items-center justify-center">
                <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6 {{ $textColors[$iconColor] ?? $textColors['accent'] }}" />
            </div>
        </div>

        {{-- Title --}}
        <h3 class="text-base font-bold text-heading text-center mb-1">{{ $title }}</h3>

        {{-- Description / dynamic slot --}}
        @if($description)
            <p class="text-sm text-subtle text-center mb-5">{{ $description }}</p>
        @else
            <div class="text-sm text-subtle text-center mb-5">{{ $slot }}</div>
        @endif

        {{-- Buttons --}}
        <div class="flex gap-3">
            <button @click="open = false"
                    class="flex-1 px-4 py-2.5 text-sm font-bold text-subtle bg-surface hover:bg-surface-alt rounded-xl border border-border transition-colors">
                {{ $cancelLabel }}
            </button>
            <button @click="$dispatch('{{ $event }}-confirmed', payload); open = false"
                    class="flex-1 px-4 py-2.5 text-sm font-bold text-white {{ $btnColors[$confirmVariant] ?? $btnColors['accent'] }} rounded-xl transition-colors">
                {{ $confirmLabel }}
            </button>
        </div>
    </div>
</div>
</template>
