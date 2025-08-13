#!/bin/bash
set -e

cd /var/www/html

# Direktori & permission
mkdir -p storage/app/livewire-tmp
mkdir -p storage/framework/{cache,sessions,views}
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Bersihkan config & views lebih awal agar artisan baca env runtime (tidak menyentuh DB)
php artisan config:clear || true
php artisan view:clear || true

# Jangan buat atau tulis file .env di container
# Jika APP_KEY tidak tersedia, generate sementara untuk proses ini saja (tanpa menulis file)
if [ -z "${APP_KEY:-}" ]; then
  echo "APP_KEY tidak ter-set, generate sementara untuk runtime..."
  export APP_KEY=$(php artisan key:generate --show)
fi

# Default agar tidak menyentuh DB saat cache:clear jika user belum set
export CACHE_DRIVER=${CACHE_DRIVER:-file}

# Auto-map env dari Koyeb PostgreSQL Add-on jika DB_* belum diset
export DB_CONNECTION=${DB_CONNECTION:-pgsql}
export DB_HOST=${DB_HOST:-${POSTGRESQL_HOST:-}}
export DB_PORT=${DB_PORT:-${POSTGRESQL_PORT:-5432}}
export DB_DATABASE=${DB_DATABASE:-${POSTGRESQL_DATABASE:-}}
export DB_USERNAME=${DB_USERNAME:-${POSTGRESQL_USER:-}}
export DB_PASSWORD=${DB_PASSWORD:-${POSTGRESQL_PASSWORD:-}}
export DB_SSLMODE=${DB_SSLMODE:-${POSTGRESQL_SSLMODE:-prefer}}

# Jika ada POSTGRESQL_URL / DATABASE_URL, parse ke DB_*
URL_TO_PARSE="${POSTGRESQL_URL:-${DATABASE_URL:-}}"
if [ -n "$URL_TO_PARSE" ]; then
  # Format yang umum: postgres://user:pass@host:port/dbname?sslmode=require
  proto_removed=${URL_TO_PARSE#*://}
  if [[ "$URL_TO_PARSE" == *"@"* ]]; then
    userpass=${proto_removed%%@*}
    hostpath=${proto_removed#*@}
  else
    userpass=""
    hostpath=$proto_removed
  fi
  hostport=${hostpath%%/*}
  dbname_qs=${hostpath#*/}
  dbname=${dbname_qs%%\?*}
  host=${hostport%%:*}
  port=${hostport#*:}
  [ "$host" = "$hostport" ] && port="5432"

  if [ -n "$userpass" ]; then
    dbuser=${userpass%%:*}
    dbpass=${userpass#*:}
  fi

  export DB_HOST=${DB_HOST:-$host}
  export DB_PORT=${DB_PORT:-$port}
  export DB_DATABASE=${DB_DATABASE:-$dbname}
  [ -n "$dbuser" ] && export DB_USERNAME=${DB_USERNAME:-$dbuser}
  [ -n "$dbpass" ] && export DB_PASSWORD=${DB_PASSWORD:-$dbpass}
fi

# Pastikan storage link (idempotent)
php artisan storage:link || true

# Jika koneksi pgsql dan DB_HOST tersedia, tunggu DB siap
if [ "${DB_CONNECTION}" = "pgsql" ] && [ -n "${DB_HOST}" ]; then
  echo "Menunggu PostgreSQL siap di ${DB_HOST}:${DB_PORT}..."
  until php -r 'try {
      $dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s", getenv("DB_HOST")?: "localhost", getenv("DB_PORT")?: "5432", getenv("DB_DATABASE")?: "");
      new PDO($dsn, getenv("DB_USERNAME")?: "", getenv("DB_PASSWORD")?: "");
      exit(0);
  } catch (Throwable $e) { exit(1); }'; do
    sleep 2
  done
  echo "PostgreSQL siap. Lanjut..."
else
  echo "Peringatan: DB_HOST tidak ter-set atau DB_CONNECTION bukan pgsql. Lewati penantian DB."
fi

# Discover package dan migrasi (akan berhasil jika DB terkonfigurasi benar)
php artisan package:discover --ansi || true
php artisan migrate --force || true

# Jalankan seeder hanya jika table users masih kosong (first time deploy)
USER_COUNT=$(php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap(); echo App\Models\User::count();") 
if [ "$USER_COUNT" = "0" ]; then
    echo "Menjalankan database seeder untuk pertama kali..."
    php artisan db:seed --force || true
else
    echo "Database sudah berisi user, skip seeding."
fi

# Jalankan cache:clear setelah DB siap jika driver=database, jika bukan maka aman kapan saja
if [ "${CACHE_DRIVER}" = "database" ]; then
  php artisan cache:clear || true
else
  php artisan cache:clear || true
fi

# Optional: cache setelah semua siap
# php artisan config:cache || true
# php artisan route:cache || true
# php artisan view:cache || true

# Jalankan CMD (apache2-foreground)
exec "$@"
