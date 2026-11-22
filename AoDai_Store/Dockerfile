FROM node:22-bookworm-slim AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


FROM composer:2 AS composer


FROM php:8.2-apache
WORKDIR /var/www/html

# Cài đặt PHP extensions và Apache
RUN apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates libonig-dev libzip-dev unzip \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && a2enmod rewrite \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel \
    && rm -rf /var/lib/apt/lists/*

# Cài đặt Composer
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-autoloader

# Copy mã nguồn Laravel
COPY . .

# Copy Tailwind CSS đã build
COPY --from=frontend /app/public/build ./public/build

# Chuẩn bị thư mục và phân quyền
RUN composer dump-autoload --no-dev --optimize \
    && mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        storage/app/public/ratings \
        public/img/products \
        public/img/details \
        bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
        public/img/products \
        public/img/details \
    && chmod -R 775 \
        storage \
        bootstrap/cache \
        public/img/products \
        public/img/details \
    && php artisan storage:link \
    && sed -i 's!/var/www/html!/var/www/html/public!g' \
        /etc/apache2/sites-available/000-default.conf \
        /etc/apache2/apache2.conf

# Cấu hình Render
ENV PORT=10000

EXPOSE 10000

CMD ["sh", "-c", "sed -i \"s/Listen 80/Listen ${PORT}/\" /etc/apache2/ports.conf && sed -i \"s/:80>/:${PORT}>/\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"]
