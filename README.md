ScriptTemplates
===============
В данной папке содержатся скрипты интеграции проекта для авторизации игроков через Мафусаил API.

## Инструкция
* Скачать представленные скрипты в каталог на Вашем сайте, например `http://mc-example.ru/methuselah/*.php`
* Отредактировать `settings.php`, указав:
  * Пятисимвольный код Вашего проекта (выдаётся при регистрации проекта)
  * Секретное кодовое слово или фраза (следует соблюдать корректный регистр символов)
  * Путь к движку вашего сайта, пользователи которого аутентифицироваться. Например, если форум находится по адресу `http://mc-example.ru/forum/`, то следует ввести значение `__DIR__ . "/../forum/"`
* Сообщить нам корректный путь к скриптам на Вашем сайте.

## Поддерживаемые движки
* XenForo 1.x
