FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    zip \
    npm \
    nodejs

RUN docker-php-ext-install pdo pdo_sqlite mbstring sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader

EXPOSE 8002
CMD ["sh", "-c", "unlink public/storage && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8002"]
