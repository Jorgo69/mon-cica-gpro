@if(auth()->user()?->role === \App\Enums\AccountType::ROOT && session('acting_as_organization_id'))
<div class="fixed top-0 left-0 right-0 z-[60] bg-error text-white text-center py-1.5 text-sm font-bold shadow-lg">
    <div class="flex items-center justify-center gap-3">
        <x-lucide-shield-alert class="w-4 h-4" />
        <span>Mode supervision — Vous êtes dans l'espace <strong>{{ session('acting_as_organization_name') }}</strong></span>
        <a href="{{ route('system.org.leave') }}" class="ml-2 px-3 py-0.5 bg-white/20 hover:bg-white/30 rounded text-xs font-bold transition-colors">
            Quitter l'espace
        </a>
    </div>
</div>
@endif
