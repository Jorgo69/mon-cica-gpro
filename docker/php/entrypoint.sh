#!/bin/bash
set -e

echo "=========================================="
echo "  CICA-GPRO — Starting application..."
echo "=========================================="

# Create log directory
mkdir -p /var/log/php

# Wait for database to be ready
echo "[1/7] Waiting for database..."
MAX_RETRIES=30
RETRY=0
until php artisan db:monitor --databases="${DB_CONNECTION:-mysql}" > /dev/null 2>&1 || [ $RETRY -eq $MAX_RETRIES ]; do
    RETRY=$((RETRY + 1))
    echo "  Database not ready, retry $RETRY/$MAX_RETRIES..."
    sleep 2
done

if [ $RETRY -eq $MAX_RETRIES ]; then
    echo "  WARNING: Database check timed out, proceeding anyway..."
fi

# Generate key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "[2/7] Generating application key..."
    php artisan key:generate --force
else
    echo "[2/7] Application key already set."
fi

# Storage link
echo "[3/7] Storage link..."
php artisan storage:link --force 2>/dev/null || true

# Migrations
echo "[4/7] Running migrations..."
php artisan migrate --force --no-interaction

# Seed if fresh install (no users)
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null || echo "0")
if [ "$USER_COUNT" = "0" ]; then
    echo "[5/7] Fresh install detected, seeding..."
    php artisan db:seed --force --no-interaction
else
    echo "[5/7] Database already seeded ($USER_COUNT users), skipping."
fi

# Cache
echo "[6/7] Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Permissions
echo "[7/7] Fixing permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "=========================================="
echo "  CICA-GPRO is ready!"
echo "=========================================="

# Execute CMD (php-fpm)
exec "$@"
