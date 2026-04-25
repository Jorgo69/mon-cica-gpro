@props(['rows' => 5, 'cols' => 4])

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 animate-pulse">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <x-ui.skeleton type="block" class="w-48 h-7" />
        <div class="flex gap-3">
            <x-ui.skeleton type="block" class="w-64 h-10 rounded-xl" />
            <x-ui.skeleton type="block" class="w-28 h-10 rounded-xl" />
        </div>
    </div>

    {{-- Table header --}}
    <div class="flex gap-4 mb-4 px-4">
        @for($c = 0; $c < $cols; $c++)
            <x-ui.skeleton type="text" class="flex-1 h-3" />
        @endfor
    </div>

    {{-- Rows --}}
    <div class="space-y-3">
        @for($r = 0; $r < $rows; $r++)
            <div class="flex items-center gap-4 px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-slate-800/30">
                @for($c = 0; $c < $cols; $c++)
                    <x-ui.skeleton type="text" class="flex-1" />
                @endfor
            </div>
        @endfor
    </div>
</div>
