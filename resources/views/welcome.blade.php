<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Conception et Gestion de Projets Simplifiées</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .futuristic-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600">{{ config('app.name') }}</h1>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-indigo-600 transition-colors">Fonctionnalités</a>
                    <a href="#why-us" class="text-gray-600 hover:text-indigo-600 transition-colors">Pourquoi nous choisir</a>
                    <a href="#methodologies" class="text-gray-600 hover:text-indigo-600 transition-colors">Méthodologies</a>
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">Se connecter</a>
                </div>
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-600 hover:text-indigo-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div x-show="open" class="md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="#features" class="block px-3 py-2 text-gray-600 hover:text-indigo-600">Fonctionnalités</a>
                <a href="#why-us" class="block px-3 py-2 text-gray-600 hover:text-indigo-600">Pourquoi nous choisir</a>
                <a href="#methodologies" class="block px-3 py-2 text-gray-600 hover:text-indigo-600">Méthodologies</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-indigo-600 font-medium">Se connecter</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-6">Concevez et gérez vos projets de développement avec expertise</h1>
                    <p class="text-xl mb-8 opacity-90">{{ config('app.name') }} rend les méthodologies complexes accessibles grâce à une interface intuitive guidée par IA.</p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-medium hover:bg-gray-100 transition-colors text-center">
                            Commencer maintenant
                        </a>
                        <a href="#features" class="border border-white text-white px-6 py-3 rounded-md font-medium hover:bg-white hover:bg-opacity-10 transition-colors text-center">
                            En savoir plus
                        </a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="futuristic-card rounded-2xl p-8 max-w-md w-full">
                        <div class="text-center mb-6">
                            <svg class="w-16 h-16 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-4 text-center">Plateforme guidée pour novices</h3>
                        <p class="text-center opacity-90">Interface questions-réponses intuitive avec assistance IA pour créer des projets de haute qualité alignés sur les meilleures pratiques.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fonctionnalités principales</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Découvrez comment {{ config('app.name') }} simplifie la conception et la gestion de vos projets</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Assistance IA</h3>
                    <p class="text-gray-600">Corrections et suggestions d'amélioration par intelligence artificielle pour optimiser votre projet.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Interface intuitive</h3>
                    <p class="text-gray-600">Approche guidée par questions-réponses pour une prise en main facile, même sans expérience préalable.</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Export standardisé</h3>
                    <p class="text-gray-600">Générez et exportez vos projets dans des formats standards reconnus par les organismes de développement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why-us" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pourquoi choisir {{ config('app.name') }} ?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Notre plateforme est spécialement conçue pour les personnes et organisations novices en gestion de projet</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="space-y-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Guidance pas à pas</h3>
                                <p class="text-gray-600 mt-1">Nous vous accompagnons à chaque étape de la conception de votre projet, de l'analyse de l'environnement à la planification détaillée.</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Méthodologies éprouvées</h3>
                                <p class="text-gray-600 mt-1">Notre plateforme intègre les approches reconnues comme l'ACL, le Cadre Logique, la GCP et la GAR pour garantir la qualité de vos projets.</p>
                            </div>
                        </div>
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Analyse environnementale intégrée</h3>
                                <p class="text-gray-600 mt-1">L'Approche du Cadre Logique (ACL) vous aide à analyser systématiquement l'environnement de votre projet et à identifier les parties prenantes clés.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg">
                    <div class="aspect-w-16 aspect-h-9 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-xl p-8 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-indigo-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-800">Augmentez vos chances de succès</h3>
                            <p class="text-gray-600 mt-2">Avec {{ config('app.name') }}, structurez vos projets selon les meilleures pratiques et maximisez leur impact.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Methodologies Section -->
    <section id="methodologies" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Méthodologies intégrées</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ config('app.name') }} s'appuie sur des approches reconnues en gestion de projet</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                    <h3 class="text-lg font-semibold text-indigo-800 mb-3">Approche du Cadre Logique (ACL)</h3>
                    <p class="text-gray-700">Méthode participative pour concevoir les éléments clés d'un projet avec analyse étape par étape de l'environnement du projet.</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-xl border border-purple-100">
                    <h3 class="text-lg font-semibold text-purple-800 mb-3">Cadre Logique (Logframe)</h3>
                    <p class="text-gray-700">Outil de gestion présentant les informations du projet dans une matrice 4x4 pour une conception et un suivi efficaces.</p>
                </div>
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3">Gestion du Cycle de Projet (GCP)</h3>
                    <p class="text-gray-700">Approche permettant d'améliorer la qualité des projets au fil du temps via un cycle d'apprentissage continu.</p>
                </div>
                <div class="bg-green-50 p-6 rounded-xl border border-green-100">
                    <h3 class="text-lg font-semibold text-green-800 mb-3">Gestion Axée sur les Résultats (GAR)</h3>
                    <p class="text-gray-700">Évolution des approches de cadre logique avec des outils pour une conception réellement participative.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 gradient-bg text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6">Prêt à créer des projets de développement de qualité ?</h2>
            <p class="text-xl mb-8 opacity-90">Rejoignez {{ config('app.name') }} dès aujourd'hui et bénéficiez d'un accompagnement expert pour la conception et la gestion de vos projets.</p>
            <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-md font-medium hover:bg-gray-100 transition-colors inline-block">
                Commencer maintenant
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">{{ config('app.name') }}</h3>
                    <p class="text-gray-400">Plateforme de conception et gestion de projets de développement pour novices.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Navigation</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#why-us" class="text-gray-400 hover:text-white transition-colors">Pourquoi nous choisir</a></li>
                        <li><a href="#methodologies" class="text-gray-400 hover:text-white transition-colors">Méthodologies</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Liens utiles</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Connexion</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Support</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Email: contact@{{ config('app.name') }}.com</li>
                        <li>Téléphone: +XX XXX XXX XXX</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 {{ config('app.name') }}. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
