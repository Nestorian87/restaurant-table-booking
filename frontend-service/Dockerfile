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

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader

EXPOSE 8000
CMD ["sh", "-c", "\
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=8000"]
