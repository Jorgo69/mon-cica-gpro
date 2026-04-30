<div class="space-y-6">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-heading uppercase tracking-tighter">Créer un compte</h2>
        <p class="text-xs text-subtle font-medium mt-1">Rejoignez CICA-GPRO pour gérer vos projets avec excellence.</p>
    </div>

    <form wire:submit="register" class="space-y-6">
        <!-- Name -->
        <x-ui.input 
            label="Nom complet" 
            type="text" 
            wire:model="name"
            icon="user"
            required 
            autofocus 
            autocomplete="name"
            :error="$errors->first('name')"
        />

        <!-- Email Address -->
        <x-ui.input 
            label="Adresse Email" 
            type="email" 
            wire:model="email"
            icon="mail"
            required 
            autocomplete="username"
            :error="$errors->first('email')"
        />

        <!-- Password -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-ui.input 
                label="Mot de passe" 
                type="password" 
                wire:model="password"
                icon="lock"
                required 
                autocomplete="new-password"
                :error="$errors->first('password')"
            />

            <x-ui.input 
                label="Confirmation" 
                type="password" 
                wire:model="password_confirmation"
                icon="shield-check"
                required 
                autocomplete="new-password"
                :error="$errors->first('password_confirmation')"
            />
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" icon="user-plus" size="lg" class="w-full" loadingText="Création du compte...">
                S'inscrire
            </x-ui.button>
        </div>

        {{-- Social Login Placeholders --}}
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-border-light dark:border-surface-alt"></div>
            </div>
            <div class="relative flex justify-center text-[10px] uppercase tracking-[0.2em] font-black">
                <span class="bg-card px-4 text-muted">Ou s'inscrire avec</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-ui.button variant="outline" class="w-full !rounded-2xl !py-3 bg-white/50 dark:bg-surface-alt/50">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Google
            </x-ui.button>
            <x-ui.button variant="outline" class="w-full !rounded-2xl !py-3 bg-white/50 dark:bg-surface-alt/50">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.69 3.33 1.68-3.13 1.88-2.5 6.16.63 7.43-.65 1.59-1.55 3.14-2.61 3.9M12.03 7.25c-.02-2.13 1.14-4.14 3.01-5.25.32 2.28-1.02 4.41-3.01 5.25"/>
                </svg>
                Apple
            </x-ui.button>
        </div>

        <p class="text-center text-[10px] font-black text-muted uppercase tracking-[0.2em] pt-4">
            Déjà inscrit ? 
            <a href="{{ route('login') }}" wire:navigate class="text-accent hover:underline decoration-accent underline-offset-4">Se connecter</a>
        </p>
    </form>
</div>
