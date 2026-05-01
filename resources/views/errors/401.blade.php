<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - {{ __('errors.401.title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-yellow-50 flex items-center justify-center min-h-screen" x-data="{ offset: 0 }" x-init="setInterval(() => { offset = (offset + 1) % 10 }, 100)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-yellow-600" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="25" y="25" width="50" height="50" rx="5" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="50" cy="40" r="5" fill="currentColor"/>
                    <path d="M35 65C35 60 40 60 50 60C60 60 65 60 65 65" stroke="currentColor" stroke-width="2" fill="none"/>
                    <rect x="60" y="15" width="10" height="5" :y="15 + offset" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-yellow-800 mb-4">401</h1>
            <h2 class="text-2xl font-semibold text-yellow-700 mb-6">{{ __('errors.401.title') }}</h2>
            <p class="text-yellow-600 mb-8">{{ __('errors.401.message') }}</p>
            <a href="/login" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                {{ __('errors.login') }}
            </a>
        </div>
    </div>
</body>
</html>