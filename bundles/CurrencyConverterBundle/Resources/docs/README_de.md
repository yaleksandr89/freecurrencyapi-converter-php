# Währungsumrechner (FreecurrencyAPI)

### Sprache Auswählen

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../../../README.md) | [English](README_en.md) | [Español](README_es.md) | [中文](README_zh.md) | [Français](README_fr.md) | **Ausgewählt** |

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
