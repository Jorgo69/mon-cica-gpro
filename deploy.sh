#!/bin/bash
# ==============================================================================
# DEPLOY.SH - Scripts post-déploiement exécutés après le transfert FTP
# ==============================================================================

echo "--- Démarrage des tâches post-déploiement ---"

# 1. Vider les caches
echo "Configuration du cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Migrations
echo "Exécution des migrations..."
php artisan migrate --force

# 3. Optimisation Livewire (si nécessaire)
# php artisan livewire:publish --assets

echo "--- Déploiement terminé avec succès ! ---"
