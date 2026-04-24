FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files (we are now in the root)
COPY . .

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Expose port
ENV PORT=8080
EXPOSE ${PORT}

# Start server as requested by user
CMD php -S 0.0.0.0:${PORT} -t public
