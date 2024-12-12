# Currency Converter (FreecurrencyAPI)

### Choose Language

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../README.md) | **Selected** | [Español](README_es.md) | [中文](README_zh.md) | [Français](README_fr.md) | [Deutsch](README_de.md) |

---

**Currency Converter** — this is a project for working with a currency exchange API. You can request exchange rates, perform conversions, and test functionality using prebuilt tests.

## Project Structure

Reflected the structure of the `CurrencyConverterBundle`, excluding the framework `Symfony` structure.

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # Controllers handling user actions
│      ├── Command/                       # Console commands to manage bundle functions
│      ├── DependencyInjection/           # Logic for dependency and parameter configuration
│      ├── DTO/                           # Data Transfer Objects for structuring information
│      ├── Entity/                        # Entities for database operations
│      ├── Form/                          # Files for creating and handling forms
│      ├── Migrations/                    # Migration scripts for modifying database structure
│      ├── Repository/                    # Repositories for data access and processing
│      ├── Resources/                     # Templates, localization, and other resources
│      ├── Service/                       # Business logic and helper services
│      ├── CurrencyConverterBundle.php    # Main bundle file to integrate it into the project
```

---

## Installation

### 1. Cloning the Repository
Clone the project to your local machine:

```bash
git clone https://github.com/yaleksandr89/freecurrencyapi-converter-php.git
cd freecurrencyapi-converter-php
```

### 2. Project Configuration

#### 2.1 Setting up the .env File

PostgreSQL is used as the DBMS, but you can use others. Example connection configuration:

```dotenv
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/db_name?serverVersion=17&charset=utf8"
# user - username
# password - user password
# db_name - database name
# serverVersion - PostgreSQL version (check with psql -V)
```
You must also specify:

- `APP_TIMEZONE` - timezone, default is **UTC**
- `CURRENCY_CONVERTER_API_KEY` - API key generated in the service's personal account
- `CURRENCY_CONVERTER_API_URL` - Current API URL (as of 12.12.2024): https://api.freecurrencyapi.com/v1
- `USE_MOCK_DATA` - false or true. Use mock data for testing. Default is **false**

![env.png](../images/env.png)

#### 2.2 Installing Composer Packages

```bash
composer i && composer dump-autoload
```

#### 2.3 Creating the Database

```bash
php bin/console doctrine:database:create
```

![database-create.png](../images/database-create.png)

#### 2.4 Running Migrations

```bash
php bin/console doctrine:migrations:migrate
```

![migrations-migrate.png](../images/migrations-migrate.png)

#### 2.5 Configuring the Web Server

Example Nginx configuration:

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

Adjust:

- `server_name` - domain name
- `root` - project directory
- `fastcgi_pass` - adjust PHP version if needed (check with `php -v`)
- `access_log` / `error_log` - log file paths

#### 2.6 Configuring Cron

For automatic updates via cron. For testing, add the command to run every minute:

```bash
crontab -e

# Testing (every minute)
* * * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

For production, schedule twice a day (at midnight and noon):

```bash
crontab -e

# Twice daily at 00:00 and 12:00
0 0,12 * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

---

## Using the Web Application

...
