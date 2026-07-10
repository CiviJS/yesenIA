FROM php:8.3-fpm-alpine

RUN apk add --no-cache libpng-dev libzip-dev zip unzip sqlite-dev nodejs npm \
    && docker-php-ext-install pdo_mysql pdo_sqlite bcmath gd

RUN sed -i 's/user = www-data/user = 1000/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/group = www-data/group = 1000/g' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/user = www-data/user = 1000/g' /usr/local/etc/php-fpm.d/docker.conf && \
    sed -i 's/group = www-data/group = 1000/g' /usr/local/etc/php-fpm.d/docker.conf

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize && \
    npm install && npm run build

RUN mkdir -p /var/www/storage /var/www/bootstrap/cache /var/www/database && \
    chown -R 1000:1000 /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/public && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database && \
    chmod -R 755 /var/www/public

VOLUME /var/www/public

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]