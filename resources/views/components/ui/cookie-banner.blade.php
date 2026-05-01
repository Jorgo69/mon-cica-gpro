{{-- Cookie consent banner --}}
<div x-data="{ show: !localStorage.getItem('cookie_consent') }"
     x-show="show" x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full opacity-0"
     x-transition:enter-end="translate-y-0 opacity-100"
     class="fixed bottom-0 inset-x-0 z-50 p-4">
    <div class="max-w-3xl mx-auto bg-card border border-border-light rounded-2xl shadow-xl p-4 flex flex-col sm:flex-row items-center gap-4">
        <div class="flex items-center gap-3 flex-1">
            <x-lucide-cookie class="w-5 h-5 text-accent flex-shrink-0" />
            <p class="text-xs text-body leading-relaxed">
                {{ __('legal.cookie_message') }}
                <a href="{{ route('legal.privacy') }}" class="text-accent underline">{{ __('legal.learn_more') }}</a>
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button @click="localStorage.setItem('cookie_consent', 'accepted'); show = false"
                    class="px-4 py-2 text-xs font-bold bg-accent text-white rounded-lg hover:bg-accent/90 transition-colors">
                {{ __('legal.accept') }}
            </button>
            <button @click="localStorage.setItem('cookie_consent', 'minimal'); show = false"
                    class="px-4 py-2 text-xs font-bold bg-surface text-muted rounded-lg hover:text-heading transition-colors">
                {{ __('legal.essential_only') }}
            </button>
        </div>
    </div>
</div>
