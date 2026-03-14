@props([
    'title' => '',
    'maxWidth' => 'max-w-2xl',
    'show' => false,
    'id' => null,
])

@php
    $id = $id ?? md5($attributes->get('wire:model') ?? $title);
@endphp

<div 
    wire:key="modal-{{ $id }}"
    x-data="{ 
        show: @entangle($attributes->wire('show')).live,
        close() { 
            this.show = false;
            $dispatch('close-modal');
        }
    }"
    x-show="show"
    x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Overlay --}}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm"
        @click="close()"
    ></div>

    {{-- Content --}}
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-800 w-full {{ $maxWidth }} max-h-[90vh] flex flex-col overflow-hidden"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 shrink-0">
            <h2 class="text-[15px] font-bold text-slate-800 dark:text-slate-100 tracking-tight">{{ $title }}</h2>
            <button type="button" @click="close()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                <x-lucide-x class="w-4 h-4" />
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 overflow-y-auto flex-1">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-end gap-2 shrink-0">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
