#!/bin/sh
set -e

cd /var/www

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    composer install
fi

php artisan key:generate
php artisan optimize:clear
php artisan migrate --force

chmod -R 775 storage bootstrap/cache

exec "$@"
