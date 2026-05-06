<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        (function() {
            var isDark = localStorage.getItem('darkMode') === 'true' ||
                (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) document.documentElement.classList.add('dark');
        })();
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-surface text-body min-h-screen font-sans"
      x-data="{
          darkMode: localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleDark() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              document.documentElement.classList.toggle('dark', this.darkMode);
          }
      }"
>
    {{-- Navbar minimal --}}
    <nav class="sticky top-0 z-50 bg-card border-b border-border">
        <div class="max-w-3xl mx-auto px-6 flex justify-between items-center h-14">
            <a href="{{ url('/') }}">
                <x-ui.logo size="sm" />
            </a>
            <div class="flex items-center gap-3">
                <button @click="toggleDark()" class="p-2 text-muted hover:text-accent transition-colors cursor-pointer">
                    <x-lucide-sun x-show="darkMode" x-cloak class="w-4 h-4" />
                    <x-lucide-moon x-show="!darkMode" class="w-4 h-4" />
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-accent hover:underline">{{ __('landing.nav.dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-subtle hover:text-accent transition-colors">{{ __('landing.nav.login') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="{{ $width ?? 'max-w-3xl' }} mx-auto px-6 py-12">
        {{ $slot }}
    </div>

    {{-- Footer minimal --}}
    <footer class="border-t border-border py-8">
        <div class="{{ $width ?? 'max-w-3xl' }} mx-auto px-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-muted">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('landing.footer.copyright') }}</p>
            <div class="flex gap-4">
                <a href="{{ url('/privacy') }}" class="hover:text-accent transition-colors">{{ __('landing.footer.privacy') }}</a>
                <a href="{{ url('/terms') }}" class="hover:text-accent transition-colors">{{ __('landing.footer.terms') }}</a>
                <a href="{{ url('/') }}" class="hover:text-accent transition-colors">{{ config('app.name') }}</a>
            </div>
        </div>
    </footer>
</body>
</html>
