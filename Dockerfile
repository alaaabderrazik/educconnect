FROM php:8.2-cli

WORKDIR /var/www/html

# install deps
RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ⚠️ هنا التغيير المهم
COPY ./laravel-app .

# install laravel deps
RUN composer install --no-dev --optimize-autoloader

# fix permissions
RUN chmod -R 775 storage bootstrap/cache

# generate key (مهم!)
RUN php artisan key:generate

CMD php artisan serve --host=0.0.0.0 --port=9000 