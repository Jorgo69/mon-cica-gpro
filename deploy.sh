#!/bin/bash
# ==============================================================================
# DEPLOY.SH - Scripts post-déploiement exécutés après le transfert FTP
# ==============================================================================

echo "--- Démarrage des tâches post-déploiement ---"

# 1. Vider les caches
echo "Nettoyage des caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Migrations
echo "Execution des migrations..."
php artisan migrate --force

# 3. Reconstruire les caches (production)
echo "Construction des caches de production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Optimisation Livewire (si nécessaire)
# php artisan livewire:publish --assets

echo "--- Déploiement terminé avec succès ! ---"
