# AudioDrive — интернет-магазин автомобильной акустики

Laravel + Bootstrap 5

## Возможности

- Каталог товаров с фильтрами по уникальным параметрам (мощность RMS, импеданс, размер, тип крепления, каналы)
- Корзина (сессия)
- Многошаговое оформление заказа (контакты → доставка → оплата → подтверждение)
- Личный кабинет (заказы, бонусы, профиль)
- Автоматические акции и промокоды
- Бонусная программа (начисление, списание, регистрационный бонус)
- Админ-панель (товары, категории, заказы, акции, настройки бонусов)
- Шапка и подвал сайта

## Запуск

```bash
composer install
cp .env.example .env   # если .env нет
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Оптимизация размера

Папка `vendor` без dev-зависимостей — **~72 МБ** (было ~760 МБ с PHPUnit, Faker, Pint и др.).

Установка для продакшена / минимального размера:

```bash
composer prod-install
```

Вернуть dev-пакеты для разработки:

```bash
composer dev-install
```

Локальная оптимизация проекта:

```bash
composer optimize-app      # кэш + минификация CSS + сжатие статики
composer optimize-images   # сжатие фото товаров (нужно PHP GD)
composer optimize-all      # всё сразу
```

Новые загрузки фото в админке автоматически сжимаются до 1200px (JPEG 82%). `optimize-app` минифицирует `site.css` и `admin.css`, сжимает статику в `public/images`.

**Фото товаров:** реальные с [loudsound.ru](https://www.loudsound.ru/) (`config/shop.php` → `product_photos`). Обновить URL: `php scripts/fetch-loudsound-images.php`, локальный кэш: `php scripts/download-loudsound-images.php`. Иконки — jsDelivr (`cdn_asset()`).

**Включите в php.ini:** `extension=gd`, `extension=fileinfo`

Откройте http://127.0.0.1:8000

## Деплой на Railway

1. Подключите репозиторий к Railway, builder: **Railpack** (файл `railway.toml`).
2. Добавьте плагин **PostgreSQL** и переменные из [`.env.railway.example`](.env.railway.example).
3. Обязательно задайте **`APP_KEY`** до билда (Variables доступны на этапе сборки).
4. `composer.json` требует **PHP ^8.4** — совпадает с Symfony 8 в `composer.lock`.
5. **Не добавляйте** `package.json` в корень — иначе Railpack запустит лишний `npm run build` (фронт на CDN + `public/css`).
6. Перед каждым деплоем автоматически: `scripts/predeploy.sh` (миграции + идемпотентный сид) — см. `preDeployCommand` в `railway.toml`.

Healthcheck: `/up`

### Ошибка 500 на Railway

1. Проверьте **`APP_KEY`** (обязателен).
2. Подключён **PostgreSQL** и есть переменная **`DATABASE_URL`** (или `DB_URL`).
3. **`DB_CONNECTION=pgsql`** — если не задано, при `DATABASE_URL` выберется `pgsql` автоматически.
4. В логах деплоя (фаза **Pre-deploy**): `Pre-deploy: migrations...` и `Pre-deploy: done.` без ошибок.
5. Для диагностики временно: `APP_DEBUG=true`, затем снова `false`.

Локально то же, что на Railway: `sh scripts/predeploy.sh`

## Тестовые аккаунты

| Роль        | Email           | Пароль   |
|-------------|-----------------|----------|
| Администратор | admin@audio.ru  | password |
| Покупатель  | user@audio.ru   | password |

## Промокод

`AUDIO500` — скидка 500 ₽ при заказе от 3000 ₽

Автоакция: 10% при заказе от 15 000 ₽ (применяется автоматически)
