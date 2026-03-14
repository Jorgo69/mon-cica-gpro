@props([
    'title' => null,
    'subtitle' => null,
])

<header class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        @if($title)
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
            <div class="mt-1.5 w-10 h-0.5 bg-accent rounded-full"></div>
        @endif
        @if($subtitle)
            <p class="mt-3 text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-widest">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="flex items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</header>
