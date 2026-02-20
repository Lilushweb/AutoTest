# Система управления служебными автомобилями

API и админ-панель для выбора и бронирования служебных автомобилей в корпоративной среде.

## Стек

- **Laravel 12**, PHP 8.2+
- **Filament 3** — админ-панель
- **Laravel Sanctum** — API-аутентификация
- **PostgreSQL** (поддерживаются также MySQL / SQLite)

---

## Техническое задание

В компании предусмотрена возможность выбора служебного автомобиля для служебной поездки из не занятых другими сотрудниками. В административной части корпоративного сайта необходимо размещать актуальную информацию о доступных для конкретного сотрудника автомобилях на запланированное время поездки.

**Дополнительные условия:**
- каждая модель автомобиля имеет определённую категорию комфорта (первая, вторая, третья...);
- для определённой должности сотрудников доступны только автомобили определённой категории комфорта (одной или нескольких категорий);
- за каждым автомобилем закреплён свой водитель.

**Реализовано:**
1. Миграции для создания таблиц и связей в БД;
2. API-метод получения списка доступных текущему пользователю автомобилей с фильтрацией по модели и категории комфорта;
3. Админ-панель Filament с CRUD по автомобилям, бронированиям, должностям, категориям комфорта и пользователям.

---

## Требования

- PHP 8.2+ (для Docker — 8.3)
- Composer
- PostgreSQL / MySQL / SQLite

---

## Установка (локально)

```bash
git clone <repository-url>
cd autoTest
composer install

cp .env.example .env
php artisan key:generate

php artisan migrate
php artisan db:seed   # должности, категории комфорта, базовый админ (admin@example.com / password)
```

## Запуск (локально)

```bash
php artisan serve
```

- Приложение: **http://localhost:8000**
- API: **http://localhost:8000/api**
- Админка: **http://localhost:8000/admin**

---

## Docker (без установки PHP/PostgreSQL)

Используются только готовые образы (без Dockerfile): `thecodingmachine/php:8.3-v4-fpm`, `nginx:alpine`, `postgres:16-alpine`. Подробнее: **[README.docker.md](README.docker.md)**.

```bash
cp .env.docker.example .env
docker compose run --rm app php artisan key:generate
docker compose run --rm app composer install --no-interaction
docker compose run --rm app php artisan migrate --force
docker compose run --rm app php artisan db:seed --force
docker compose up -d
```

- Приложение: **http://localhost:8080**
- Админка: **http://localhost:8080/admin**
- PostgreSQL в контейнере: порт **5433** на хосте (чтобы не конфликтовать с локальным PostgreSQL на 5432)

В `.env` для Docker должны быть: `DB_HOST=postgres`, `APP_URL=http://localhost:8080` (см. `.env.docker.example`).

---

## Filament (админ-панель)

- **URL:** `/admin` (локально: `http://localhost:8000/admin`, в Docker: `http://localhost:8080/admin`)
- **Доступ:** только пользователи с `role = 'admin'` (`App\Models\User::canAccessPanel()`)

**Вход после сида:** email `admin@example.com`, пароль `password`.

**Разделы (ресурсы):**

| Группа меню   | Раздел              | Ресурс                 | Описание                              |
|---------------|---------------------|------------------------|---------------------------------------|
| Данные        | Автомобили          | `CarResource`          | Модель, категория комфорта, водитель  |
| Данные        | Бронирования        | `CarBookingResource`   | Авто, пользователь, период            |
| Справочники   | Должности           | `PositionResource`     | Название, категории комфорта (M:N)    |
| Справочники   | Категории комфорта  | `ComfortCategoryResource` | Название                          |
| Управление    | Пользователи        | `UserResource`         | Имя, email, должность, роль, пароль    |

**Файлы:**
- Конфиг панели: `app/Providers/Filament/AdminPanelProvider.php`
- Ресурсы: `app/Filament/Resources/*Resource.php` — метод `form()` (схема формы), метод `table()` (схема таблицы)
- Страницы: `app/Filament/Resources/<Model>Resource/Pages/` — `List*`, `Create*`, `Edit*`

В коде используются короткие имена классов с явными `use` (например `DeleteAction::make()`, `TextInput::make()`), без префиксов `Actions\`, `Forms\Components\` и т.п.

---

## Сидеры

`php artisan db:seed` запускает:

| Сидер                      | Описание |
|----------------------------|----------|
| `PositionsSeeder`          | Должности (Developer, Manager, Designer, QA Engineer) |
| `ComfortCategorySeeder`    | Категории комфорта (first, second, third) |
| `PositionComfortCategorySeeder` | Связь должностей с категориями |
| `AdminUserSeeder`          | Админ: `admin@example.com` / `password` |

---

## API

### Аутентификация

Laravel Sanctum. Для защищённых маршрутов:

```
Authorization: Bearer <token>
```

### Маршруты

| Метод   | Endpoint                    | Описание              | Auth |
|---------|-----------------------------|------------------------|------|
| POST    | `/api/register`             | Регистрация           | —    |
| POST    | `/api/login`                | Вход                  | —    |
| POST    | `/api/logout`               | Выход                 | ✓    |
| GET     | `/api/user`                 | Текущий пользователь  | ✓    |
| GET     | `/api/cars/available`       | Доступные автомобили  | ✓    |
| GET     | `/api/cars`                 | Список автомобилей    | ✓    |
| POST    | `/api/cars`                 | Создание автомобиля   | ✓    |
| PUT     | `/api/cars/{car}`           | Обновление автомобиля | ✓    |
| DELETE  | `/api/cars/{car}`           | Удаление автомобиля   | ✓    |
| GET     | `/api/carBookings`          | Мои бронирования      | ✓    |
| POST    | `/api/carBookings`          | Создание бронирования | ✓    |
| PUT     | `/api/carBookings/{id}`     | Обновление бронирования | ✓  |
| DELETE  | `/api/carBookings/{id}`     | Отмена бронирования   | ✓    |
| GET     | `/api/positions`            | Должности             | ✓    |
| GET     | `/api/comfortCategory`      | Категории комфорта    | ✓    |

### Доступные автомобили

```
GET /api/cars/available?start_time=2025-02-10+09:00:00&end_time=2025-02-10+18:00:00&search=bmw&comfort_category_id[]=1
```

- `start_time`, `end_time` — обязательно
- `search` — поиск по модели
- `comfort_category_id[]` — массив ID категорий (по умолчанию — категории должности пользователя)

### Создание бронирования

```
POST /api/carBookings
Content-Type: application/json

{ "car_id": 1, "start_time": "2025-02-10 09:00:00", "end_time": "2025-02-10 18:00:00" }
```

---

## Структура БД

```
users              — position_id → positions, role (admin|manager|employee)
positions          — name
position_comfort_category  — position_id, comfort_category_id (pivot)
comfort_categories — name
cars               — model, comfort_category_id, user_id (водитель)
car_bookings       — car_id, user_id, start_time, end_time
sessions           — для SESSION_DRIVER=database
```

---

## Структура проекта

```
app/
├── Filament/
│   └── Resources/           # CarResource, CarBookingResource, PositionResource,
│       ├── *Resource.php   #   ComfortCategoryResource, UserResource
│       └── *Resource/Pages/
├── Providers/Filament/
│   └── AdminPanelProvider.php
├── Http/
│   ├── Controllers/         # AuthController, Car/*, ComfortCategory, Positions
│   ├── DTO/, Requests/, Resources/, Service/
│   └── Middleware/
├── Models/
└── Policies/
```

