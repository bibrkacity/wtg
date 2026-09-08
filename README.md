# Тестове завдання для Laravel Developer

## Опис завдання

https://docs.google.com/document/d/1XzC6iSXtoFNWJKlndmdaPPP-j4MMY0CIdSbD6anxgSE/edit?tab=t.0#heading=h.wy5orslc7da4

## Встановлення

1. Склонуйте репозиторій (https://github.com/bibrkacity/wtg.git) 
2. `cd` into root folder (*your-path*/wtg)
3. Скопіюйте `.env.example` to `.env`
4. Запустіть команду `composer install`
5. Запустіть команду `./vendor/bin/sail build --no-cache` 
6. Запустіть команду `./vendor/bin/sail artisan migrate`
7. Запустіть команду `./vendor/bin/sail artisan migrate --database=mysql_testing`
8. Запустіть команду `./vendor/bin/sail artisan db:seed`
9. Запустіть команду `./vendor/bin/sail artisan db:seed --database=mysql_testing`
10. Запустіть команду `php artisan l5-swagger:generate`

## Запуск
1. Запустіть команду `./vendor/bin/sail up`
2. Запустіть команду `./vendor/bin/sail artisan queue:work`

Тепер ви можете відвідати інтерфейс користувача Swagger за адресою http://127.0.0.1:8080/api/documentation

## Ручне тестування

Swagger надає інтерфейс для тестування ендпойнтів. 

## Юніт-тестування

Запустіть команду `./vendor/bin/sail artisan test`

## Коротке пояснення ідемпотентності імпорту

Перед записом нового запису до таблиці imports перевіряється, якщо вже в ній таке поєднання supplier_id і external_import_id. Якщо є – процедура імпорту зберігає запис із повідомленням про помилку та не йде далі. 

Перед записом нового запису до таблиці properties перевіряється, якщо вже в ній таке code. Якщо ні – створюється.

Для таблиці offer використовується метод updateOrCreate(), перевіряючий поєднання supplier_id і external_id.

## Механізм рішення захищене від двох одночасних бронювань останньої одиниці

Цей механізм складається із двох рубежів. 

Перший - [atomic lock](https://laravel.com/framework/docs/12.x/cache#atomic-locks). Лише один сеанс може бронювати заданий оффер. 
Іншим сеансам, блокуючим заданий оффер, повернеться помилка 423. 

Якщо atomic lock не впорається, його підстрахує тригер таблиці _reservations_: він не пропустить бронювання оффера, у якого available_units дорівнює нулю. 
