# syntax=docker/dockerfile:1
#
# Multi-stage build producing two final images from one Dockerfile:
#   --target app  (php-fpm, application code)
#   --target web  (nginx, serves public/ and proxies *.php to `app`)
#
# Mirrors the Luminet/ToonAssets Dockerfiles on the same AlphaVPS box so
# every app there is operated the same way.

# ---------------------------------------------------------------------
# Stage: composer dependencies
# ---------------------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs \
    --prefer-dist

# ---------------------------------------------------------------------
# Stage: frontend assets (Tailwind v4 / Vite build)
# ---------------------------------------------------------------------
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci

# Cache-bust so every commit rebuilds the frontend bundle.
ARG GIT_SHA=unknown
RUN echo "Building assets for commit ${GIT_SHA}"

COPY resources ./resources
COPY vite.config.js ./
COPY public ./public
RUN npm run build

# ---------------------------------------------------------------------
# Stage: shared PHP base — extensions + app code + vendor + built assets
# ---------------------------------------------------------------------
FROM php:8.3-fpm AS php-base

# gd is needed by dompdf (ticket PDFs) and endroid/qr-code.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
        libicu-dev libjpeg62-turbo-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql mbstring exif pcntl bcmath gd intl zip opcache \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/zz-tiko-opcache.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-tiko-php.ini

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --classmap-authoritative \
    && chown -R www-data:www-data storage bootstrap/cache public

# ---------------------------------------------------------------------
# Stage: app — php-fpm
# ---------------------------------------------------------------------
FROM php-base AS app
COPY docker/php/entrypoint.sh /usr/local/bin/tiko-entrypoint
USER root
RUN chmod +x /usr/local/bin/tiko-entrypoint
USER www-data
ENTRYPOINT ["tiko-entrypoint"]
EXPOSE 9000
CMD ["php-fpm"]

# ---------------------------------------------------------------------
# Stage: web — nginx, only ever gets public/
# ---------------------------------------------------------------------
FROM nginx:1.27-alpine AS web
COPY --from=php-base /var/www/html/public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
