<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\ProductCategoryTranslation;
use App\Entity\ProductTranslation;
use App\Entity\ProductOption;
use App\Entity\ProductOptionTranslation;
use App\Entity\ProductOptionValue;
use App\Entity\ProductOptionValueTranslation;
use App\Entity\ProductMedia;
use App\Entity\ProductSpecification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer les catégories de produits
        $categoryCompact = $this->createCategory('compact', 'Compact', 'Produits compacts', 'fr_FR', 'medium');
        $categoryPremium = $this->createCategory('premium', 'Premium', 'Produits premium', 'fr_FR', 'luxury');
        $categoryEco = $this->createCategory('eco', 'Eco', 'Produits écologiques', 'fr_FR', 'medium');
        
        $manager->persist($categoryCompact);
        $manager->persist($categoryPremium);
        $manager->persist($categoryEco);

        // Créer les produits MODUSCAP
        
        // 1. Capsule House
        $capsuleHouse = $this->createCapsuleHouse($categoryCompact);
        $manager->persist($capsuleHouse);
        
        // 2. Apple Cabin
        $appleCabin = $this->createAppleCabin($categoryPremium);
        $manager->persist($appleCabin);
        
        // 3. Natural House
        $naturalHouse = $this->createNaturalHouse($categoryEco);
        $manager->persist($naturalHouse);
        
        // 4. Dome House
        $domeHouse = $this->createDomeHouse($categoryPremium);
        $manager->persist($domeHouse);
        
        // 5. Model Double
        $modelDouble = $this->createModelDouble($categoryPremium);
        $manager->persist($modelDouble);

        $manager->flush();
    }

    private function createCategory(string $code, string $name, string $description, string $locale, string $priceRange): ProductCategory
    {
        $category = new ProductCategory();
        $category->setCode($code);
        $category->setPriceRange($priceRange);
        $category->setIsActive(true);
        $category->setSortOrder(0);

        $translation = new ProductCategoryTranslation();
        $translation->setLocale($locale);
        $translation->setName($name);
        $translation->setDescription($description);

        $category->addTranslation($translation);

        return $category;
    }

    private function createCapsuleHouse(ProductCategory $category): Product
    {
        $product = new Product();
        $product->setCode('capsule-house');
        $product->setPrice('38000.00');
        $product->setPricePerSquareMeter('1357.00');
        $product->setSurfaceHabitable(28);
        $product->setSurfaceTerrasse(0);
        $product->setNombrePieces(1);
        $product->setNombreChambres(1);
        $product->setHauteurSousPlafond('2.50');
        $product->setDimensions([
            'longueur' => 6.0,
            'largeur' => 4.7,
            'hauteur' => 2.8
        ]);
        $product->setPerformanceEnergetique([
            'classe' => 'C',
            'coefficient' => 0.25
        ]);
        $product->setTempsMontage(1);
        $product->setDeliveryType('convoy_1');
        $product->setAssemblyType('modulaire');
        $product->setMateriaux([
            'structure' => 'Panneaux sandwichs 100mm',
            'toiture' => 'Toiture inclinée 15°, tuiles terre cuite',
            'murs' => 'Panneaux composites 80mm, isolant 60mm',
            'menuiseries' => 'Alu/bois, simple vitrage renforcé',
            'sol' => 'Béton ciré, systématique d\'isolation phonique'
        ]);
        $product->setGaranties([
            'structure' => 10,
            'equipements' => 5,
            'esthetique' => 3
        ]);
        $product->setIsCustomizable(true);
        $product->setIsInStock(true);
        $product->setCategory($category);

        // Traductions
        $this->addProductTranslations($product, 'Capsule House', 'Maison capsule ultra-compacte pour l\'habitat optimisé', 'Le Capsule House est l\'innovation de MODUSCAP pour l\'habitat ultra-compact. Conçu pour maximiser chaque mètre carré, il propose un espace de vie optimisé et fonctionnel.');

        return $product;
    }

    private function createAppleCabin(ProductCategory $category): Product
    {
        $product = new Product();
        $product->setCode('apple-cabin');
        $product->setPrice('45000.00');
        $product->setPricePerSquareMeter('1286.00');
        $product->setSurfaceHabitable(35);
        $product->setSurfaceTerrasse(12);
        $product->setNombrePieces(2);
        $product->setNombreChambres(1);
        $product->setHauteurSousPlafond('2.60');
        $product->setDimensions([
            'longueur' => 7.0,
            'largeur' => 5.0,
            'hauteur' => 3.0
        ]);
        $product->setPerformanceEnergetique([
            'classe' => 'B',
            'coefficient' => 0.18
        ]);
        $product->setTempsMontage(2);
        $product->setDeliveryType('convoy_2');
        $product->setAssemblyType('modulaire');
        $product->setGaranties([
            'structure' => 10,
            'equipements' => 5,
            'esthetique' => 3
        ]);
        $product->setIsCustomizable(true);
        $product->setIsInStock(true);
        $product->setCategory($category);

        // Traductions
        $this->addProductTranslations($product, 'Apple Cabin', 'Cabine Apple à l\'équilibre parfait', 'L\'Apple Cabin représente l\'équilibre parfait entre fonctionnalité et esthétique moderne. Inspiré par les formes organiques et le design scandinave.');

        return $product;
    }

    private function createNaturalHouse(ProductCategory $category): Product
    {
        $product = new Product();
        $product->setCode('natural-house');
        $product->setPrice('48000.00');
        $product->setPricePerSquareMeter('1263.00');
        $product->setSurfaceHabitable(38);
        $product->setNombrePieces(3);
        $product->setNombreChambres(2);
        $product->setHauteurSousPlafond('3.20');
        $product->setDimensions([
            'longueur' => 6.5,
            'largeur' => 6.0,
            'hauteur' => 3.2
        ]);
        $product->setPerformanceEnergetique([
            'classe' => 'A',
            'coefficient' => 0.15
        ]);
        $product->setAutonomieEnergetique([
            'percentage' => 90,
            'panneaux' => 4,
            'batteries' => 10
        ]);
        $product->setTempsMontage(3);
        $product->setDeliveryType('convoy_3');
        $product->setAssemblyType('traditionnel');
        $product->setGaranties([
            'structure' => 10,
            'equipements' => 5,
            'esthetique' => 3
        ]);
        $product->setIsCustomizable(true);
        $product->setIsInStock(true);
        $product->setCategory($category);

        // Traductions
        $this->addProductTranslations($product, 'Natural House', 'Maison naturelle autonome', 'Le Natural House respecte intégralement la philosophie de l\'écologie et des matériaux naturels. Habitat entièrement autonome et respectueux de l\'environnement.');

        return $product;
    }

    private function createDomeHouse(ProductCategory $category): Product
    {
        $product = new Product();
        $product->setCode('dome-house');
        $product->setPrice('52000.00');
        $product->setPricePerSquareMeter('1238.00');
        $product->setSurfaceHabitable(42);
        $product->setNombrePieces(3);
        $product->setNombreChambres(2);
        $product->setHauteurSousPlafond('4.20');
        $product->setDimensions([
            'diametre' => 7.3,
            'hauteur' => 4.2
        ]);
        $product->setPerformanceEnergetique([
            'classe' => 'A+',
            'coefficient' => 0.15
        ]);
        $product->setTempsMontage(4);
        $product->setDeliveryType('convoy_3');
        $product->setAssemblyType('premium');
        $product->setGaranties([
            'structure' => 10,
            'equipements' => 5,
            'esthetique' => 3
        ]);
        $product->setIsCustomizable(true);
        $product->setIsInStock(true);
        $product->setCategory($category);

        // Traductions
        $this->addProductTranslations($product, 'Dome House', 'Maison dôme à l\'architecture innovante', 'Le Dome House incarne l\'excellence architecturale de MODUSCAP. Avec sa forme sphérique innovante, il offre une expérience d\'habitat unique.');

        return $product;
    }

    private function createModelDouble(ProductCategory $category): Product
    {
        $product = new Product();
        $product->setCode('model-double');
        $product->setPrice('68000.00');
        $product->setPricePerSquareMeter('1097.00');
        $product->setSurfaceHabitable(62);
        $product->setSurfaceTerrasse(15);
        $product->setNombrePieces(4);
        $product->setNombreChambres(2);
        $product->setHauteurSousPlafond('6.50');
        $product->setDimensions([
            'longueur' => 8.0,
            'largeur' => 4.0,
            'hauteur_totale' => 6.5
        ]);
        $product->setPerformanceEnergetique([
            'classe' => 'B',
            'coefficient' => 0.20
        ]);
        $product->setTempsMontage(4);
        $product->setDeliveryType('convoy_3');
        $product->setAssemblyType('modulaire');
        $product->setGaranties([
            'structure' => 10,
            'equipements' => 5,
            'esthetique' => 3
        ]);
        $product->setIsCustomizable(true);
        $product->setIsInStock(true);
        $product->setCategory($category);

        // Traductions
        $this->addProductTranslations($product, 'Model Double', 'Maison double familiale premium', 'Le Model Double représente la solution familiale premium de MODUSCAP. Conçu pour les ménages de 2-4 personnes, il offre l\'espace et le confort d\'une vraie maison.');

        return $product;
    }

    private function addProductTranslations(Product $product, string $name, string $shortDescription, string $description): void
    {
        // Français
        $translationFr = new ProductTranslation();
        $translationFr->setLocale('fr_FR');
        $translationFr->setName($name);
        $translationFr->setShortDescription($shortDescription);
        $translationFr->setDescription($description);
        $translationFr->setConceptDesign('Concept moderne et fonctionnel pour un habitat optimal');
        $product->addTranslation($translationFr);

        // Anglais
        $translationEn = new ProductTranslation();
        $translationEn->setLocale('en_US');
        $translationEn->setName($name . ' (EN)');
        $translationEn->setShortDescription('Modern and functional housing concept');
        $translationEn->setDescription('Modern and functional housing concept for optimal living');
        $product->addTranslation($translationEn);
    }
}
