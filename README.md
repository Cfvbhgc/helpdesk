# HelpDesk - Система управления тикетами

Современная система поддержки пользователей, построенная на **Laravel 11**, **Livewire 3**, **MySQL 8** и **Tailwind CSS**. Полностью контейнеризирована с помощью Docker.

## Возможности

- **Тикеты**: создание, просмотр, фильтрация, поиск и сортировка
- **Приоритеты**: low, medium, high, critical с автоматическим расчетом SLA
- **Статусы**: open → in_progress → resolved → closed (контролируемый workflow)
- **SLA**: автоматические дедлайны (critical=4ч, high=8ч, medium=24ч, low=72ч), отслеживание нарушений
- **Ответы на тикеты**: переписка между пользователями и агентами
- **Назначение**: тикеты назначаются на агентов/администраторов
- **Панель администратора**: статистика тикетов, SLA compliance, производительность агентов
- **База знаний**: статьи с категориями, поиск, публикация/черновики
- **Роли**: user, agent, admin с разграничением доступа
- **Livewire**: интерактивный интерфейс без перезагрузки страниц

## Технологический стек

| Компонент | Технология |
|-----------|-----------|
| Backend | Laravel 11 (PHP 8.2) |
| Frontend | Livewire 3 + Tailwind CSS |
| База данных | MySQL 8.0 |
| Веб-сервер | Nginx 1.25 |
| Контейнеризация | Docker + Docker Compose |

## Быстрый старт

### Требования

- Docker и Docker Compose
- Git

### Запуск проекта

```bash
# 1. Клонировать репозиторий
git clone https://github.com/cfvbhgc/helpdesk.git
cd helpdesk

# 2. Скопировать файл окружения
cp .env.example .env

# 3. Запустить контейнеры
docker-compose up -d --build

# 4. Установить зависимости
docker-compose exec app composer install

# 5. Сгенерировать ключ приложения
docker-compose exec app php artisan key:generate

# 6. Выполнить миграции и наполнить БД тестовыми данными
docker-compose exec app php artisan migrate --seed

# 7. Открыть в браузере
# http://localhost:8080
```

### Демо-аккаунты

| Роль | Email | Пароль |
|------|-------|--------|
| Администратор | admin@helpdesk.test | password |
| Агент | agent@helpdesk.test | password |
| Агент | jane.agent@helpdesk.test | password |

## Структура проекта

```
helpdesk/
├── app/
│   ├── Http/
│   │   └── Livewire/          # Livewire-компоненты
│   │       ├── AdminDashboard.php
│   │       ├── KnowledgeBaseList.php
│   │       ├── TicketCreate.php
│   │       ├── TicketDetail.php
│   │       └── TicketList.php
│   └── Models/                # Eloquent-модели
│       ├── KnowledgeBase.php
│       ├── Ticket.php
│       ├── TicketReply.php
│       └── User.php
├── database/
│   ├── factories/             # Фабрики для тестов
│   ├── migrations/            # Миграции БД
│   └── seeders/               # Наполнение тестовыми данными
├── resources/views/
│   ├── layouts/app.blade.php  # Основной шаблон
│   ├── livewire/              # Шаблоны Livewire-компонентов
│   ├── auth/login.blade.php   # Страница входа
│   └── welcome.blade.php     # Главная страница
├── routes/web.php             # Маршруты
├── tests/                     # Тесты
├── docker-compose.yml         # Docker Compose конфигурация
├── Dockerfile                 # PHP-FPM образ
└── docker/nginx/              # Конфигурация Nginx
```

## SLA-политика

Дедлайн SLA автоматически устанавливается при создании тикета на основе приоритета:

| Приоритет | Время SLA | Описание |
|-----------|-----------|----------|
| Critical | 4 часа | Система недоступна |
| High | 8 часов | Критическая функциональность нарушена |
| Medium | 24 часа | Незначительная проблема |
| Low | 72 часа | Общий вопрос |

Тикеты с нарушенным SLA отображаются на панели администратора с индикатором "Breached".

## Тестирование

```bash
docker-compose exec app php artisan test
```

## Полезные команды

```bash
# Остановить контейнеры
docker-compose down

# Пересоздать БД
docker-compose exec app php artisan migrate:fresh --seed

# Просмотр логов
docker-compose logs -f app

# Вход в контейнер приложения
docker-compose exec app bash
```

## Лицензия

MIT
