<div wire:ignore.self x-data="{
    open: false,
    pos: { top: 0, left: 0 },
    toggle() {
        if (this.open) { this.open = false; return; }
        const rect = this.$refs.trigger.getBoundingClientRect();
        const menuH = 200;
        const spaceBelow = window.innerHeight - rect.bottom;
        if (spaceBelow < menuH) {
            this.pos = { top: (rect.top - menuH) + 'px', left: (rect.right - 192) + 'px' };
        } else {
            this.pos = { top: rect.bottom + 'px', left: (rect.right - 192) + 'px' };
        }
        this.open = true;
    }
}" @click.outside="open = false" @scroll.window="open = false" class="relative inline-block">
    <button type="button" x-ref="trigger" @click="toggle()"
        class="p-2 rounded-xl text-muted hover:text-heading hover:bg-surface transition-all">
        <x-lucide-more-horizontal class="w-5 h-5" />
    </button>

    <template x-teleport="body">
        <div x-show="open" x-cloak
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95"
            :style="'position:fixed; top:' + pos.top + '; left:' + pos.left + '; z-index:9999;'"
            class="w-48 bg-card rounded-xl shadow-2xl border border-border-light overflow-hidden">

            <a href="{{ route('project.show', $project->id) }}"
                class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-body hover:bg-surface hover:text-accent transition-colors">
                <x-lucide-eye class="w-4 h-4 text-muted" /> {{ __('table.preview') }}
            </a>
            <a href="{{ route('creator.proposal.project.edit', $project->id) }}"
                class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-body hover:bg-surface hover:text-accent transition-colors">
                <x-lucide-pencil class="w-4 h-4 text-muted" /> {{ __('table.update') }}
            </a>
            <a href="{{ route('project.dashboard', $project->id) }}"
                class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-body hover:bg-surface hover:text-accent transition-colors">
                <x-lucide-bar-chart-3 class="w-4 h-4 text-muted" /> {{ __('table.dashboard') }}
            </a>
            @can('delete', $project)
            <div class="border-t border-border-light"></div>
            <button type="button"
                wire:click="deleteProject('{{ $project->id }}')"
                wire:confirm="Voulez-vous supprimer ce projet ? Cette action est irreversible."
                class="flex items-center gap-3 w-full px-4 py-2.5 text-xs font-bold text-error hover:bg-error/5 transition-colors">
                <x-lucide-trash-2 class="w-4 h-4" /> {{ __('table.delete') }}
            </button>
            @endcan
        </div>
    </template>
</div>
