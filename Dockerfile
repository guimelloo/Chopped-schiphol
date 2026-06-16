FROM composer:latest AS composer-build
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-dev --prefer-dist
COPY . .
RUN composer dump-autoload --optimize

FROM node:22-alpine AS node-build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
COPY --from=composer-build /app/vendor /app/vendor
RUN echo "VITE_APP_NAME=Chopped Schiphol" > .env && npm run build

FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    zip \
    unzip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        opcache

WORKDIR /var/www/html

COPY . .
COPY --from=composer-build /app/vendor /var/www/html/vendor
COPY --from=node-build /app/public/build /var/www/html/public/build

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
