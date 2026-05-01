<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - {{ __('errors.503.title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-yellow-50 flex items-center justify-center min-h-screen" x-data="{ pulse: false }" x-init="setInterval(() => pulse = !pulse, 800)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-yellow-600" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="20" width="60" height="60" rx="5" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="35" cy="35" r="5" :class="pulse ? 'fill-current' : 'fill-none'"/>
                    <circle cx="65" cy="35" r="5" :class="pulse ? 'fill-none' : 'fill-current'"/>
                    <circle cx="35" cy="65" r="5" :class="pulse ? 'fill-current' : 'fill-none'"/>
                    <circle cx="65" cy="65" r="5" :class="pulse ? 'fill-none' : 'fill-current'"/>
                    <path d="M40 40L60 60" stroke="currentColor" stroke-width="2"/>
                    <path d="M60 40L40 60" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-yellow-800 mb-4">503</h1>
            <h2 class="text-2xl font-semibold text-yellow-700 mb-6">{{ __('errors.503.title') }}</h2>
            <p class="text-yellow-600 mb-8">{{ __('errors.503.message') }}</p>
            <a href="/dashboard" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                {{ __('errors.retry') }}
            </a>
        </div>
    </div>
</body>
</html>