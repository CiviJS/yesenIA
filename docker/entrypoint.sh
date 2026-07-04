#!/bin/sh
set -e

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/public/build || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/public || true

if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
    chmod 666 /var/www/database/database.sqlite
fi

php artisan key:generate --force || true
php artisan migrate --force || true

exec php-fpm -R
