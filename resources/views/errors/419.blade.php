<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - {{ __('errors.419.title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-amber-50 flex items-center justify-center min-h-screen" x-data="{ time: 0 }" x-init="setInterval(() => time = (time + 1) % 100, 50)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-warning" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M50 25V50" stroke="currentColor" stroke-width="2"/>
                    <path d="M50 50L65 65" stroke="currentColor" stroke-width="2"/>
                    <circle cx="50" cy="50" r="5" fill="currentColor"/>
                    <path d="M30 20C35 15 65 15 70 20" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M25 30C20 35 20 65 25 70" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M30 80C35 85 65 85 70 80" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M80 30C85 35 85 65 80 70" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="75" cy="30" r="2" :class="time > 50 ? 'opacity-0' : 'opacity-100'" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-amber-800 mb-4">419</h1>
            <h2 class="text-2xl font-semibold text-amber-700 mb-6">{{ __('errors.419.title') }}</h2>
            <p class="text-warning mb-8">{{ __('errors.419.message') }}</p>
            <a href="/dashboard" class="inline-block bg-warning hover:bg-amber-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                {{ __('errors.refresh') }}
            </a>
        </div>
    </div>
</body>
</html>