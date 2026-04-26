<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-heading uppercase tracking-tighter">Créer un compte</h2>
        <p class="text-xs text-subtle font-medium mt-1">Rejoignez CICA-GPRO pour gérer vos projets avec excellence.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <x-ui.input 
            label="Nom complet" 
            type="text" 
            name="name" 
            :value="old('name')" 
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
            name="email" 
            :value="old('email')" 
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
                name="password" 
                icon="lock"
                required 
                autocomplete="new-password"
                :error="$errors->first('password')"
            />

            <x-ui.input 
                label="Confirmation" 
                type="password" 
                name="password_confirmation" 
                icon="shield-check"
                required 
                autocomplete="new-password"
                :error="$errors->first('password_confirmation')"
            />
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" icon="user-plus" size="lg" class="w-full">
                S'inscrire
            </x-ui.button>
        </div>

        <p class="text-center text-[10px] font-black text-muted uppercase tracking-[0.2em] pt-4">
            Déjà inscrit ? 
            <a href="{{ route('login') }}" wire:navigate class="text-accent hover:underline decoration-accent underline-offset-4">Se connecter</a>
        </p>
    </form>
</x-guest-layout>
