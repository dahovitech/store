# 🏠 ModusCap Product Management - Documentation Technique

## Vue d'ensemble

Ce module fournit une solution complète de gestion des produits ModusCap pour Symfony 7.3, incluant la gestion multilingue, les options de personnalisation et les configurations avancées.

## Architecture des Entités

### 1. **ProductCategory** - Catégories de Produits
Gère les catégories des maisons ModusCap (Compacte, Média, Écologique, Premium, Familiale).

```php
// Exemple d'utilisation
$category = new ProductCategory();
$category->setSlug('capsule-house')
         ->setName('Maison Capsule')
         ->setDescription('Maisons compactes et fonctionnelles')
         ->setIsActive(true);
```

### 2. **Product** - Entité Principale des Produits
Contient toutes les spécifications techniques ModusCap :
- Surface habitable et terrasse
- Dimensions extérieures
- Matériaux de construction
- Performances énergétiques
- Prix et options de montage

```php
// Exemple: Capsule House
$product = new Product();
$product->setSku('CAPSULE-001')
        ->setSlug('capsule-house')
        ->setPrice('38000.00')
        ->setSurfaceHabitable(28)
        ->setNbPieces(1)
        ->setClasseEnergetique('B')
        ->setTempsMontage(1);
```

### 3. **ProductTranslation** - Gestion Multilingue
Gère les traductions des produits avec intégration StofDoctrineExtensionsBundle.

```php
// Traduction française
$translationFr = new ProductTranslation();
$translationFr->setName('Capsule House')
               ->setDescription('Innovation en habitat ultra-compact...')
               ->setLanguage($languageFr)
               ->setProduct($product);

// Traduction anglaise
$translationEn = new ProductTranslation();
$translationEn->setName('Capsule House')
               ->setDescription('MODUSCAP innovation for ultra-compact housing...')
               ->setLanguage($languageEn)
               ->setProduct($product);
```

### 4. **ProductOptionGroup & ProductOption** - Options de Personnalisation

#### Groupes d'Options
```php
$bardageGroup = new ProductOptionGroup();
$bardageGroup->setSlug('bardages')
             ->setName('Types de Bardages')
             ->setType('select')
             ->setIsRequired(true);
```

#### Options Individuelles
```php
$bardageBois = new ProductOption();
$bardageBois->setSlug('bardage-bois-naturel')
            ->setName('Bois Naturel')
            ->setAdditionalPrice('500.00')
            ->setGroup($bardageGroup);

$bardageComposite = new ProductOption();
$bardageComposite->setSlug('bardage-composite')
                 ->setName('Composite Haute Densité')
                 ->setAdditionalPrice('800.00')
                 ->setGroup($bardageGroup);
```

### 5. **ProductConfiguration** - Configurations Personnalisées
Gère les configurations complètes de produits avec calcul automatique des prix.

```php
$configuration = new ProductConfiguration();
$configuration->setName('Capsule House Premium')
              ->setSlug('capsule-house-premium')
              ->setBasePrice('38000.00')
              ->setAdditionalPrice('2500.00')
              ->setProduct($product)
              ->setSelectedOptions([
                  'bardage' => 'composite',
                  'couleur' => 'gris-anthracite',
                  'isolation' => 'premium'
              ]);
```

### 6. **Media** - Gestion des Médias
Étendu pour inclure la gestion des images de produits.

```php
$mainImage = new Media();
$mainImage->setFile($uploadedFile)
          ->setType('main_image')
          ->setAlt('Capsule House - Vue extérieure')
          ->setSortOrder(1);

$product->setMainImage($mainImage);

// Galerie d'images
foreach ($galleryFiles as $file) {
    $galleryImage = new Media();
    $galleryImage->setFile($file)
                 ->setType('gallery')
                 ->setAlt('Intérieur Capsule House')
                 ->setSortOrder($sortOrder++);
    
    $product->addGallery($galleryImage);
}
```

## Repositories et Fonctionnalités Avancées

### Recherche et Filtrage
```php
// Recherche par critères multiples
$products = $productRepository->searchProducts(
    query: 'capsule',
    category: $category,
    minPrice: 30000,
    maxPrice: 50000,
    minSurface: 25,
    maxSurface: 40
);

// Produits vedettes
$featuredProducts = $productRepository->findFeatured(6);

// Comparaison de produits
$comparisonData = $productRepository->getComparisonData([1, 2, 3]);
```

### Gestion des Traductions
```php
// Obtenir traduction par langue
$frenchTranslation = $product->getTranslationByCode('fr');

// Trouver traductions manquantes
$missingTranslations = $translationRepository->findMissingTranslations(
    $product, 
    $availableLanguages
);
```

### Calculs de Prix Avancés
```php
// Calcul du prix total avec options
$totalPrice = $product->getPrice();
foreach ($selectedOptions as $option) {
    $totalPrice += floatval($option->getAdditionalPrice());
}

// Configuration avec remises
$configPrice = $configuration->calculateTotalPrice();
```

## Configuration Required

### 1. StofDoctrineExtensionsBundle (déjà configuré)
确保 dans `config/packages/stof_doctrine_extensions.yaml`:
```yaml
stof_doctrine_extensions:
    default_locale: fr
    translation_fallback: true
```

### 2. Mapping Doctrine
Les entités utilisent les annotations Doctrine pour le mapping automatique.

### 3. Validation
Toutes les entités incluent des contraintes de validation Symfony.

## Données ModusCap Intégrées

Les entités supportent tous les produits du catalogue ModusCap :

1. **Capsule House** - 38 000 € (28 m²)
2. **Apple Cabin** - 45 000 € (35 m²)
3. **Natural House** - 48 000 € (38 m²)
4. **Dome House** - 52 000 € (42 m²)
5. **Model Double** - 68 000 € (62 m²)

Chaque produit inclut :
- Spécifications techniques complètes
- Matériaux et équipements
- Performances énergétiques
- Options de personnalisation
- Images et documentation

## Auteur

Développé par **jprud67** (Prudence Dieudonné ASSOGBA) pour MODUSCAP - Solutions d'Habitat Modulaire Premium.

---

*Cette documentation accompagne les entités Symfony 7.3 pour la gestion des produits ModusCap avec support multilingue et options de personnalisation avancées.*