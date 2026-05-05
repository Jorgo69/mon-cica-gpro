#!/bin/bash
# =============================================================================
# Entrypoint CICA-GPRO
# =============================================================================

set -e
# Note: on desactive set -e temporairement pour les migrations (fallback vers migrate:fresh)

echo "=========================================="
echo "  CICA-GPRO — Demarrage du conteneur"
echo "=========================================="

# --- Etape 1 : Fichier .env ---
if [ ! -f /var/www/html/.env ]; then
    echo "[INFO] .env non trouve, copie depuis .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Injection des variables Docker dans .env
if [ -n "$DB_HOST" ]; then
    sed -i "s|^DB_CONNECTION=.*|DB_CONNECTION=${DB_CONNECTION:-mysql}|" /var/www/html/.env
    sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" /var/www/html/.env
    sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT:-3306}|" /var/www/html/.env
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE:-cica_gpro}|" /var/www/html/.env
    sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME:-gpro}|" /var/www/html/.env
    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD:-gpro_secret_2026}|" /var/www/html/.env
    echo "[OK] Variables DB injectees dans .env"
fi

if [ -n "$REDIS_HOST" ]; then
    sed -i "s|^REDIS_HOST=.*|REDIS_HOST=${REDIS_HOST}|" /var/www/html/.env
    sed -i "s|^REDIS_PASSWORD=.*|REDIS_PASSWORD=${REDIS_PASSWORD:-null}|" /var/www/html/.env
    sed -i "s|^CACHE_DRIVER=.*|CACHE_DRIVER=${CACHE_DRIVER:-redis}|" /var/www/html/.env
    sed -i "s|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=${QUEUE_CONNECTION:-redis}|" /var/www/html/.env
    sed -i "s|^SESSION_DRIVER=.*|SESSION_DRIVER=${SESSION_DRIVER:-redis}|" /var/www/html/.env
    echo "[OK] Variables Redis injectees"
fi

if [ -n "$APP_URL" ]; then
    sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" /var/www/html/.env
fi
if [ -n "$APP_ENV" ]; then
    sed -i "s|^APP_ENV=.*|APP_ENV=${APP_ENV}|" /var/www/html/.env
    sed -i "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG:-false}|" /var/www/html/.env
fi
if [ -n "$GPRO_MODE" ]; then
    sed -i "s|^GPRO_MODE=.*|GPRO_MODE=${GPRO_MODE:-selfhosted}|" /var/www/html/.env
fi
if [ -n "$BROADCAST_DRIVER" ]; then
    sed -i "s|^BROADCAST_DRIVER=.*|BROADCAST_DRIVER=${BROADCAST_DRIVER}|" /var/www/html/.env
fi

# --- Etape 2 : Attente base de donnees ---
echo "[INFO] Attente de la base de donnees..."
MAX_RETRIES=30
RETRY_COUNT=0

if [ "${DB_CONNECTION}" = "pgsql" ]; then
    while ! php -r "
        try {
            new PDO('pgsql:host=${DB_HOST:-postgres};port=${DB_PORT:-5432};dbname=${DB_DATABASE:-cica_gpro}',
                '${DB_USERNAME:-gpro}', '${DB_PASSWORD:-gpro_secret_2026}');
            exit(0);
        } catch (Exception \$e) { exit(1); }
    " 2>/dev/null; do
        RETRY_COUNT=$((RETRY_COUNT + 1))
        if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
            echo "[ATTENTION] Timeout DB apres ${MAX_RETRIES} tentatives"
            break
        fi
        echo "  Retry ${RETRY_COUNT}/${MAX_RETRIES}..."
        sleep 2
    done
else
    while ! php -r "
        try {
            new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306};dbname=${DB_DATABASE:-cica_gpro}',
                '${DB_USERNAME:-gpro}', '${DB_PASSWORD:-gpro_secret_2026}');
            exit(0);
        } catch (Exception \$e) { exit(1); }
    " 2>/dev/null; do
        RETRY_COUNT=$((RETRY_COUNT + 1))
        if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
            echo "[ATTENTION] Timeout DB apres ${MAX_RETRIES} tentatives"
            break
        fi
        echo "  Retry ${RETRY_COUNT}/${MAX_RETRIES}..."
        sleep 2
    done
fi
echo "[OK] Base de donnees prete !"

# --- Etape 3 : Cle d'application ---
if [ -z "$(grep '^APP_KEY=base64:' /var/www/html/.env)" ]; then
    echo "[INFO] Generation de la cle d'application..."
    php artisan key:generate --force
else
    echo "[OK] Cle d'application deja configuree"
fi

# --- Etape 4 : Vider le cache avant migration (important pour premier demarrage) ---
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# --- Etape 5 : Migrations + Seed ---
echo "[INFO] Execution des migrations..."
set +e  # Desactiver exit-on-error pour le fallback
if php artisan migrate --force 2>&1; then
    echo "[OK] Migrations terminees"
    # Seed si aucun utilisateur
    USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
    if [ "$USER_COUNT" = "0" ]; then
        echo "[INFO] Aucun utilisateur, seeding..."
        php artisan db:seed --force --no-interaction
        echo "[OK] Seeding termine"
    fi
else
    echo "[ATTENTION] Migration incrementale echouee, tentative migrate:fresh..."
    php artisan migrate:fresh --seed --force
    echo "[OK] migrate:fresh + seed termines"
fi
set -e  # Reactiver exit-on-error

# --- Etape 6 : Storage link ---
if [ ! -L /var/www/html/public/storage ]; then
    echo "[INFO] Creation du lien storage..."
    php artisan storage:link
fi

# --- Etape 7 : Permissions ---
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
echo "[OK] Permissions configurees"

# --- Etape 8 : Cache (apres migration, apres .env injecte) ---
echo "[INFO] Mise en cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "[OK] Cache configure"

echo "=========================================="
echo "  CICA-GPRO — Pret !"
echo "=========================================="

exec "$@"
