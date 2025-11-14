# Entités MODUSCAP pour Symfony 7.3

## Vue d'ensemble

Ce projet étend le dépôt store avec un système complet d'entités pour gérer les produits MODUSCAP : des maisons en capsule modulaires personnalisables.

## Nouvelles Entités Créées

### 🏘️ ProductCategory
- Gestion des catégories de produits
- Support multilingue (nom, description)
- Position et ordre de tri
- Couleurs de représentation

### 🏠 Product
- Produit principal (maison en capsule)
- Informations techniques complètes
- Prix et dimensions
- Garanties et certifications
- Support multilingue complet
- Système de vues et ventes

### ⚙️ ProductOptionGroup
- Groupes d'options de personnalisation
- Types : select, radio, checkbox, text, number
- Contraintes (obligatoire, min/max sélections)

### 🔧 ProductOption
- Options individuelles
- Prix fixe ou pourcentage
- Valeurs par défaut
- Support multilingue

### 🎯 ProductOptionValue
- Association option-produit
- Valeurs personnalisées par produit
- Prix personnalisés
- Sélection d'options

### 🖼️ ProductImage
- Images de produits
- Classification par type (exterior, interior, detail, etc.)
- Image principale
- Métadonnées (titre, alt, description)

## Configuration Requise

### stof_doctrine_extensions
Extension translatable activée dans `config/packages/stof_doctrine_extensions.yaml`

### Migration
```bash
php bin/console doctrine:migrations:migrate
```

### Fixtures
```bash
php bin/console doctrine:fixtures:load
```

## Utilisation Rapide

```php
// Créer un produit
$product = new Product();
$product->setName('Capsule House');
$product->setPrice('38000.00');
$product->setSurface('28.00');

// Ajouter des options
$optionValue = new ProductOptionValue();
$optionValue->setProduct($product);
$optionValue->setOption($selectedOption);
$optionValue->setIsSelected(true);

// Calculer le prix final
$finalPrice = $product->getFinalPrice();
```

## Fonctionnalités

✅ **Multilingue** - Support natif des traductions
✅ **Personnalisation** - Système d'options avancées
✅ **Prix dynamiques** - Calcul automatique avec options
✅ **Médias** - Gestion complète des images
✅ **Performance** - Index optimisés
✅ **Extensibilité** - Architecture modulaire

## Structure des Fichiers

```
src/Entity/
├── Product.php              # Produit principal
├── ProductCategory.php      # Catégories
├── ProductOption.php        # Options
├── ProductOptionGroup.php   # Groupes d'options
├── ProductOptionValue.php   # Valeurs d'options
└── ProductImage.php         # Images

src/Repository/
├── ProductRepository.php
├── ProductCategoryRepository.php
├── ProductOptionRepository.php
├── ProductOptionGroupRepository.php
├── ProductOptionValueRepository.php
└── ProductImageRepository.php

src/DataFixtures/
└── ProductFixtures.php      # Données de démonstration

migrations/
└── Version20251114131306.php

MODUSCAP_ENTITIES_DOCUMENTATION.md
demo_moduscap_entities.php
```

## Exemple : Maison Capsule avec Options

```php
// Catégorie
$category = new ProductCategory();
$category->setSlug('capsule-house');
$category->setName('Capsule House');

// Produit
$product = new Product();
$product->setName('Capsule House - 28m²');
$product->setPrice('38000.00');
$product->setSurface('28.00');
$product->setCategory($category);

// Option : Type de bardage
$group = new ProductOptionGroup();
$group->setType('select');

$option = new ProductOption();
$option->setName('Bardage bois naturel');
$option->setPrice('2000.00');
$option->setGroup($group);

// Valeur d'option pour le produit
$optionValue = new ProductOptionValue();
$optionValue->setProduct($product);
$optionValue->setOption($option);
$optionValue->setIsSelected(true);

// Prix final
echo $product->getFinalPrice(); // 40000.00€
```

## Support

Voir `MODUSCAP_ENTITIES_DOCUMENTATION.md` pour la documentation complète.

---

**Auteur:** jprud67 (Prudence Dieudonné ASSOGBA)
**Framework:** Symfony 7.3
**Extensions:** stof/doctrine-extensions-bundle