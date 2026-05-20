FROM php:8.4-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    sqlite-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    zip \
    unzip

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        opcache

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first (layer cache)
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-dev --prefer-dist

COPY . .

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
