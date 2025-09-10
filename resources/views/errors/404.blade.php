<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Non Trouvée</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen" x-data="{ pulse: false }" x-init="setInterval(() => pulse = !pulse, 1000)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-blue-500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="2" fill="none"/>
                    <line x1="30" y1="30" x2="70" y2="70" stroke="currentColor" stroke-width="2"/>
                    <circle cx="75" cy="30" r="5" :class="pulse ? 'opacity-100' : 'opacity-20'" fill="currentColor"/>
                    <circle cx="65" cy="50" r="5" :class="pulse ? 'opacity-100' : 'opacity-40'" fill="currentColor"/>
                    <circle cx="40" cy="65" r="5" :class="pulse ? 'opacity-100' : 'opacity-60'" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-blue-800 mb-4">404</h1>
            <h2 class="text-2xl font-semibold text-blue-700 mb-6">Page Non Trouvée</h2>
            <p class="text-blue-600 mb-8">Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="/dashboard" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>