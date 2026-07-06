#!/bin/sh
set -e

if [ ! -d /var/www/database ]; then
    mkdir -p /var/www/database
fi

if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
fi
if [ -f /var/www/vendor/autoload.php ]; then
    echo "Ejecutando migraciones..."
    php artisan migrate --force
else
    echo "Error: vendor/autoload.php no encontrado"
fi

chown -R www-data:www-data /var/www/database
chmod -R 775 /var/www/database
echo "Ejecutando migraciones ACTUALIZADO OJO SINO SALE ESTO ENTONCES NO ESTA USANDO ESTA VERSION."
php artisan migrate --force

echo "Limpiando cache..."
php artisan config:clear

echo "Iniciando PHP-FPM..."
php-fpm -F