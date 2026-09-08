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
