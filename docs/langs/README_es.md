# Convertidor de divisas (FreecurrencyAPI)

### Elija Idioma

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../README.md) | [English](README_en.md) | **Seleccionado** | [中文](README_zh.md) | [Français](README_fr.md) | [Deutsch](README_de.md) |

---

**Currency Converter** — este es un proyecto para trabajar con una API de cambio de divisas. Puede solicitar tasas de cambio, realizar conversiones y probar la funcionalidad con pruebas predefinidas.

## Estructura del Proyecto

Refleja la estructura del paquete `CurrencyConverterBundle`, excluyendo la estructura del framework `Symfony`.

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # Controladores que gestionan acciones de usuario
│      ├── Command/                       # Comandos de consola para gestionar funciones del bundle
│      ├── DependencyInjection/           # Lógica para configurar dependencias y parámetros
│      ├── DTO/                           # Objetos de transferencia de datos para estructurar información
│      ├── Entity/                        # Entidades para operaciones de base de datos
│      ├── Form/                          # Archivos para crear y manejar formularios
│      ├── Migrations/                    # Scripts de migración para modificar la estructura de la base de datos
│      ├── Repository/                    # Repositorios para acceso y procesamiento de datos
│      ├── Resources/                     # Plantillas, localización y otros recursos
│      ├── Service/                       # Lógica de negocio y servicios auxiliares
│      ├── CurrencyConverterBundle.php    # Archivo principal del bundle para integrarlo al proyecto
```

---

## Instalación

### 1. Clonar el repositorio

Clone el proyecto en su ordenador local:

```bash
git clone https://github.com/yaleksandr89/freecurrencyapi-converter-php.git
cd freecurrencyapi-converter-php
```

### 2. Configuración del proyecto

#### 2.1 Configuración del archivo .env

Se utilizó `PostgreSQL` como base de datos. Sin embargo, la estructura de la base de datos no es compleja, por lo que otras bases de datos también podrían funcionar. Ejemplo de configuración de conexión a la base de datos:

```dotenv
DATABASE_URL="postgresql://usuario:contraseña@127.0.0.1:5432/nombre_bd?serverVersion=17&charset=utf8"
# usuario - nombre del usuario
# contraseña - contraseña del usuario
# nombre_bd - nombre de la base de datos
# serverVersion - versión de postgresql (puede verificarse con el comando psql -V)
```

También necesita especificar:

* `APP_TIMEZONE` - Zona horaria, utilizada para actualizar las divisas automáticamente. Valor predeterminado **UTC**
* `CURRENCY_CONVERTER_API_KEY` - TOKEN, generado en el panel de control del servicio API
* `CURRENCY_CONVERTER_API_URL` - Actualmente (12.12.2024), el enlace para conectar con la API es: https://api.freecurrencyapi.com/v1
* `USE_MOCK_DATA` - false o true. Permite usar datos de prueba para evitar exceder los límites del servicio. Valor predeterminado **false**

![env.png](../images/env.png)

#### 2.3 Instalación de paquetes con composer

```bash
composer i && composer dump-autoload
```

#### 2.4 Creación de la base de datos

Para crear la base de datos, use:

```bash
php bin/console doctrine:database:create
```

![database-create.png](../images/database-create.png)

#### 2.5 Aplicar migraciones

Ejecute las migraciones:

```bash
php bin/console doctrine:migrations:migrate
```

![migrations-migrate.png](../images/migrations-migrate.png)

#### 2.6 Configuración del servidor web

Se utilizó nginx como servidor web. Ejemplo de configuración:

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

Asegúrese de modificar según sea necesario:

* `server_name` - nombre del dominio
* `root` - directorio del proyecto (reemplace **freecurrencyapi-converter-php** si es necesario)
* `fastcgi_pass` - php8.3-fpm.sock; si usa otra versión de PHP, cámbiela (puede verificar la versión con `php -v`)
* `access_log` / `error_log` - directorios para los archivos de registro.

#### 2.7 Configuración del cron

Para actualizar las divisas automáticamente, configure cron. Para pruebas, puede agregar la ejecución del comando cada minuto:

```bash
crontab -e

# Para pruebas (ejecución cada minuto)
* * * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

Después de probar, puede ajustar el intervalo de ejecución a dos veces al día (al mediodía y a la medianoche):

```bash
crontab -e

# Ejecución dos veces al día: a las 00:00 y a las 12:00.
0 0,12 * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

---

## Uso de la aplicación web

...