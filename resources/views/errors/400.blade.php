<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>400 - Mauvaise Requête</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-orange-50 flex items-center justify-center min-h-screen" x-data="{ rotate: 0 }" x-init="setInterval(() => { rotate = (rotate + 1) % 360 }, 20)">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-lg mx-auto text-center">
            <div class="mb-8">
                <svg x-bind:style="{ transform: `rotate(${rotate}deg)` }" class="mx-auto h-40 w-40 text-orange-500" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 10L10 50L50 90L90 50L50 10Z" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M35 35L65 65" stroke="currentColor" stroke-width="2"/>
                    <path d="M65 35L35 65" stroke="currentColor" stroke-width="2"/>
                </svg>
            </div>
            <h1 class="text-5xl font-bold text-orange-800 mb-4">400</h1>
            <h2 class="text-2xl font-semibold text-orange-700 mb-6">Mauvaise Requête</h2>
            <p class="text-orange-600 mb-8">Désolé, la requête envoyée au serveur est incorrecte ou mal formée.</p>
            <a href="/" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300 ease-in-out transform hover:-translate-y-1">
                Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>