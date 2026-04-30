<x-guest-layout>
    <div class="text-center py-12">
        <div class="w-16 h-16 rounded-full bg-success/10 text-success flex items-center justify-center mx-auto mb-6">
            <x-lucide-mail-check class="w-8 h-8" />
        </div>
        <h2 class="text-2xl font-bold text-heading mb-2">Desabonnement confirme</h2>
        <p class="text-subtle mb-6">L'adresse <strong>{{ $email }}</strong> ne recevra plus de notifications par email.</p>
        <p class="text-sm text-muted">
            Desabonne par erreur ?
            <a href="{{ route('email.resubscribe', \App\Http\Controllers\EmailUnsubscribeController::generateToken($email)) }}" class="text-accent hover:underline font-semibold">
                Se reabonner
            </a>
        </p>
    </div>
</x-guest-layout>
