
Даний застосунок написаний на фреймфорку Laravel, створюючи локальне підключення.
Для запуску додатку використовується команда "php artisan serv --host=IP_ADDRESS --port=PORT".
Для отримання оновлення для Телеграм боту, потрібно використати GET-запрос "http://IP_ADDRESS:PORT/api/bot/updates"
Для того, щоб виконати міграцію використовується команда "php artisan migrate", порередньо створідь порожню базу даних і вкажіть доступ до неї в .env.