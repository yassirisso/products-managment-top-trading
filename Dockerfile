FROM php:8.2-cli

WORKDIR /var/www/html


RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        zip \
        pdo \
        pdo_mysql \
        gd


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . .


ENV COMPOSER_MEMORY_LIMIT=-1


RUN composer install --no-dev --optimize-autoloader


RUN php artisan storage:link


EXPOSE 8000


CMD php artisan serve --host=0.0.0.0 --port=8000