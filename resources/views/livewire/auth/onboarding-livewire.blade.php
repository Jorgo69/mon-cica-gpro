<div class="space-y-8">
    <div class="text-center">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">Onboarding</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">Configurez votre environnement de travail professionnel.</p>
    </div>

    @if($step === 'choice')
        <div class="grid grid-cols-1 gap-4" x-transition:enter="transition ease-out duration-300 delay-150" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <button wire:click="selectCreate" class="group relative p-6 text-left rounded-3xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-800/50 hover:border-accent/30 dark:hover:border-accent/30 hover:bg-white dark:hover:bg-slate-800 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform">
                        <x-ui.icon name="plus-circle" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Créer un espace</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Lancez une nouvelle organisation pour votre équipe.</p>
                    </div>
                </div>
            </button>

            <button wire:click="selectJoin" class="group relative p-6 text-left rounded-3xl border border-slate-100 dark:border-slate-800 bg-white/50 dark:bg-slate-800/50 hover:border-accent/30 dark:hover:border-accent/30 hover:bg-white dark:hover:bg-slate-800 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-accent group-hover:scale-110 transition-transform">
                        <x-ui.icon name="users" class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm">Rejoindre une équipe</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Utilisez un code d'invitation pour rejoindre un projet.</p>
                    </div>
                </div>
            </button>
        </div>
    @elseif($step === 'create')
        <form wire:submit="createOrganization" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <x-ui.input 
                label="Nom de l'Organisation" 
                placeholder="Ex: Cave Tech, ONG Humanitaire..."
                wire:model="organizationName"
                icon="building"
                required 
                autofocus 
                :error="$errors->first('organizationName')"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="button" variant="ghost" icon="arrow-left" wire:click="back">
                    Retour
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" icon="check" class="flex-1">
                    Finaliser la création
                </x-ui.button>
            </div>
        </form>
    @elseif($step === 'join')
        <form wire:submit="joinOrganization" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <x-ui.input 
                label="Code d'Invitation" 
                placeholder="Entrez le code reçu par email"
                wire:model="inviteCode"
                icon="key"
                required 
                autofocus 
                :error="$errors->first('inviteCode')"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="button" variant="ghost" icon="arrow-left" wire:click="back">
                    Retour
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" icon="send" class="flex-1">
                    Rejoindre l'espace
                </x-ui.button>
            </div>
        </form>
    @endif
</div>
