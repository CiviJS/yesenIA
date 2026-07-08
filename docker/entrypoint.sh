#!/bin/sh
set -e

if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
    chown 1000:1000 /var/www/database/database.sqlite
    chmod 664 /var/www/database/database.sqlite
fi


php artisan migrate --force
php artisan config:clear

php artisan livewire:publish --assets
php artisan flux:publish --assets

exec php -S 0.0.0.0:8080 -t public
