# Base image PHP 8.2 + Apache
FROM php:8.2-apache

# 1) System deps
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libonig-dev libzip-dev libicu-dev libpng-dev \
    && rm -rf /var/lib/apt/lists/*

# 2) PHP extensions (pgsql buat Koyeb, pdo_mysql buat lokal/dev)
RUN docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql mbstring zip intl gd

# 3) Apache: point ke /public, enable mod_rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
 && a2enmod rewrite

# 4) Workdir & copy composer terlebih dulu untuk cache
WORKDIR /var/www/html
COPY composer.json composer.lock* ./

# 5) Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --no-interaction --no-ansi --no-progress --prefer-dist

# 6) Copy source
COPY . .

# 7) Laravel perms
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache

# 8) Cache config & routes (opsional, jalan kalau APP_KEY sudah ada)
# RUN php artisan config:cache && php artisan route:cache

# 9) Koyeb listen di $PORT (Apache default 80 -> ganti ke $PORT)
ENV PORT=8000
EXPOSE ${PORT}
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

# 10) Healthcheck sederhana (opsional)
HEALTHCHECK CMD curl -f http://localhost:${PORT}/ || exit 1

# 11) Entrypoint: jangan generate key di build; set di env
CMD ["apache2-foreground"]
