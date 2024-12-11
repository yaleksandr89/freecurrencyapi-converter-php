# Currency Converter (FreecurrencyAPI)

### Choose Language

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../../../README.md) | **Selected** | [Español](README_es.md) | [中文](README_zh.md) | [Français](README_fr.md) | [Deutsch](README_de.md) |

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
