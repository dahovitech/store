<?php

/**
 * Script de démonstration pour les entités MODUSCAP
 * 
 * Ce script montre comment utiliser les entités pour créer et gérer
 * des produits de maisons en capsule avec leurs options de personnalisation.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Entity\ProductCategory;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\Media;
use App\Entity\ProductOptionGroup;
use App\Entity\ProductOption;
use App\Entity\ProductOptionValue;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// Configuration de l'EntityManager (en mode démonstration)
$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = true;

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);

$connection = DriverManager::getConnection([
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/../var/database.sqlite',
], $config);

$entityManager = new EntityManager($connection, $config);

echo "=== DÉMONSTRATION MODUSCAP - ENTITÉS PRODUITS ===\n\n";

// 1. Création d'une nouvelle catégorie
echo "1. Création d'une nouvelle catégorie de produits...\n";
$ecoCategory = new ProductCategory();
$ecoCategory->setSlug('eco-luxury');
$ecoCategory->setName('Eco Luxury');
$ecoCategory->setDescription('Maisons écologiques haut de gamme avec matériaux durables');
$ecoCategory->setColor('#22C55E');
$ecoCategory->setIsActive(true);
$ecoCategory->setSortOrder(10);
$ecoCategory->setPosition(6);

$entityManager->persist($ecoCategory);
$entityManager->flush();

echo "✓ Catégorie créée: " . $ecoCategory->getName() . "\n\n";

// 2. Création d'un nouveau produit
echo "2. Création d'un nouveau produit...\n";
$luxuryHouse = new Product();
$luxuryHouse->setSlug('eco-luxury-house');
$luxuryHouse->setName('Eco Luxury House');
$luxuryHouse->setShortDescription('Maison écologique haut de gamme de 50m²');
$luxuryHouse->setDescription('Une maison écologique moderne avec tous les équipements de luxe.');
$luxuryHouse->setFeatures('Panneaux solaires intégrés, récupération eau de pluie, matériaux biosourcés');
$luxuryHouse->setSpecifications('Structure CLT 160mm, Isolation fibre de lin 200mm, Panneaux solaires 6kWc');
$luxuryHouse->setPrice('75000.00');
$luxuryHouse->setSurface('50.00');
$luxuryHouse->setDimensions('7m × 7m × 3,5m');
$luxuryHouse->setAssemblyTime('3 jours');
$luxuryHouse->setEnergyClass('A++');
$luxuryHouse->setConstructionType('Bois massif CLT');
$luxuryHouse->setRooms(3);
$luxuryHouse->setBathrooms(2);
$luxuryHouse->setBedrooms(2);
$luxuryHouse->setTerrace('20.00');
$luxuryHouse->setFloorHeight('3.50');
$luxuryHouse->setWarrantyStructure('15 ans');
$luxuryHouse->setWarrantyEquipment('7 ans');
$luxuryHouse->setIsActive(true);
$luxuryHouse->setIsFeatured(true);
$luxuryHouse->setIsPreOrder(false);
$luxuryHouse->setStockQuantity(3);
$luxuryHouse->setSortOrder(6);
$luxuryHouse->setCategory($ecoCategory);

$entityManager->persist($luxuryHouse);
$entityManager->flush();

echo "✓ Produit créé: " . $luxuryHouse->getName() . " - " . $luxuryHouse->getPrice() . "€\n\n";

// 3. Création d'un groupe d'options
echo "3. Création d'un groupe d'options de personnalisation...\n";
$materialsGroup = new ProductOptionGroup();
$materialsGroup->setSlug('materiaux-finitions');
$materialsGroup->setName('Matériaux et finitions');
$materialsGroup->setType('select');
$materialsGroup->setIsRequired(true);
$materialsGroup->setIsActive(true);
$materialsGroup->setSortOrder(1);

$entityManager->persist($materialsGroup);
$entityManager->flush();

echo "✓ Groupe d'options créé: " . $materialsGroup->getName() . "\n\n";

// 4. Création d'options pour ce groupe
echo "4. Création d'options de matériaux...\n";

$woodFinishes = [
    [
        'name' => 'Bois naturel ciré',
        'description' => 'Finition naturelle avec cire d\'abeille',
        'isDefault' => true,
        'price' => 0
    ],
    [
        'name' => 'Bois lasuré',
        'description' => 'Protection lasure pour durabilité',
        'isDefault' => false,
        'price' => 2000
    ],
    [
        'name' => 'Bois peint',
        'description' => 'Peinture écologique monochrome',
        'isDefault' => false,
        'price' => 3000
    ],
    [
        'name' => 'Métal corten',
        'description' => 'Finitions métalliques corten',
        'isDefault' => false,
        'price' => 8000
    ]
];

foreach ($woodFinishes as $finishData) {
    $option = new ProductOption();
    $option->setSlug(strtolower(str_replace(' ', '-', $finishData['name'])));
    $option->setName($finishData['name']);
    $option->setDescription($finishData['description']);
    $option->setIsDefault($finishData['isDefault']);
    $option->setIsActive(true);
    $option->setSortOrder(1);
    $option->setGroup($materialsGroup);
    
    if ($finishData['price'] > 0) {
        $option->setPrice((string)$finishData['price']);
    }
    
    $entityManager->persist($option);
}

$entityManager->flush();

echo "✓ " . count($woodFinishes) . " options de matériaux créées\n\n";

// 5. Configuration des options pour le produit
echo "5. Configuration des options pour le produit...\n";

// Récupérer les options créées
$options = $entityManager->getRepository(ProductOption::class)->findBy(['group' => $materialsGroup]);

foreach ($options as $option) {
    $optionValue = new ProductOptionValue();
    $optionValue->setProduct($luxuryHouse);
    $optionValue->setOption($option);
    $optionValue->setIsSelected($option->isDefault());
    $optionValue->setSortOrder(1);
    
    // Si l'option a un prix personnalisé pour ce produit
    if ($option->getPrice() !== null) {
        $optionValue->setPrice($option->getPrice());
    }
    
    $entityManager->persist($optionValue);
}

$entityManager->flush();

echo "✓ Options configurées pour le produit\n\n";

// 6. Affichage du prix final avec options
echo "6. Calcul du prix final avec options sélectionnées...\n";
$basePrice = floatval($luxuryHouse->getPrice());
$optionsPrice = 0;

foreach ($luxuryHouse->getOptionValues() as $optionValue) {
    if ($optionValue->isSelected()) {
        $optionPrice = $optionValue->getFinalPrice($luxuryHouse->getPrice());
        if ($optionPrice !== null) {
            $optionsPrice += floatval($optionPrice);
        }
    }
}

$finalPrice = $basePrice + $optionsPrice;

echo "Prix de base: " . number_format($basePrice, 2) . "€\n";
echo "Prix des options: " . number_format($optionsPrice, 2) . "€\n";
echo "Prix final: " . number_format($finalPrice, 2) . "€\n\n";

// 7. Recherche et filtrage
echo "7. Démonstration de recherche et filtrage...\n";

// Rechercher des produits par catégorie
$categoryRepo = $entityManager->getRepository(ProductCategory::class);
$ecoProducts = $categoryRepo->findBy(['name' => 'Eco Luxury']);

if (!empty($ecoProducts)) {
    echo "✓ Produits trouvés dans la catégorie 'Eco Luxury': " . count($ecoProducts) . "\n";
}

// Rechercher des produits par prix
$productRepo = $entityManager->getRepository(Product::class);
$expensiveProducts = $productRepo->findByPriceRange('50000', null, 10);

echo "✓ Produits coûttant plus de 50 000€: " . count($expensiveProducts) . "\n";

// Rechercher des produits par surface
$mediumSizeProducts = $productRepo->findBySurfaceRange('30', '60', 10);
echo "✓ Produits entre 30 et 60m²: " . count($mediumSizeProducts) . "\n\n";

// 8. Gestion des images
echo "8. Ajout d'images au produit...\n";

// Créer quelques médias de démonstration
for ($i = 1; $i <= 3; $i++) {
    $media = new Media();
    $media->setAlt('Eco Luxury House vue ' . $i);
    $media->setExtension('jpg');
    
    $productImage = new ProductImage();
    $productImage->setMedia($media);
    $productImage->setProduct($luxuryHouse);
    $productImage->setTitle('Vue ' . $i . ' - Eco Luxury House');
    $productImage->setAlt('Eco Luxury House - Vue ' . $i);
    $productImage->setImageType($i === 1 ? 'exterior' : ($i === 2 ? 'interior' : 'detail'));
    $productImage->setSortOrder($i);
    $productImage->setIsMain($i === 1);
    $productImage->setIsActive(true);
    
    $entityManager->persist($media);
    $entityManager->persist($productImage);
}

$entityManager->flush();

echo "✓ 3 images ajoutées au produit\n\n";

// 9. Statistiques
echo "9. Statistiques du catalogue...\n";

$stats = [
    'Produits totaux' => $entityManager->getRepository(Product::class)->count([]),
    'Catégories totales' => $entityManager->getRepository(ProductCategory::class)->count([]),
    'Options totales' => $entityManager->getRepository(ProductOption::class)->count([]),
    'Groupes d\'options' => $entityManager->getRepository(ProductOptionGroup::class)->count([]),
    'Images totales' => $entityManager->getRepository(ProductImage::class)->count([])
];

foreach ($stats as $label => $count) {
    echo "- " . $label . ": " . $count . "\n";
}

echo "\n=== FIN DE LA DÉMONSTRATION ===\n";
echo "\nLe système d'entités MODUSCAP est maintenant configuré et fonctionnel!\n";
echo "Vous pouvez maintenant:\n";
echo "- Créer des produits de maisons en capsule\n";
echo "- Gérer les catégories et options de personnalisation\n";
echo "- Configurer les images et médias\n";
echo "- Calculer les prix avec options\n";
echo "- Rechercher et filtrer les produits\n";
echo "\n";