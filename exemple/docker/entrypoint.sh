#!/bin/bash
# =============================================================================
# Script d'entrée (entrypoint) pour le conteneur CICA-ARCHIVES
# =============================================================================
# Ce script s'exécute automatiquement au démarrage du conteneur.
# Il prépare l'environnement Laravel avant de lancer PHP-FPM.
#
# Étapes :
#   1. Vérifier que le fichier .env existe
#   2. Attendre que MySQL soit prêt
#   3. Générer la clé d'application si nécessaire
#   4. Exécuter les migrations de base de données
#   5. Mettre en cache la configuration Laravel
#   6. Créer le lien symbolique pour le stockage public
#   7. Lancer la commande passée en argument (php-fpm)
# =============================================================================

set -e  # Arrêter le script en cas d'erreur

echo "========================================"
echo "  CICA-ARCHIVES - Démarrage du conteneur"
echo "========================================"

# -----------------------------------------------------------------------------
# Étape 1 : Vérifier/créer le fichier .env
# Le fichier .env contient la configuration de l'application.
# S'il n'existe pas, on le copie depuis .env.example puis on injecte
# les variables d'environnement Docker pour écraser les valeurs par défaut
# -----------------------------------------------------------------------------
if [ ! -f /var/www/html/.env ]; then
    echo "[INFO] Fichier .env non trouvé, copie depuis .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Mise à jour du .env avec les variables Docker (passées par docker-compose)
# On utilise sed pour remplacer les valeurs dans le fichier .env
# Cela garantit que Laravel utilise les bons paramètres de connexion Docker
if [ -n "$DB_HOST" ]; then
    sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" /var/www/html/.env
    sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT:-3306}|" /var/www/html/.env
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" /var/www/html/.env
    sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" /var/www/html/.env
    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" /var/www/html/.env
    echo "[OK] Variables de connexion DB injectées dans .env"
fi

# -----------------------------------------------------------------------------
# Étape 2 : Attendre que MySQL soit prêt
# On utilise PHP pour tester la connexion via PDO (même driver que Laravel)
# C'est plus fiable que mysqladmin car ça teste avec les mêmes credentials
# -----------------------------------------------------------------------------
echo "[INFO] Attente de la connexion MySQL..."
MAX_RETRIES=30
RETRY_COUNT=0

# Test de connexion via PHP/PDO — identique à ce que Laravel utilise
while ! php -r "
    try {
        new PDO(
            'mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-cica-archives}',
            '${DB_USERNAME:-cica_user}',
            '${DB_PASSWORD:-cica_secret}'
        );
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "[ERREUR] Impossible de se connecter à MySQL après ${MAX_RETRIES} tentatives"
        echo "[INFO] Démarrage quand même..."
        break
    fi
    echo "[INFO] MySQL pas encore prêt... tentative ${RETRY_COUNT}/${MAX_RETRIES}"
    sleep 2
done

echo "[OK] MySQL est prêt !"

# -----------------------------------------------------------------------------
# Étape 3 : Générer la clé d'application Laravel (si pas déjà définie)
# La clé APP_KEY est utilisée pour chiffrer les sessions, cookies, etc.
# Elle ne doit être générée qu'une seule fois.
# -----------------------------------------------------------------------------
if [ -z "$(grep '^APP_KEY=base64:' /var/www/html/.env)" ]; then
    echo "[INFO] Génération de la clé d'application..."
    php artisan key:generate --force
else
    echo "[OK] Clé d'application déjà configurée"
fi

# -----------------------------------------------------------------------------
# Étape 4 : Exécuter les migrations de base de données
# --force est nécessaire en environnement de production
# Les migrations créent/modifient les tables de la base de données
# -----------------------------------------------------------------------------
echo "[INFO] Exécution des migrations de base de données..."
if php artisan migrate --force; then
    echo "[OK] Migrations terminées"
else
    echo "[ATTENTION] Certaines migrations ont échoué, vérifiez les logs"
fi

# -----------------------------------------------------------------------------
# Étape 5 : Mise en cache de la configuration Laravel
# Accélère l'application en combinant tous les fichiers de config en un seul
# -----------------------------------------------------------------------------
echo "[INFO] Mise en cache de la configuration..."
php artisan config:cache    # Cache la configuration (config/*.php → cache)
php artisan route:cache     # Cache les routes (plus rapide en production)
php artisan view:cache      # Cache les vues Blade compilées
echo "[OK] Cache configuré"

# -----------------------------------------------------------------------------
# Étape 6 : Créer le lien symbolique pour le stockage public
# Permet d'accéder aux fichiers uploadés via une URL publique
# storage/app/public → public/storage
# -----------------------------------------------------------------------------
if [ ! -L /var/www/html/public/storage ]; then
    echo "[INFO] Création du lien symbolique storage..."
    php artisan storage:link
    echo "[OK] Lien storage créé"
fi

# -----------------------------------------------------------------------------
# Étape 7 : S'assurer que les permissions sont correctes
# www-data (l'utilisateur PHP-FPM) doit pouvoir écrire dans ces dossiers
# -----------------------------------------------------------------------------
echo "[INFO] Vérification des permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
echo "[OK] Permissions configurées"

echo "========================================"
echo "  CICA-ARCHIVES - Prêt !"
echo "  Accessible sur http://localhost:8080"
echo "========================================"

# -----------------------------------------------------------------------------
# Étape 8 : Exécuter la commande passée en argument
# Par défaut : "php-fpm" (défini dans le CMD du Dockerfile)
# exec remplace le processus shell par php-fpm (PID 1)
# Cela permet à Docker de gérer correctement les signaux d'arrêt
# -----------------------------------------------------------------------------
exec "$@"
