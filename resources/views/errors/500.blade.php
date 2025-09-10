<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erreur Serveur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-red-50 flex items-center justify-center min-h-screen" x-data="{ shake: 0 }" x-init="setInterval(() => { shake = (shake + 1) % 10 }, 100)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg class="mx-auto h-40 w-40 text-red-600" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 10L10 50L50 90L90 50L50 10Z" stroke="currentColor" stroke-width="2" fill="none"/>
                    <circle cx="50" cy="40" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M50 55V70" stroke="currentColor" stroke-width="2"/>
                    <path d="M50 25V35" stroke="currentColor" stroke-width="2"/>
                    <circle cx="30" cy="30" r="2" :cx="30 + (shake % 3 - 1)" fill="currentColor"/>
                    <circle cx="70" cy="30" r="2" :cx="70 + (shake % 3 - 1)" fill="currentColor"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-red-800 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-red-700 mb-6">Erreur Interne du Serveur</h2>
            <p class="text-red-600 mb-8">Désolé, quelque chose s'est mal passé de notre côté. Notre équipe technique a été notifiée.</p>
            <a href="/dashboard" class="inline-block bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>