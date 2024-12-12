# Конвертер валют (FreecurrencyAPI)

### Выберите язык

| Русский  | English                              | Español                                   | 中文                              | Français                              | Deutsch                              |
|----------|--------------------------------------|-------------------------------------------|---------------------------------|---------------------------------------|--------------------------------------|
| **Выбран** | [English](./docs/langs/README_en.md) | [Español](./docs/langs/README_es.md) | [中文](./docs/langs/README_zh.md) | [Français](./docs/langs/README_fr.md) | [Deutsch](./docs/langs/README_de.md) |

---

**Currency Converter** — это проект для работы с API обмена валют. Вы можете запрашивать курсы валют, выполнять конвертацию и тестировать функционал через готовые тесты.

## Структура проекта

Отразил структуру самого бандла `CurrencyConverterBundle`, структуру фреймворка `Symfony` - не включал

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # Контроллеры, обрабатывающие действия пользователей
│      ├── Command/                       # Консольные команды для управления функциями бандла
│      ├── DependencyInjection/           # Логика для конфигурации зависимостей и параметров
│      ├── DTO/                           # Объекты передачи данных для структурирования информации
│      ├── Entity/                        # Сущности для работы с базой данных
│      ├── Form/                          # Файлы для создания и обработки форм
│      ├── Migrations/                    # Скрипты миграций для изменения структуры базы данных
│      ├── Repository/                    # Репозитории для доступа к данным и их обработки
│      ├── Resources/                     # Шаблоны, локализация и другие ресурсы
│      ├── Service/                       # Бизнес-логика и вспомогательные сервисы
│      ├── CurrencyConverterBundle.php    # Главный файл бандла, подключающий его в проект
```

---

## Установка

### 1. Клонирование репозитория

Склонируйте проект на ваш локальный компьютер:

```bash
git clone https://github.com/yaleksandr89/freecurrencyapi-converter-php.git
cd freecurrencyapi-converter-php
```
### 2. Конфигурация проекта

#### 2.1 Настройка .env

В качестве СУБД использовал `PostgreSQL`. Но структура БД несложная поэтому в принципе можно использовать другие СУБД. Пример конфигурация подключения БД:

```dotenv
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/db_name?serverVersion=17&charset=utf8"
# user - имя пользователя
# password - пароль пользователя
# db_name - имя БД
# serverVersion - версия postgresql (проверить можно командой psql -V)
```
Также требуется указать:

* `APP_TIMEZONE` - временная зона, используется для обновления валют по расписанию. Значение по умолчанию **UTC**
* `CURRENCY_CONVERTER_API_KEY` - ТОКЕН, который генерируется в личном кабинете API сервиса
* `CURRENCY_CONVERTER_API_URL` - На текущий  момент (12.12.2024) ссылка для подключения к API: https://api.freecurrencyapi.com/v1
* `USE_MOCK_DATA` - false или true. Можно использовать тестовые данные, для тестирования. Реализованно с целью не вылезти за лимиты сервиса. Значение по умолчанию **false**

![env.png](./docs/images/env.png)

#### 2.3 Установка composer пакетов

```bash
composer i && composer dump-autoload
```

#### 2.4 Создание БД

Для создания БД можно использовать

```bash
php bin/console doctrine:database:create
```

![database-create.png](./docs/images/database-create.png)

#### 2.5 Применение миграции

Необходимо применить миграции

```bash
php bin/console doctrine:migrations:migrate
```

![migrations-migrate.png](./docs/images/migrations-migrate.png)

#### 2.6 Настройка веб-сервера

В качестве веб-сервера использовал nginx, конфигурация:

```apacheconf
server {
    listen 80;

    server_name currency-converter.loc;

    root /www/freecurrencyapi-converter-php/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_pass              unix:/run/php/php8.3-fpm.sock;
        fastcgi_split_path_info   ^(.+\.php)(/.*)$;
        include                   fastcgi_params;
        fastcgi_param             SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_param             DOCUMENT_ROOT $realpath_root;
    }

    access_log /var/logs/firebird-tours-access.log;
    error_log /var/logs/firebird-tours-error.log;
}
```

Обратите внимание и при необходимости поменяйте:

* `server_name` - имя домена
* `root` - директория с рабочим проектом (при необходимости заменить **freecurrencyapi-converter-php**)
* `fastcgi_pass` - php8.3-fpm.sock, если используете другую версию php замените версию (версию php можно посмотреть командой `php -v`)
* `access_log` / `error_log` - директория до файлов логов.

#### 2.7 Настройка cron

Для автоматического обновления валют через cron. В качестве тестирования можно добавить выполнение команды каждую минуту

```bash
crontab -e

# Для тестирования (выполнение 1 раз в минуту)
* * * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

После проверки, можно увеличить время вызова консольной команды до 2 раз в сутки (в полдень и в полночь)

```bash
crontab -e

# Выполнение 2 раза в день: в 00:00 и в 12:00.
0 0,12 * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

---

## Использование веб-приложения

...
