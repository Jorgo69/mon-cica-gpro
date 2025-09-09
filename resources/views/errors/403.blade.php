<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Interdit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-red-50 flex items-center justify-center min-h-screen" x-data="{ shake: 0 }" x-init="setInterval(() => { shake = (shake + 1) % 20 }, 100)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-red-600" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="20" width="60" height="60" rx="5" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M35 35L65 65" stroke="currentColor" stroke-width="2"/>
                    <path d="M65 35L35 65" stroke="currentColor" stroke-width="2"/>
                    <circle cx="50" cy="75" r="5" fill="currentColor"/>
                    <rect x="45" y="10" width="10" height="15" :x="45 + (shake % 3 - 1)" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-red-800 mb-4">403</h1>
            <h2 class="text-2xl font-semibold text-red-700 mb-6">Accès Refusé</h2>
            <p class="text-red-600 mb-8">Désolé, vous n'avez pas les permissions nécessaires pour accéder à cette ressource.</p>
            <a href="/" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>