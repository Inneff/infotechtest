# Каталог книг (Yii2 + MySQL)

Тестовое задание: каталог книг на Yii2 (basic-шаблон) с авторизацией, CRUD,
подписками гостей и отчётом «ТОП-10 авторов».

## Требования

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Composer

## Установка

```bash
composer install

# 1. Создать БД и указать доступ в config/db.php (по умолчанию bookcatalog / root / без пароля)
# 2. Применить миграции (создаёт таблицы и тестового пользователя admin/admin)
php yii migrate

# 3. Запустить встроенный веб-сервер
php yii serve
```

Приложение доступно по адресу http://localhost:8080.

Тестовый пользователь: `admin` / `admin`.
Зарегистрировать нового пользователя можно на странице «Регистрация».

## Структура

```
controllers/   SiteController (вход/регистрация), BookController, AuthorController,
               ReportController (отчёт), SubscriptionController (подписка)
models/        User, Author, Book, Subscription + формы (LoginForm, SignupForm,
               SubscriptionForm, ReportForm, BookSearch)
services/      SmsNotifier (шлюз SMSPILOT), BookNotificationService (рассылка)
migrations/    Создание таблиц user, author, book, book_author, subscription
views/         Представления (без вёрстки)
```

## Роли и доступ

- **Гость** (без авторизации): просмотр каталога, авторов, отчёта; подписка на новые книги автора.
- **Пользователь** (авторизован): всё перечисленное + добавление/редактирование/удаление книг и авторов.
- **Отчёт** «ТОП-10 авторов» доступен всем (страница /report).

## Уведомления (SMS)

При добавлении книги подписчикам её авторов отправляется SMS через шлюз
[SMSPILOT](https://smspilot.ru/apikey.php). Ключ задаётся в `config/params.php`
(`smspilot.apiKey`) — по умолчанию стоит ключ-эмулятор, реальная отправка не происходит.
