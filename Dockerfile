FROM php:8.3-fpm

ARG NODE_VERSION=20

RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libicu-dev libzip-dev libsqlite3-dev \
    && curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash - \
    && apt-get install -y --no-install-recommends nodejs \
    && docker-php-ext-configure gd --with-external-gd \
    && docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd intl zip \
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

EXPOSE 9000

CMD ["php-fpm"]