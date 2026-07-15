# ════════════════════════════════════════════════════════════════════
# Stage 1 — Node: compile JS/CSS assets
# ════════════════════════════════════════════════════════════════════
FROM node:20-alpine AS assets

WORKDIR /app

COPY package*.json ./
RUN npm ci --frozen-lockfile

COPY vite.config.js ./
COPY resources/ resources/
COPY public/ public/

RUN npm run build

# ════════════════════════════════════════════════════════════════════
# Stage 2 — Composer: install PHP dependencies (no dev)
# ════════════════════════════════════════════════════════════════════
FROM composer:2.7 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
# `install` (no `update`): build reproducible respetando composer.lock.
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts --ignore-platform-reqs

# ════════════════════════════════════════════════════════════════════
# Stage 3 — Production: PHP-FPM + Nginx via Supervisor
# ════════════════════════════════════════════════════════════════════
FROM php:8.2-fpm-alpine AS production

ARG APP_VERSION=dev
ENV APP_VERSION=${APP_VERSION}

# System packages
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev

# PHP extensions (+ PECL redis — phpize/autoconf via $PHPIZE_DEPS)
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        zip \
        opcache \
        intl \
        pcntl \
 && pecl install redis \
 && docker-php-ext-enable redis \
 && apk del .build-deps

# PHP production tuning
COPY docker/php/php.ini        $PHP_INI_DIR/conf.d/kapitalya.ini
COPY docker/php/opcache.ini    $PHP_INI_DIR/conf.d/opcache.ini

# PHP-FPM pool personalizado (max_children=25, max_requests=500)
COPY docker/php/www.conf       /usr/local/etc/php-fpm.d/www.conf

# Nginx
COPY docker/nginx/nginx.conf   /etc/nginx/nginx.conf
COPY docker/nginx/site.conf    /etc/nginx/http.d/default.conf

# Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# Application files
COPY --from=vendor  /app/vendor        ./vendor
COPY --from=vendor  /app               .
COPY --from=assets  /app/public/build  ./public/build

# Storage & cache dirs with correct ownership
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=10s --start-period=15s --retries=3 \
    CMD curl -sf http://localhost/api/health/live || exit 1

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
