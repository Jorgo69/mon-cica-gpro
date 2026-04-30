<div x-data="{
        online: navigator.onLine,
        init() {
            window.addEventListener('online', () => this.online = true)
            window.addEventListener('offline', () => this.online = false)
        }
    }"
    x-show="!online"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-full"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-full"
    x-cloak
    class="fixed top-0 left-0 right-0 z-[100] bg-amber-500 text-white text-center py-2 px-4 text-sm font-semibold shadow-lg"
>
    <div class="flex items-center justify-center gap-2">
        <x-lucide-wifi-off class="w-4 h-4" />
        <span>Connexion perdue — les modifications seront envoyées au retour du réseau.</span>
    </div>
</div>
