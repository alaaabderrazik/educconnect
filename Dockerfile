FROM php:8.2-cli

WORKDIR /var/www/html

# install deps
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# copy project (صححناها هنا)
COPY . .

# install laravel deps
RUN composer install --no-dev --optimize-autoloader

# fix permissions
RUN chmod -R 775 storage bootstrap/cache

# start server (صححناها هنا)
CMD php -S 0.0.0.0:$PORT -t public