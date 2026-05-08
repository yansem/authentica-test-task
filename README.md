Инструкция по запуску:

docker compose build --no-cache
docker compose up -d
docker compose exec app sh start.sh


Тестовое задание
Задача: «API для каталога товаров с кэшированием»
Суть: реализовать REST API для управления товарами с фильтрацией, пагинацией и кэшированием.
Требования:
1.Модель Product:
поля: id, name, price, category_id, created_at, updated_at.
связи: belongsTo(Category::class).
2.Модель Category:
поля: id, name.
3.API endpoint GET /api/products:
фильтры: по category_id, по price_min / price_max, поиск по name (LIKE).
пагинация (по 15 элементов).
сортировка по price или created_at.
кэширование через Redis (TTL 5 минут, инвалидация при создании/обновлении/удалении товара).
4.API endpoint POST /api/products (только для аутентифицированных пользователей):
валидация (name обязателен, price ≥ 0).
создание товара.
после создания — инвалидация кэша списка.
5.API endpoint PUT /api/products/{id} + DELETE /api/products/{id}:
обновление / удаление.
инвалидация кэша.
6.Дополнительно (плюс):
вынести логику кэширования в отдельный Service class.
написать один тест (PEST или PHPUnit) для проверки кэширования.
Стек: Laravel, Redis, MySQL, PHP 8.x.
Ожидаемый результат:
Pull request на GitHub / GitLab с кодом и инструкцией по запуску (Docker или php artisan serve).
