# Documentation Technique - Système d'Entités MODUSCAP

## Vue d'ensemble

Ce document décrit le système d'entités Symfony 7.3 conçu pour gérer les produits MODUSCAP : des maisons en capsule modulaires personnalisables.

## Architecture des Entités

### 1. ProductCategory - Catégories de Produits

**Fichier:** `src/Entity/ProductCategory.php`

**Description:** Gère les catégories de maisons en capsule.

**Attributs principaux:**
- `slug` - Identifiant unique (URL-friendly)
- `name` - Nom de la catégorie (multilingue)
- `description` - Description détaillée (multilingue)
- `color` - Couleur de représentation hexadécimale
- `position` - Position dans l'affichage
- `isActive` - Statut actif/inactif
- `sortOrder` - Ordre de tri

**Exemple d'utilisation:**
```php
$category = new ProductCategory();
$category->setSlug('capsule-house');
$category->setName('Capsule House');
$category->setDescription('Innovation pour l\'habitat ultra-compact');
$category->setColor('#3B82F6');
$category->setPosition(1);
```

### 2. Product - Produits Principaux

**Fichier:** `src/Entity/Product.php`

**Description:** Entité centrale représentant une maison en capsule.

**Attributs techniques:**
- Informations de base : `slug`, `name`, `description`, `features`, `specifications`
- Prix : `price`, `pricePerSquareMeter`
- Dimensions : `surface`, `dimensions`, `assemblyTime`
- Caractéristiques : `energyClass`, `constructionType`, `rooms`, `bathrooms`, `bedrooms`
- Garanties : `warrantyStructure`, `warrantyEquipment`
- Statuts : `isActive`, `isFeatured`, `isPreOrder`, `stockQuantity`
- Statistiques : `views`, `sales`

**Méthodes importantes:**
- `getFinalPrice()` - Calcule le prix final avec options
- `incrementViews()` - Incrémente les vues
- `getSelectedOptions()` - Récupère les options sélectionnées

### 3. ProductOptionGroup - Groupes d'Options

**Fichier:** `src/Entity/ProductOptionGroup.php`

**Description:** Groupe les options par thème (matériaux, équipements, etc.).

**Types de groupes:**
- `select` - Sélection unique
- `radio` - Boutons radio
- `checkbox` - Cases à cocher multiples
- `text` - Champ texte libre
- `number` - Valeur numérique

**Contraintes:**
- `isRequired` - Option obligatoire
- `minSelections` - Minimum de sélections
- `maxSelections` - Maximum de sélections

### 4. ProductOption - Options Individuelles

**Fichier:** `src/Entity/ProductOption.php`

**Description:** Représente une option spécifique dans un groupe.

**Attributs de pricing:**
- `price` - Prix fixe en euros
- `pricePercentage` - Pourcentage de modification du prix
- `isDefault` - Option par défaut

**Méthodes:**
- `getFinalPrice()` - Calcule le prix final incluant les pourcentages

### 5. ProductOptionValue - Valeurs d'Options par Produit

**Fichier:** `src/Entity/ProductOptionValue.php`

**Description:** Lie les options aux produits avec des valeurs personnalisées.

**Fonctionnalités:**
- Valeurs personnalisées par produit
- Prix personnalisés par produit
- Sélection d'options
- Contrainte unique produit-option

### 6. ProductImage - Images de Produits

**Fichier:** `src/Entity/ProductImage.php`

**Description:** Gère les images liées aux produits avec classification.

**Types d'images:**
- `exterior` - Vue extérieure
- `interior` - Vue intérieure
- `detail` - Détails techniques
- `technical` - Plans techniques
- `lifestyle` - Photos de style de vie

**Fonctionnalités:**
- Image principale (`isMain`)
- Ordre d'affichage (`sortOrder`)
- Métadonnées (titre, alt, description)

### 7. Media - Gestion des Fichiers

**Fichier:** `src/Entity/Media.php`

**Description:** Gestion centralisée des fichiers uploadés.

**Fonctionnalités:**
- Upload automatique avec génération de noms uniques
- Support des métadonnées (alt, extension)
- Gestion du cycle de vie (création, mise à jour, suppression)

## Système Multilingue

### Configuration

Le système utilise `stof/doctrine-extensions-bundle` avec l'extension `translatable` activée.

**Configuration dans `config/packages/stof_doctrine_extensions.yaml`:**
```yaml
stof_doctrine_extensions:
    default_locale: fr_FR
    orm:
        default:
            timestampable: true
            sluggable: true
            translatable: true
```

### Entités Multilingues

Les entités suivantes sont multilingues :
- `ProductCategory` - `name`, `description`
- `Product` - `name`, `shortDescription`, `description`, `features`, `specifications`
- `ProductOptionGroup` - `name`, `description`
- `ProductOption` - `name`, `description`

