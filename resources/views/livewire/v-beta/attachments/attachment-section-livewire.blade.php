<div class="space-y-4">
    {{-- Upload zone --}}
    <div x-data="{ dragging: false }" class="relative">
        <div class="p-6 border-2 border-dashed rounded-2xl text-center transition-all cursor-pointer"
            :class="dragging ? 'border-accent bg-accent/5' : 'border-border bg-surface/50 hover:border-accent/30'"
            @dragover.prevent="dragging = true"
            @dragleave.prevent="dragging = false"
            @drop.prevent="dragging = false">
            <input type="file" wire:model="newFiles" multiple
                class="absolute inset-0 opacity-0 cursor-pointer" />
            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-card shadow-sm flex items-center justify-center text-muted">
                    <x-lucide-paperclip class="w-5 h-5" />
                </div>
                <p class="text-[11px] font-bold text-subtle">Deposez des fichiers ou <span class="text-accent underline">cliquez pour parcourir</span></p>
                <p class="text-[9px] text-muted uppercase tracking-widest font-black">PDF, Word, Excel, Images — Max 50MB</p>
            </div>
        </div>
    </div>

    {{-- Pending files --}}
    @if(count($newFiles) > 0)
        <div class="p-4 bg-accent/5 border border-accent/20 rounded-xl space-y-2">
            <p class="text-[10px] font-black text-accent uppercase tracking-wider">{{ count($newFiles) }} fichier(s) selectionne(s)</p>
            <input wire:model="description" type="text" placeholder="Description (optionnel)..."
                class="w-full rounded-lg border border-border-light bg-card text-xs text-body px-3 py-2 focus:ring-1 focus:ring-accent/20 focus:border-accent" />
            <div class="flex justify-end">
                <x-ui.button wire:click="upload" variant="accent" icon="upload" size="sm">
                    Ajouter
                </x-ui.button>
            </div>
        </div>
    @endif

    {{-- File list --}}
    @if($attachments->isNotEmpty())
        <div class="space-y-2">
            @foreach($attachments as $attachment)
                <div class="flex items-center justify-between p-3 bg-card border border-border-light rounded-xl hover:border-accent/20 transition-all group" wire:key="att-{{ $attachment->id }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-surface flex items-center justify-center text-subtle border border-border-light flex-shrink-0">
                            @php
                                $icon = match(true) {
                                    str_contains($attachment->file_type ?? '', 'pdf') => 'file-text',
                                    str_contains($attachment->file_type ?? '', 'image') => 'image',
                                    str_contains($attachment->file_type ?? '', 'spreadsheet') || str_contains($attachment->file_type ?? '', 'excel') => 'table',
                                    str_contains($attachment->file_type ?? '', 'word') || str_contains($attachment->file_type ?? '', 'document') => 'file-text',
                                    default => 'file',
                                };
                            @endphp
                            <x-dynamic-component :component="'lucide-' . $icon" class="w-3.5 h-3.5" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-body truncate group-hover:text-accent transition-colors">{{ $attachment->file_name }}</p>
                            <div class="flex items-center gap-2 text-[10px] text-muted">
                                <span>{{ $attachment->human_size }}</span>
                                <span>·</span>
                                <span>{{ $attachment->user?->name }}</span>
                                <span>·</span>
                                <span>{{ $attachment->created_at?->diffForHumans() }}</span>
                            </div>
                            @if($attachment->description)
                                <p class="text-[10px] text-subtle italic mt-0.5">{{ $attachment->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button wire:click="download('{{ $attachment->id }}')" class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-accent hover:bg-accent/5 transition-all">
                            <x-lucide-download class="w-3.5 h-3.5" />
                        </button>
                        @if($attachment->user_id === auth()->id() || auth()->user()->can('manage-users'))
                            <button wire:click="delete('{{ $attachment->id }}')" wire:confirm="Supprimer ce fichier ?"
                                class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-error hover:bg-error/5 transition-all">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(count($newFiles) === 0)
        <div class="text-center py-4">
            <p class="text-[10px] text-muted font-bold uppercase tracking-wider">Aucune piece jointe</p>
        </div>
    @endif
</div>
