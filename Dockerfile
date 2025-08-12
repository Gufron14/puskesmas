FROM php:8.2-apache

# deps
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libicu-dev libpng-dev curl \
 && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql mbstring zip intl gd

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
  && a2enmod rewrite

WORKDIR /var/www/html

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# 1) install vendor TANPA scripts (belum ada artisan)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-ansi --no-progress --no-scripts

# 2) baru copy semua source (ini baru bawa file artisan)
COPY . .

# 3) permission
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache

# 4) sekarang bolehin scripts (artisan sudah ada)
RUN composer install --no-dev --no-interaction --prefer-dist --no-ansi --no-progress

# (opsional) cache laravel
# RUN php artisan config:cache && php artisan route:cache

# Koyeb listen
ENV PORT=8000
EXPOSE ${PORT}
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

HEALTHCHECK CMD curl -f http://localhost:${PORT}/ || exit 1
CMD ["apache2-foreground"]
