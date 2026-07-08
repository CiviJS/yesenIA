#!/bin/sh
set -e

if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
    chown 1000:1000 /var/www/database/database.sqlite
    chmod 664 /var/www/database/database.sqlite
fi


php artisan migrate --force
php artisan config:clear

php artisan livewire:publish 
php artisan flux:publish 

exec php artisan serve --host=0.0.0.0 --port=8080
