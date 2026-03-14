# Guide : Tests et Qualité en Temps Réel (DX)

Pour obtenir une expérience de développement (DX) proche de Vue/Vite avec Laravel, voici les outils recommandés :

## 1. Analyse Statique (Anti-Syntax Errors)
Au lieu d'attendre l'erreur au runtime, utilise **PHPStan**. Il détecte les erreurs de syntaxe et de logique pendant que tu codes.
```bash
composer require --dev phpstan/phpstan
vendor/bin/phpstan analyse app
```

## 2. Tests Automatisés "Watch Mode"
Pour lancer tes tests (Pest) à chaque modification de fichier, installe le module de watch.
```bash
composer require pestphp/pest-plugin-watch --dev
php artisan test --watch
```

## 3. Linting et Style (Pint)
Laravel Pint permet d'uniformiser ton code automatiquement. Tu peux le lancer avant chaque commit ou build.
```bash
./vendor/bin/pint
```

## 4. Vite (Déjà configuré)
Pour le CSS et le JS (Alpine.js), la commande suivante gère le Refresh en temps réel (HMR) :
```bash
npm run dev
```

## 5. Astuce Blade + Alpine
Lors de l'utilisation de composants Blade (`<x-... />`), évite d'utiliser `:class="{...}"` pour passer de la logique Alpine, car Blade tente de l'interpréter comme du PHP. Utilise toujours **`x-bind:class`** pour rester côté JavaScript.
