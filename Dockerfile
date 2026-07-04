FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    zip unzip git \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .


RUN chown -R www-data:www-data /var/www/storage /var/www/database