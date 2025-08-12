#!/bin/bash
set -e

cd /var/www/html

# Siapkan .env kalau belum ada
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Direktori & permission
mkdir -p storage/app/livewire-tmp
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Pastikan storage link
php artisan storage:link || true

# Generate app key kalau kosong
php artisan key:generate || true

# Tunggu Postgres ready (pakai PDO biar simple)
echo "Menunggu PostgreSQL siap di ${DB_HOST:-localhost}:${DB_PORT:-5432}..."
until php -r 'try {
    $dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s", getenv("DB_HOST")?: "localhost", getenv("DB_PORT")?: "5432", getenv("DB_DATABASE")?: "");
    new PDO($dsn, getenv("DB_USERNAME")?: "", getenv("DB_PASSWORD")?: "");
    exit(0);
} catch (Throwable $e) { exit(1); }'; do
  sleep 2
done
echo "PostgreSQL siap. Lanjut..."

# Sekarang aman jalanin stuff yang bisa sentuh DB
php artisan package:discover --ansi || true
php artisan migrate --force || true

# Bersihin & cache config/views (opsional: route cache kalau gak ada closure)
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
# php artisan route:cache || true

# Lariin CMD (apache2-foreground)
exec "$@"
