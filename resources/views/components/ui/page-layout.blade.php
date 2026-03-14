@props([
    'maxWidth' => '1440px',
])

<main class="lg:ml-60 pt-14 pb-12 min-h-screen bg-slate-50 dark:bg-slate-950">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width: {{ $maxWidth }}" wire:loading.class="opacity-60 pointer-events-none">
        {{ $slot }}
    </div>
</main>
