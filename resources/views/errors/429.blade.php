<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>429 - Trop de Requêtes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-purple-50 flex items-center justify-center min-h-screen" x-data="{ count: 0 }" x-init="setInterval(() => count = (count + 1) % 10, 300)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-purple-600" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="20" width="60" height="60" rx="5" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="35" cy="35" r="3" :class="count > 0 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="50" cy="35" r="3" :class="count > 1 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="65" cy="35" r="3" :class="count > 2 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="35" cy="50" r="3" :class="count > 3 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="50" cy="50" r="3" :class="count > 4 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="65" cy="50" r="3" :class="count > 5 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="35" cy="65" r="3" :class="count > 6 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="50" cy="65" r="3" :class="count > 7 ? 'fill-current' : 'fill-none'"/>
                    <circle cx="65" cy="65" r="3" :class="count > 8 ? 'fill-current' : 'fill-none'"/>
                    <path d="M80 30V20H90" stroke="currentColor" stroke-width="2" fill="none"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-purple-800 mb-4">429</h1>
            <h2 class="text-2xl font-semibold text-purple-700 mb-6">Trop de Requêtes</h2>
            <p class="text-purple-600 mb-8">Désolé, vous avez envoyé trop de requêtes en peu de temps. Veuillez patienter avant de réessayer.</p>
            <a href="/dashboard" class="inline-block bg-purple-500 hover:bg-purple-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                Réessayer
            </a>
        </div>
    </div>
</body>
</html>