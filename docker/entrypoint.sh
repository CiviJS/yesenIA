#!/bin/sh
set -e

chown -R www-data:www-data /var/www/database
chmod -R 775 /var/www/database


echo "Ejecutando migraciones ACTUALIZADO OJO SINO SALE ESTO ENTONCES NO ESTA USANDO ESTA VERSION."
php artisan migrate --force

echo "Limpiando cache..."
php artisan config:clear

echo "Iniciando PHP-FPM..."
php-fpm -F