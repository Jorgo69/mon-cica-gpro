<div class="space-y-6">
    {{-- New comment form --}}
    <div class="flex gap-3">
        <x-ui.avatar :user="auth()->user()" size="sm" class="flex-shrink-0 mt-1" />
        <div class="flex-1">
            @if($replyingTo)
                <div class="flex items-center gap-2 mb-2 text-xs text-accent font-bold">
                    <x-lucide-corner-down-right class="w-3 h-3" />
                    <span>Reponse a un commentaire</span>
                    <button wire:click="cancelReply" class="text-muted hover:text-error transition-colors">
                        <x-lucide-x class="w-3 h-3" />
                    </button>
                </div>
            @endif
            <form wire:submit="post" class="space-y-2">
                <textarea wire:model="body" rows="2" placeholder="Ecrire un commentaire..."
                    class="w-full rounded-xl border border-border-light bg-card text-sm text-body px-4 py-3 focus:ring-2 focus:ring-accent/20 focus:border-accent transition-all resize-none"></textarea>
                @error('body') <p class="text-xs text-error">{{ $message }}</p> @enderror
                <div class="flex justify-end">
                    <x-ui.button type="submit" variant="accent" icon="send" size="sm">
                        Publier
                    </x-ui.button>
                </div>
            </form>
        </div>
    </div>

    {{-- Comments list --}}
    @forelse($comments as $comment)
        <div class="flex gap-3" wire:key="comment-{{ $comment->id }}">
            <x-ui.avatar :user="$comment->user" size="sm" class="flex-shrink-0 mt-1" />
            <div class="flex-1 min-w-0">
                <div class="p-4 bg-surface rounded-xl border border-border-light">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-heading">{{ $comment->user?->name ?? 'Utilisateur' }}</span>
                            <span class="text-[10px] text-muted">{{ $comment->created_at?->diffForHumans() }}</span>
                        </div>
                        @if($comment->user_id === auth()->id() || auth()->user()->can('manage-users'))
                            <button wire:click="delete('{{ $comment->id }}')" wire:confirm="Supprimer ce commentaire ?"
                                class="text-muted hover:text-error transition-colors">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                            </button>
                        @endif
                    </div>
                    <div class="text-sm text-body leading-relaxed prose prose-sm max-w-none">
                        {!! clean($comment->body) !!}
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-1.5 ml-1">
                    <button wire:click="reply('{{ $comment->id }}')"
                        class="text-[10px] font-bold text-muted hover:text-accent transition-colors uppercase tracking-wider">
                        Repondre
                    </button>
                    @if($comment->replies->count() > 0)
                        <span class="text-[10px] text-muted">{{ $comment->replies->count() }} reponse(s)</span>
                    @endif
                </div>

                {{-- Replies --}}
                @if($comment->replies->isNotEmpty())
                    <div class="mt-3 ml-4 space-y-3 border-l-2 border-border-light pl-4">
                        @foreach($comment->replies as $reply)
                            <div class="flex gap-3" wire:key="reply-{{ $reply->id }}">
                                <x-ui.avatar :user="$reply->user" size="xs" class="flex-shrink-0 mt-1" />
                                <div class="flex-1 min-w-0">
                                    <div class="p-3 bg-card rounded-lg border border-border-light">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[11px] font-bold text-heading">{{ $reply->user?->name ?? 'Utilisateur' }}</span>
                                                <span class="text-[10px] text-muted">{{ $reply->created_at?->diffForHumans() }}</span>
                                            </div>
                                            @if($reply->user_id === auth()->id() || auth()->user()->can('manage-users'))
                                                <button wire:click="delete('{{ $reply->id }}')" wire:confirm="Supprimer ?"
                                                    class="text-muted hover:text-error transition-colors">
                                                    <x-lucide-trash-2 class="w-3 h-3" />
                                                </button>
                                            @endif
                                        </div>
                                        <div class="text-xs text-body leading-relaxed">
                                            {!! clean($reply->body) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-8">
            <x-lucide-message-circle class="w-8 h-8 text-muted mx-auto mb-2" />
            <p class="text-xs text-muted font-bold uppercase tracking-wider">Aucun commentaire</p>
            <p class="text-[10px] text-muted mt-1">Soyez le premier a commenter</p>
        </div>
    @endforelse
</div>
