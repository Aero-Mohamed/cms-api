FROM php:8.2-fpm

# Install system dependencies and PHP extensions including Redis
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Copy Composer from official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set PHP memory limit to 512M (for: phpstan)
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Set working directory
WORKDIR /var/www

# Set correct permissions
RUN chown -R www-data:www-data /var/www
