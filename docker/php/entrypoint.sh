#!/bin/sh
set -e
cd /var/www

[ -f .env ] || cp .env.example .env

# Записать KEY=VALUE в .env (заменить, если уже есть)
set_env() {
    grep -v "^$1=" .env > .env.tmp || true
    echo "$1=$2" >> .env.tmp
    cat .env.tmp > .env && rm .env.tmp
}

set_env DB_CONNECTION mysql
set_env DB_HOST "${DB_HOST}"
set_env DB_PORT "${DB_PORT}"
set_env DB_DATABASE "${DB_DATABASE}"
set_env DB_USERNAME "${DB_USERNAME}"
set_env DB_PASSWORD "${DB_PASSWORD}"
set_env SESSION_DRIVER file
set_env CACHE_STORE file
set_env QUEUE_CONNECTION sync

grep -q '^APP_KEY=.\+' .env || php artisan key:generate --force

# Структура БД и демо-данные (сидер идемпотентен)
php artisan migrate --seed --force

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
