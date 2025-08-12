#!/bin/bash
set -e

cd /var/www/html

# Direktori & permission
mkdir -p storage/app/livewire-tmp
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Bersihkan cache lebih awal agar artisan baca env runtime (dari Koyeb)
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Jangan buat atau tulis file .env di container
# Jika APP_KEY tidak tersedia, generate sementara untuk proses ini saja (tanpa menulis file)
if [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY tidak ter-set, generate sementara untuk runtime..."
  export APP_KEY=$(php artisan key:generate --show)
fi

# Auto-map env dari Koyeb PostgreSQL Add-on jika DB_* belum diset
export DB_CONNECTION=${DB_CONNECTION:-pgsql}
export DB_HOST=${DB_HOST:-${POSTGRESQL_HOST:-localhost}}
export DB_PORT=${DB_PORT:-${POSTGRESQL_PORT:-5432}}
export DB_DATABASE=${DB_DATABASE:-${POSTGRESQL_DATABASE:-}}
export DB_USERNAME=${DB_USERNAME:-${POSTGRESQL_USER:-}}
export DB_PASSWORD=${DB_PASSWORD:-${POSTGRESQL_PASSWORD:-}}
export DB_SSLMODE=${DB_SSLMODE:-${POSTGRESQL_SSLMODE:-prefer}}

# Pastikan storage link (idempotent)
php artisan storage:link || true

# Tunggu Postgres ready (pakai PDO)
echo "Menunggu PostgreSQL siap di ${DB_HOST}:${DB_PORT}..."
until php -r 'try {
    $dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s", getenv("DB_HOST")?: "localhost", getenv("DB_PORT")?: "5432", getenv("DB_DATABASE")?: "");
    new PDO($dsn, getenv("DB_USERNAME")?: "", getenv("DB_PASSWORD")?: "");
    exit(0);
} catch (Throwable $e) { exit(1); }'; do
  sleep 2
done
echo "PostgreSQL siap. Lanjut..."

# Discover package dan migrasi
php artisan package:discover --ansi || true
php artisan migrate --force || true

# Optional: cache setelah semua siap
# php artisan config:cache || true
# php artisan route:cache || true
# php artisan view:cache || true

# Jalankan CMD (apache2-foreground)
exec "$@"
