FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl ca-certificates libpng-dev libonig-dev libxml2-dev zip unzip libicu-dev libzip-dev libsqlite3-dev \
    && apt-get install -y --no-install-recommends nodejs npm \
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath intl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

COPY package.json package-lock.json ./
RUN npm ci --omit=optional

COPY . .

RUN touch database/database.sqlite \
    && chmod 666 database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/public/build \
    && npm run build

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]