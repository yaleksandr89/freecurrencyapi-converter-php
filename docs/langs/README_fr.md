# Convertisseur de devises (FreecurrencyAPI)

### Choisissez la Langue

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../README.md) | [English](README_en.md) | [Español](README_es.md) | [中文](README_zh.md) | **Sélectionné** | [Deutsch](README_de.md) |

---

**Currency Converter** — c’est un projet pour travailler avec une API de conversion de devises. Vous pouvez demander des taux de change, effectuer des conversions et tester la fonctionnalité à l'aide de tests préconfigurés.

## Structure du Projet

Reflète la structure du bundle `CurrencyConverterBundle`, sans inclure la structure du framework `Symfony`.

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # Contrôleurs gérant les actions des utilisateurs
│      ├── Command/                       # Commandes console pour gérer les fonctions du bundle
│      ├── DependencyInjection/           # Logique pour configurer les dépendances et paramètres
│      ├── DTO/                           # Objets de transfert de données pour structurer les informations
│      ├── Entity/                        # Entités pour les opérations de base de données
│      ├── Form/                          # Fichiers pour créer et gérer des formulaires
│      ├── Migrations/                    # Scripts de migration pour modifier la structure de la base de données
│      ├── Repository/                    # Répertoires pour l'accès et le traitement des données
│      ├── Resources/                     # Modèles, localisation et autres ressources
│      ├── Service/                       # Logique métier et services auxiliaires
│      ├── CurrencyConverterBundle.php    # Fichier principal du bundle pour l'intégrer au projet
```

---

## Installation

### 1. Clonage d'un référentiel

Inclinez le projet vers votre ordinateur local:

```bash
git clone https://github.com/yaleksandr89/freecurrencyapi-converter-php.git
cd freecurrencyapi-converter-php
```

### 2. Configuration du projet

#### 2.1 configuration .env

En tant que SGBD utilisé `PostgreSQL'. Mais la structure de la base de données est simple, donc en principe, vous pouvez utiliser d'autres SGBD. Exemple configuration de connexion OBD:

```dotenv
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/db_name?serverVersion=17&charset=utf8"
# user - nom d'utilisateur
# password - mot de passe utilisateur
# db_name - nom de la base de données
# serverVersion - version postgresql (vous pouvez vérifier avec la commande psql-V)
```
Также требуется указать:

* `APP_TIMEZONE` est un fuseau horaire utilisé pour mettre à jour les devises selon un calendrier. Valeur par défaut **UTC**
* `CURRENCY_CONVERTER_API_KEY` - JETON généré dans le compte personnel de l'API du service
* `CURRENCY_CONVERTER_API_URL` - actuellement (12.12.2024) lien pour se connecter à l'API: https://api.freecurrencyapi.com/v1
* `USE_MOCK_DATA` est false ou true. Vous pouvez utiliser des données de test pour tester. Mis en œuvre dans le but de ne pas dépasser les limites du service. Valeur par défaut **false**

![env.png](../images/env.png)

#### 2.3 Installer les paquets composer

```bash
composer i && composer dump-autoload
```

#### 2.4 Créer une base de données

Pour créer une base de données, vous pouvez utiliser

```bash
php bin/console doctrine:database:create
```

![database-create.png](../images/database-create.png)

#### 2.5 Application de la migration

Vous devez appliquer les migrations

```bash
php bin/console doctrine:migrations:migrate
```

![migrations-migrate.png](../images/migrations-migrate.png)

#### 2.6 configuration du serveur Web

En tant que serveur Web utilisé nginx, configuration:

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

Faites attention et changez si nécessaire:

* `server_name` - nom de domaine
* `root` - répertoire avec le projet de travail (si nécessaire, remplacer**freecurrencyapi-converter-php**)
* `fastcgi_pass` - php8.3-fpm.sock, si vous utilisez une autre version de php, remplacez la version (la version de php peut être consultée avec la commande `php-v')
* `access_log` / `error_log` - répertoire jusqu'aux fichiers journaux.

#### 2.7 configuration cron

Pour mettre à jour automatiquement les devises via cron. En tant que test, vous pouvez ajouter l'exécution de la commande toutes les minutes

```bash
crontab -e

# Pour tester (exécution 1 fois par minute)
* * * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

Après vérification, vous pouvez augmenter le temps d'appel de la commande de la console jusqu'à 2 fois par jour (à midi et à minuit)

```bash
crontab -e

# Effectuer 2 fois par jour: à 00h00 et à 12h00.
0 0,12 * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

---

## Utilisation d'une application Web

...
