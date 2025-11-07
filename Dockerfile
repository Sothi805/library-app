FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

WORKDIR /var/www/html

COPY . .

RUN chmod -R 777 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
