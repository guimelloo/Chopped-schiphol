#!/bin/sh
set -e

php artisan migrate --force

# Seed database on first run (fails silently if data already exists)
php artisan db:seed --force 2>&1 || true

exec php-fpm
