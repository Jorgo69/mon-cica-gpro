<x-guest-layout>
    <div class="text-center py-12">
        <div class="w-16 h-16 rounded-full bg-accent/10 text-accent flex items-center justify-center mx-auto mb-6">
            <x-lucide-mail-plus class="w-8 h-8" />
        </div>
        <h2 class="text-2xl font-bold text-heading mb-2">Reabonnement confirme</h2>
        <p class="text-subtle">L'adresse <strong>{{ $email }}</strong> recevra a nouveau les notifications par email.</p>
    </div>
</x-guest-layout>
