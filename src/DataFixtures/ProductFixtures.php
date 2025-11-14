<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ProductCategory;
use App\Entity\Product;
use App\Entity\ProductImage;
use App\Entity\Media;
use App\Entity\ProductOptionGroup;
use App\Entity\ProductOption;
use App\Entity\ProductOptionValue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création des catégories
        $categories = [
            [
                'slug' => 'capsule-house',
                'name' => 'Capsule House',
                'name_fr' => 'Maison Capsule',
                'description' => 'Innovation pour l\'habitat ultra-compact',
                'description_fr' => 'Innovation pour l\'habitat ultra-compact',
                'color' => '#3B82F6',
                'position' => 1,
                'price' => 38000,
                'surface' => 28,
                'dimensions' => '6m × 4,7m × 2,8m',
                'assemblyTime' => '1 jour',
                'energyClass' => 'B',
                'constructionType' => 'Modules préfabriqués',
                'rooms' => 1,
                'bathrooms' => 1,
                'features' => 'Cuisine modulaire, espace nuit optimisé, salle d\'eau compacte',
                'specifications' => 'Structure: Panneaux sandwichs 100mm, Toiture: Toiture inclinée 15°, Murs: Panneaux composites 80mm'
            ],
            [
                'slug' => 'apple-cabin',
                'name' => 'Apple Cabin',
                'name_fr' => 'Cabine Pomme',
                'description' => 'Équilibre parfait entre fonctionnalité et esthétique moderne',
                'description_fr' => 'Équilibre parfait entre fonctionnalité et esthétique moderne',
                'color' => '#10B981',
                'position' => 2,
                'price' => 45000,
                'surface' => 35,
                'dimensions' => '7m × 5m × 3m',
                'assemblyTime' => '2 jours',
                'energyClass' => 'B',
                'constructionType' => 'Ossature bois',
                'rooms' => 2,
                'bathrooms' => 1,
                'terrace' => 12,
                'features' => 'Cuisine intégrée, salle d\'eau, électricité et domotique',
                'specifications' => 'Structure: Ossature bois CL3, Isolation: 120mm, Toiture: Toit-terrasse EPDM'
            ],
            [
                'slug' => 'natural-house',
                'name' => 'Natural House',
                'name_fr' => 'Maison Naturelle',
                'description' => 'Respecte intégralement la philosophie écologique et des matériaux naturels',
                'description_fr' => 'Respecte intégralement la philosophie écologique et des matériaux naturels',
                'color' => '#059669',
                'position' => 3,
                'price' => 48000,
                'surface' => 38,
                'dimensions' => '6,5m × 6m × 3,2m',
                'assemblyTime' => '3 jours',
                'energyClass' => 'A+',
                'constructionType' => 'Matériaux biosourcés',
                'rooms' => 3,
                'bathrooms' => 1,
                'bedrooms' => 1,
                'features' => 'Matériaux biosourcés, systèmes eco-énergétiques, Technologies écosophères',
                'specifications' => 'Structure: Bois massif CLT, Isolation: Laine de mouton, Production: Panneaux solaires 4kWc'
            ],
            [
                'slug' => 'dome-house',
                'name' => 'Dome House',
                'name_fr' => 'Maison Dôme',
                'description' => 'Excellence architecturale avec forme sphérique innovante',
                'description_fr' => 'Excellence architecturale avec forme sphérique innovante',
                'color' => '#7C3AED',
                'position' => 4,
                'price' => 52000,
                'surface' => 42,
                'dimensions' => 'Diamètre 7,3m, hauteur 4,2m',
                'assemblyTime' => '4 jours',
                'energyClass' => 'A+',
                'constructionType' => 'Béton armé',
                'rooms' => 3,
                'bathrooms' => 1,
                'bedrooms' => 1,
                'floorHeight' => 4.2,
                'features' => 'Forme hémisphérique, fenêtres panoramiques, équipements premium',
                'specifications' => 'Structure: Béton armé fers HA16, Isolation: Fibre de verre 140mm, Enveloppe: Bardage composite'
            ],
            [
                'slug' => 'model-double',
                'name' => 'Model Double',
                'name_fr' => 'Modèle Double',
                'description' => 'Solution familiale premium pour les maisonnées de 2-4 personnes',
                'description_fr' => 'Solution familiale premium pour les maisonnées de 2-4 personnes',
                'color' => '#DC2626',
                'position' => 5,
                'price' => 68000,
                'surface' => 62,
                'dimensions' => '8m × 4m × 6,5m',
                'assemblyTime' => '4 jours',
                'energyClass' => 'A',
                'constructionType' => 'Ossature bois renforcée',
                'rooms' => 4,
                'bathrooms' => 2,
                'bedrooms' => 2,
                'terrace' => 15,
                'features' => 'Architecture modulaire optimale, finitions haut de gamme, système domotique complet',
                'specifications' => 'Structure: Ossature bois 145mm, Isolation: 200mm, Équipements: Cuisine premium, VMC double flux'
            ]
        ];

        // Création des catégories
        $categoryEntities = [];
        foreach ($categories as $index => $catData) {
            $category = new ProductCategory();
            $category->setSlug($catData['slug']);
            $category->setName($catData['name']);
            $category->setDescription($catData['description']);
            $category->setColor($catData['color']);
            $category->setPosition($catData['position']);
            $category->setIsActive(true);
            $category->setSortOrder($index + 1);
            
            $manager->persist($category);
            $categoryEntities[] = $category;
        }

        // Création des groupes d'options
        $optionGroups = [
            [
                'slug' => 'bardage',
                'name' => 'Bardages et vernissage',
                'type' => 'select',
                'isRequired' => true,
                'sortOrder' => 1
            ],
            [
                'slug' => 'couverture',
                'name' => 'Couvertures spécialisées',
                'type' => 'select',
                'isRequired' => false,
                'sortOrder' => 2
            ],
            [
                'slug' => 'equipements',
                'name' => 'Équipements optionnels',
                'type' => 'checkbox',
                'isRequired' => false,
                'sortOrder' => 3
            ],
            [
                'slug' => 'climatisation',
                'name' => 'Chauffage et climatisation',
                'type' => 'select',
                'isRequired' => false,
                'sortOrder' => 4
            ]
        ];

        $optionGroupEntities = [];
        foreach ($optionGroups as $groupData) {
            $group = new ProductOptionGroup();
            $group->setSlug($groupData['slug']);
            $group->setName($groupData['name']);
            $group->setType($groupData['type']);
            $group->setIsRequired($groupData['isRequired']);
            $group->setIsActive(true);
            $group->setSortOrder($groupData['sortOrder']);
            
            $manager->persist($group);
            $optionGroupEntities[] = $group;
        }

        // Création des options
        $options = [
            // Bardages
            ['group' => 0, 'slug' => 'bardage-original', 'name' => 'Original', 'description' => 'Bardage suivant la Lanka', 'isDefault' => true],
            ['group' => 0, 'slug' => 'bardage-teint', 'name' => 'Ternir', 'description' => 'Bardage teñit con solución rústica', 'isDefault' => false],
            ['group' => 0, 'slug' => 'bardage-ailes', 'name' => 'Aile', 'description' => 'Bardage avec traitement spécial', 'isDefault' => false],
            
            // Couvertures
            ['group' => 1, 'slug' => 'toiture-verte', 'name' => 'Toiture végétalisée', 'description' => 'Toit vert avec végétation', 'price' => 5000, 'isDefault' => false],
            ['group' => 1, 'slug' => 'terrasse-acces', 'name' => 'Terrasse accessible', 'description' => 'Terrasse sur le niveau supérieur', 'price' => 3000, 'isDefault' => false],
            
            // Équipements
            ['group' => 2, 'slug' => 'domotique-avancee', 'name' => 'Domotique avancée', 'description' => 'Système domotique intelligent', 'price' => 2500, 'isDefault' => false],
            ['group' => 2, 'slug' => 'securite-renforcee', 'name' => 'Sécurité renforcée', 'description' => 'Système de sécurité intégré', 'price' => 1500, 'isDefault' => false],
            
            // Chauffage
            ['group' => 3, 'slug' => 'chauffage-electrique', 'name' => 'Chauffage électrique', 'description' => 'Radiateurs électriques économiques', 'isDefault' => true],
            ['group' => 3, 'slug' => 'pompe-chaleur', 'name' => 'Pompe à chaleur', 'description' => 'Pompe à chaleur air-eau', 'price' => 8000, 'isDefault' => false]
        ];

        $optionEntities = [];
        foreach ($options as $optData) {
            $option = new ProductOption();
            $option->setSlug($optData['slug']);
            $option->setName($optData['name']);
            $option->setDescription($optData['description'] ?? null);
            $option->setIsDefault($optData['isDefault']);
            $option->setIsActive(true);
            $option->setGroup($optionGroupEntities[$optData['group']]);
            $option->setSortOrder(1);
            
            if (isset($optData['price'])) {
                $option->setPrice((string)$optData['price']);
            }
            
            $manager->persist($option);
            $optionEntities[] = $option;
        }

        // Création des produits
        foreach ($categories as $index => $catData) {
            $product = new Product();
            $product->setSlug($catData['slug']);
            $product->setName($catData['name']);
            $product->setShortDescription($catData['description']);
            $product->setDescription($catData['description']);
            $product->setFeatures($catData['features']);
            $product->setSpecifications($catData['specifications']);
            $product->setPrice((string)$catData['price']);
            $product->setSurface((string)$catData['surface']);
            $product->setDimensions($catData['dimensions']);
            $product->setAssemblyTime($catData['assemblyTime']);
            $product->setEnergyClass($catData['energyClass']);
            $product->setConstructionType($catData['constructionType']);
            $product->setRooms($catData['rooms']);
            $product->setBathrooms($catData['bathrooms']);
            
            if (isset($catData['terrace'])) {
                $product->setTerrace((string)$catData['terrace']);
            }
            if (isset($catData['bedrooms'])) {
                $product->setBedrooms($catData['bedrooms']);
            }
            if (isset($catData['floorHeight'])) {
                $product->setFloorHeight((string)$catData['floorHeight']);
            }
            
            $product->setWarrantyStructure('10 ans');
            $product->setWarrantyEquipment('5 ans');
            $product->setIsActive(true);
            $product->setIsFeatured($index < 3); // Les 3 premiers sont en vedette
            $product->setIsPreOrder(false);
            $product->setStockQuantity(5);
            $product->setSortOrder($index + 1);
            $product->setViews(0);
            $product->setSales(0);
            $product->setCategory($categoryEntities[$index]);
            
            $manager->persist($product);
            
            // Création d'images de démonstration
            $imageTypes = ['exterior', 'interior', 'detail'];
            foreach ($imageTypes as $imgIndex => $imgType) {
                $media = new Media();
                $media->setAlt($catData['name'] . ' - ' . $imgType);
                $media->setExtension('jpg');
                
                $productImage = new ProductImage();
                $productImage->setMedia($media);
                $productImage->setProduct($product);
                $productImage->setTitle($catData['name'] . ' - Vue ' . $imgType);
                $productImage->setAlt($catData['name'] . ' ' . $imgType);
                $productImage->setImageType($imgType);
                $productImage->setSortOrder($imgIndex + 1);
                $productImage->setIsMain($imgIndex === 0);
                $productImage->setIsActive(true);
                
                $manager->persist($media);
                $manager->persist($productImage);
            }
        }

        $manager->flush();
    }
}