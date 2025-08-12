FROM php:8.2-apache

# --- System deps
RUN apt-get update && apt-get install -y \
    git unzip curl pkg-config \
    libpq-dev libzip-dev libicu-dev \
    libonig-dev \ 
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
 && rm -rf /var/lib/apt/lists/*

# --- PHP extensions
# gd perlu di-config agar support jpeg & freetype
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j$(nproc) \
    pdo pdo_pgsql pgsql pdo_mysql \
    mbstring zip intl gd

# --- Apache document root & mod_rewrite
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
  /etc/apache2/sites-available/000-default.conf /etc/apache2/apache2.conf \
  && a2enmod rewrite

WORKDIR /var/www/html

# --- Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# 1) Warm vendor cache TANPA scripts (artisan belum dicopy)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-ansi --no-progress --no-scripts

# 2) Copy semua source (termasuk artisan)
COPY . .

# 3) Permission Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R ug+rwx storage bootstrap/cache

# 4) Sekarang bolehin composer scripts (artisan sudah ada)
RUN composer install --no-dev --no-interaction --prefer-dist --no-ansi --no-progress

# (opsional) cache laravel di runtime setelah APP_KEY ada:
# RUN php artisan config:cache && php artisan route:cache

# --- Koyeb listen port
ENV PORT=8000
EXPOSE ${PORT}
RUN sed -i "s/Listen 80/Listen \${PORT}/" /etc/apache2/ports.conf

HEALTHCHECK CMD curl -f http://localhost:${PORT}/ || exit 1
CMD ["apache2-foreground"]
