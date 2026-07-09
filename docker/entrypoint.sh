#!/bin/sh
set -e

if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
    chown 1000:1000 /var/www/database/database.sqlite
    chmod 664 /var/www/database/database.sqlite
fi


php artisan migrate:fresh --seed
php artisan config:clear
npm run build

exec php artisan serve --host=0.0.0.0 --port=8080
