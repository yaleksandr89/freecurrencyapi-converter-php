# Convertisseur de devises (FreecurrencyAPI)

### Choisissez la Langue

| Русский                          | English | Español | 中文 | Français | Deutsch |
|----------------------------------|------------|------------|-----------|-------------|----------|
| [Русский](../../../../README.md) | [English](README_en.md) | [Español](README_es.md) | [中文](README_zh.md) | **Sélectionné** | [Deutsch](README_de.md) |

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
