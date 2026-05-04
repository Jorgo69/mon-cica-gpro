<div class="space-y-6">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-heading uppercase tracking-tighter">{{ __('auth.welcome') }}</h2>
        <p class="text-xs text-subtle font-medium mt-1">{{ __('auth.login_subtitle') }}</p>
    </div>

    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-success/10 border border-emerald-100 dark:border-emerald-500/20 text-success dark:text-emerald-400 text-xs font-bold uppercase tracking-wider">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <x-ui.input 
            label="{{ __('auth.email_address') }}"
            type="email" 
            wire:model="email"
            icon="mail"
            required 
            autofocus 
            autocomplete="username"
            :error="$errors->first('email')"
        />

        <!-- Password -->
        <div class="space-y-2">
            <x-ui.input 
                label="{{ __('auth.password_label') }}"
                type="password" 
                wire:model="password"
                icon="lock"
                required 
                autocomplete="current-password"
                :error="$errors->first('password')"
            />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" wire:model="remember" class="w-4 h-4 text-accent border-border dark:border-border-light rounded focus:ring-accent/20 dark:bg-surface transition-all cursor-pointer">
                <span class="ms-2 text-[11px] font-bold text-subtle uppercase tracking-wider group-hover:text-body dark:group-hover:text-heading transition-colors">{{ __('auth.remember_me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[11px] font-black uppercase tracking-wider text-muted hover:text-accent transition-colors underline decoration-dotted underline-offset-4" href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" icon="log-in" size="lg" class="w-full" loadingText="{{ __('auth.logging_in') }}">
                {{ __('auth.login') }}
            </x-ui.button>
        </div>

        @if(config('services.google.client_id') || config('services.facebook.client_id') || config('services.microsoft.client_id'))
        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-border-light dark:border-surface-alt"></div>
            </div>
            <div class="relative flex justify-center text-[10px] uppercase tracking-[0.2em] font-black">
                <span class="bg-card px-4 text-muted">{{ __('common.or') }} {{ mb_strtolower(__('auth.continue_with', ['provider' => ''])) }}</span>
            </div>
        </div>

        <div class="flex gap-3 justify-center">
            @if(config('services.google.client_id'))
            <a href="{{ route('social.redirect', ['provider' => 'google', 'intent' => 'login']) }}" class="flex items-center gap-2 px-5 py-3 rounded-2xl border border-border-light bg-white/50 dark:bg-surface-alt/50 text-xs font-bold text-body hover:border-accent/30 hover:shadow-sm transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Google
            </a>
            @endif
            @if(config('services.facebook.client_id'))
            <a href="{{ route('social.redirect', ['provider' => 'facebook', 'intent' => 'login']) }}" class="flex items-center gap-2 px-5 py-3 rounded-2xl border border-border-light bg-white/50 dark:bg-surface-alt/50 text-xs font-bold text-body hover:border-accent/30 hover:shadow-sm transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                Facebook
            </a>
            @endif
            @if(config('services.microsoft.client_id'))
            <a href="{{ route('social.redirect', ['provider' => 'microsoft', 'intent' => 'login']) }}" class="flex items-center gap-2 px-5 py-3 rounded-2xl border border-border-light bg-white/50 dark:bg-surface-alt/50 text-xs font-bold text-body hover:border-accent/30 hover:shadow-sm transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#F25022" d="M1 1h10v10H1z"/><path fill="#7FBA00" d="M13 1h10v10H13z"/><path fill="#00A4EF" d="M1 13h10v10H1z"/><path fill="#FFB900" d="M13 13h10v10H13z"/></svg>
                Microsoft
            </a>
            @endif
        </div>
        @endif

        @if (Route::has('register'))
            <p class="text-center text-[10px] font-black text-muted uppercase tracking-[0.2em] pt-4">
                {{ __('auth.no_account') }}
                <a href="{{ route('register') }}" wire:navigate class="text-accent hover:underline decoration-accent underline-offset-4">{{ __('auth.register') }}</a>
            </p>
        @endif
    </form>
</div>
