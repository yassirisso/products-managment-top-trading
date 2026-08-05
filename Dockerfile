FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    nodejs \
    npm \
    libpng-dev \
    libzip-dev

RUN docker-php-ext-install pdo_mysql gd zip

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN npm install

RUN npm run build

RUN php artisan storage:link

CMD php artisan serve --host=0.0.0.0 --port=8000