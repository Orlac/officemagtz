# Тестовое задание: уникальные коды скидок (Laravel)

На хосте нужен только Docker.

## Запуск

    cp .env.example .env
    docker compose up -d --build

При старте контейнера `app` настраивается окружение, генерируется ключ, выполняются
миграции и сидеры. Прогресс: `docker compose logs -f app`.

Приложение: http://localhost:8087 (порт задаётся в `.env`).
Логины: `user1` / `user2`, пароль `password`.

## Файлы на хосте

Весь проект смонтирован в контейнер (`- .:/var/www`), поэтому все файлы — в том числе
`vendor/`, `storage/`, `bootstrap/cache/`, `.env` — доступны на хосте сразу, без
пересборки образа. Изменения в коде применяются при обновлении страницы (opcache
проверяет timestamps). При добавлении новых классов выполните:

    docker compose exec app composer dump-autoload

## Войти в контейнер

    # bash-сессия в контейнере app (como root)
    docker compose exec app bash

    # или под пользователем www-data
    docker compose exec --user www-data app bash

    # выполнить одну команду без интерактивного входа
    docker compose exec app php artisan tinker

## Команды (внутри контейнера)

    docker compose exec app php artisan user:create <login> [password]
    docker compose exec app php artisan migrate:fresh --seed

## Архитектура

- `app/Domain` — сущность `Discount`, интерфейсы репозиториев
- `app/Application` — сервисы с бизнес-правилами; настройки берутся из `config/discount.php` (1 час / 3 часа)
- `app/Infrastructure` — реализации репозиториев; единственное место с запросами к БД
- `app/Http` — контроллеры и FormRequest; `resources/views` — Blade + Bootstrap
- DI: интерфейсы связываются в `AppServiceProvider`, зависимости приходят через конструкторы

## Настройки скидок (`.env` / `config/discount.php`)

- `DISCOUNT_MIN_PERCENT` / `DISCOUNT_MAX_PERCENT` — диапазон случайной скидки (1–50)
- `DISCOUNT_REISSUE_LOCK_HOURS` — в течение скольких часов повторное получение возвращает прежнюю скидку (1)
- `DISCOUNT_VALID_HOURS` — срок действия кода, влияет на дату истечения на странице (3)

## Демо-данные

- user1, user2 (пароль `password`)
- код `DEMOACTIVE01` (user1, получен 2 ч назад) — проверка вернёт 15%
- код `DEMOEXPIRED1` (user2, получен 5 ч назад) — «Скидка недоступна»
