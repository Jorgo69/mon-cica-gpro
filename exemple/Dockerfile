# =============================================================================
# Dockerfile pour CICA-ARCHIVES (Laravel 10 + PHP 8.2)
# =============================================================================
# Ce Dockerfile construit une image PHP-FPM optimisée pour Laravel.
# Il installe toutes les extensions PHP nécessaires, Composer, Node.js,
# et prépare l'application pour être servie par Nginx.
# =============================================================================

# -----------------------------------------------------------------------------
# Étape 1 : Image de base PHP 8.2 avec FPM (FastCGI Process Manager)
# FPM est le gestionnaire de processus recommandé pour servir PHP avec Nginx
# -----------------------------------------------------------------------------
FROM php:8.2-fpm

# -----------------------------------------------------------------------------
# Étape 2 : Installation des dépendances système
# Ces paquets sont nécessaires pour compiler les extensions PHP et
# pour le bon fonctionnement de l'application
# -----------------------------------------------------------------------------
RUN apt-get update && apt-get install -y \
    # --- Outils de base ---
    git \
    curl \
    unzip \
    zip \
    # --- Bibliothèques pour les extensions PHP ---
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    # --- Client MySQL pour les commandes artisan ---
    default-mysql-client \
    # --- Superviseur pour gérer plusieurs processus ---
    supervisor \
    # Nettoyage du cache apt pour réduire la taille de l'image
    && docker-cleanup || apt-get clean && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------------------------------
# Étape 3 : Configuration et installation des extensions PHP
# Chaque extension est nécessaire pour Laravel et ses fonctionnalités
# -----------------------------------------------------------------------------

# GD : Manipulation d'images (thumbnails, avatars, etc.)
# On configure GD avec le support JPEG et FreeType (polices)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# Installation des extensions PHP requises :
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# -----------------------------------------------------------------------------
# Étape 4 : Installation de Composer (gestionnaire de dépendances PHP)
# On copie le binaire depuis l'image officielle Composer
# -----------------------------------------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# -----------------------------------------------------------------------------
# Étape 5 : Installation de Node.js 20 LTS (pour Vite et le build frontend)
# Node.js est nécessaire pour compiler les assets CSS/JS avec Vite
# -----------------------------------------------------------------------------
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------------------------------
# Étape 6 : Configuration de PHP pour la production
# On augmente les limites mémoire et la taille max des uploads
# -----------------------------------------------------------------------------
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Configuration PHP personnalisée pour Laravel
RUN echo "upload_max_filesize = 64M" >> "$PHP_INI_DIR/conf.d/laravel.ini" \
    && echo "post_max_size = 64M" >> "$PHP_INI_DIR/conf.d/laravel.ini" \
    && echo "memory_limit = 256M" >> "$PHP_INI_DIR/conf.d/laravel.ini" \
    && echo "max_execution_time = 120" >> "$PHP_INI_DIR/conf.d/laravel.ini"

# Configuration OPcache pour de meilleures performances
# OPcache met en cache le bytecode PHP compilé pour éviter de recompiler à chaque requête
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.max_accelerated_files=4000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# -----------------------------------------------------------------------------
# Étape 7 : Création de l'utilisateur applicatif
# On ne fait PAS tourner l'application en root pour des raisons de sécurité
# L'utilisateur www-data est le standard pour PHP-FPM
# -----------------------------------------------------------------------------
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# -----------------------------------------------------------------------------
# Étape 8 : Définition du répertoire de travail
# Toutes les commandes suivantes s'exécuteront dans ce dossier
# -----------------------------------------------------------------------------
WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Étape 9 : Copie des fichiers de dépendances en premier (pour le cache Docker)
# Docker met en cache chaque couche. En copiant d'abord les fichiers de
# dépendances, on évite de réinstaller tout si seul le code source change
# -----------------------------------------------------------------------------

# Dépendances PHP (Composer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Dépendances JavaScript (npm)
COPY package.json package-lock.json* ./
RUN npm ci --ignore-scripts 2>/dev/null || npm install

# -----------------------------------------------------------------------------
# Étape 10 : Copie de tout le code source de l'application
# -----------------------------------------------------------------------------
COPY . .

# -----------------------------------------------------------------------------
# Étape 11 : Finalisation de Composer (autoloader optimisé)
# Maintenant que tout le code est copié, on peut générer l'autoloader
# -----------------------------------------------------------------------------
RUN composer dump-autoload --optimize

# -----------------------------------------------------------------------------
# Étape 12 : Build des assets frontend avec Vite
# Compile et minifie les fichiers CSS/JS pour la production
# -----------------------------------------------------------------------------
RUN npm run build

# -----------------------------------------------------------------------------
# Étape 13 : Configuration des permissions
# Laravel a besoin d'écrire dans storage/ et bootstrap/cache/
# -----------------------------------------------------------------------------
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# -----------------------------------------------------------------------------
# Étape 14 : Copie du script d'entrée (entrypoint)
# Ce script s'exécute au démarrage du conteneur pour initialiser Laravel
# -----------------------------------------------------------------------------
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# -----------------------------------------------------------------------------
# Étape 15 : Exposition du port PHP-FPM
# Le port 9000 est le port standard de PHP-FPM
# Nginx se connecte à ce port pour envoyer les requêtes PHP
# -----------------------------------------------------------------------------
EXPOSE 9000

# -----------------------------------------------------------------------------
# Étape 16 : Point d'entrée et commande par défaut
# L'entrypoint exécute les migrations et le cache, puis lance PHP-FPM
# -----------------------------------------------------------------------------
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
