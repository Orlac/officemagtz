#!/bin/sh
set -e
cd /var/www

[ -f .env ] || cp .env.example .env

# Установить зависимости если vendor пуст
if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist --no-scripts
    chown -R www-data:www-data vendor
fi

# Перегенерировать автозагрузчик (подхватит новые классы без перезапуска)
composer dump-autoload --no-scripts >/dev/null 2>&1 || true

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

# Создаём недостающие папки для storage (иначе artisan не запустится)
mkdir -p storage/framework/views storage/framework/cache/data storage/framework/cache/locks storage/framework/sessions

# Генерируем APP_KEY напрямую (artisan может не запуститься без ключа)
grep -v '^APP_KEY=' .env > .env.tmp || true
cat .env.tmp > .env && rm .env.tmp
KEY=$(php -r "echo base64_encode(random_bytes(32));")
echo "APP_KEY=base64:${KEY}" >> .env

rm -f bootstrap/cache/config.php


# Структура БД и демо-данные (сидер идемпотентен)

chown -R www-data:www-data storage bootstrap/cache vendor

exec "$@"
