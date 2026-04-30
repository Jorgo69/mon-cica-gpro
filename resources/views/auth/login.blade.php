<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-heading uppercase tracking-tighter">Bienvenue</h2>
        <p class="text-xs text-subtle font-medium mt-1">Connectez-vous pour accéder à votre espace de gestion.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <x-ui.input 
            label="Adresse Email" 
            type="email" 
            name="email" 
            :value="old('email')" 
            icon="mail"
            required 
            autofocus 
            autocomplete="username"
            :error="$errors->first('email')"
        />

        <!-- Password -->
        <div class="space-y-2">
            <x-ui.input 
                label="Mot de passe" 
                type="password" 
                name="password" 
                icon="lock"
                required 
                autocomplete="current-password"
                :error="$errors->first('password')"
            />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-4 h-4 text-accent border-border dark:border-border-light rounded focus:ring-accent/20 dark:bg-surface transition-all" name="remember">
                <span class="ms-2 text-[11px] font-bold text-subtle uppercase tracking-wider group-hover:text-body transition-colors">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[11px] font-black uppercase tracking-wider text-muted hover:text-accent transition-colors underline decoration-dotted underline-offset-4" href="{{ route('password.request') }}">
                    Oubli ?
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" icon="log-in" size="lg" class="w-full">
                Se connecter
            </x-ui.button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-[10px] font-black text-muted uppercase tracking-[0.2em] pt-4">
                Pas encore de compte ? 
                <a href="{{ route('register') }}" wire:navigate class="text-accent hover:underline decoration-accent underline-offset-4">S'inscrire</a>
            </p>
        @endif
    </form>
</x-guest-layout>
