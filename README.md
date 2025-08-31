# Students-API
REST API для управления студентами, классами и лекциями

## 📚 Документация API

Документация Swagger доступна по корневому пути: `/`

## 📊 Технологии

-   **Backend**: Laravel 12
-   **База данных**: MySQL
-   **Документация**: Swagger (пакет darkaonline/l5-swagger)
-   **Тесты**: PHPUnit

## 🚀 Функционал

### Основные сущности

-   Студенты
-   Классы
-   Лекции

## 🧪 Тестирование

Проект включает полное покрытие CRUD операций (66 тестов):

-   Feature тесты
    - LectureApiTest,
    - StudentApiTest,
    - StudentClassApiTest
-   Unit тесты сервисов
    -   ClassServiceTest
    -   LectureServiceTest
    -   StudentServiceTest

Запуск тестов:

```bash
php artisan test
```

## 🔄 Установка
1. Клонировать репозиторий:
```bash
git clone https://github.com/ManasArs13/students-api.git && cd students-api
```

2. Установите зависимости:
```bash
composer install && npm install && npm run build
```

3. Настройте:

```bash
cp .env.example .env
php artisan key:generate
```

4. Запустить миграции:

```bash
php artisan migrate --seed
```

