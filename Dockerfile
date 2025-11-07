# Use official PHP-FPM image
FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies + PHP extensions (including mysqli)
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql gd \
    # Ensure mysqli is loaded for both FPM and CLI
    && echo "extension=mysqli" > /usr/local/etc/php/conf.d/docker-php-ext-mysqli.ini

# Install Composer from official composer image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy project files into container
COPY . .

# Install PHP dependencies for production
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Set permissions for Laravel
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
