# Тестовое задание: уникальные коды скидок (Laravel)

На хосте нужен только Docker.

## Запуск
    cp .env.example .env
    docker compose up -d --build

При сборке образа ставится свежий Laravel (composer), поверх копируется код из `src/`.
При старте контейнера `app` настраивается окружение, генерируется ключ, выполняются
миграции и сидеры. Прогресс: `docker compose logs -f app`.

Приложение: http://localhost:8080 (порт задаётся в `.env`).
Логины: `user1` / `user2`, пароль `password`.

## Команды (внутри контейнера)
    docker compose exec app php artisan user:create <login> [password]
    docker compose exec app php artisan migrate:fresh --seed

После изменений в `src/`: `docker compose up -d --build`.

## Архитектура (лёгкий DDD)
- `src/app/Domain` — сущность `Discount`, интерфейсы репозиториев
- `src/app/Application` — сервисы с бизнес-правилами (1 час / 3 часа)
- `src/app/Infrastructure` — реализации репозиториев; единственное место с запросами к БД
- `src/app/Http` — контроллеры и FormRequest; `src/resources/views` — Blade + Bootstrap
- DI: интерфейсы связываются в `AppServiceProvider`, зависимости приходят через конструкторы

## Демо-данные
- user1, user2 (пароль `password`)
- код `DEMOACTIVE01` (user1, получен 2 ч назад) — проверка вернёт 15%
- код `DEMOEXPIRED1` (user2, получен 5 ч назад) — «Скидка недоступна»
