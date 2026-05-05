# =============================================================================
# Dockerfile pour CICA-GPRO (Laravel 12 + PHP 8.3)
# =============================================================================

# --- Image de base PHP 8.3 FPM ---
FROM php:8.3-fpm

# --- Dependances systeme ---
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    libsqlite3-dev \
    default-mysql-client \
    supervisor \
    nano \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- Extensions PHP ---
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    mysqli \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    xml

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# --- Composer ---
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- Node.js 20 LTS ---
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# --- PHP production config ---
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN echo "upload_max_filesize = 64M" >> "$PHP_INI_DIR/conf.d/gpro.ini" \
    && echo "post_max_size = 64M" >> "$PHP_INI_DIR/conf.d/gpro.ini" \
    && echo "memory_limit = 256M" >> "$PHP_INI_DIR/conf.d/gpro.ini" \
    && echo "max_execution_time = 120" >> "$PHP_INI_DIR/conf.d/gpro.ini" \
    && echo "max_input_vars = 5000" >> "$PHP_INI_DIR/conf.d/gpro.ini"

# OPcache
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.interned_strings_buffer=16" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# --- Utilisateur ---
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# --- Repertoire de travail ---
WORKDIR /var/www/html

# --- Dependances PHP (cache Docker) ---
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# --- Dependances JS (cache Docker) ---
COPY package.json package-lock.json* ./
RUN npm ci --ignore-scripts 2>/dev/null || npm install

# --- Code source ---
COPY . .

# --- Finaliser Composer ---
RUN composer dump-autoload --optimize

# --- Build frontend ---
RUN npm run build

# --- Permissions ---
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# --- Entrypoint ---
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
