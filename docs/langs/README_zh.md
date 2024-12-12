# 货币转换器 (FreecurrencyAPI)

### 选择语言

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../README.md) | [English](README_en.md) | [Español](README_es.md) | **已选** | [Français](README_fr.md) | [Deutsch](README_de.md) |

---

**Currency Converter** — 这是一个用于操作货币兑换 API 的项目。您可以请求汇率、执行货币转换并通过预设的测试检查功能。

## 项目结构

反映了 `CurrencyConverterBundle` 的结构，不包括 `Symfony` 框架的结构。

```plaintext
root_dir/
├── bundles/
│   ├── CurrencyConverterBundle/
│      ├── Action/                        # 处理用户操作的控制器
│      ├── Command/                       # 用于管理捆绑包功能的控制台命令
│      ├── DependencyInjection/           # 用于依赖和参数配置的逻辑
│      ├── DTO/                           # 用于结构化信息的数据传输对象
│      ├── Entity/                        # 数据库操作的实体
│      ├── Form/                          # 用于创建和处理表单的文件
│      ├── Migrations/                    # 修改数据库结构的迁移脚本
│      ├── Repository/                    # 数据访问和处理的存储库
│      ├── Resources/                     # 模板、本地化和其他资源
│      ├── Service/                       # 业务逻辑和辅助服务
│      ├── CurrencyConverterBundle.php    # 将捆绑包集成到项目中的主文件
```

---

## 安装

### 1. 克隆存储库

将项目克隆到本地计算机:

```bash
git clone https://github.com/yaleksandr89/freecurrencyapi-converter-php.git
cd freecurrencyapi-converter-php
```

### 2. 项目配置

#### 2.1设置。env

我使用'PostgreSQL'作为DBMS。 但是数据库结构简单，所以原则上可以使用其他数据库。 示例数据库连接配置:

```dotenv
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/db_name?serverVersion=17&charset=utf8"
# user - 用户名
# password  - 用户的密码
# db_name  - 数据库的名称
# serverVersion - 是postgresql版本（可以用psql-V命令检查）
```

还需要指定:

* `APP_TIMEZONE` 一 是 个时区，用于按计划更新货币。 默认值为 **UTC**
* `CURRENCY_CONVERTER_API_KEY` - 在API服务的个人帐户中生成的令牌
* `CURRENCY_CONVERTER_API_URL` - 当前（12.12.2024）连接到API的链接：https://api.freecurrencyapi.com/v1
* `USE_MOCK_DATA` - false或true。 可以使用测试数据进行测试。 为了不超出服务的限制而实施。 默认值为 **false**

![env.png](../images/env.png)

#### 2.3安装composer软件包

```bash
composer i && composer dump-autoload
```

#### 2.4创建数据库

要创建数据库，您可以使用

```bash
php bin/console doctrine:database:create
```

![database-create.png](../images/database-create.png)

#### 2.5迁移应用

必须应用迁移

```bash
php bin/console doctrine:migrations:migrate
```

![migrations-migrate.png](../images/migrations-migrate.png)

#### 2.6配置Web服务器

我使用nginx作为web服务器，配置:

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

注意，如果有必要，改变:

* `server_name` - 域名
* `root` - 是工作项目的目录（如有必要，替换**freecurrencyapi-converter-php**）
* `fastcgi_pass` - php8.3-fpm。因此，如果您使用其他版本的php，请替换该版本（可以使用命令`php-v'查看php版本）
* `access_log`/ `error_log` - 是日志文件的目录。

#### 2.7Cron设置

通过cron自动更新货币。 作为测试，您可以每分钟添加一个命令的执行

```bash
crontab -e

#用于测试（每分钟运行1次）
* * * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

检查后，您可以将调用控制台命令的时间增加到每天2次（中午和午夜）

```bash
crontab -e

#每天执行2次：00:00和12:00。
0 0,12 * * * /bin/php /www/freecurrencyapi-converter-php/bin/console currency:update-rates >> /www/test-tasks/php/firebird-tours.col/var/log/currency_update.log 2>&1
```

---

## 使用web应用程序

...
