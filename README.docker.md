# Локальный запуск через Docker (без Dockerfile)

Используются только готовые образы: `thecodingmachine/php:8.3-v4-fpm`, `nginx:alpine`, `postgres:16-alpine`.

## Первый запуск

```bash
# 1. Переменные для Docker (если ещё не настроены)
cp .env.docker.example .env
docker compose run --rm app php artisan key:generate

# 2. Зависимости (если нет папки vendor)
docker compose run --rm app composer install --no-interaction

# 3. Миграции и сидер (базовый админ)
docker compose run --rm app php artisan migrate --force
docker compose run --rm app php artisan db:seed --force

# 4. Поднять сервисы
docker compose up -d
```

## Доступ

- Приложение: http://localhost:8080
- Filament admin: http://localhost:8080/admin

**Вход в админку:** после `db:seed` — email `admin@example.com`, пароль `password`.

## Полезные команды

```bash
# Artisan в контейнере
docker compose run --rm app php artisan migrate
docker compose run --rm app php artisan make:filament-resource Car --generate

# Остановить
docker compose down
```
