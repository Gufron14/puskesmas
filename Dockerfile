FROM php:8.2-apache

# Sistem deps + PGSQL extension
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libonig-dev libxml2-dev libpq-dev \
 && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Apache ke /public dan rewrite
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
 && a2enmod rewrite

WORKDIR /var/www/html

# Optimalkan cache layer composer
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-scripts

# Baru copy semua source (biar layer composer ke-cache)
COPY . /var/www/html

# Direktori penting + permission dasar
RUN mkdir -p storage/app/public \
 && mkdir -p storage/logs \
 && mkdir -p storage/framework/cache \
 && mkdir -p storage/framework/sessions \
 && mkdir -p storage/framework/views \
 && mkdir -p bootstrap/cache \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html \
 && chmod -R 775 storage bootstrap/cache

# (Opsional) Build asset—kalau mau simple tetap di image ini
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get install -y nodejs \
 && npm install \
 && npm run build

# PHP upload & session tuning
RUN printf "upload_max_filesize=50M\npost_max_size=50M\nmax_execution_time=300\nmax_input_time=300\nmemory_limit=256M\nsession.cookie_secure=0\nsession.cookie_httponly=1\nsession.cookie_samesite=Lax\n" > /usr/local/etc/php/conf.d/uploads.ini

# Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
