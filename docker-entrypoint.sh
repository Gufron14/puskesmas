#!/bin/bash
set -e

# Pastikan direktori storage dan subdirektori ada
mkdir -p /var/www/html/storage/app/livewire-tmp
mkdir -p /var/www/html/storage/framework/{cache,sessions,views}
mkdir -p /var/www/html/bootstrap/cache

# Atur kepemilikan
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Atur izin
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Buat symbolic link untuk storage jika belum ada
if [ ! -L "/var/www/html/public/storage" ]; then
    php artisan storage:link
fi

# Bersihkan cache untuk memastikan konfigurasi terbaru digunakan
php artisan config:clear
php artisan cache:clear

exec "$@"