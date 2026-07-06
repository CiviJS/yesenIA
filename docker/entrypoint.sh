#!/bin/sh
set -e


if [ ! -f /var/www/vendor/autoload.php ]; then
    echo "ERROR: vendor/autoload.php no encontrado. Ejecutando composer install de emergencia..."
    cd /var/www && composer install --no-dev --optimize-autoloader
fi

mkdir -p /var/www/database
if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
fi

echo "Aplicando permisos..."
chown -R www-data:www-data /var/www/database /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/database /var/www/storage /var/www/bootstrap/cache

echo "Ejecutando tareas de Laravel..."
php artisan migrate --force
php artisan config:clear
php artisan key:generate

echo "Iniciando PHP-FPM..."
exec php-fpm -F