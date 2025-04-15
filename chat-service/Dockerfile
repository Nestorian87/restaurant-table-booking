FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    zip \
    npm \
    nodejs \
    supervisor

RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl
RUN docker-php-ext-install pdo pdo_sqlite mbstring sockets

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader

EXPOSE 8004
CMD ["sh", "-c", "\
    php artisan migrate --force && \
    supervisord -c /var/www/supervisord.conf"]
