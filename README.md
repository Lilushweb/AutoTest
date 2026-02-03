# Система управления служебными автомобилями

API для выбора и бронирования служебных автомобилей в корпоративной среде.

## Техническое задание

В компании предусмотрена возможность выбора служебного автомобиля для служебной поездки из не занятых другими сотрудниками. В административной части корпоративного сайта необходимо размещать актуальную информацию о доступных для конкретного сотрудника автомобилях на запланированное время поездки.

**Дополнительные условия:**
- каждая модель автомобиля имеет определённую категорию комфорта (первая, вторая, третья...);
- для определённой должности сотрудников доступны только автомобили определённой категории комфорта (одной или нескольких категорий);
- за каждым автомобилем закреплён свой водитель.

**Реализовано:**
1. Миграции для создания таблиц и связей в БД;
2. API-метод получения списка доступных текущему пользователю автомобилей с фильтрацией по модели и категории комфорта.

---

## Требования

- PHP 8.2+
- Composer
- MySQL / PostgreSQL / SQLite

## Установка

```bash
# Клонирование и установка зависимостей
git clone <repository-url>
cd autoTest
composer install

# Конфигурация
cp .env.example .env
php artisan key:generate

# База данных
php artisan migrate
php artisan db:seed  # при необходимости
```

## Запуск

```bash
php artisan serve
```

API доступен по адресу: `http://localhost:8000/api`

---

## API

### Аутентификация

Используется Laravel Sanctum (токены). Для защищённых маршрутов передавайте заголовок:

```
Authorization: Bearer <token>
```

### Маршруты

| Метод | Endpoint | Описание | Auth |
|-------|----------|----------|------|
| POST | `/api/register` | Регистрация | — |
| POST | `/api/login` | Вход | — |
| POST | `/api/logout` | Выход | ✓ |
| GET | `/api/user` | Текущий пользователь | ✓ |
| GET | `/api/cars/available` | Список доступных автомобилей | ✓ |
| GET | `/api/cars` | Список всех автомобилей (пагинация) | ✓ |
| POST | `/api/cars` | Создание автомобиля | ✓ |
| PUT | `/api/cars/{car}` | Обновление автомобиля | ✓ |
| DELETE | `/api/cars/{car}` | Удаление автомобиля | ✓ |
| GET | `/api/carBookings` | Мои бронирования | ✓ |
| POST | `/api/carBookings` | Создание бронирования | ✓ |
| PUT | `/api/carBookings/{carBooking}` | Обновление бронирования | ✓ |
| DELETE | `/api/carBookings/{carBooking}` | Отмена бронирования | ✓ |
| GET | `/api/positions` | Должности | ✓ |
| GET | `/api/comfortCategory` | Категории комфорта | ✓ |

### Доступные автомобили

```
GET /api/cars/available?start_time=2025-02-10+09:00:00&end_time=2025-02-10+18:00:00&search=bmw&comfort_category_id[]=1
```

**Параметры:**
- `start_time` (required) — начало поездки
- `end_time` (required) — окончание поездки
- `search` (optional) — поиск по модели
- `comfort_category_id` (optional) — массив ID категорий комфорта (по умолчанию — категории должности пользователя)

### Создание бронирования

```
POST /api/carBookings
Content-Type: application/json

{
  "car_id": 1,
  "start_time": "2025-02-10 09:00:00",
  "end_time": "2025-02-10 18:00:00"
}
```

---

## Структура БД

```
users
├── position_id → positions
└── role (admin, manager, employee)

positions
└── position_comfort_category (pivot) → comfort_categories

comfort_categories

cars
├── comfort_category_id → comfort_categories
└── user_id (водитель/текущий владелец) → users

car_bookings
├── car_id → cars
├── user_id → users
├── start_time
└── end_time
```

**Связи:**
- **Car** — принадлежит категории комфорта, может быть закреплён за пользователем (водитель/владелец)
- **Position** — many-to-many с ComfortCategory (какие категории доступны должности)
- **CarBooking** — связывает автомобиль и пользователя на период времени

---

## Структура проекта

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── Car/CarController, CarBookingController
│   │   ├── ComfortCategoryController
│   │   └── PositionsController
│   ├── DTO/
│   │   ├── CarDTO, UpdateCarDTO, AvailableCarsFilterDTO
│   │   ├── CarBookingDTO, UpdateCarBookingDTO
│   │   ├── ComfortCategoryDTO
│   │   └── PositionDTO
│   ├── Requests/Car/        # FormRequest для валидации
│   ├── Resources/          # CarResource, CarBookingResource
│   └── Service/
│       ├── CarService
│       ├── CarBookingService
│       ├── ComfortCategoryService
│       └── PositionService
├── Models/
└── Policies/               # CarPolicy, CarBookingPolicy, ComfortCategoryPolicy, PositionPolicy
```

---

## Лицензия

MIT
