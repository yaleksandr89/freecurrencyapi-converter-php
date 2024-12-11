# Convertidor de divisas (FreecurrencyAPI)

### Elija Idioma

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../../../README.md) | [English](README_en.md) | **Seleccionado** | [中文](README_zh.md) | [Français](README_fr.md) | [Deutsch](README_de.md) |

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
