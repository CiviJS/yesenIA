FROM php:8.3-fpm-alpine

RUN apk add --no-cache libpng-dev libzip-dev zip unzip sqlite-dev nodejs npm \
    && docker-php-ext-install pdo_mysql pdo_sqlite bcmath gd

RUN echo "[global]" > /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "error_log = /proc/self/fd/2" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "[www]" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "listen = 0.0.0.0:9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www


COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

RUN npm install && npm run build

RUN mkdir -p /var/www/database /var/www/storage /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www/database /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/database /var/www/storage /var/www/bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]