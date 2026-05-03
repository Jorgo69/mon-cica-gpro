<div x-data="{ step: @entangle('step') }" class="space-y-8">
    <div class="text-center">
        <h2 class="text-2xl font-black text-heading uppercase tracking-tighter">{{ __('auth.onboarding') }}</h2>
        <p class="text-xs text-subtle font-medium mt-1">{{ __('auth.onboarding_subtitle') }}</p>
    </div>

    <div class="text-center">
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 text-xs text-muted hover:text-subtle dark:hover:text-body transition-colors">
                <x-lucide-log-out class="w-3.5 h-3.5" />
                {{ __('auth.logout') }}
            </button>
        </form>
    </div>

    <div x-show="step === 'choice'"
         x-transition:enter="transition ease-out duration-300 delay-150" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0"
    >
        <div class="grid grid-cols-1 gap-4">
            <button type="button" @click="step = 'create'" class="group relative p-6 text-left rounded-3xl border border-border-light dark:border-surface-alt bg-white/50 dark:bg-surface-alt/50 hover:border-accent/30 dark:hover:border-accent/30 hover:bg-white dark:hover:bg-surface-alt transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform">
                        <x-lucide-plus-circle class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-heading text-sm">{{ __('auth.create_workspace') }}</h3>
                        <p class="text-[11px] text-subtle mt-0.5">{{ __('auth.create_workspace_desc') }}</p>
                    </div>
                </div>
            </button>

            <button type="button" @click="step = 'join'" class="group relative p-6 text-left rounded-3xl border border-border-light dark:border-surface-alt bg-white/50 dark:bg-surface-alt/50 hover:border-accent/30 dark:hover:border-accent/30 hover:bg-white dark:hover:bg-surface-alt transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-surface-alt dark:bg-surface-alt flex items-center justify-center text-accent group-hover:scale-110 transition-transform">
                        <x-lucide-users class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-heading text-sm">{{ __('auth.join_team') }}</h3>
                        <p class="text-[11px] text-subtle mt-0.5">{{ __('auth.join_team_desc') }}</p>
                    </div>
                </div>
            </button>

            <button type="button" @click="step = 'independent'" class="group relative p-6 text-left rounded-3xl border border-border-light dark:border-surface-alt bg-white/50 dark:bg-surface-alt/50 hover:border-accent/30 dark:hover:border-accent/30 hover:bg-white dark:hover:bg-surface-alt transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-success dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <x-lucide-user class="w-6 h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-heading text-sm">{{ __('auth.work_independent') }}</h3>
                        <p class="text-[11px] text-subtle mt-0.5">{{ __('auth.work_independent_desc') }}</p>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <div x-show="step === 'create'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak
         style="display: none;"
    >
        <form wire:submit="createOrganization" class="space-y-6">
            <x-ui.input 
                label="{{ __('auth.organization_name') }}"
                placeholder="{{ __('auth.organization_placeholder') }}"
                wire:model="organizationName"
                icon="building"
                required 
                :error="$errors->first('organizationName')"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="button" variant="ghost" icon="arrow-left" @click="step = 'choice'">
                    {{ __('common.back') }}
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" icon="check" class="flex-1">
                    {{ __('auth.finalize_creation') }}
                </x-ui.button>
            </div>
        </form>
    </div>

    <div x-show="step === 'join'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak
         style="display: none;"
    >
        <form wire:submit="joinOrganization" class="space-y-6">
            <x-ui.input 
                label="{{ __('auth.invitation_code') }}"
                placeholder="{{ __('auth.invitation_code_placeholder') }}"
                wire:model="inviteCode"
                icon="key"
                required 
                :error="$errors->first('inviteCode')"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="button" variant="ghost" icon="arrow-left" @click="step = 'choice'">
                    {{ __('common.back') }}
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" icon="send" class="flex-1">
                    {{ __('auth.join_workspace') }}
                </x-ui.button>
            </div>
        </form>
    </div>

    <div x-show="step === 'independent'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 scale-95" 
         x-transition:enter-end="opacity-100 scale-100"
         x-cloak
         style="display: none;"
    >
        <form wire:key="independent" wire:submit="selectIndependent" class="space-y-6 text-center">
            <div class="mx-auto w-16 h-16 rounded-3xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center text-success dark:text-emerald-400 mb-4 shadow-sm border border-emerald-100 dark:border-emerald-800/50">
                <x-lucide-user class="w-8 h-8" />
            </div>
            
            <div>
                <h3 class="text-xl font-black text-heading uppercase tracking-tight">{{ __('auth.work_independent') }}</h3>
                <p class="text-xs text-subtle font-medium mt-2 max-w-sm mx-auto">
                    {{ __('auth.independent_confirm_text') }}
                </p>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <x-ui.button type="button" variant="ghost" icon="arrow-left" @click="step = 'choice'">
                    {{ __('common.back') }}
                </x-ui.button>
                <x-ui.button type="submit" variant="primary" icon="check" class="flex-1">
                    {{ __('auth.confirm_choice') }}
                </x-ui.button>
            </div>
        </form>
    </div>
</div>
