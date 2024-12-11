# Конвертер валют (FreecurrencyAPI)

### Выберите язык

| Русский  | English                              | Español                                   | 中文                              | Français                              | Deutsch                              |
|----------|--------------------------------------|-------------------------------------------|---------------------------------|---------------------------------------|--------------------------------------|
| **Выбран** | [English](./bundles/CurrencyConverterBundle/Resources/docs/README_en.md) | [Español](./bundles/CurrencyConverterBundle/Resources/docs/README_es.md) | [中文](./bundles/CurrencyConverterBundle/Resources/docs/README_zh.md) | [Français](./bundles/CurrencyConverterBundle/Resources/docs/README_fr.md) | [Deutsch](./bundles/CurrencyConverterBundle/Resources/docs/README_de.md) |

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