### Utilisation

```php
// Définir une traduction
$product = new Product();
$product->setName('Capsule House'); // Langue par défaut
$product->setName('Maison Capsule', 'fr'); // Français explicite

// Récupérer la traduction
$name = $product->getName(); // Langue actuelle
```

## Repositories

### ProductRepository

**Méthodes principales:**
- `findActiveProducts()` - Produits actifs
- `findFeaturedProducts()` - Produits en vedette
- `findByCategory()` - Produits par catégorie
- `searchProducts()` - Recherche textuelle
- `findByPriceRange()` - Filtrage par prix
- `findBySurfaceRange()` - Filtrage par surface

### ProductOptionRepository

**Méthodes principales:**
- `findByGroup()` - Options par groupe
- `findGroupedOptions()` - Options groupées par thème
- `findRequiredOptions()` - Options obligatoires
- `findOptionsWithPrice()` - Options avec impact prix

### ProductImageRepository

**Méthodes principales:**
- `findByProduct()` - Images d'un produit
- `findByImageType()` - Images par type
- `findMainImageForProduct()` - Image principale
- `searchImages()` - Recherche dans métadonnées

## Système de Personnalisation

### Workflow de Configuration

1. **Créer un groupe d'options**
   ```php
   $group = new ProductOptionGroup();
   $group->setType('select');
   $group->setIsRequired(true);
   ```

2. **Définir les options**
   ```php
   $option = new ProductOption();
   $option->setName('Bois naturel');
   $option->setPrice('2000');
   $option->setGroup($group);
   ```

3. **Associer au produit**
   ```php
   $optionValue = new ProductOptionValue();
   $optionValue->setProduct($product);
   $optionValue->setOption($option);
   $optionValue->setIsSelected(true);
   ```

### Calcul des Prix

Le système calcule automatiquement le prix final :

```php
$finalPrice = $product->getFinalPrice(); // Prix de base + options sélectionnées

// OU manuellement
$basePrice = floatval($product->getPrice());
$optionsPrice = 0;

foreach ($product->getSelectedOptions() as $optionValue) {
    $optionPrice = $optionValue->getFinalPrice($product->getPrice());
    if ($optionPrice !== null) {
        $optionsPrice += floatval($optionPrice);
    }
}
```

## Migrations

**Fichier:** `migrations/Version20251114131306.php`

### Tables créées:
- `product_categories` - Catégories
- `products` - Produits
- `product_option_groups` - Groupes d'options
- `product_options` - Options
- `product_option_values` - Valeurs d'options
- `product_images` - Images
- `ext_translations` - Traductions

### Index et contraintes:
- Index uniques sur les slugs
- Index de performance sur les champs de recherche
- Contraintes de clés étrangères
- Index de tri pour l'affichage

## Fixtures

**Fichier:** `src/DataFixtures/ProductFixtures.php`

Données de démonstration basées sur les produits MODUSCAP réels :
- 5 catégories de maisons
- 4 groupes d'options
- 8 options de personnalisation
- Images de démonstration

## Utilisation en Production

### 1. Installation
```bash
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### 2. Configuration multilingue
```php
// Dans un contrôleur
use Gedmo\Translatable\Entity\Translation;
use Gedmo\Translatable\TranslatableListener;

$product = $productRepository->find($id);

// Changer la locale
$product->setLocale('en');
$entityManager->refresh($product);

$name = $product->getName(); // Nom en anglais
```

### 3. Gestion des médias
```php
// Upload d'image
$media = new Media();
$media->setFile($uploadedFile);
$entityManager->persist($media);

$productImage = new ProductImage();
$productImage->setMedia($media);
$productImage->setProduct($product);
$productImage->setIsMain(true);
$entityManager->persist($productImage);
```

## Avantages de l'Architecture

1. **Extensibilité** - Facile d'ajouter de nouvelles options
2. **Multilingue** - Support natif des traductions
3. **Performance** - Index optimisés pour les requêtes
4. **Flexibilité** - Types d'options variés
5. **Maintenabilité** - Code organisé et documenté
6. **Évolutivité** - Architecture modulaire

## Considérations de Performance

1. **Index数据库** - Tous les champs de recherche sont indexés
2. **Pagination** - Utiliser les méthodes avec `limit`
3. **Cache** - Implémenter du cache pour les requêtes fréquentes
4. **Optimisation** - Limiter les jointures multiples

## Conclusion

Ce système d'entités fournit une base solide et extensible pour gérer le catalogue de produits MODUSCAP avec :
- Support complet du multilingue
- Système de personnalisation avancé
- Gestion des médias optimisée
- Architecture scalable et maintenable