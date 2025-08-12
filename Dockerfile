FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

# Pastikan direktori storage dan bootstrap/cache ada dan memiliki permission yang benar
RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/bootstrap/cache

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html

RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && cp .env.example .env \
    && php artisan key:generate \
    && php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear

# Jalankan storage:link setelah composer install
RUN php artisan storage:link

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN npm install && npm run build

# Perbaiki konfigurasi PHP untuk upload dan session
RUN echo "upload_max_filesize=50M\npost_max_size=50M\nmax_execution_time=300\nmax_input_time=300\nmemory_limit=256M\nsession.cookie_secure=0\nsession.cookie_httponly=1\nsession.cookie_samesite=Lax" > /usr/local/etc/php/conf.d/uploads.ini

# Set permission lagi setelah semua operasi selesai
# RUN chown -R www-data:www-data /var/www/html/storage \
#     && chown -R www-data:www-data /var/www/html/public/storage \
#     && chown -R www-data:www-data /var/www/html/bootstrap/cache \
#     && chmod -R 775 /var/www/html/storage \
#     && chmod -R 775 /var/www/html/public/storage \
#     && chmod -R 775 /var/www/html/bootstrap/cache

# Tambahkan script untuk memastikan permission saat container start
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]