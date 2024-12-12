# Währungsumrechner (FreecurrencyAPI)

### Sprache Auswählen

| Русский                               | English | Español | 中文 | Français | Deutsch |
|---------------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../README.md) | [English](README_en.md) | [Español](README_es.md) | [中文](README_zh.md) | [Français](README_fr.md) | **Ausgewählt** |

---

**Currency Converter** — dies ist ein Projekt zur Arbeit mit einer Währungsumrechnungs-API. Sie können Wechselkurse abfragen, Währungen konvertieren und die Funktionalität mit vorgefertigten Tests überprüfen.

## Projektstruktur

Zeigt die Struktur des Bundles `CurrencyConverterBundle`. Die Struktur des Frameworks `Symfony` ist nicht enthalten.

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # Controller zur Bearbeitung von Benutzeraktionen
│      ├── Command/                       # Konsolenbefehle zur Verwaltung der Bundle-Funktionen
│      ├── DependencyInjection/           # Logik zur Konfiguration von Abhängigkeiten und Parametern
│      ├── DTO/                           # Datenübertragungsobjekte zur Strukturierung von Informationen
│      ├── Entity/                        # Entitäten für Datenbankoperationen
│      ├── Form/                          # Dateien zur Erstellung und Verarbeitung von Formularen
│      ├── Migrations/                    # Migrationsskripte zur Änderung der Datenbankstruktur
│      ├── Repository/                    # Repositories für Datenzugriff und Verarbeitung
│      ├── Resources/                     # Vorlagen, Lokalisierung und andere Ressourcen
│      ├── Service/                       # Geschäftslogik und Hilfsdienste
│      ├── CurrencyConverterBundle.php    # Hauptdatei des Bundles zur Integration in das Projekt
```

---

## Installation

### 1. Repository klonen

Klonen Sie das Projekt auf Ihren lokalen Computer:

```bash
git clone https://github.com/yaleksandr89/freecurrencyapi-converter-php.git
cd freecurrencyapi-converter-php
```

### 2. Projektkonfiguration

#### 2.1 .env-Datei konfigurieren

Als Datenbank-Management-System wurde `PostgreSQL` verwendet. Die Datenbankstruktur ist jedoch einfach, daher können auch andere Datenbanken verwendet werden. Beispiel für die Konfiguration der Datenbankverbindung:

```dotenv
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/db_name?serverVersion=17&charset=utf8"
# user - Datenbankbenutzername
# password - Passwort des Benutzers
# db_name - Datenbankname
# serverVersion - PostgreSQL-Version (mit dem Befehl psql -V überprüfbar)
```

Außerdem müssen folgende Parameter angegeben werden:

* `APP_TIMEZONE` - Zeitzone, die für das regelmäßige Aktualisieren der Wechselkurse verwendet wird. Standardwert ist **UTC**
* `CURRENCY_CONVERTER_API_KEY` - API-TOKEN, der im Benutzerkonto des API-Dienstes generiert wird
* `CURRENCY_CONVERTER_API_URL` - Aktuelle API-URL (Stand 12.12.2024): https://api.freecurrencyapi.com/v1
* `USE_MOCK_DATA` - true oder false. Testdaten können verwendet werden, um die Dienstlimits nicht zu überschreiten. Standardwert ist **false**

![env.png](../images/env.png)

#### 2.3 Composer-Pakete installieren

```bash
composer i && composer dump-autoload
```

#### 2.4 Datenbank erstellen

Erstellen Sie die Datenbank mit folgendem Befehl:

```bash
php bin/console doctrine:database:create
```

![database-create.png](../images/database-create.png)

#### 2.5 Migrationen anwenden

Führen Sie die Migrationen aus:

```bash
php bin/console doctrine:migrations:migrate
```

![migrations-migrate.png](../images/migrations-migrate.png)

#### 2.6 Webserver konfigurieren

Als Webserver wurde nginx verwendet. Beispielkonfiguration:

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

    access_log /var/logs/currency-converter-access.log;
    error_log /var/logs/currency-converter-error.log;
}
```

Beachten Sie und ändern Sie gegebenenfalls:

* `server_name` - Domainname
* `root` - Verzeichnis des Arbeitsprojekts (falls nötig, **freecurrencyapi-converter-php** ändern)
* `fastcgi_pass` - php8.3-fpm.sock, bei Verwendung einer anderen PHP-Version ändern (Version mit `php -v` überprüfen)
* `access_log` / `error_log` - Verzeichnisse der Log-Dateien.

#### 2.7 Cronjob einrichten

Für die automatische Aktualisierung der Wechselkurse können Cronjobs eingerichtet werden. Zum Testen kann der Befehl jede Minute ausgeführt werden:

```bash
crontab -e

# Für Tests (Ausführung jede Minute)
* * * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/currency-converter/var/log/currency_update.log 2>&1
```

Nach erfolgreichem Test kann die Ausführungsfrequenz auf zweimal täglich erhöht werden (um Mitternacht und Mittag):

```bash
crontab -e

# Ausführung zweimal täglich: um 00:00 und 12:00 Uhr
0 0,12 * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/currency-converter/var/log/currency_update.log 2>&1
```

---

## Nutzung der Webanwendung

...
